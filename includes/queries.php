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