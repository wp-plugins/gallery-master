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
		$gm_license = wp_create_nonce("gallery_master_licensing");
		?>
		<div class="page-content-wrapper">
			<div  class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box red-sunglo">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-lock"></i>
									<?php _e("Licensing Module",gallery_master); ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_licensing_gallery_master">
									<div class="form-body">
										<div class="note note-warning">
											<h4 class="block"><?php _e("Important Notice!",gallery_master)?></h4>
											<p><?php _e("Congratulations! You have recently purchased the Gallery Master and now you need to activate the license in order to unlock it!",gallery_master)?>
											</p>
											<p><?php _e("Kindly fill in the required details and click on Validate License to unlock it.",gallery_master)?></p>
											<p><?php _e("If you face any issues activating the license, You may contact us at ",gallery_master)?>
												<a href="mailto:support@tech-prodigy.org" target="_blank">support@tech-prodigy.org</a>
											</p>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Product Name",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("This field shows your installed Product.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<input type="text" class="form-control" disabled="disabled" name="ux_txt_product_name" id="ux_txt_product_name" value="<?php echo esc_attr($gm_licensing->type);?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Current Version",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("This field shows your installed Product Version.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<input type="text" class="form-control" disabled="disabled" name="ux_txt_current_version" id="ux_txt_current_version" value="<?php echo esc_attr($gm_licensing->version);?>">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Website URL",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("This field shows your website URL.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<input type="text" class="form-control" disabled="disabled" name="ux_txt_website_url" id="ux_txt_website_url" value="<?php echo esc_attr($gm_licensing->url);?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Order ID",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Order ID received after purchasing the Product. This will be used for Validating the License.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<div class="input-icon right">
														<i class="fa"></i>
														<input type="text" class="form-control" name="ux_txt_order_id" id="ux_txt_order_id" placeholder="<?php _e("Enter the Order ID recieved after making the purchase", gallery_master) ?>" value="<?php echo $gm_licensing->order_id;?>">
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label"><?php _e("API KEY",gallery_master); ?> :
												<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your API key received after purchasing the Product. This will be used for Validating the License.", gallery_master) ?>" data-placement="right"></i>
												<span class="required" aria-required="true">*</span>
											</label>
											<div class="input-icon right">
												<i class="fa"></i>
												<input type="text" class="form-control" name="ux_txt_api_key" id="ux_txt_api_key" value="<?php echo esc_attr($gm_licensing->api_key);?>" placeholder="<?php _e("Enter the API Key recieved after making the purchase", gallery_master)?>">
											</div>
										</div>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="btn-set pull-right">
												<button type="submit" class="btn red-sunglo"><?php _e("Validate License!",gallery_master)?></button>
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