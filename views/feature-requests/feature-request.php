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
		$send_request = wp_create_nonce("gallery_master_feature_request");
		?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box red-sunglo">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-call-out"></i>
									<?php _e("Feature Requests",gallery_master)?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_feature_requests">
									<div class="form-body">
										<div class="note note-warning">
											<h4 class="block"><?php _e("Thank You!",gallery_master)?></h4>
											<p><?php _e("Kindly fill in the below form, If you would like to suggest some features which are not in the Plugin.",gallery_master)?></p>
											<p><?php _e("If you also have any suggestion/complaint, You can use the same form below.",gallery_master)?></p>
											<p><?php _e("You can also write us on",gallery_master)?>
												<a href="mailto:support@tech-prodigy.org" target="_blank">support@tech-prodigy.org</a>
											</p>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Your Name",gallery_master)?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Name which will be sent along with your Feature Request.", gallery_master)?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<div class="input-icon right">
														<i class="fa"></i>
														<input type="text" class="form-control" name="ux_txt_your_name" id="ux_txt_your_name" value="" placeholder="<?php _e("Enter your name here", gallery_master)?>">
													</div>	
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Your Email",gallery_master) ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Email which will be sent along with your Feature Request.", gallery_master)?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<div class="input-icon right">
														<i class="fa"></i>
														<input type="text" class="form-control" name="ux_txt_email_address" id="ux_txt_email_address" value=""  placeholder="<?php _e("Enter your Email here", gallery_master)?>">
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label"><?php _e("Feature Request",gallery_master) ?> :
												<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Feature Request which will be sent along.", gallery_master) ?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<div class="input-icon right">
												<i class="fa"></i>
												<textarea class="form-control" name="ux_txtarea_feature_request" id="ux_txtarea_feature_request" rows="8"  placeholder="<?php _e("Enter your Feature Request here", gallery_master)?>"></textarea>
											</div>
										</div>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="btn-set pull-right">
												<button type="submit" class="btn red-sunglo"><?php _e("Send Request",gallery_master)?></button>
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