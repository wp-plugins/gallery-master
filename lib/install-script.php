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
			break;
		}
		
		update_option("gallery-master-key", "1.0");
		update_option("gallery-master-automatic_update", "1");
	}
}
?>