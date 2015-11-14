<?php
/*
Plugin Name: Gallery Master Lite Edition
Plugin URI: http://tech-prodigy.org
Description: Gallery Master is an interactive WordPress photo gallery plugin, best fit for creative and corporate portfolio websites.
Version: 1.0.19
Author: techprodigy
Author URI: http://tech-prodigy.org
*/

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR DEFINING CONSTANTS
//--------------------------------------------------------------------------------------------------------------//
$running_year = date("Y");
$running_month = date("m");
$today_date = date("d");

if (!defined("GALLERY_MASTER_FILE")) define("GALLERY_MASTER_FILE","gallery-master/gallery-master.php");
if (!defined("GALLERY_MASTER_BK_PLUGIN_DIR")) define("GALLERY_MASTER_BK_PLUGIN_DIR",  plugin_dir_path(__FILE__));
if (!defined("GALLERY_MASTER_BK_PLUGIN_DIRNAME")) define("GALLERY_MASTER_BK_PLUGIN_DIRNAME", plugin_basename(dirname(__FILE__)));
if (!defined("GALLERY_MASTER_BK_PLUGIN_URL")) define("GALLERY_MASTER_BK_PLUGIN_URL", content_url()."/plugins/".GALLERY_MASTER_BK_PLUGIN_DIRNAME );
if (!defined("GALLERY_MASTER_MAIN_DIR")) define("GALLERY_MASTER_MAIN_DIR", dirname(dirname(dirname(__FILE__)))."/gallery-master/");
if (!defined("GALLERY_MASTER_YEAR_DIR")) define("GALLERY_MASTER_YEAR_DIR", GALLERY_MASTER_MAIN_DIR.$running_year."/");
if (!defined("GALLERY_MASTER_MONTH_DIR")) define("GALLERY_MASTER_MONTH_DIR", GALLERY_MASTER_YEAR_DIR.$running_month."/");
if (!defined("GALLERY_MASTER_DATE_DIR")) define("GALLERY_MASTER_DATE_DIR", GALLERY_MASTER_MONTH_DIR.$today_date."/");
if (!defined("GALLERY_MASTER_UPLOAD_DIR")) define("GALLERY_MASTER_UPLOAD_DIR", GALLERY_MASTER_DATE_DIR."uploads/");
if (!defined("GALLERY_MASTER_THUMBS_DIR")) define("GALLERY_MASTER_THUMBS_DIR", GALLERY_MASTER_DATE_DIR."thumbs/");
if (!defined("GALLERY_MASTER_UPLOAD_PATH")) define("GALLERY_MASTER_UPLOAD_PATH", $running_year."/".$running_month."/".$today_date."/");
if (!defined("GALLERY_MASTER_MAIN_URL")) define("GALLERY_MASTER_MAIN_URL", content_url()."/gallery-master/");
if (!defined("GALLERY_MASTER_THUMBS_URL")) define("GALLERY_MASTER_THUMBS_URL", content_url()."/gallery-master/".GALLERY_MASTER_UPLOAD_PATH."thumbs/");
if (!defined("gallery_master")) define("gallery_master", "gallery-master");

