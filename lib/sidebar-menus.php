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
		switch ($gm_role)
		{
			case "administrator":
				add_menu_page("Gallery Master", __("Gallery Master", gallery_master), "read", "gallery_master", "", plugins_url("assets/admin/images/icon.png",dirname(__FILE__)));
				add_submenu_page("gallery_master", "Dashboard", __("Dashboard", gallery_master), "read", "gallery_master","gallery_master");
				add_submenu_page("", "", "", "read", "gm_save_basic_details", "gm_save_basic_details");
				add_submenu_page("", "", "", "read", "gm_upload_media", "gm_upload_media");
				add_submenu_page("", "", "", "read", "gm_save_gallery", "gm_save_gallery");
				add_submenu_page("", "", "", "read", "gm_shortcode_generator", "gm_shortcode_generator");
				add_submenu_page("", "", "", "read", "gm_feature_request", "gm_feature_request");
				add_submenu_page("", "", "", "read", "gm_premium_editions", "gm_premium_editions");
				add_submenu_page("", "", "", "read", "gm_system_information", "gm_system_information");
			break;

			case "editor":
				add_menu_page("Gallery Master", __("Gallery Master", gallery_master), "read", "gallery_master", "", plugins_url("assets/admin/images/icon.png",dirname(__FILE__)));
				add_submenu_page("gallery_master", "Dashboard", __("Dashboard", gallery_master), "read", "gallery_master","gallery_master");
				add_submenu_page("", "", "", "read", "gm_save_basic_details", "gm_save_basic_details");
				add_submenu_page("", "", "", "read", "gm_upload_media", "gm_upload_media");
				add_submenu_page("", "", "", "read", "gm_save_gallery", "gm_save_gallery");
				add_submenu_page("", "", "", "read", "gm_shortcode_generator", "gm_shortcode_generator");
				add_submenu_page("", "", "", "read", "gm_feature_request", "gm_feature_request");
				add_submenu_page("", "", "", "read", "gm_premium_editions", "gm_premium_editions");
				add_submenu_page("", "", "", "read", "gm_system_information", "gm_system_information");
			break;

			case "author":
				add_menu_page("Gallery Master", __("Gallery Master", gallery_master), "read", "gallery_master", "", plugins_url("assets/admin/images/icon.png",dirname(__FILE__)));
				add_submenu_page("gallery_master", "Dashboard", __("Dashboard", gallery_master), "read", "gallery_master","gallery_master");
				add_submenu_page("", "", "", "read", "gm_save_basic_details", "gm_save_basic_details");
				add_submenu_page("", "", "", "read", "gm_upload_media", "gm_upload_media");
				add_submenu_page("", "", "", "read", "gm_save_gallery", "gm_save_gallery");
				add_submenu_page("", "", "", "read", "gm_shortcode_generator", "gm_shortcode_generator");
				add_submenu_page("", "", "", "read", "gm_feature_request", "gm_feature_request");
				add_submenu_page("", "", "", "read", "gm_premium_editions", "gm_premium_editions");
				add_submenu_page("", "", "", "read", "gm_system_information", "gm_system_information");
			break;

			case "contributor":
				add_menu_page("Gallery Master", __("Gallery Master", gallery_master), "read", "gallery_master", "", plugins_url("assets/admin/images/icon.png",dirname(__FILE__)));
				add_submenu_page("gallery_master", "Dashboard", __("Dashboard", gallery_master), "read", "gallery_master","gallery_master");
				add_submenu_page("", "", "", "read", "gm_save_basic_details", "gm_save_basic_details");
				add_submenu_page("", "", "", "read", "gm_upload_media", "gm_upload_media");
				add_submenu_page("", "", "", "read", "gm_save_gallery", "gm_save_gallery");
				add_submenu_page("", "", "", "read", "gm_shortcode_generator", "gm_shortcode_generator");
				add_submenu_page("", "", "", "read", "gm_feature_request", "gm_feature_request");
				add_submenu_page("", "", "", "read", "gm_premium_editions", "gm_premium_editions");
				add_submenu_page("", "", "", "read", "gm_system_information", "gm_system_information");
			break;
		}
		
		//--------------------------------------------------------------------------------------------------------------//
		// CODE FOR CREATING PAGES
		//---------------------------------------------------------------------------------------------------------------//
		
		if(!function_exists("gallery_master"))
		{
			function gallery_master()
			{
				global $wpdb,$current_user,$user_role_permission;
				
				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}
				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}
				
				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}
				
				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/manage-galleries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/manage-galleries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}

		if(!function_exists("gm_save_basic_details"))
		{
			function gm_save_basic_details()
			{
				global $wpdb,$current_user,$user_role_permission,$wp_version;

				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/basic-details.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/basic-details.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}

		if(!function_exists("gm_upload_media"))
		{
			function gm_upload_media()
			{
				global $wpdb,$current_user,$user_role_permission,$wp_version;

				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/upload-media.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/upload-media.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}

		if(!function_exists("gm_save_gallery"))
		{
			function gm_save_gallery()
			{
				global $wpdb,$current_user,$user_role_permission,$wp_version;

				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/confirm.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/galleries/confirm.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}
		
		if(!function_exists("gm_shortcode_generator"))
		{
			function gm_shortcode_generator()
			{
				global $wpdb,$current_user,$user_role_permission;

				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/shortcode-generator/shortcode-generator.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/shortcode-generator/shortcode-generator.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}
		
		if(!function_exists("gm_feature_request"))
		{
			function gm_feature_request()
			{
				global $wpdb,$current_user,$user_role_permission;

				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/feature-requests/feature-request.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/feature-requests/feature-request.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}

		if(!function_exists("gm_premium_editions"))
		{
			function gm_premium_editions()
			{
				global $wpdb,$current_user,$user_role_permission;

				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/premium-editions/premium-editions.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/premium-editions/premium-editions.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}
	
		if(!function_exists("gm_system_information"))
		{
			function gm_system_information()
			{
				global $wpdb,$current_user,$user_role_permission;

				if(is_super_admin())
				{
					$gm_role = "administrator";
				}
				else
				{
					$gm_role = $wpdb->prefix . "capabilities";
					$current_user->role = array_keys($current_user->$gm_role);
					$gm_role = $current_user->role[0];
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/header.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/sidebar.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/queries.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "views/system-information/system-information.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "views/system-information/system-information.php";
				}

				if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php"))
				{
					include_once GALLERY_MASTER_BK_PLUGIN_DIR . "includes/footer.php";
				}
			}
		}
	}
}
?>