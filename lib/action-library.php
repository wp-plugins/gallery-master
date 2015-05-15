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
		if (isset($_REQUEST["param"]))
		{
			$obj_save_data = new tech_prodigy_save_data();
			switch($_REQUEST["param"])
			{
				case "save_gallery_details":
					if(wp_verify_nonce( $_REQUEST["_wp_nonce"], "manage_basic_details"))
					{
						$title = esc_attr($_REQUEST["ux_gallery_title"]) != "" ? esc_attr(html_entity_decode($_REQUEST["ux_gallery_title"])) : "Untitled Gallery";
						$description = esc_attr(html_entity_decode($_REQUEST["ux_gallery_desc"]));
						$gallery_id = intval($_REQUEST["is_gallery_id"]);

						$count_galleries = $wpdb->get_var
						(
							$wpdb->prepare
							(
								"SELECT count(gallery_id) FROM ".gallery_master_galleries()." WHERE type = %s",
								"gallery"
							)
						);

						if($gallery_id == "")
						{
							if($count_galleries < 3)
							{
								$array_insert_data = array();

								$array_insert_data["parent_id"] = 0;
								$array_insert_data["type"] = "gallery";

								echo $last_insert_id = $obj_save_data->insert_data(gallery_master_galleries(), $array_insert_data);

								$array_insert_data = array();
								$where = array();

								$array_insert_data["sorting_order"] = $last_insert_id;
								$where["gallery_id"] = $last_insert_id;

								$obj_save_data->update_data(gallery_master_galleries(), $array_insert_data, $where);

								$array_insert_data = array();

								$array_insert_data["gallery_title"] = $title;
								$array_insert_data["gallery_description"] = $description;
								$array_insert_data["edited_on"] = date("Y-m-d");
								$array_insert_data["edited_by"] = $current_user->display_name;
								$array_insert_data["author"] = $current_user->display_name;
								$array_insert_data["gallery_date"] = date("Y-m-d");

								foreach ($array_insert_data as $val => $innerKey)
								{
									$gallery_value = array();
									$gallery_value["gallery_id"] = $last_insert_id;
									$gallery_value["gallery_meta_key"] = $val;
									$gallery_value["gallery_meta_value"] = $innerKey;
									$obj_save_data->insert_data(gallery_master_meta(), $gallery_value);
								}
							}
						}
						else
						{
							$array_update_data = array ();
							$where = array();

							echo $gallery_id;
							$array_update_data["gallery_title"] = $title;
							$array_update_data["gallery_description"] = $description;
							$array_update_data["edited_on"] = date("Y-m-d");
							$array_update_data["edited_by"] = $current_user->display_name;

							foreach ($array_update_data as $val => $innerKey)
							{
								$gallery_value = array();
								$where["gallery_id"] = $gallery_id;
								$where["gallery_meta_key"] = $val;
								$gallery_value["gallery_meta_value"] = $innerKey;
								$obj_save_data->update_data(gallery_master_meta(),$gallery_value,$where);
							}
						}
					}
				break;

				case "upload_images":
					if(wp_verify_nonce($_REQUEST["_wp_nonce"], "manage_files_uploading"))
					{
						$array_insert_data = array();
						$helper_functions = new tech_prodigy_helper_functions();

						$file_type = esc_attr($_REQUEST["ux_file_type"]);
						$ux_img_target_name = $file_type == "image" ? esc_attr($_REQUEST["ux_img_target_name"]) : esc_attr(urldecode($_REQUEST["ux_img_target_name"]));
						$upload_type = esc_attr($_REQUEST["upload_type"]);

						$helper_functions->process_file_uploading($ux_img_target_name,200,160);

						$array_insert_data["parent_id"] = intval($_REQUEST["id"]);
						$array_insert_data["type"] = "pic";
						$last_insert_id = $obj_save_data->insert_data(gallery_master_galleries(),$array_insert_data);

						$array_insert_data = array();
						$where = array();

						$array_insert_data["sorting_order"] = $last_insert_id;
						$where["gallery_id"] = $last_insert_id;

						$obj_save_data->update_data(gallery_master_galleries(),$array_insert_data,$where);

						$array_insert_data = array();

						$array_insert_data["image_title"] = $file_type == "image" ? ($upload_type == "wp_upload" ? esc_attr(html_entity_decode($_REQUEST["title"])) : "") : "";
						$array_insert_data["image_description"] = $file_type == "image" ? ($upload_type == "wp_upload" ? esc_attr(html_entity_decode($_REQUEST["desc"])) : "") : "";
						$array_insert_data["alt_text"] = $file_type == "image" ? ($upload_type == "wp_upload" ? esc_attr(html_entity_decode($_REQUEST["altText"])) : "") : "";

						$array_insert_data["thumbnail_url"] = GALLERY_MASTER_UPLOAD_PATH."thumbs/".$ux_img_target_name;
						$array_insert_data["image_name"] = esc_attr($_REQUEST["ux_image_name"]);
						$array_insert_data["enable_redirect"] = 0;
						$array_insert_data["redirect_url"] = "http://";

						$array_insert_data["upload_url"] = $file_type == "image" ? GALLERY_MASTER_UPLOAD_PATH."uploads/".$ux_img_target_name : $ux_img_target_name;
						$array_insert_data["exclude_image"] = 0;

						foreach ($array_insert_data as $val => $innerKey)
						{
							$gallery_value = array();
							$gallery_value["gallery_id"] = $last_insert_id;
							$gallery_value["gallery_meta_key"] = $val;
							$gallery_value["gallery_meta_value"] = $innerKey;
							$obj_save_data->insert_data(gallery_master_meta(),$gallery_value);
						}
						echo $file_type == "image" ? ($upload_type == "wp_upload" ? $ux_img_target_name.",".$last_insert_id : $last_insert_id) : $last_insert_id;
					}
				break;

				case "delete_images":
					if(wp_verify_nonce($_REQUEST["_wp_nonce"], "delete_gallery_images"))
					{
						$delete_array = urldecode($_REQUEST["delete_data"]);

						$obj_save_data->bulk_delete_data(gallery_master_meta(),"gallery_id",$delete_array);
						$obj_save_data->bulk_delete_data(gallery_master_galleries(),"gallery_id",$delete_array);
					}
				break;

				case "update_images":
					if(wp_verify_nonce($_REQUEST["_wp_nonce"], "update_gallery_images"))
					{
						$images_data = json_decode(stripcslashes(urldecode($_REQUEST["gallery_data"])));
						foreach($images_data as $image)
						{
							$array_update_data = array();
							$image_id = intval($image[0]);
							$file_type = esc_attr($image[1]);
							$array_update_data["exclude_image"] = intval($image[2]);
							$array_update_data["image_title"] = esc_attr(html_entity_decode($image[3]));
							$array_update_data["image_description"] = esc_attr(html_entity_decode($image[5]));
							$array_update_data["alt_text"] = esc_attr(html_entity_decode($image[4]));

							if($file_type == "image")
							{
								$array_update_data["enable_redirect"] = intval($image[6]);
								$array_update_data["redirect_url"] = esc_attr($image[7]);
							}

							foreach ($array_update_data as $val => $innerKey)
							{
								$gallery_value = array();
								$where = array();
								$where["gallery_id"] = $image_id;
								$where["gallery_meta_key"] = $val;
								$gallery_value["gallery_meta_value"] = $innerKey;
								$obj_save_data->update_data(gallery_master_meta(),$gallery_value,$where);
							}
						}
					}
				break;

				case "delete_gallery":
					if(wp_verify_nonce($_REQUEST["_wp_nonce"], "delete_uploaded_gallery"))
					{
						$gallery_id = intval($_REQUEST["id"]);

						$gallery_ids = $wpdb->get_col
						(
							$wpdb->prepare
							(
								"SELECT gallery_id FROM ".gallery_master_galleries()." WHERE parent_id = %d or gallery_id = %d",
								$gallery_id,
								$gallery_id
							)
						);

						$obj_save_data->bulk_delete_data(gallery_master_meta(),"gallery_id",implode(",",$gallery_ids));
						$obj_save_data->bulk_delete_data(gallery_master_galleries(),"gallery_id",implode(",",$gallery_ids));
					}
				break;
			}
			die();
		}
	}
}
?>
