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
		?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box red-sunglo">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-basket"></i><?php _e("Premium Editions",gallery_master)?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_premium_editions">
									<div class="form-body">
										<div class="row margin-bottom-40 margin-top-10">
											<div class="col-md-4">
												<div class="pricing hover-effect">
													<div class="pricing-head">
														<h3><?php _e("Beginner",gallery_master)?>
															<span><?php _e("For WordPress Beginners",gallery_master)?></span>
														</h3>
														<h4>
															<i>$</i><i>7</i>
															<span><?php _e("Per Month",gallery_master)?></span>
														</h4>
													</div>
													<ul class="pricing-content list-unstyled">
														<li>
															<i class="icon icon-check"></i><?php _e("For Single Installation",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Support Multisite",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Albums",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Multi-Lingual",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Auto Plugin Updates",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Default Lightbox",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Restore Settings",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Purge Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("SEO Friendly",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Thumbnail Settings",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Support Media Manager",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Upload Videos",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Widgets",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Bulk Deletion",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Flip Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Rotate Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Copy Images to Other Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Move Images to Other Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Exclude Images from Galleries",gallery_master)?>
														</li>
													</ul>
													<div class="pricing-footer">
														<a target="_blank" href="http://tech-prodigy.org/product/gallery-master-beginner-edition/" class="btn red-sunglo">
															<?php _e("Order Now",gallery_master)?> <i class="m-icon-swapright m-icon-white"></i>
														</a>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="pricing pricing-active hover-effect">
													<div class="pricing-head pricing-head-active">
														<h3><?php _e("Pro",gallery_master)?>
															<span><?php _e("For Wordpress Pro Users",gallery_master)?></span>
														</h3>
														<h4><i>$</i><i>9</i>
															<span><?php _e("Per Month",gallery_master)?></span>
														</h4>
													</div>
													<ul class="pricing-content list-unstyled">
														<li>
															<i class="icon icon-check"></i><?php _e("For Single Installation",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-close red-icon"></i><?php _e("Support Multisite",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Albums",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Multi-Lingual",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Auto Plugin Updates",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Default Lightbox",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Restore Settings",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Purge Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("SEO Friendly",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Thumbnail Settings",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Support Media Manager",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Upload Videos",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Widgets",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Bulk Deletion",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Flip Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Rotate Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Copy Images to Other Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Move Images to Other Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Exclude Images from Galleries",gallery_master)?>
														</li>
													</ul>
													<div class="pricing-footer">
														<a target="_blank" href="http://tech-prodigy.org/product/gallery-master-pro-edition/" class="btn red-sunglo">
															<?php _e("Oreder Now",gallery_master)?> <i class="m-icon-swapright m-icon-white"></i>
														</a>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="pricing hover-effect">
													<div class="pricing-head">
														<h3><?php _e("Developer",gallery_master)?>
															<span><?php _e("Upto 5 Websites for Pro Users",gallery_master)?></span>
														</h3>
														<h4><i>$</i><i>29</i>
															<span><?php _e("Per Month",gallery_master)?></span>
														</h4>
													</div>
													<ul class="pricing-content list-unstyled">
														<li>
															<i class="icon icon-check"></i><?php _e("For 5 Installations",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Support Multisite",gallery_master)?>
															<span class="required" aria-required="true">*</span>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Albums",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Unlimited Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Multi-Lingual",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Auto Plugin Updates",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Default Lightbox",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Restore Settings",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Purge Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("SEO Friendly",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Thumbnail Settings",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Support Media Manager",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Upload Videos",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Widgets",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Bulk Deletion",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Flip Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Rotate Images",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Copy Images to Other Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Move Images to Other Galleries",gallery_master)?>
														</li>
														<li>
															<i class="icon icon-check"></i><?php _e("Exclude Images from Galleries",gallery_master)?>
														</li>
													</ul>
													<div class="pricing-footer">
														<a target="_blank" href="http://tech-prodigy.org/product/gallery-master-developer-edition/" class="btn red-sunglo">
															<?php _e("Order Now",gallery_master)?> <i class="m-icon-swapright m-icon-white"></i>
														</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
}
?>