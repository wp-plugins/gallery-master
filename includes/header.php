<?php
if (!is_user_logged_in())
{
	return;
}
else
{
	switch ( $gm_role )
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
	if ( ! current_user_can ( $user_role_permission ) )
	{
		return;
	}
	else
	{
		?>
		<div class="wrap">
			<div id="welcome-panel" class="welcome-panel">
				<input id="welcomepanelnonce" name="welcomepanelnonce" value="b0cc378aab" type="hidden">
				<div class="welcome-panel-content">
					<h3><?php _e("Welcome to Gallery Master",gallery_master)?></h3>
					<p class="about-description"><?php _e("We’ve assembled some links to get you started:",gallery_master)?></p>
					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column">
							<h4><?php _e("Get Started",gallery_master)?></h4>
							<a class="button button-primary button-hero load-customize hide-if-no-customize custom-btn" target="_blank" href="http://tech-prodigy.org/demos/"><?php _e("View Demos Online",gallery_master)?></a>
						</div>
						<div class="welcome-panel-column">
							<h4><?php _e("More Links",gallery_master)?></h4>
							<ul>
								<li>
									<a target="_blank" href="http://tech-prodigy.org/" class="welcome-icon welcome-write-blog" target="_blank"><?php _e("Upgrade To Premium",gallery_master)?></a>
								</li>
								<li>
									<a href="http://tech-prodigy.org/demos/" class="welcome-icon welcome-view-site" target="_blank"><?php _e("Online Demos",gallery_master)?></a>
								</li>
								<li>
									<a href="http://tech-prodigy.org/forums/forum/community-support-forum/" class="welcome-icon welcome-write-blog" target="_blank"><?php _e("Community Forum",gallery_master)?></a>
								</li>
							</ul>
						</div>
						<div class="welcome-panel-column welcome-panel-last">
							<h4><?php _e("Important Pages",gallery_master)?></h4>
							<ul>
								<li>
									<a href="admin.php?page=gm_feature_request" class="welcome-icon welcome-widgets-menus" target="_blank"><?php _e("Feature Requests",gallery_master)?> </a>
								</li>
								<li>
									<a href="admin.php?page=gm_premium_editions" class="welcome-icon welcome-add-page" target="_blank"><?php _e("Premium Editions",gallery_master)?></a>
								</li>
								<li>
									<a href="http://tech-prodigy.org/documentation/" class="welcome-icon welcome-learn-more" target="_blank"><?php _e("Documentation",gallery_master)?></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="page-container">
			<div id="manage_messages" style="display:none;">
				<div class="radio">
					<input type="radio" value="success" checked="checked" />
				</div>
			</div>
		<?php
	}
}
?>