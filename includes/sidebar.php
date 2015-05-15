<?php
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
	$count_img = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SELECT count(gallery_id) FROM ".gallery_master_galleries()." WHERE type = %s",
			"gallery"
		)
	);
	?>
	<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse">
			<ul class="page-sidebar-menu" data-slide-speed="200">
				<li class="sidebar-search-wrapper" style="padding:20px;text-align:center">
					<a class="gm-logo" href="http://tech-prodigy.org/" target="_blank">
						<img src="<?php echo GALLERY_MASTER_BK_PLUGIN_URL . "/assets/admin/images/gallery-master-logo.png"?>" width="200px"/>
					</a>
				</li>
				<li class="" id="ux_li_galleries">
					<a href="javascript:;">
						<i class="icon-grid"></i>
						<span class="title"><?php _e("Galleries", gallery_master); ?></span>
					</a>
					<ul class="sub-menu">
						<li id="ux_li_add_new_gallery">
							<a href="admin.php?page=gm_save_basic_details">
								<i class="icon-plus"></i>
								<?php _e("Add New Gallery", gallery_master); ?>
							</a>
						</li>
						<li id="ux_li_manage_galleries">
							<a href="admin.php?page=gallery_master">
								<i class="icon-layers"></i>
								<?php _e("Manage Galleries", gallery_master); ?>
								<span class="badge badge-warning badge-roundless"><?php echo $count_img; ?></span>
							</a>
						</li>
					</ul>
				</li>
				<li class="" id="ux_li_shortcodes">
					<a href="admin.php?page=gm_shortcode_generator">
						<i class="icon-rocket"></i>
						<span class="title"><?php _e("Shortcode Generator", gallery_master); ?></span>
						<span class="arrow-custom"></span>
					</a>
				</li>
				<li class="" id="ux_li_feature_request">
					<a href="admin.php?page=gm_feature_request">
						<i class="icon-call-out"></i>
						<span class="title"><?php _e("Feature Requests", gallery_master); ?></span>
						<span class="arrow-custom"></span>
					</a>
				</li>
				<li class="" id="ux_li_premium_editions">
					<a href="admin.php?page=gm_premium_editions">
						<i class="icon-basket"></i>
						<span class="title"><?php _e("Premium Editions", gallery_master); ?></span>
						<span class="arrow-custom"></span>
					</a>
				</li>
				<li class="" id="ux_li_system_information">
					<a href="admin.php?page=gm_system_information">
						<i class="icon-settings"></i>
						<span class="title"><?php _e("System Information", gallery_master); ?></span>
						<span class="arrow-custom"></span>
					</a>
				</li>
			</ul>
		</div>
	</div>
<?php
}
?>