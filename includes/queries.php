<?php
if (!is_user_logged_in())
{
	return;
}
else
{
	switch($gm_role)
	{
		case "administrator":
			$user_role_permission = "manage_options";
		break;

		case "editor":
			$user_role_permission = "publish_pages";
		break;

		case "author":
			$user_role_permission = "publish_posts";
		break;

		case "contributor":
			$user_role_permission = "edit_posts";
		break;

		case "subscriber":
			$user_role_permission = "read";
		break;
	}

	if (!current_user_can($user_role_permission))
	{
		return;
	}
	else
	{
		if(!function_exists("get_gallery_data"))
		{
			function get_gallery_data($manage_gallery)
			{
				$array_gallery_ids = array();
				$gallery_details = array();

				foreach($manage_gallery as $row)
				{
					array_push($array_gallery_ids,$row->gallery_id);
				}
				$array_gallery_ids = array_unique($array_gallery_ids,SORT_REGULAR);

				foreach($array_gallery_ids as $id)
				{
					$gallery_details_data = get_gallery_details($id,$manage_gallery);
					array_push($gallery_details,$gallery_details_data);
				}
				return array_unique($gallery_details,SORT_REGULAR);
			}
		}
		
		if(!function_exists("get_gallery_details"))
		{
			function get_gallery_details($id,$gallery_details)
			{
				$get_single_detail = array();
				foreach ($gallery_details as $row)
				{
					if ($row->gallery_id == $id)
					{
						$get_single_detail["$row->gallery_meta_key"] = $row->gallery_meta_value;
						$get_single_detail["gallery_id"] = $row->gallery_id;
					}
				}
				return $get_single_detail;
			}
		}

		if(!function_exists("get_pic_data"))
		{
			function get_pic_data($id,$gallery_settings)
			{
				$gallery_id_array = array();
				$images_data = array();
				foreach ($gallery_settings as $row)
				{
					if($row->parent_id == $id)
					{
						array_push($gallery_id_array, $row->gallery_id);
					}
				}
				$gallery_id_array = array_unique($gallery_id_array, SORT_REGULAR);
				foreach ($gallery_id_array as $id)
				{
					$gallery = get_gallery_details($id,$gallery_settings);
					array_push($images_data, $gallery);
				}
				return array_unique($images_data, SORT_REGULAR);
			}
		}

		if (isset($_REQUEST["page"]))
		{
			switch (esc_attr($_REQUEST["page"]))
			{
				case "gallery_master":

					$manage_gallery = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM " . gallery_master_meta(). " INNER JOIN ".gallery_master_galleries().
							" ON ".gallery_master_galleries().".gallery_id = ".gallery_master_meta().".gallery_id WHERE "
							.gallery_master_galleries().".parent_id = %d and ".gallery_master_galleries().".type = %s",
							0,
							"gallery"
						)
					);
					$gallery_details = get_gallery_data($manage_gallery);

				break;

				case "gm_save_basic_details":

					$count_galleries = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT count(gallery_id) FROM ".gallery_master_galleries()." WHERE type = %s",
							"gallery"
						)
					);
					if(isset($_REQUEST["gallery_id"]))
					{
						$gallery_id = intval($_REQUEST["gallery_id"]);
						$gallery_details = $wpdb->get_results
						(
							$wpdb->prepare
							(
								"SELECT * FROM " . gallery_master_meta () . " INNER JOIN " . gallery_master_galleries () .
								" ON " . gallery_master_galleries () . ".gallery_id = " . gallery_master_meta () . ".gallery_id WHERE " . gallery_master_galleries () .
								".gallery_id = %d ",
								$gallery_id
							)
						);
						$array_gallery_details = get_gallery_details($gallery_id,$gallery_details);
					}

				break;

				case "gm_upload_media":

					if(!function_exists("check_configuration"))
					{
						function check_configuration( $con = true, $x = "0", $y = "0" )
						{
							if( ! function_exists( "memory_get_usage")) return "";

							$server_memory_limit = 0;
							$ini_memory_limit = gallery_convert_bytes( ini_get( "memory_limit" ) );
							$php_configuration = gallery_convert_bytes( get_cfg_var( "memory_limit" ) );

							if ( $ini_memory_limit && $php_configuration ) $server_memory_limit = min( $ini_memory_limit, $php_configuration );
							elseif($ini_memory_limit) $server_memory_limit = $ini_memory_limit;
							else $server_memory_limit = $php_configuration;

							if ( ! $server_memory_limit) return "";

							$free_memory = $server_memory_limit - memory_get_usage( true );
							$image_pixels = gallery_get_minisize() * gallery_get_minisize() * 3 / 4;

							$bytes_per_pixel = $server_memory_limit / ( 1024 * 1024 );
							$factor_result = "6.00" - "0.58" * ( $bytes_per_pixel / 104 );

							$max_image_pixel = ( $free_memory / $factor_result ) - $image_pixels;

							if ( $max_image_pixel < 0 ) return "";

							if ( $x && $y )
							{
								if ($x * $y <= $max_image_pixel) $result = true;
								else $result = false;
							}
							else
							{

								$max_x = sqrt($max_image_pixel / 12) * 4;
								$max_y = sqrt($max_image_pixel / 12) * 3;
								if($con)
								{
									$result = "<br />".sprintf(__( "Based on your server memory limit you should not upload images larger then <strong>%d x %d (%2.1f MP)</strong>", gallery_master), $max_x, $max_y, $max_image_pixel / ( 1024 * 1024 ));
								}
								else
								{
									$result["maxx"] = $max_x;
									$result["maxy"] = $max_y;
									$result["maxp"] = $max_image_pixel;
								}
							}
							return $result;
						}
					}

					if(!function_exists("gallery_convert_bytes"))
					{
						function gallery_convert_bytes($value)
						{
							if (is_numeric($value))
							{
								return max("0",$value);
							}
							else
							{
								$value_length = strlen($value);
								$value_string = substr($value, 0, $value_length - 1);
								$unit = strtolower(substr($value, $value_length - 1));
								switch ($unit)
								{
									case "k":
										$value_string *= 1024;
										break;
									case "m":
										$value_string *= 1048576;
										break;
									case "g":
										$value_string *= 1073741824;
										break;
								}
								return max("0", $value_string);
							}
						}
					}

					if(!function_exists("gallery_get_minisize"))
					{
						function gallery_get_minisize()
						{
							$result = "100";
							$result = ceil($result / 25) * 25;
							return $result;
						}
					}

					if ( ! function_exists( "imagecreatefromjpeg" ) )
					{
						_e( "There is a serious misconfiguration in your servers PHP config. Function imagecreatefromjpeg() does not exist. You will encounter problems when uploading photos and not be able to generate thumbnail images. Ask your hosting provider to add GD support with a minimal version 1.8.", gallery_master );
					}

					$max_upload_files = ini_get( "max_file_uploads" );
					$max_files_upload = $max_upload_files;
					if ( $max_upload_files < "1" ) {
						$max_files_upload = __( "unknown", gallery_master );
						$max_upload_files = "15";
					}
					$max_files_size = ini_get( "upload_max_filesize" );
					$max_files_time = ini_get( "max_input_time" );
					if ( $max_files_time < "1" ) $max_files_time = __( "unknown", gallery_master );


					$gallery_id = intval($_REQUEST["gallery_id"]);
					$gallery_data = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT * FROM " . gallery_master_meta(). " INNER JOIN ".gallery_master_galleries().
							" ON ".gallery_master_galleries().".gallery_id = ".gallery_master_meta().".gallery_id WHERE "
							.gallery_master_galleries().".parent_id = %d",
							$gallery_id
						)
					);

					$galleries = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT ".gallery_master_meta().".gallery_meta_value, ".gallery_master_galleries().".gallery_id FROM " . gallery_master_meta(). " INNER JOIN ".gallery_master_galleries().
							" ON ".gallery_master_galleries().".gallery_id = ".gallery_master_meta().".gallery_id WHERE "
							.gallery_master_galleries().".parent_id = %d and ".gallery_master_meta().".gallery_meta_key = %s",
							0,
							"gallery_title"
						)
					);

					$pics_array = get_pic_data($gallery_id,$gallery_data);
				break;
				
				case "gm_save_gallery":
					$gallery_id = intval($_REQUEST["gallery_id"]);
					$gallery_details = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"SELECT " . gallery_master_meta () . ".*, " . gallery_master_galleries () . ".sorting_order FROM " . gallery_master_meta () . " INNER JOIN " . gallery_master_galleries () .
							" ON " . gallery_master_galleries () . ".gallery_id = " . gallery_master_meta () . ".gallery_id WHERE " . gallery_master_galleries () .
							".gallery_id = %d or " . gallery_master_galleries () . ".parent_id = %d" ,
							$gallery_id ,
							0
						)
					);
					$array_gallery_details = get_gallery_details($gallery_id,$gallery_details);

					$count_pic = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT count(gallery_id) FROM ".gallery_master_galleries()." WHERE parent_id = %d",
							$gallery_id
						)
					);
				break;
				
				case "gm_shortcode_generator":
					$galleries = $wpdb->get_results
					(
						$wpdb->prepare
						(
							"select ".gallery_master_meta().".gallery_meta_value , ".gallery_master_meta().".gallery_id from  ".gallery_master_meta()."
							inner join ".gallery_master_galleries()." on  ".gallery_master_meta().".gallery_id = ".gallery_master_galleries().".gallery_id where ".gallery_master_meta().".gallery_meta_key =%s",
							"gallery_title"
						)
					);
				break;
			}
		}
	}
}
?>