//-----------------------------------------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING GALLERY MASTER FOLDERS
//----------------------------------------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_thumbnail_folder"))
{
	function gallery_master_thumbnail_folder()
	{
		if (!is_dir(GALLERY_MASTER_MAIN_DIR))
		{
			wp_mkdir_p(GALLERY_MASTER_MAIN_DIR);
		}

		if (!is_dir(GALLERY_MASTER_YEAR_DIR))
		{
			wp_mkdir_p(GALLERY_MASTER_YEAR_DIR);
		}

		if (!is_dir(GALLERY_MASTER_MONTH_DIR))
		{
			wp_mkdir_p(GALLERY_MASTER_MONTH_DIR);
		}

		if (!is_dir(GALLERY_MASTER_DATE_DIR))
		{
			wp_mkdir_p(GALLERY_MASTER_DATE_DIR);
		}

		if (!is_dir(GALLERY_MASTER_UPLOAD_DIR))
		{
			wp_mkdir_p(GALLERY_MASTER_UPLOAD_DIR);
		}

		if (!is_dir(GALLERY_MASTER_THUMBS_DIR))
		{
			wp_mkdir_p(GALLERY_MASTER_THUMBS_DIR);
		}
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR INSTALLING PLUGIN
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("plugin_install_script_for_gallery_master"))
{
	function plugin_install_script_for_gallery_master()
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

		if (is_multisite())
		{
			$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach($blog_ids as $blog_id)
			{
				switch_to_blog($blog_id);
				if(file_exists(GALLERY_MASTER_BK_PLUGIN_DIR. "lib/install-script.php"))
				{
					include GALLERY_MASTER_BK_PLUGIN_DIR . "lib/install-script.php";
				}
				restore_current_blog();
			}
		}
		else
		{
			if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "lib/install-script.php"))
			{
				include_once GALLERY_MASTER_BK_PLUGIN_DIR . "lib/install-script.php";
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR UNINSTALLING PLUGIN
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("plugin_uninstall_script_for_gallery_master"))
{
	function plugin_uninstall_script_for_gallery_master()
	{
		delete_option("gallery-master-automatic_update");
		wp_clear_scheduled_hook("gallery_master_auto_update");
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR GALLERY MASTER LANGUAGES
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_plugin_load_text_domain"))
{
	function gallery_master_plugin_load_text_domain()
	{

	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CREATING SIDEBAR MENUS
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("create_global_menus_for_gallery_master"))
{
	function create_global_menus_for_gallery_master()
	{
		global $current_user, $wpdb, $user_role_permission;

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

		if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "lib/sidebar-menus.php"))
		{
			include_once GALLERY_MASTER_BK_PLUGIN_DIR . "lib/sidebar-menus.php";
		}
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CREATING TOPBAR MENUS
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("create_top_bar_gallery_master_menu"))
{
	function create_top_bar_gallery_master_menu($meta = TRUE)
	{

	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CALLING JS ON BACKEND
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_backend_scripts_calls"))
{
	function gallery_master_backend_scripts_calls()
	{
		wp_enqueue_script("jquery");
		wp_enqueue_script("plupload.full.min.js",plugins_url("assets/global/plugins/pluploader/js/plupload.full.min.js",__FILE__),array("jquery-ui-draggable","jquery-ui-sortable","jquery-ui-dialog","jquery-ui-widget","jquery-ui-progressbar"),false);
		wp_enqueue_script("jquery.ui.plupload.js",plugins_url("assets/global/plugins/pluploader/js/jquery.ui.plupload.js",__FILE__));
		wp_enqueue_script("bootstrap.js",plugins_url("assets/global/plugins/bootstrap/js/bootstrap.js",__FILE__));
		wp_enqueue_script("bootstrap-tabdrop.js",plugins_url("assets/global/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js",__FILE__));
		wp_enqueue_script("jquery.validate.js",plugins_url("assets/global/plugins/validation/jquery.validate.js",__FILE__));
		wp_enqueue_script("jquery.dataTables.min.js",plugins_url("assets/global/plugins/datatables/media/js/jquery.dataTables.min.js",__FILE__));
		wp_enqueue_script("dataTables.bootstrap.js",plugins_url("assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js",__FILE__));
		wp_enqueue_script("toastr.min.js",plugins_url("assets/global/plugins/bootstrap-toastr/toastr.min.js",__FILE__));

	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CALLING JS ON FRONTEND
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_frontend_scripts_calls"))
{
	function gallery_master_frontend_scripts_calls()
	{
		wp_enqueue_script("jquery");
		wp_enqueue_script("jquery.masonry.min.js",plugins_url("assets/global/plugins/masonry/jquery.masonry.min.js",__FILE__));
		wp_enqueue_script("imgLiquid.js",plugins_url("assets/global/plugins/imgLiquidFill/imgLiquid.js",__FILE__));
		wp_enqueue_script("wp-lightbox.js",plugins_url("assets/global/plugins/lightbox/js/wp-lightbox.js",__FILE__));
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CALLING CSS ON BACKEND
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_backend_css_calls"))
{
	function gallery_master_backend_css_calls()
	{

		wp_enqueue_style("jquery-ui.css", plugins_url("assets/global/plugins/pluploader/css/jquery-ui.css",__FILE__));
		wp_enqueue_style("jquery.ui.plupload.css", plugins_url("assets/global/plugins/pluploader/css/jquery.ui.plupload.css",__FILE__));
		wp_enqueue_style("simple-line-icons.css", plugins_url("assets/global/plugins/simple-line-icons/simple-line-icons.css",__FILE__));
		wp_enqueue_style("bootstrap.css", plugins_url("assets/global/plugins/bootstrap/css/bootstrap.css",__FILE__));
		wp_enqueue_style("components.css", plugins_url("assets/global/css/components.css",__FILE__));
		wp_enqueue_style("plugins.css", plugins_url("assets/global/css/plugins.css",__FILE__));
		wp_enqueue_style("layout.css", plugins_url("assets/admin/layout/css/layout.css",__FILE__));
		wp_enqueue_style("gallery-master-default.css", plugins_url("assets/admin/layout/css/themes/default.css",__FILE__));
		wp_enqueue_style("gallery-master-custom.css", plugins_url("assets/admin/layout/css/custom.css",__FILE__));
		wp_enqueue_style("pricing-table.css", plugins_url("assets/admin/pages/css/pricing-table.css",__FILE__));
		wp_enqueue_style("dataTables.bootstrap.css",plugins_url("assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css",__FILE__));
		wp_enqueue_style("toastr.min.css", plugins_url("assets/global/plugins/bootstrap-toastr/toastr.min.css",__FILE__));
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CALLING CSS ON FRONTEND
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_frontend_css_calls"))
{
	function gallery_master_frontend_css_calls()
	{
		wp_enqueue_style("wp-lightbox.css",plugins_url("assets/global/plugins/lightbox/css/wp-lightbox.css",__FILE__));
		wp_enqueue_style("gallery-master-frontend-custom.css", plugins_url("assets/admin/layout/css/frontend-custom.css",__FILE__));
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR REPLACING TABLE NAMES
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_galleries"))
{
	function gallery_master_galleries()
	{
		global $wpdb;
		return $wpdb->prefix . "gallery_master";
	}
}

if(!function_exists("gallery_master_meta"))
{
	function gallery_master_meta()
	{
		global $wpdb;
		return $wpdb->prefix . "gallery_master_meta";
	}
}

if(!function_exists("gallery_master_settings"))
{
	function gallery_master_settings()
	{
		global $wpdb;
		return $wpdb->prefix . "gallery_master_settings";
	}
}

if(!function_exists("gallery_master_licensing"))
{
	function gallery_master_licensing()
	{
		global $wpdb;
		return $wpdb->prefix . "gallery_master_licensing";
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CALLING AJAX BASED FUNCTIONS TO BE CALLED ON ACTION TYPE
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("create_ajax_library_gallery_master"))
{
	function create_ajax_library_gallery_master()
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

		if(isset($_REQUEST["action"]))
		{
			switch($_REQUEST["action"])
			{
				case "gallery_master_action_library":
					if(file_exists(GALLERY_MASTER_BK_PLUGIN_DIR ."lib/action-library.php"))
					{
						include_once GALLERY_MASTER_BK_PLUGIN_DIR . "lib/action-library.php";
					}
				break;

				case "manage_images_upload_library":
					if(file_exists(GALLERY_MASTER_BK_PLUGIN_DIR ."lib/upload.php"))
					{
						include_once GALLERY_MASTER_BK_PLUGIN_DIR . "lib/upload.php";
					}
				break;
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR CREATING HELPER CLASS
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("create_gallery_master_helper_class"))
{
	function create_gallery_master_helper_class()
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

		if (file_exists(GALLERY_MASTER_BK_PLUGIN_DIR . "lib/helper.php"))
		{
			include_once GALLERY_MASTER_BK_PLUGIN_DIR . "lib/helper.php";
		}
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR SHORTCODE
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("gallery_master_short_code"))
{
	function gallery_master_short_code($atts)
	{
		extract(shortcode_atts(array(
			"theme" => "",
			"source_type" => "",
			"ids" => "",
			"id" => "",
			"gallery_type" => "",
			"layout" => "",
			"show_title" => "",
			"show_desc" => "",
			"height" => "",
			"width" => "",
			"border_style" => "",
			"margin" => "",
			"padding" => "",
			"title_color" => "",
			"desc_color" => "",
			"lightbox" => "",
			"order_by" => "",
		), $atts));
		if(!is_feed())
		{
		return extract_short_code_for_gallery_master_images($theme,$source_type,urldecode($ids),intval($id),$gallery_type,$layout,$show_title,$show_desc,$height,$width,$border_style,$margin,$padding,$title_color,$desc_color,$lightbox,$order_by);
		}
	}
}

//--------------------------------------------------------------------------------------------------------------//
// FUNCTION FOR EXTRACTING SHORTCODE
//--------------------------------------------------------------------------------------------------------------//

if(!function_exists("extract_short_code_for_gallery_master_images"))
{
	function extract_short_code_for_gallery_master_images($theme,$source_type,$gallery_ids,$gallery_id,$gallery_type,$layout,$show_title,$show_desc,$thumb_height,$thumb_width,$border_style,$margin,$padding,$title_color,$desc_color,$lightbox,$order_by)
	{
		ob_start();

		include GALLERY_MASTER_BK_PLUGIN_DIR . "users-views/helper.php";
		include GALLERY_MASTER_BK_PLUGIN_DIR . "users-views/include-before.php";

		switch($theme)
		{
			case "thumbnails":
				include GALLERY_MASTER_BK_PLUGIN_DIR . "users-views/thumbnail-gallery.php";
			break;

			case "masonry":
				include GALLERY_MASTER_BK_PLUGIN_DIR . "users-views/masonry-gallery.php";
			break;
		}

		include GALLERY_MASTER_BK_PLUGIN_DIR . "users-views/include-after.php";

		$gallery_master_output = ob_get_clean();
		wp_reset_query();
		return $gallery_master_output;
	}
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR PLUGIN UPDATE MESSAGE
//--------------------------------------------------------------------------------------------------------------//
if(!function_exists("gallery_master_plugin_update_message"))
{
	function gallery_master_plugin_update_message($args)
	{
		$response = wp_remote_get( 'https://plugins.svn.wordpress.org/gallery-master/trunk/readme.txt' );
		if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) )
		{
			// Output Upgrade Notice
			$matches        = null;
			$regexp         = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote($args['Version']) . '\s*=|$)~Uis';
			$upgrade_notice = '';
			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));
				$upgrade_notice .= '<div class="gallery_plugin_message">';
				foreach ( $changelog as $index => $line ) {
					$upgrade_notice .= "<p>".$line."</p>";
				}
				$upgrade_notice .= '</div> ';
				echo $upgrade_notice;
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR PLUGIN AUTOMATIC UPDATE
//--------------------------------------------------------------------------------------------------------------//
$is_option_auto_update = get_option("gallery-master-automatic_update");

if($is_option_auto_update == "" || $is_option_auto_update == "1")
{
	if (!wp_next_scheduled("gallery_master_auto_update"))
	{
		wp_schedule_event(time(), "daily", "gallery_master_auto_update");
	}
	add_action("gallery_master_auto_update", "gallery_master_plugin_autoUpdate");
}
else
{
	wp_clear_scheduled_hook("gallery_master_auto_update");
}
function gallery_master_plugin_autoUpdate()
{
	try
	{
		require_once(ABSPATH . "wp-admin/includes/class-wp-upgrader.php");
		require_once(ABSPATH . "wp-admin/includes/misc.php");
		define("FS_METHOD", "direct");
		require_once(ABSPATH . "wp-includes/update.php");
		require_once(ABSPATH . "wp-admin/includes/file.php");
		wp_update_plugins();
		ob_start();
		$plugin_upgrader = new Plugin_Upgrader();
		$plugin_upgrader->upgrade("gallery-master/gallery-master.php");
		$output = @ob_get_contents();
		@ob_end_clean();
	}
	catch(Exception $e)
	{
	}
}

$gallery_version = get_option("gallery-master-key");
if($gallery_version == "" || $gallery_version == "1.0")
{
	add_action("admin_init", "plugin_install_script_for_gallery_master");
}

//--------------------------------------------------------------------------------------------------------------//
// CALL HOOKS
//--------------------------------------------------------------------------------------------------------------//

// add_action Hook called for creating thumbnail folders
add_action("admin_init", "gallery_master_thumbnail_folder");

// activation Hook called for installation of Gallery Master
register_activation_hook(__FILE__, "plugin_install_script_for_gallery_master");

// uninstall Hook called for uninstallation of Gallery Master
register_uninstall_hook( __FILE__, "plugin_uninstall_script_for_gallery_master");

// add_action Hook called for adding languages
add_action("plugins_loaded", "gallery_master_plugin_load_text_domain");

// add_action Hook called for creating global menus
add_action("admin_menu", "create_global_menus_for_gallery_master");

// add_action Hook called for creating menus on admin bar
add_action("admin_bar_menu", "create_top_bar_gallery_master_menu", 100);

// add_action Hook called for adding css on backend
add_action("admin_init", "gallery_master_backend_css_calls");

// add_action Hook called for adding js on backend
add_action("admin_init", "gallery_master_backend_scripts_calls");

// add_action Hook called for adding js on frontend
add_action("init", "gallery_master_frontend_scripts_calls");

// add_action Hook called for adding css on frontend
add_action("init", "gallery_master_frontend_css_calls");

// add_action Hook called for creating helper class
add_action("admin_init", "create_gallery_master_helper_class");

// add_action Hook called for registering libraries
add_action("admin_init", "create_ajax_library_gallery_master");

// add_shortcode hook called to add shortcode on page/post
add_shortcode("gallery_master", "gallery_master_short_code");

add_action("in_plugin_update_message-".GALLERY_MASTER_FILE,"gallery_master_plugin_update_message" );
