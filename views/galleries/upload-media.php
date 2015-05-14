<?php
if (!is_user_logged_in())
{
	return;
}
else {
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
		$upload_local_system_files = wp_create_nonce("manage_local_system_uploading");
		$upload_media_files = wp_create_nonce("manage_files_uploading");
		$update_media_files = wp_create_nonce("update_gallery_images");
		$delete_media_files = wp_create_nonce("delete_gallery_images");
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
								<form id="ux_frm_upload_images">
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
												<li>
													<a href="javascript:void(0);" class="step">
														<span class="number"> 3 </span>
														<span class="desc"><i class="fa fa-check"></i><?php _e(" Confirm & Finish",gallery_master)?> </span>
													</a>
												</li>
											</ul>
										</div>
										<div id="bar" class="progress progress-striped" role="progressbar">
											<div style="width: 67%;" class="progress-bar progress-bar-success"></div>
										</div>
										<div class="line-separator"></div>
										<div class="tabbable-custom">
											<ul class="nav nav-tabs ">
												<li class="active">
													<a href="#tab_gallery_master_upload" data-toggle="tab">
														<?php _e("Gallery Master Uploader", gallery_master);?></a>
												</li>
												<li>
													<a href="#tab_wp_media_upload" data-toggle="tab">
														<?php _e("WP Media Manager", gallery_master);?></a>
												</li>
												<li>
													<a href="#tab_online_video_upload" data-toggle="tab">
														<?php _e("Online Videos", gallery_master);?></a>
												</li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane active" id="tab_gallery_master_upload">
													<div id="local_file_upload">
														<p><?php _e("Your browser doesn\"t have Flash, Silverlight or HTML5 support.", gallery_master) ?></p>
													</div>
												</div>
												<div class="tab-pane" id="tab_wp_media_upload">
													<div class="form-group">
														<label class="control-label"><span class="required" aria-required="true"><?php _e("This feature is available in Pro Editions.", gallery_master);?></span></label>
														<h4><?php _e("To import your Media Gallery, click on the button given below", gallery_master);?></h4>
													</div>
													<div class="form-group">
														<a class="btn red-sunglo" id="wp_media_upload_button"><?php _e("Upload Thumbnails ", gallery_master); ?></a>
														<p id="wp_media_upload_error_message" style="display: none;"><?php _e("Your wordpress version doesn\"t support Wp Media. Kindly update to latest version of wordpress", gallery_master) ?></p>
													</div>
												</div>
												<div class="tab-pane" id="tab_online_video_upload">
													<div class="form-group">
														<h4><?php _e("Give the URL to upload video", gallery_master);?></h4>
													</div>
													<div class="form-group">
														<label class="control-label"><?php _e("Video Url",gallery_master);?> :
															<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Video URL here.", gallery_master)?>" data-placement="right"></i>
															<span class="required" aria-required="true">* (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</span>
														</label>
														<div class="input-icon right">
															<input type="text" name="ux_upload_video_url" class="form-control" readonly="readonly" value="" id="ux_upload_video_url" placeholder="<?php _e("Enter your Video Url", gallery_master); ?>"/>
														</div>
													</div>
													<div class="form-actions">
														<div class="btn-set pull-right">
															<button type="button" class="btn red-sunglo" id="btn_online_video" name="btn_online_video" onclick="insert_video_to_gallery();"><?php _e("Upload Video", gallery_master); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<select name="ux_ddl_bulk_action" id="ux_ddl_bulk_action" class="input-small">
											<option value="" selected="selected" ><?php _e( "Bulk Action", gallery_master ); ?></option>
											<option value="delete" class="required" ><?php _e( "Delete Images", gallery_master ); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
											<option value="copy" class="required"><?php _e( "Copy Images ", gallery_master ); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
											<option value="move" class="required"><?php _e( "Move Images", gallery_master ); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
											<option value="rotate_clockwise" class="required"><?php _e( "Rotate Clockwise", gallery_master ); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
											<option value="rotate_anticlockwise" class="required"><?php _e( "Rotate Anti-Clockwise", gallery_master ); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
											<option value="flip_vertical" class="required"><?php _e( "Flip Images Vertically", gallery_master ); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
											<option value="flip_horizontal" class="required"><?php _e( "Flip Images Horizontally", gallery_master ); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
										</select>
										<button type="button" class="btn red-sunglo" name="ux_btn_bulk_action" id="ux_btn_bulk_action" onclick="gm_perform_action();"><?php _e("Apply!",gallery_master)?></button>
										<table class="table table-striped table-bordered table-hover table-margin-top" id="tbl_upload_gallery" >
											<thead class="align-thead-left">
												<tr>
													<th style="width:4% !important;" class="custom-table-th-left">
														<input type="checkbox" id="grp_select" name="grp_select" value="" />
													</th>
													<th class="text-left custom-table-th-left" style="width:20% !important;"><?php _e("Thumbnail", gallery_master); ?></th>
													<th class="text-left custom-table-th-right" style="width:70% !important;"><?php _e("Image Details", gallery_master); ?></th>
												</tr>
											</thead>
											<tbody>
												<tr id="ux_dynamic_tr_0" class="ux_dynamic_tr" style="display: none;">
													<td>
														<input type="checkbox" id="ux_grp_select_items_" name="ux_grp_select_items_" value=""/>
													</td>
													<td>
														<div class="custom-image-style">
															<img file_type="" src="" image_thumb_path="" id="ux_gm_file_" name="ux_gm_file_"/>
														</div>
														<div class="custom-div-gap">
															<a href="javascript:void(0);" class="tooltips" data-original-title="<?php _e("Please click on this button, If you would like to Delete this Image.", gallery_master)?>" data-placement="right" id="ux_delete_image_" onclick="gm_delete_image(this);" control_id="">
																<i class="icon-trash" ></i> <?php _e("Delete", gallery_master) ?>
															</a>
														</div>
													</td>
													<td>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label"><?php _e("Image Title",gallery_master); ?> :
																		<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Image Title here. This would be displayed  with Images when using Short Code.", gallery_master) ?>" data-placement="right"></i>
																		<span class="required" aria-required="true">*</span>
																	</label>
																	<div class="input-icon right">
																		<input type="text" placeholder="<?php _e("Enter your Title", gallery_master) ?>" class="form-control edit" name="ux_txt_title_" id="ux_txt_title_" value=""/>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label"><?php _e("Image Alt Text",gallery_master); ?> :
																		<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Image Alt Text here. This would be used for Search Engines for better SEO Performance.", gallery_master) ?>" data-placement="right"></i>
																		<span class="required" aria-required="true">*</span>
																	</label>
																	<div class="input-icon right">
																		<input type="text" placeholder="<?php _e("Enter Alt Text for image", gallery_master) ?>" class="form-control edit" name="ux_alt_text_" id="ux_alt_text_" value=""/>
																	</div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label"><?php _e("Image Description",gallery_master); ?> :
																<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Image Description here. This would be displayed with Images when using Short Code.", gallery_master) ?>" data-placement="right"></i>
																<span class="required" aria-required="true">*</span>
															</label>
															<div class="input-icon right">
																<textarea placeholder="<?php _e("Enter your Description", gallery_master) ?>" class="form-control edit" rows="3" name="ux_txt_desc_" id="ux_txt_desc_"></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label"><?php _e("Enable Url Redirect on click of Image",gallery_master); ?> :
																<i class="icon-question tooltips" data-original-title="<?php _e("Please choose URL Redirect Enabled, If you would like to Redirect your Image to a Specific URL on click of an Image instead of opening it in a Lightbox.", gallery_master) ?>" data-placement="right"></i>
																<span class="required" aria-required="true">* (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</span>
															</label>
															<div class="input-group">
																<label>
																	<input type="radio" name="ux_url_redirect_" id="ux_enable_redirect_" disabled="disabled" value="1" onclick="enable_url_redirect();"><?php _e("Enable", gallery_master);?>
																</label>
																<label>
																	<input type="radio" checked="checked" name="ux_url_redirect_" disabled="disabled" id="ux_disable_redirect_" value="0" onclick="enable_url_redirect();"><?php _e("Disable", gallery_master);?>
																</label>
															</div>
														</div>
														<div class="form-group" id="div_url_redirect_">
															<label class="control-label"><?php _e("Url Link",gallery_master); ?> :
																<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your URL Link which would be used to Redirect.", gallery_master) ?>" data-placement="right"></i>
																<span class="required" aria-required="true">* (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</span>
															</label>
															<div class="input-icon right">
																<input placeholder="<?php _e("Enter URL link to Redirect", gallery_master) ?>" readonly="readonly" class="form-control edit" type="text" name="ux_txt_url_<?php echo intval($pic["gallery_id"]);?>" id="ux_txt_url_<?php echo intval($pic["gallery_id"]);?>" value="http://"/>
															</div>
														</div>
													</td>
												</tr>
												<?php
												foreach($pics_array as $pic)
												{
													?>
													<tr id="ux_dynamic_tr_<?php echo intval($pic["gallery_id"]);?>">
														<td>
															<input type="checkbox" id="ux_grp_select_items_<?php echo intval($pic["gallery_id"]);?>" name="ux_grp_select_items_<?php echo intval($pic["gallery_id"]);?>" value="<?php echo intval($pic["gallery_id"]);?>" />
														</td>
														<td>
															<div class="custom-image-style">
																<img file_type="image" src="<?php echo GALLERY_MASTER_MAIN_URL.esc_attr($pic["thumbnail_url"]);?>" image_thumb_path="<?php echo esc_attr($pic["thumbnail_url"]);?>" id="ux_gm_file_<?php echo intval($pic["gallery_id"]);?>" name="ux_gm_file_<?php echo intval($pic["gallery_id"]);?>" style="width: 100%;"/>
															</div>
															<div class="custom-div-gap">
																<a href="javascript:void(0);" class="tooltips" data-original-title="<?php _e("Please click on this button, If you would like to Delete this Image.", gallery_master)?>" onclick="gm_delete_image(this);" control_id="<?php echo intval($pic["gallery_id"]);?>">
																	<i class="icon-trash" ></i> <?php _e("Delete", gallery_master) ?>
																</a>
															</div>
														</td>
														<td>
															<div class="row">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="control-label"><?php _e("Image Title",gallery_master); ?> :
																			<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Image Title here. This would be displayed  with Images when using Short Code.", gallery_master)?>" data-placement="right"></i>
																			<span class="required" aria-required="true">*</span>
																		</label>
																		<div class="input-icon right">
																			<input type="text" placeholder="<?php _e("Enter your Title", gallery_master) ?>" class="form-control edit" name="ux_txt_title_<?php echo intval($pic["gallery_id"]);?>" id="ux_txt_title_<?php echo intval($pic["gallery_id"]);?>" value="<?php echo esc_attr(stripcslashes(urldecode($pic["image_title"])));?>"/>
																		</div>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label class="control-label"><?php _e("Image Alt Text",gallery_master); ?> :
																			<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Image Alt Text here. This would be used for Search Engines for better SEO Performance.", gallery_master) ?>" data-placement="right"></i>
																			<span class="required" aria-required="true">*</span>
																		</label>
																		<div class="input-icon right">
																			<input type="text" placeholder="<?php _e("Enter Alt Text for image", gallery_master) ?>" class="form-control edit" name="ux_alt_text_<?php echo intval($pic["gallery_id"]);?>" id="ux_alt_text_<?php echo intval($pic["gallery_id"]);?>" value="<?php echo esc_attr(stripcslashes(urldecode($pic["alt_text"])));?>"/>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label"><?php _e("Image Description",gallery_master); ?> :
																	<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your Image Description here. This would be displayed with Images when using Short Code.", gallery_master) ?>" data-placement="right"></i>
																	<span class="required" aria-required="true">*</span>
																</label>
																<div class="input-icon right">
																	<textarea placeholder="<?php _e("Enter your Description", gallery_master) ?>" class="form-control edit" rows="3" name="ux_txt_desc_<?php echo intval($pic["gallery_id"]);?>" id="ux_txt_desc_<?php echo intval($pic["gallery_id"]);?>"><?php echo stripcslashes(urldecode($pic["image_description"]));?></textarea>
																</div>
															</div>
															<div class="form-group">
																<label class="control-label"><?php _e("Enable Url Redirect on click of Image",gallery_master); ?> :
																	<i class="icon-question tooltips" data-original-title="<?php _e("Please choose URL Redirect Enabled, If you would like to Redirect your Image to a Specific URL on click of an Image instead of opening it in a Lightbox.", gallery_master) ?>" data-placement="right"></i>
																	<span class="required" aria-required="true">* (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</span>
																</label>
																<div class="input-group">
																	<label>
																		<input type="radio" name="ux_url_redirect_<?php echo intval($pic["gallery_id"]);?>" disabled="disabled" id="ux_enable_redirect_<?php echo intval($pic["gallery_id"]);?>" value="1" onclick="enable_url_redirect(<?php echo intval($pic["gallery_id"]);?>);"><?php _e("Enable", gallery_master);?>
																	</label>
																	<label>
																		<input type="radio" checked="checked" name="ux_url_redirect_<?php echo intval($pic["gallery_id"]);?>" disabled="disabled" id="ux_disable_redirect_<?php echo intval($pic["gallery_id"]);?>" value="0" onclick="enable_url_redirect(<?php echo intval($pic["gallery_id"]);?>);"><?php _e("Disable", gallery_master);?>
																	</label>
																</div>
															</div>
															<div class="form-group" id="div_url_redirect_<?php echo intval($pic["gallery_id"]);?>">
																<label class="control-label"><?php _e("Url Link",gallery_master); ?> :
																	<i class="icon-question tooltips" data-original-title="<?php _e("Please enter your URL Link which would be used to Redirect.", gallery_master) ?>" data-placement="right"></i>
																	<span class="required" aria-required="true">* (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</span>
																</label>
																<div class="input-icon right">
																	<input placeholder="<?php _e("Enter url link to redirect", gallery_master) ?>" readonly="readonly" class="form-control edit" type="text" name="ux_txt_url_<?php echo intval($pic["gallery_id"]);?>" id="ux_txt_url_<?php echo intval($pic["gallery_id"]);?>" value="http://"/>
																</div>
															</div>
														</td>
													</tr>
													<?php
												}
												?>
											</tbody>
										</table>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="btn-set pull-left" style="border-right: none;">
												<button type="button" class="btn red-sunglo" onclick="gm_proceed_next(1,<?php echo $gallery_id;?>);" ><?php _e("<< Previous Step",gallery_master)?></button>
											</div>
											<div class="btn-set pull-right" style="border-right: none;">
												<button type="submit" class="btn red-sunglo"><?php _e("Next Step >>",gallery_master)?></button>
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