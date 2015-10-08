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
								<form id="ux_frm_save_gallery">
									<div class="form-body">
										
										<div class="form-wizard">
											<ul class="nav nav-pills nav-justified steps">
												<li class="active">
													<a aria-expanded="true" href="javascript:void(0);" class="step">
														<span class="number"> 1 </span>
														<span class="desc"><i class="fa fa-check"></i> <?php _e("Basic Details",gallery_master)?> </span>
													</a>
												</li>
												<li class="active">
													<a href="javascript:void(0);" class="step">
														<span class="number"> 2 </span>
														<span class="desc"><i class="fa fa-check"></i><?php _e("Upload Images / Videos",gallery_master)?> </span>
													</a>
												</li>
												<li class="active">
													<a href="javascript:void(0);" class="step">
														<span class="number"> 3 </span>
														<span class="desc"><i class="fa fa-check"></i><?php _e(" Confirm & Finish",gallery_master)?> </span>
													</a>
												</li>
											</ul>
										</div>
										<div id="bar" class="progress progress-striped" role="progressbar">
											<div style="width: 100%;" class="progress-bar progress-bar-success"></div>
										</div>
										<div class="line-separator"></div>
										<form id="ux_frm_confirm_finish">
											<div class="form-body">
												<div class="custom-form-body">
													<h3 class="form-section"><?php _e("Gallery Details",gallery_master); ?></h3>
													<div class="form-group">
														<label class="control-label">
															<b><?php _e("Gallery Title", gallery_master) ?></b>
														</label>
														<div class="right gallery_text_italic">
															<?php echo stripcslashes(urldecode($array_gallery_details["gallery_title"]));?>
														</div>
													</div>
													<?php
													if(isset($array_gallery_details["gallery_description"]) && $array_gallery_details["gallery_description"] != "")
													{
														?>
														<div class="form-group">
															<div class="form-group">
																<label class="control-label">
																	<b><?php _e("Description", gallery_master) ?></b>
																</label>
																<div class="right gallery_text_italic">
																	<?php echo stripcslashes(htmlspecialchars_decode(urldecode($array_gallery_details["gallery_description"])));?>
																</div>
															</div>
														</div>
														<?php
													}
													?>
													<div class="form-group">
														<label class="control-label">
															<b><?php _e("Total No. of Images/Videos uploaded", gallery_master) ?></b>
														</label>
														<div class="right gallery_text_italic">
															<?php echo $count_pic;?>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<b><?php _e("Uploaded By", gallery_master) ?></b>
																</label>
																<div class="right gallery_text_italic">
																	<?php echo $array_gallery_details["author"];?>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<b><?php _e("Date of Creation", gallery_master) ?></b>
																</label>
																<div class="right gallery_text_italic">
																	<?php echo date("F j, Y", strtotime($array_gallery_details["gallery_date"]));?>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<b><?php _e("Last Edited By", gallery_master) ?></b>
																</label>
																<div class="right gallery_text_italic">
																	<?php echo $array_gallery_details["edited_by"];?>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<b><?php _e("Last Edited On", gallery_master) ?></b>
																</label>
																<div class="right gallery_text_italic">
																	<?php echo date("F j, Y", strtotime($array_gallery_details["edited_on"]));?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="btn-set pull-left" style="border-right: none;">
												<button type="button" class="btn red-sunglo" onclick="gm_proceed_next(2,<?php echo $gallery_id;?>);" ><?php _e("<< Previous Step",gallery_master)?></button>
											</div>
											<div class="btn-set pull-right" style="border-right: none;">
												<button type="button" class="btn red-sunglo" onclick="gm_proceed_next(4,<?php echo $gallery_id;?>);" ><?php _e("Save Changes &amp; Close",gallery_master)?></button>
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