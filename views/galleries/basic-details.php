<?php
if (!is_user_logged_in())
{
	return;
}
else {
	switch ( $gm_role ) {
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
		$basic_details = wp_create_nonce("manage_basic_details");
		$bind_link = wp_create_nonce("save_unique_url");
		?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<a href="admin.php?page=gallery_master" class="btn red-sunglo"><?php _e("Back to Manage Galleries",gallery_master)?></a>
						</div>
						<div class="line-separator"></div>
						<div class="portlet box red-sunglo">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon icon-plus"></i><?php _e("Add New Gallery",gallery_master)?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_add_basic_details">
									<div class="form-body">
										<div class="form-wizard">
											<ul class="nav nav-pills nav-justified steps">
												<li class="active">
													<a aria-expanded="true" href="javascript:void(0);" class="step">
														<span class="number"> 1 </span>
														<span class="desc"><i class="fa fa-check"></i> <?php _e("Basic Details",gallery_master)?> </span>
													</a>
												</li>
												<li>
													<a href="javascript:void(0);" class="step">
														<span class="number"> 2 </span>
														<span class="desc"><i class="fa fa-check"></i><?php _e("Upload Images / Videos",gallery_master)?> </span>
													</a>
												</li>
												<li>
													<a href="javascript:void(0);" class="step">
														<span class="number"> 3 </span>
														<span class="desc"><i class="fa fa-check"></i><?php _e("Confirm & Finish",gallery_master)?> </span>
													</a>
												</li>
											</ul>
										</div>
										<div id="bar" class="progress progress-striped" role="progressbar">
											<div style="width: 30%;" class="progress-bar progress-bar-success"></div>
										</div>
										<div class="line-separator"></div>
										<div class="form-group">
											<label class="control-label"><?php _e("Gallery Title",gallery_master); ?> :
												<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Gallery Title here. This would be displayed when using Short Code.", gallery_master) ?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<input type="text" class="form-control" name="ux_txt_gallery_title" id="ux_txt_gallery_title" placeholder="<?php _e("Enter Your Gallery Title", gallery_master) ?>" value="<?php echo isset($array_gallery_details["gallery_title"]) ? stripcslashes(urldecode($array_gallery_details["gallery_title"])) : "Untitled Gallery";?>">
										</div>
										<div class="form-group">
											<label class="control-label"><?php _e("Gallery Description",gallery_master); ?> :
												<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Gallery Description here. This would be displayed when using Short Code.", gallery_master) ?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<div class="input-icon right">
												<textarea class="form-control" name="ux_txtarea_gallery_description" id="ux_txtarea_gallery_description" rows="8" placeholder="<?php _e("Enter Your Gallery Description", gallery_master) ?>"><?php echo isset($array_gallery_details["gallery_description"]) ? stripcslashes(urldecode($array_gallery_details["gallery_description"])) : "";?></textarea>
											</div>
										</div>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="btn-set pull-right" style="border-right: none;">
												<button type="submit" class="btn red-sunglo" ><?php _e("Next Step >>",gallery_master)?></button>
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