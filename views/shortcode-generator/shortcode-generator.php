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
		?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box red-sunglo">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon-rocket"></i><?php _e("Shortcode Generator", gallery_master); ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_shortcode_generator">
									<div class="form-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Theme View",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose Thumbnails to display Images in a fixed dimension format or Choose Masonry to display Images as per aspect ratio.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<select class="form-control" name="ux_ddl_theme_view" id="ux_ddl_theme_view">
														<option value="thumbnails"><?php _e("Thumbnails",gallery_master)?></option>
														<option value="masonry"><?php _e("Masonry",gallery_master)?></option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Display",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose the relevant option to display Galleries.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<select class="form-control" name="ux_ddl_display_galleries" id="ux_ddl_display_galleries" onchange ="change_galleries()">
														<option value="all" style="color:red;"><?php _e("All Galleries",gallery_master)?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="selected" style="color:red;"><?php _e("Selected Galleries",gallery_master)?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="individual"><?php _e("Individual Gallery",gallery_master)?></option>
													</select>
												</div>
											</div>
										</div>
										<div id ="ux_ddl_select_galleries">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="control-label"><?php _e("Choose",gallery_master); ?> :
															<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose the Selected Galleries to display the Images.", gallery_master) ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<select class="form-control" name="ux_ddl_choose" id="ux_ddl_choose" multiple="multiple">
															<?php
															foreach($galleries as $row)
															{
																?>
																<option value="<?php echo $row->gallery_id;?>"><?php echo urldecode ( $row->gallery_meta_value );?></option>
															<?php
															}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div id ="ux_ddl_individual_galleries">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="control-label"><?php _e("Choose",gallery_master); ?> :
															<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose the Individual Gallery to display Images.", gallery_master) ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<select class="form-control" name="ux_ddl_choose_gallery" id="ux_ddl_choose_gallery">
															<?php
															foreach($galleries as $row)
															{
																?>
																<option value="<?php echo $row->gallery_id;?>"><?php echo urldecode ( $row->gallery_meta_value );?></option>
															<?php
															}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Gallery Type",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose the relevant Gallery Type to display Image. Compact Gallery to display Gallery with Covers, Extended Gallery to display Galleries with Cover and Type and Description and Only Images to Show Images with Gallery Cover. ", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<select class="form-control" name="ux_ddl_gallery_type" id="ux_ddl_gallery_type" onchange ="change_gallery_type()">
														<option value="compact" style="color:red;"><?php _e("Compact Gallery",gallery_master)?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="extended" style="color:red;"><?php _e("Extended Gallery",gallery_master)?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="only_images" ><?php _e("Only Images",gallery_master)?></option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Layout Type",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose the layout View to display Images i.e. List View and Grid View.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">* <?php _e(" (Available in Pro Editions)")?></span>
													</label>
													<select class="form-control" name="ux_ddl_layout_type" id="ux_ddl_layout_type">
														<option value="list" class="required"><?php _e("List View",gallery_master)?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="grid" class="required"><?php _e("Grid View",gallery_master)?><?php _e(" (Available in Pro Editions)")?></option>
													</select>
												</div>
											</div>
										</div>
										<div id = "ux_ddl_type">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php _e("Gallery Title",gallery_master); ?> :
															<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose Show to show Gallery Title or Hide to hide it.", gallery_master) ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<select class="form-control" name="ux_ddl_gallery_title" id="ux_ddl_gallery_title">
															<option value="show"><?php _e("Show",gallery_master)?></option>
															<option value="hide"><?php _e("Hide",gallery_master)?></option>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><?php _e("Gallery Description",gallery_master); ?> :
															<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose Show to show Gallery Description or Hide to hide it.", gallery_master) ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<select class="form-control" name="ux_ddl_gallery_description" id="ux_ddl_gallery_description">
															<option value="show"><?php _e("Show",gallery_master)?></option>
															<option value="hide"><?php _e("Hide",gallery_master)?></option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Gallery Thumbnails Dimensions (px)",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please provide height and width of the Thumbnails to be displayed.", gallery_master) ?>" data-placement="right"></i>
														<span style="color:red;"class="required" aria-required="true">*<?php _e(" (Available in Pro Editions)")?></span>
													</label>
													<div class="input-icon right">
														<i class="fa custom-icon-set"></i>
														<input name="ux_txt_gallery_thumb_dimension_width" id="ux_txt_gallery_thumb_dimension_width" type="text" class="form-control custom-input-medium input-inline " value="200" placeholder="<?php _e("Enter Width For Gallery", gallery_master) ?>">
														<input name="ux_txt_gallery_thumb_dimension_height" id="ux_txt_gallery_thumb_dimension_height" type="text" class="form-control custom-input-medium input-inline " value="160" placeholder="<?php _e("Enter Height For Gallery", gallery_master) ?>">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Gallery Thumbnails Border Style",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please provide the relevant Style for the Thumbnails. ", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*<?php _e(" (Available in Pro Editions)")?></span>
													</label>
													<div class="input-icon right">
														<i class="fa custom-icon-set"></i>
														<select class="form-control input-width-25 input-inline" name="ux_ddl_gallery_thumb_border_size" id="ux_ddl_gallery_thumb_border_size">
															<?php
															for($flag = 2;$flag<=20;$flag++)
															{
																?>
																<option value="<?php echo $flag?>"><?php echo $flag?></option>
															<?php
															}
															?>
														</select>
														<select class="form-control input-width-25 input-inline" name="ux_ddl_gallery_thumb_border_style" id="ux_ddl_gallery_thumb_border_style">
															<option value="solid"><?php _e("Solid",gallery_master)?></option>
															<option value="Dotted" ><?php _e("Dotted",gallery_master)?></option>
															<option value="Double"><?php _e("Double",gallery_master)?></option>
															<option value="Dashed"><?php _e("Dashed",gallery_master)?></option>
														</select>
														<input name="ux_txt_gallery_thumb_border_color" id="ux_txt_gallery_thumb_border_color" type="text" class="form-control input-normal input-inline" value="#000" placeholder="<?php _e("Enter Border Color", gallery_master) ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Margin Between Gallery Thumbnails (px)",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please provide the Margin between the Gallery Thumbnails.", gallery_master) ?>" data-placement="right"></i>
														<span style="color:red;" class="required" aria-required="true">*<?php _e(" (Available in Pro Editions)")?></span>
													</label>
													<div class="input-icon right">
														<i class="fa custom-icon-set"></i>
														<input name="ux_txt_margin_top_gallery" id="ux_txt_margin_top_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Top", gallery_master) ?>">
														<input name="ux_txt_margin_right_gallery" id="ux_txt_margin_right_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Right", gallery_master) ?>">
														<input name="ux_txt_margin_bottom_gallery" id="ux_txt_margin_bottom_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Bottom", gallery_master) ?>">
														<input name="ux_txt_margin_left_gallery" id="ux_txt_margin_left_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Left", gallery_master) ?>">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Padding Between Gallery Thumbnails (px)",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please provide the padding between the Gallery Thumbnails.", gallery_master) ?>" data-placement="right"></i>
														<span style="color:red;" class="required" aria-required="true">*<?php _e(" (Available in Pro Editions)")?></span>
													</label>
													<div class="input-icon right">
														<i class="fa custom-icon-set"></i>
														<input name="ux_txt_padding_top_gallery" id="ux_txt_padding_top_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Top", gallery_master) ?>">
														<input name="ux_txt_padding_right_gallery" id="ux_txt_padding_right_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Right", gallery_master) ?>">
														<input name="ux_txt_padding_bottom_gallery" id="ux_txt_padding_bottom_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Bottom", gallery_master) ?>">
														<input name="ux_txt_padding_left_gallery" id="ux_txt_padding_left_gallery" type="text" class="form-control custom-input-xsmall input-inline " value="10" placeholder="<?php _e("Left", gallery_master) ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Title Color",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please provide relevant color for the Title.", gallery_master) ?>" data-placement="right"></i>
														<span style="color:red;" class="required" aria-required="true">*<?php _e(" (Available in Pro Editions)")?></span>
													</label>
													<div class="input-icon right">
														<i class="fa"></i>
														<input type="text" class="form-control" name="ux_txt_title_color" id="ux_txt_title_color" value = "#000" placeholder="<?php _e("Enter Title Color", gallery_master) ?>">
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Description Color",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please provide relevant color for the Description.", gallery_master) ?>" data-placement="right"></i>
														<span style="color:red;" class="required" aria-required="true">*<?php _e(" (Available in Pro Editions)")?></span>
													</label>
													<div class="input-icon right">
														<i class="fa"></i>
														<input type="text" class="form-control" name="ux_txt_description_color" id="ux_txt_description_color" value = "#000" placeholder="<?php _e("Enter Description Color", gallery_master) ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Lightbox",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose Enabled to enable the Lightbox and Disabled to disable it.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<select class="form-control" name="ux_ddl_lightbox" id="ux_ddl_lightbox">
														<option value="enabled" ><?php _e("Enabled",gallery_master)?></option>
														<option value="disabled" style="color:red;"><?php _e("Disabled",gallery_master)?><?php _e(" (Available in Pro Editions)")?></option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php _e("Order By",gallery_master); ?> :
														<i class="icon-question tooltips" data-original-title="<?php _e("Please Choose the relevant order to display Images.", gallery_master) ?>" data-placement="right"></i>
														<span class="required" aria-required="true">*</span>
													</label>
													<select class="form-control" name="ux_ddl_order_by" id="ux_ddl_order_by" onchange ="">
														<option value="random"><?php _e("Random",gallery_master) ?></option>
														<option value="pic_id" style="color:red;"><?php _e("Image Id",gallery_master) ?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="pic_name" style="color:red;"><?php _e("File Name",gallery_master) ?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="title" style="color:red;"><?php _e("Title Text",gallery_master) ?><?php _e(" (Available in Pro Editions)")?></option>
														<option value="date" style="color:red;"><?php _e("Date",gallery_master) ?><?php _e(" (Available in Pro Editions)")?></option>
													</select>
												</div>
											</div>
										</div>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="btn-set pull-right" style="border-right: none;">
												<button type="submit" class="btn red-sunglo" ><?php _e("Generate Shortcode",gallery_master)?> </button>
											</div>
										</div>
										<div id = "ux_txt_generated_shortcode">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="control-label"><?php _e("Generated Shortcode",gallery_master); ?> :
															<i class="icon-question tooltips" data-original-title="<?php _e("This field shows your Generated Shortcode.", gallery_master) ?>" data-placement="right"></i>
															<span class="required" aria-required="true">*</span>
														</label>
														<textarea class="form-control" rows="5" name="ux_txtarea_generated_shortcode" id="ux_txtarea_generated_shortcode" placeholder="<?php _e("Here is your Generated Shortcode", gallery_master) ?>"></textarea>
														<p>
															<strong><?php _e("Copy this Shortcode and paste it to your Page/Post where you want to implement the Shortcode.",gallery_master)?></strong>
														</p>
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