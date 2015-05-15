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
		if(!class_exists("tech_prodigy_save_data"))
		{
			class tech_prodigy_save_data
			{
				/**
				 * @param $tbl
				 * @param $data
				 * @return mixed
				 */
				function insert_data($tbl, $data)
				{
					global $wpdb;
					$wpdb->insert($tbl, $data);
					return $wpdb->insert_id;
				}

				/**
				 * @param $tbl
				 * @param $data
				 * @param $where
				 */
				function update_data($tbl, $data, $where)
				{
					global $wpdb;
					$wpdb->update($tbl, $data, $where);
				}

				/**
				 * @param $tbl
				 * @param $where
				 */
				function delete_data($tbl, $where)
				{
					global $wpdb;
					$wpdb->delete($tbl, $where);
				}

				/**
				 * @param $tbl
				 * @param $where
				 * @param $data
				 */

				function bulk_delete_data($tbl,$where,$data)
				{
					global $wpdb;
					$wpdb->query("DELETE FROM $tbl WHERE $where IN ($data)");
				}

				/**
				 * @param $destination_image
				 * @param $src_image
				 */

				function copy_images($src_image,$destination_image)
				{
					if (PHP_VERSION > 5)
					{
						copy($src_image, $destination_image);
					}
					else
					{
						$content = file_get_contents($src_image);
						$fp = fopen($destination_image, "w");
						fwrite($fp, $content);
						fclose($fp);
					}
				}
			}
		}
		
		require_once(ABSPATH . "wp-admin/includes/upgrade.php");
		$gallery_master_version = get_option("gallery-master-key");
		update_option("gallery-master-updation-check-url","http://tech-prodigy.org/wp-admin/admin-ajax.php");
		
		/****************************************** FUNCTION FOR CREATING TABLES ************************************/

		if(!function_exists("create_table_gallery_master"))
		{
			function create_table_gallery_master ()
			{
				$sql = "CREATE TABLE IF NOT EXISTS ". gallery_master_galleries() ." (
				`gallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`parent_id` int(10) DEFAULT NULL,
				`type` varchar(100) NOT NULL,
				`sorting_order` int(10) NOT NULL,
				PRIMARY KEY (`gallery_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				dbDelta($sql);
			}
		}
		
		if(!function_exists("create_table_gallery_master_meta"))
		{
			function create_table_gallery_master_meta()
			{
				$sql = "CREATE TABLE IF NOT EXISTS ". gallery_master_meta() ." (
				`gallery_meta_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`gallery_id` int(10) NOT NULL,
				`gallery_meta_key` varchar(100) NOT NULL,
				`gallery_meta_value` longtext NOT NULL,
				PRIMARY KEY (`gallery_meta_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				dbDelta($sql);
			}
		}
		
		if(!function_exists("create_table_gallery_master_settings"))
		{
			function create_table_gallery_master_settings()
			{
				$sql = "CREATE TABLE IF NOT EXISTS ". gallery_master_settings() ." (
				`setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`setting_key` varchar(100) NOT NULL,
				`setting_value` text NOT NULL,
				PRIMARY KEY (`setting_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				dbDelta($sql);
				//include (GALLERY_MASTER_BK_PLUGIN_DIR . "/includes/include-settings.php");
			}
		}
		
		if(!function_exists("create_table_gallery_master_licensing"))
		{
			function create_table_gallery_master_licensing()
			{
				$sql = "CREATE TABLE IF NOT EXISTS ". gallery_master_licensing() ." (
				`licensing_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`version` varchar(10) NOT NULL,
				`type` varchar(100) NOT NULL,
				`url` text NOT NULL,
				`api_key` text,
				`order_id` int(10) DEFAULT NULL,
				PRIMARY KEY (`licensing_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
				dbDelta($sql);
				
				$gallery_licensing = new tech_prodigy_save_data();
				$gallery_licensing_array = array();
				$gallery_licensing_array["version"] = "1.0";
				$gallery_licensing_array["type"] = "Gallery Master";
				$gallery_licensing_array["url"] = site_url();
				
				$gallery_licensing->insert_data(gallery_master_licensing(),$gallery_licensing_array);
			}
		}
		
		/************************************* CODE FOR CREATING DATABASE ************************************/
		switch ($gallery_master_version)
		{
			case "":
				create_table_gallery_master();
				
				create_table_gallery_master_meta();
				
				create_table_gallery_master_settings();
				
				create_table_gallery_master_licensing();

				$obj_save_gallery = new tech_prodigy_save_data();
				$copy_images = array("thumbnail1.png","thumbnail2.png","thumbnail3.png","thumbnail4.png","thumbnail5.png","thumbnail6.png");

				$insert_gallery = array();

				$insert_gallery["parent_id"] = 0;
				$insert_gallery["type"] = "gallery";

				$last_gallery_id = $obj_save_gallery->insert_data(gallery_master_galleries(),$insert_gallery);

				$update_gallery_data = array();
				$where = array();

				$update_gallery_data["sorting_order"] = $last_gallery_id;
				$where["gallery_id"] = $last_gallery_id;

				$obj_save_gallery->update_data(gallery_master_galleries(),$update_gallery_data,$where);

				$insert_gallery = array();

				$insert_gallery["gallery_title"] = "Untitled Gallery";
				$insert_gallery["gallery_description"] = "";
				$insert_gallery["edited_on"] = date("Y-m-d");
				$insert_gallery["edited_by"] = $current_user->display_name;
				$insert_gallery["author"] = $current_user->display_name;
				$insert_gallery["gallery_date"] = date("Y-m-d");

				foreach ($insert_gallery as $val => $innerKey)
				{
					$gallery_value = array();
					$gallery_value["gallery_id"] = $last_gallery_id;
					$gallery_value["gallery_meta_key"] = $val;
					$gallery_value["gallery_meta_value"] = $innerKey;
					$obj_save_gallery->insert_data(gallery_master_meta(),$gallery_value);
				}

				foreach($copy_images as $image)
				{
					$src_image = GALLERY_MASTER_BK_PLUGIN_DIR."images/".$image;
					$destination_upload = GALLERY_MASTER_UPLOAD_DIR.$image;
					$destination_thumb = GALLERY_MASTER_THUMBS_DIR.$image;

					$obj_save_gallery->copy_images($src_image,$destination_upload);
					$obj_save_gallery->copy_images($src_image,$destination_thumb);

					/************* For Gallery Pics **************/
					$insert_images = array();

					$insert_images["parent_id"] = $last_gallery_id;
					$insert_images["type"] = "pic";
					$last_pic_id = $obj_save_gallery->insert_data(gallery_master_galleries(),$insert_images);

					$update_images = array();
					$where = array();

					$update_images["sorting_order"] = $last_pic_id;
					$where["gallery_id"] = $last_pic_id;

					$obj_save_gallery->update_data(gallery_master_galleries(),$update_images,$where);

					$insert_images = array();

					$insert_images["image_title"] = "";
					$insert_images["image_description"] = "";
					$insert_images["alt_text"] = "";

					$insert_images["thumbnail_url"] = GALLERY_MASTER_UPLOAD_PATH."thumbs/".$image;
					$insert_images["image_name"] = $image;
					$insert_images["enable_redirect"] = 0;
					$insert_images["redirect_url"] = "http://";

					$insert_images["upload_url"] = GALLERY_MASTER_UPLOAD_PATH."uploads/".$image;
					$insert_images["exclude_image"] = 0;

					foreach ($insert_images as $val => $innerKey)
					{
						$gallery_value = array();
						$gallery_value["gallery_id"] = $last_pic_id;
						$gallery_value["gallery_meta_key"] = $val;
						$gallery_value["gallery_meta_value"] = $innerKey;
						$obj_save_gallery->insert_data(gallery_master_meta(),$gallery_value);
					}
				}
			break;
		}
		
		update_option("gallery-master-key", "1.0");
		update_option("gallery-master-automatic_update", "1");
	}
}
?>