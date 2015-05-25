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

		$delete_gallery_files = wp_create_nonce("delete_uploaded_gallery");
		$delete_selected_files = wp_create_nonce("delete_selected_galleries");
		?>
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="portlet box red-sunglo">
							<div class="portlet-title">
								<div class="caption">
									<i class="icon icon-layers"></i><?php _e("Manage Galleries", gallery_master); ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_manage_galleries">
									<div class="form-body">
										<div class="note note-warning">
											<?php _e( "<p><b>Important Notice :</b></p>", gallery_master); ?>
											<?php _e( "<p>You are using the Lite Edition of Gallery Master.</p>", gallery_master); ?>
											<?php _e( "<p>Lite Edition offers 3 galleries and if you would like to enjoy unlimited galleries,<br/>", gallery_master); ?>
											<?php _e( "please upgrade to the Premium Editions and enjoy unlimited access + advanced features.</p>", gallery_master); ?>
										</div>
										<div class="table-margin-top">
											<select id="ux_ddl_bulk_action" name="ux_ddl_bulk_action" class="input-small">
												<option value=""><?php _e("Bulk Action", gallery_master); ?></option>
												<option value="delete" class="required"><?php _e("Delete", gallery_master); ?> (<?php _e("This feature is available in Pro Editions.", gallery_master);?>)</option>
											</select>
											<input type="button" id="ux_btn_apply" name="ux_btn_apply" onclick="bulk_delete();" class="btn red-sunglo" value="<?php _e("Apply", gallery_master); ?>">
											<?php
											if(count($gallery_details) < 3)
											{
												?>
												<a href="admin.php?page=gm_save_basic_details" class="btn red-sunglo"><?php _e("Add New Gallery", gallery_master); ?></a>
												<?php
											}
											?>
										</div>
										<table class="table table-striped table-bordered table-hover table-margin-top" id="tbl_manage_galleries">
											<thead>
												<tr>
													<th>
														<input type="checkbox" id="ux_chk_all_gallery" name="ux_chk_all_gallery">
													</th>
													<th>
														<?php _e("Thumbnails", gallery_master);?>
													</th>
													<th>
														<?php _e("Gallery Overview", gallery_master);?>
													</th>
												</tr>
											</thead>
											<tbody>
											<?php
											foreach($gallery_details as $row)
											{
												$count_pic = $wpdb->get_var
												(
													$wpdb->prepare
													(
														"SELECT count(gallery_id) FROM ".gallery_master_galleries()." WHERE parent_id = %d",
														$row["gallery_id"]
													)
												);

												$thumb_pics = $wpdb->get_col
												(
													$wpdb->prepare
													(
														"SELECT gallery_meta_value FROM " . gallery_master_meta() .
														" WHERE gallery_meta_key = %s and gallery_id in (SELECT gallery_id FROM " . gallery_master_galleries()." WHERE parent_id = %d) ORDER BY RAND() LIMIT 4",
														"thumbnail_url",
														$row["gallery_id"]
													)
												);
												?>
												<tr class="alternate">
													<td>
														<input type="checkbox" id="ux_chk_items_<?php echo $row["gallery_id"];?>" name="ux_chk_items_<?php echo $row["gallery_id"];?>" value="<?php echo $row["gallery_id"];?>" >
													</td>
													<td class="thumbnail-text">
														<a href="admin.php?page=gm_save_basic_details&gallery_id=<?php echo $row["gallery_id"];?>">
															<?php
															switch(count($thumb_pics))
															{
																case 0:
																	?>
																	<img id="ux_gm_img" class="gallery_cover_image" src="<?php echo plugins_url("assets/admin/images/gallery-cover.png",dirname(dirname(__FILE__)));?>"/>
																	<?php
																break;

																case 4:
																	?>
																	<div class="gm_image_overlay" style="width: 220px;">
																		<div class= "gallery_content_overlay_txt">
																			<div class="gm-image-div">
																				<div class="gm-section">
																					<span class="hover-thumbnails">
																						<span class="first-thumbnail">
																							<img class="" src="<?php echo GALLERY_MASTER_MAIN_URL.$thumb_pics[0];?>" style="width: 90px; height: 90px;">
																						</span>
																						<span class="second-thumbnail">
																							<img class="" src="<?php echo GALLERY_MASTER_MAIN_URL.$thumb_pics[1];?>" style="width: 50px; height: 50px;">
																						</span>
																						<span class="third-thumbnail">
																							<img class="" src="<?php echo GALLERY_MASTER_MAIN_URL.$thumb_pics[2];?>" style="width: 70px; height: 70px;">
																						</span>
																					</span>
																				</div>
																			</div>
																		</div>
																		<img id="ux_gm_img" class="gallery_cover_image" src="<?php echo GALLERY_MASTER_MAIN_URL.$thumb_pics[3];?>"/>
																	</div>
																	<?php
																break;
																default :
																	?>
																	<img id="ux_gm_img" class="gallery_cover_image" src="<?php echo GALLERY_MASTER_MAIN_URL.$thumb_pics[0];?>"/>
																	<?php
																break;
															}
															?>
														</a>
														<a href="admin.php?page=gm_save_basic_details&gallery_id=<?php echo $row["gallery_id"];?>" class="tooltips" data-original-title="<?php _e("Please click on this button, If you would like to Edit this Gallery.", gallery_master)?>" data-placement="right" id="ux_edit_gallery">
															<i class="icon-note tooltips" ></i> <?php _e("Edit", gallery_master)?>
														</a> |
														<a href="javascript:void(0);" class="tooltips" data-original-title = "<?php _e("Please click on this button, If you would like to Delete this Gallery.", gallery_master)?>" data-placement="right" id="ux_delete_gallery" onclick=delete_gallery(<?php echo $row["gallery_id"];?>)>
															<i class="icon-trash " ></i> <?php _e("Delete", gallery_master)?>
														</a>
													</td>
													<td>
														<table class="gallery_data_tbl">
															<tr>
																<th style="text-align: left; width:160px;">
																	<label class="gallery_text_underline"><?php _e("Gallery Details", gallery_master)?></label>
																</th>
															</tr>
															<tr>
																<td colspan="2">
																	<label class="gallery_text_bold"><?php _e("Gallery Title",gallery_master)?></label>
																</td>
															</tr>
															<tr>
																<td colspan="2">
																	<label class="gallery_text_italic"><?php echo urldecode($row["gallery_title"]);?></label>
																</td>
															</tr>
															<?php
															if(isset($row["gallery_description"]) &&  $row["gallery_description"] != "")
															{
																?>
																<tr>
																	<td colspan="2">
																		<label class="gallery_text_bold"><?php _e("Gallery Description",gallery_master)?></label>
																	</td>
																</tr>
																<tr>
																	<td colspan="2">
																		<label class="gallery_text_italic"><?php echo stripcslashes(htmlspecialchars_decode(urldecode($row["gallery_description"])));?></label>
																	</td>
																</tr>
															<?php
															}
															?>
														</table>
														<table class="gallery_data_tbl">
															<tr>
																<th style="text-align: left; width:160px;">
																	<label class="gallery_text_bold gallery_text_underline"><?php _e("Meta Information",gallery_master)?></label>
																</th>
															</tr>
															<tr>
																<td colspan="2">
																	<label class="gallery_text_bold"><?php _e("Total Images / Videos",gallery_master)?></label>
																</td>
															</tr>
															<tr>
																<td colspan="2">
																	<label id="ux_category" class="gallery_text_italic"><?php echo $count_pic;?></label>
																</td>
															</tr>
															<tr>
																<td>
																	<label class="gallery_text_bold"><?php _e("Created By",gallery_master)?></label>
																</td>
																<td>
																	<label class="gallery_text_bold"><?php _e("Date Of Creation",gallery_master)?></label>
																</td>
															</tr>
															<tr>
																<td>
																	<label id="ux_created_by" class="gallery_text_italic"><?php echo $row["author"];?></label>
																</td>
																<td>
																	<label id="ux_created_on" class="gallery_text_italic"><?php echo date("F j, Y", strtotime($row["gallery_date"]));?></label>
																</td>
															</tr>
															<tr>
																<td>
																	<label class="gallery_text_bold"><?php _e("Last Edited By",gallery_master)?></label>
																</td>
																<td>
																	<label class="gallery_text_bold"><?php _e("Last Edited On",gallery_master)?></label>
																</td>
															</tr>
															<tr>
																<td>
																	<label id="ux_created_by" class="gallery_text_italic"><?php echo $row["edited_by"];?></label>
																</td>
																<td>
																	<label id="ux_created_by" class="gallery_text_italic"><?php echo date("F j, Y", strtotime($row["edited_on"]));?></label>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											<?php
											}
											?>
											</tbody>
										</table>
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