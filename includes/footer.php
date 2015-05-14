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

	if ( ! current_user_can ($user_role_permission))
	{
		return;
	}
	else
	{
		?>
		</div>
		<script type="text/javascript">
			jQuery(".tooltips").tooltip();
			jQuery("li > a").parents("li").each(function ()
			{
				if (jQuery(this).parent("ul.page-sidebar-menu").size() === 1) {
					jQuery(this).find("> a").append("<span class=\"selected\"></span>");
				}
			});

			jQuery(".page-sidebar").on("click", "li > a", function (e)
			{
				var hasSubMenu = jQuery(this).next().hasClass("sub-menu");
				var parent = jQuery(this).parent().parent();
				var sidebar_menu = jQuery(".page-sidebar-menu");
				var sub = jQuery(this).next();
				var slideSpeed = parseInt(sidebar_menu.data("slide-speed"));
				parent.children("li.open").children(".sub-menu:not(.always-open)").slideUp(slideSpeed);
				parent.children("li.open").removeClass("open");
				if (sub.is(":visible")) {
					jQuery(this).parent().removeClass("open");
					sub.slideUp(slideSpeed);
				} else if (hasSubMenu) {
					jQuery(this).parent().addClass("open");
					sub.slideDown(slideSpeed);
				}
			});

			if (typeof(load_sidebar_content) != "function")
			{
				function load_sidebar_content()
				{
					var menus_height = jQuery(".page-sidebar-menu").height();
					var content_height = jQuery(".page-content").height() + 30;
					if(parseInt(menus_height) > parseInt(content_height))
					{
						jQuery(".page-content").attr("style","min-height:"+menus_height+"px")
					}
					else
					{
						jQuery(".page-sidebar-menu").attr("style","min-height:"+content_height +"px")
					}
				}
			}

			var sidebar_load_interval = setInterval(load_sidebar_content ,1000);
			setTimeout(function( ) { clearInterval( sidebar_load_interval ); }, 3000);

			if (typeof(overlay_loading) != "function")
			{
				function overlay_loading(control_id)
				{
					var overlay_opacity = jQuery("<div class=\"opacity_overlay\"></div>");
					jQuery("body").append(overlay_opacity);
					var overlay = jQuery("<div class=\"loader_opacity\"><div class=\"processing_overlay\"></div></div>");
					jQuery("body").append(overlay);

					if(control_id != undefined)
					{
						switch(control_id)
						{
							case "delete_gallery":
								var message = "<?php _e("Gallery has been deleted successfully.",gallery_master)?>";
								var success = "<?php _e("Success!",gallery_master)?>";
							break;

							case "confirm_saving":
								var message = "<?php _e("Gallery has been saved successfully.",gallery_master)?>";
								var success = "<?php _e("Success!",gallery_master)?>";
							break;

							case "feature_request":
								var message = "<?php _e("Your request email has been sent successfully.",gallery_master)?>";
								var success = "<?php _e("Success!",gallery_master)?>";
							break;
						}
						var issuccessmessage = jQuery("#toast-container").exists();
						if(issuccessmessage != true)
						{
							var shortCutFunction = $("#manage_messages input:checked").val();
							var $toast = toastr[shortCutFunction](message, success);
						}
					}
				}
			}

			if (typeof(remove_overlay) != "function")
			{
				function remove_overlay()
				{
					jQuery(".loader_opacity").remove();
					jQuery(".opacity_overlay").remove();
				}
			}

			if (typeof(gm_proceed_next) != "function")
			{
				function gm_proceed_next(jump_to_step,id)
				{
					switch(jump_to_step)
					{
						case 1:
							overlay_loading();
							setTimeout(function()
							{
								remove_overlay();
								window.location.href="admin.php?page=gm_save_basic_details&gallery_id="+id;
							}, 3000);
						break;

						case 2:
							overlay_loading();
							setTimeout(function()
							{
								remove_overlay();
								window.location.href="admin.php?page=gm_upload_media&gallery_id="+id;
							}, 3000);
						break;

						case 3:
							overlay_loading();
							setTimeout(function()
							{
								remove_overlay();
								window.location.href="admin.php?page=gm_save_gallery&gallery_id="+id;
							}, 3000);
						break;

						default :
							overlay_loading("confirm_saving");
							setTimeout(function()
							{
								remove_overlay();
								window.location.href="admin.php?page=gallery_master";
							}, 3000);
						break;
					}
				}
			}
			<?php
		if(isset($_REQUEST["page"]))
		{
			switch (esc_attr($_REQUEST["page"]))
			{
				case "gm_save_basic_details":
					?>
					jQuery("#ux_li_galleries").addClass("active");
					jQuery("#ux_li_add_new_gallery").addClass("active");

					jQuery("#ux_frm_add_basic_details").validate
					({
						submitHandler: function (form)
						{
							var gallery_title = encodeURIComponent(jQuery("#ux_txt_gallery_title").val());
							var gallery_description = encodeURIComponent(jQuery("#ux_txtarea_gallery_description").val());
							var gallery_id = "<?php echo isset($_REQUEST["gallery_id"]) ? intval($_REQUEST["gallery_id"]) : "";?>";
							var post_id = "<?php echo isset($array_gallery_details["post_id"]) ? intval($array_gallery_details["post_id"]) : "";?>";

							jQuery.post(ajaxurl,
								{
									ux_gallery_title : gallery_title,
									ux_gallery_desc : gallery_description,
									is_gallery_id : gallery_id,
									is_post_id : post_id,
									param : "save_gallery_details",
									action : "gallery_master_action_library",
									_wp_nonce : "<?php echo $basic_details;?>"
								},
								function(data)
								{
									var id = jQuery.trim(data);
									gm_proceed_next(2,id);
								});
						}
					});
					<?php
					break;

				case "gm_upload_media":
					?>
					jQuery("#ux_li_galleries").addClass("active");
					jQuery("#ux_li_add_new_gallery").addClass("active");
					var pl_upload_url = "<?php echo plugins_url("assets/global/plugins/pluploader/",dirname(dirname(__FILE__))) ?>";
					var gallery_id = <?php echo $gallery_id;?>;
					var array_gallery_data = [];
					var array_delete_images = [];

					jQuery(document).ready(function()
					{
						display_action();

						jQuery("#local_file_upload").plupload
						({
							runtimes: "html5,flash,silverlight,html4",
							url: "?param=upload_gallery_master_pics&action=manage_images_upload_library&_wp_nonce=<?php echo $upload_local_system_files;?>",
							chunk_size: "1mb",
							filters:
							{
								max_file_size: "100mb",
								mime_types: [
									{title: "Image files", extensions: "jpg,jpeg,gif,png"}
								]
							},
							rename: true,
							sortable: true,
							dragdrop: true,
							unique_names: true,
							max_file_count: 20,
							views: {
								list: true,
								thumbs: true,
								active: "thumbs"
							},
							flash_swf_url: pl_upload_url + "Moxie.swf",
							silverlight_xap_url: pl_upload_url + "Moxie.xap",
							init:
							{
								FileUploaded: function (up, file)
								{
									var image_target_name = file.target_name;
									var image_name = file.name;
									jQuery.post(ajaxurl,
										{
											ux_img_target_name : image_target_name,
											ux_image_name : image_name,
											upload_type : "system_upload",
											id : gallery_id,
											ux_file_type : "image",
											param : "upload_images",
											action : "gallery_master_action_library",
											_wp_nonce : "<?php echo $upload_media_files;?>"
										},
										function(data)
										{
											var insert_id = jQuery.trim(data);
											create_dynamic_tr(image_target_name,image_name,"","","",insert_id);
											display_action();
											load_sidebar_content();
										});
								}
							}
						});
					});

					var oTable = jQuery("#tbl_upload_gallery").dataTable
					({
						"pagingType": "full_numbers",
						"language": {
							"emptyTable": "No data available in table",
							"info": "Showing _START_ to _END_ of _TOTAL_ entries",
							"infoEmpty": "No entries found",
							"infoFiltered": "(filtered1 from _MAX_ total entries)",
							"lengthMenu": "Show _MENU_ entries",
							"search": "Search:",
							"zeroRecords": "No matching records found"
						},
						"bSort": false,
						"pageLength": 10
					});

					jQuery("#ux_frm_upload_images").validate
					({
						submitHandler: function (form)
						{
							if(array_delete_images.length > 0)
							{
								jQuery.post(ajaxurl,
									{
										delete_data: encodeURIComponent(array_delete_images),
										param: "delete_images",
										action: "gallery_master_action_library",
										_wp_nonce: "<?php echo $delete_media_files;?>"
									});
							}

							if(oTable.fnGetNodes().length > 1)
							{
								jQuery.each(oTable.fnGetNodes(), function (index, value)
								{
									var tr_data = [];
									var fileId = jQuery(value.cells[0]).find("input:checkbox").val();
									if (fileId != "")
									{
										var fileType = jQuery(value.cells[1]).find("img").attr("file_type");
										var controlType = (fileType == undefined ? "video" : "image");
										var excludeFile = jQuery(value.cells[1]).find("input:checkbox").prop("checked");
										var isExclude = (excludeFile == true ? 1 : 0);
										var title = encodeURIComponent(jQuery(value.cells[2]).find("input:text").eq(0).val());
										var altText = encodeURIComponent(jQuery(value.cells[2]).find("input:text").eq(1).val());
										var description = encodeURIComponent(jQuery(value.cells[2]).find("textarea").val());
										var isUrlRedirect = jQuery(value.cells[2]).find("input[name=ux_url_redirect_" + fileId + "]:checked").val();
										var urlRedirect = jQuery(value.cells[2]).find("input:text").eq(2).val();

										tr_data.push(fileId);
										tr_data.push(controlType);
										tr_data.push(isExclude);
										tr_data.push(title);
										tr_data.push(altText);
										tr_data.push(description);
										tr_data.push(isUrlRedirect);
										tr_data.push(urlRedirect);
										array_gallery_data.push(tr_data);
									}
								});
								jQuery.post(ajaxurl,
									{
										gallery_data: encodeURIComponent(JSON.stringify(array_gallery_data)),
										param: "update_images",
										action: "gallery_master_action_library",
										_wp_nonce: "<?php echo $update_media_files;?>"
									},
									function ()
									{
										gm_proceed_next(3, gallery_id);
									});
							}
							else
							{
								var confirm_delete = "<?php _e("Are you sure you want to continue without uploading images/videos?", gallery_master)?>";
								bootbox.confirm(confirm_delete, function (result)
								{
									if (result == true)
									{
										gm_proceed_next(3, gallery_id);
									}
								});
							}
						}
					});

					if (typeof(display_action) != "function")
					{
						function display_action()
						{
							var rowCount = jQuery("#tbl_upload_gallery > tbody > tr:not(:first)").length;
							if(rowCount > 0)
							{
								jQuery("#ux_ddl_bulk_action").css("display","inline-block");
								jQuery("#ux_btn_bulk_action").css("display","inline-block");
								jQuery("#tbl_upload_gallery").css("display","block");
								jQuery("#tbl_upload_gallery_length").css("display","block");
								jQuery("#tbl_upload_gallery_info").css("display","block");
								jQuery("#tbl_upload_gallery_paginate").css("display","block");
								jQuery(".dataTables_wrapper").css("margin-top","-40px");
							}
							else
							{
								jQuery("#tbl_upload_gallery").css("display","none");
								jQuery("#tbl_upload_gallery_length").css("display","none");
								jQuery("#tbl_upload_gallery_info").css("display","none");
								jQuery("#tbl_upload_gallery_paginate").css("display","none");
								jQuery("#ux_ddl_bulk_action").css("display","none");
								jQuery("#ux_btn_bulk_action").css("display","none");
							}
						}
					}

					if (typeof(create_dynamic_tr) != "function")
					{
						function create_dynamic_tr(target_name,image_name,image_title,image_desc,alt_text,file_id)
						{
							var oTable = jQuery("#tbl_upload_gallery").dataTable();

							var col1 = jQuery("#ux_dynamic_tr_0 td:first-child").clone();
							var col2 = jQuery("#ux_dynamic_tr_0 td:nth-child(2)").clone();
							var col3 = jQuery("#ux_dynamic_tr_0 td:nth-child(3)").clone();
							var rowIdxes = oTable.fnAddData([col1.html(),col2.html(),col3.html()]);
							var rowTr = oTable.fnGetNodes(rowIdxes[0]);
							jQuery(rowTr).attr("id", "#ux_dynamic_tr_"+ file_id);

							jQuery(rowTr).find("td:first-child input[type=checkbox]").prop("id","ux_grp_select_items_"+file_id);
							jQuery(rowTr).find("td:first-child input[type=checkbox]").prop("name","ux_grp_select_items_"+file_id);
							jQuery(rowTr).find("td:first-child input[type=checkbox]").attr("value",file_id);

							jQuery(rowTr).find("td:nth-child(2) img").prop("id","ux_gm_file_"+file_id);
							jQuery(rowTr).find("td:nth-child(2) img").prop("name","ux_gm_file_"+file_id);
							jQuery(rowTr).find("td:nth-child(2) img").attr("src","<?php echo GALLERY_MASTER_THUMBS_URL;?>"+target_name);
							jQuery(rowTr).find("td:nth-child(2) img").attr("imgpath",target_name);
							jQuery(rowTr).find("td:nth-child(2) img").attr("img_name",image_name);
							jQuery(rowTr).find("td:nth-child(2) img").attr("image_thumb_path","<?php echo GALLERY_MASTER_UPLOAD_PATH."thumbs/";?>"+target_name);
							jQuery(rowTr).find("td:nth-child(2) img").attr("file_type","image");
							jQuery(rowTr).find("td:nth-child(2) img").attr("style","width:100%");

							jQuery(rowTr).find("td:nth-child(2) a:first-child").prop("id","ux_delete_image_"+file_id);
							jQuery(rowTr).find("td:nth-child(2) a:first-child").attr("control_id",file_id);

							jQuery(rowTr).find("td:nth-child(3) input:text:eq(0)").prop("id","ux_txt_title_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:text:eq(0)").prop("name","ux_txt_title_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:text:eq(0)").val(image_title);

							jQuery(rowTr).find("td:nth-child(3) input:text:eq(1)").last().prop("id","ux_alt_text_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:text:eq(1)").last().prop("name","ux_alt_text_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:text:eq(1)").val(alt_text);

							jQuery(rowTr).find("td:nth-child(3) textarea").prop("id","ux_txt_desc_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) textarea").prop("name","ux_txt_desc_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) textarea").val(image_desc);

							jQuery(rowTr).find("td:nth-child(3) input:radio:eq(0)").prop("id","ux_enable_redirect_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:radio:eq(0)").prop("name","ux_url_redirect_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:radio:eq(0)").attr("onclick","enable_url_redirect("+file_id+")");

							jQuery(rowTr).find("td:nth-child(3) input:radio:eq(1)").prop("id","ux_disable_redirect_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:radio:eq(1)").prop("name","ux_url_redirect_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:radio:eq(1)").attr("onclick","enable_url_redirect("+file_id+")");

							jQuery(rowTr).find("td:nth-child(3) input:text:eq(2)").last().prop("id","ux_txt_url_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:text:eq(2)").last().prop("name","ux_txt_url_"+file_id);
							jQuery(rowTr).find("td:nth-child(3) input:text:eq(2)").parent().parent().prop("id","div_url_redirect_"+file_id);
						}
					}

					jQuery("#grp_select").click(function ()
					{
						var checkProp = jQuery("#grp_select").prop("checked");
						jQuery("input[type=checkbox][name*=ux_grp_select_items_]", oTable.fnGetNodes()).each(function ()
						{
							if (checkProp)
							{
								jQuery(this).attr("checked", "checked");
							}
							else
							{
								jQuery(this).removeAttr("checked");
							}
						});
					});

					if (typeof(gm_perform_action) != "function")
					{
						function gm_perform_action()
						{
							alert("<?php _e("This feature is available in Pro Editions.", gallery_master)?>");
						}
					}

					if(typeof(insert_video_to_gallery) != "function")
					{
						function insert_video_to_gallery()
						{
							alert("<?php _e("This feature is available in Pro Editions.", gallery_master)?>");
						}
					}

					if (typeof(gm_delete_image) != "function")
					{
						function gm_delete_image(control)
						{
							var confirm_delete = "<?php _e("Are you sure you want to delete this Images?", gallery_master)?>";
							bootbox.confirm(confirm_delete, function (result)
							{
								if (result == true)
								{
									var row = jQuery(control).closest("tr");
									var image_id = jQuery(control).attr("control_id");
									array_delete_images.push(image_id);
									oTable.fnDeleteRow(row[0]);
								}
							});
						}
					}
					<?php
					break;

				case "gm_save_gallery":
					?>
					jQuery("#ux_li_galleries").addClass("active");
					jQuery("#ux_li_add_new_gallery").addClass("active");
					<?php
					break;

				case "gallery_master":
					?>
					jQuery(document).ready(function()
					{
						jQuery("#ux_li_galleries").addClass("active");
						jQuery("#ux_li_manage_galleries").addClass("active");
						jQuery(".dataTables_wrapper").css("margin-top","-40px");
					});
					var oTable = jQuery("#tbl_manage_galleries").dataTable
					({
						"pagingType": "full_numbers",
						"language": {
							"emptyTable": "No data available in table",
							"info": "Showing _START_ to _END_ of _TOTAL_ entries",
							"infoEmpty": "No entries found",
							"infoFiltered": "(filtered1 from _MAX_ total entries)",
							"lengthMenu": "Show _MENU_ entries",
							"search": "Search:",
							"zeroRecords": "No matching records found"
						},
						"bSort": false,
						"pageLength": 10
					});


					if (typeof(delete_gallery) != "function")
					{
						function delete_gallery(gallery_id)
						{
							var confirm_delete = "<?php _e("Are you sure you want to delete this Album?", gallery_master)?>";
							bootbox.confirm(confirm_delete, function (result)
							{
								if (result == true)
								{
									jQuery.post(ajaxurl,
										{
											id : gallery_id,
											param : "delete_gallery",
											action : "gallery_master_action_library",
											_wp_nonce : "<?php echo $delete_gallery_files;?>"
										},
										function()
										{
											overlay_loading("delete_gallery");
											setTimeout(function()
											{
												remove_overlay();
												window.location.reload();
											}, 3000);
										});
								}
							});
						}
					}

					jQuery("#ux_chk_all_gallery").click(function ()
					{
						var oTable = jQuery("#tbl_manage_galleries").dataTable();
						var checkProp = jQuery("#ux_chk_all_gallery").prop("checked");
						jQuery("input[type=checkbox][name*=ux_chk_items_]", oTable.fnGetNodes()).each(function ()
						{
							if (checkProp)
							{
								jQuery(this).attr("checked", "checked");
							}
							else
							{
								jQuery(this).removeAttr("checked");
							}
						});
					});
					if (typeof(bulk_delete) != "function")
					{
						function bulk_delete()
						{
							alert("<?php _e("This feature is available in Pro Editions.", gallery_master);?>")
						}
					}
					<?php
					break;

				case "gm_shortcode_generator":
					?>
					jQuery(document).ready(function()
					{
						change_galleries();
						change_gallery_type();
						jQuery("#ux_txt_generated_shortcode").css("display","none");
					});
					jQuery("#ux_li_shortcodes").addClass("active");

					if(typeof(change_galleries) != "function")
					{
						function change_galleries()
						{
							var hide = jQuery("#ux_ddl_display_galleries").val();
							if(hide == "all")
							{
								jQuery("#ux_ddl_select_galleries").css("display","none");
								jQuery("#ux_ddl_individual_galleries").css("display","none");
							}
							else if(hide == "selected")
							{
								jQuery("#ux_ddl_select_galleries").css("display","block");
								jQuery("#ux_ddl_individual_galleries").css("display","none");
							}
							else
							{
								jQuery("#ux_ddl_select_galleries").css("display","none");
								jQuery("#ux_ddl_individual_galleries").css("display","block");
							}
							load_sidebar_content();
						}
					}

					if(typeof(change_gallery_type) != "function")
					{
						function change_gallery_type()
						{
							var type = jQuery("#ux_ddl_gallery_type").val();
							if(type =="compact")
							{
								jQuery("#ux_ddl_type").css("display","none");
								jQuery("#ux_ddl_layout_type").val("grid");
								jQuery("#ux_ddl_layout_type").prop('disabled', true);
							}
							else if(type == "extended")
							{
								jQuery("#ux_ddl_type").css("display","block");
								jQuery("#ux_ddl_layout_type").prop('disabled', false);
							}
							else
							{
								jQuery("#ux_ddl_type").css("display","block");
							}
							load_sidebar_content();
						}
					}

					jQuery("#ux_ddl_display_galleries").val("individual");
					jQuery("#ux_ddl_display_galleries option[value=all]").attr("disabled",true);
					jQuery("#ux_ddl_display_galleries option[value=selected]").attr("disabled",true);

					jQuery("#ux_ddl_gallery_type").val("only_images");
					jQuery("#ux_ddl_gallery_type option[value=compact]").attr("disabled",true);
					jQuery("#ux_ddl_gallery_type option[value=extended]").attr("disabled",true);

					jQuery("#ux_ddl_layout_type option[value=list]").attr("disabled",true);
					jQuery("#ux_ddl_layout_type option[value=grid]").attr("disabled",true);

					jQuery("#ux_ddl_lightbox option[value=disabled]").attr("disabled",true);

					jQuery("#ux_ddl_order_by option[value=pic_id]").attr("disabled",true);
					jQuery("#ux_ddl_order_by option[value=pic_name]").attr("disabled",true);
					jQuery("#ux_ddl_order_by option[value=disabled]").attr("disabled",true);
					jQuery("#ux_ddl_order_by option[value=title]").attr("disabled",true);
					jQuery("#ux_ddl_order_by option[value=date]").attr("disabled",true);

					jQuery("#ux_txt_gallery_thumb_dimension_width").prop("disabled", true);
					jQuery("#ux_txt_gallery_thumb_dimension_height").prop("disabled", true);

					jQuery("#ux_ddl_gallery_thumb_border_size").prop("disabled", true);
					jQuery("#ux_ddl_gallery_thumb_border_style").prop("disabled", true);
					jQuery("#ux_txt_gallery_thumb_border_color").prop("disabled", true);

					jQuery("#ux_txt_margin_top_gallery").prop("disabled", true);
					jQuery("#ux_txt_margin_right_gallery").prop("disabled", true);
					jQuery("#ux_txt_margin_bottom_gallery").prop("disabled", true);
					jQuery("#ux_txt_margin_left_gallery").prop("disabled", true);

					jQuery("#ux_txt_padding_top_gallery").prop("disabled", true);
					jQuery("#ux_txt_padding_right_gallery").prop("disabled", true);
					jQuery("#ux_txt_padding_bottom_gallery").prop("disabled", true);
					jQuery("#ux_txt_padding_left_gallery").prop("disabled", true);

					jQuery("#ux_txt_title_color").prop("disabled", true);
					jQuery("#ux_txt_description_color").prop("disabled", true);

					var gm_frm_shortcode_generator = jQuery("#ux_frm_shortcode_generator");
					gm_frm_shortcode_generator.validate
					({
						submitHandler: function(form)
						{
							var theme_view = jQuery("#ux_ddl_theme_view").val();
							var display = jQuery("#ux_ddl_display_galleries").val();
							var choose_individual_gallery = jQuery("#ux_ddl_choose_gallery").val();
							var gallery_type = jQuery("#ux_ddl_gallery_type").val();
							var gallery_title = jQuery("#ux_ddl_gallery_title").val();
							var gallery_description = jQuery("#ux_ddl_gallery_description").val();
							var lightbox = jQuery("#ux_ddl_lightbox").val();
							var order_by= jQuery("#ux_ddl_order_by").val();

							if(display == "individual")
							{
								var disp = "id=\""+encodeURIComponent(choose_individual_gallery)+"\"";
							}

							if(gallery_type=="only_images")
							{
								var show ="show_title=\""+gallery_title+"\"";
								var desc ="show_desc=\""+gallery_description+"\"";
							}

							jQuery("#ux_txt_generated_shortcode").css("display","block");
							var shortcode = "[gallery_master theme=\""+theme_view+"\" source_type=\""+display+"\" "+disp+" gallery_type=\""+gallery_type+"\" "+show+" "+desc+" lightbox=\""+lightbox+"\" order_by=\""+order_by+"\"] [/gallery_master]";
							jQuery("#ux_txtarea_generated_shortcode").html(shortcode);
						}
					});
					<?php
				break;

				case "gm_feature_request":
					?>
					jQuery("#ux_li_feature_request").addClass("active");
					var features_array = [];
					var url = "http://tech-prodigy.org/request.php";
					var gm_frm_feature_request = jQuery("#ux_frm_feature_requests");
					var error_feature_request = jQuery(".alert-danger", gm_frm_feature_request);
					var success_feature_request = jQuery(".alert-success", gm_frm_feature_request);

					gm_frm_feature_request.validate
					({
						errorElement: "span",
						errorClass: "help-block help-block-error",
						focusInvalid: true,
						ignore: "",
						rules:
						{
							ux_txt_your_name:
							{
								required:true
							},
							ux_txt_email_address:
							{
								required:true,
								email:true
							},
							ux_txtarea_feature_request:
							{
								required:true
							}
						},
						invalidHandler: function (event, validator)
						{
							success_feature_request.hide();
							error_feature_request.show();
						},
						errorPlacement: function (error, element)
						{
							var icon = jQuery(element).parent(".input-icon").children("i");
							icon.removeClass("fa-check").addClass("fa-warning");
							icon.attr("data-original-title", error.text()).tooltip({"container": "body"});
						},
						highlight: function (element)
						{
							jQuery(element)
								.closest(".form-group").removeClass("has-success").addClass("has-error");
						},
						success: function (label, element)
						{
							var icon = jQuery(element).parent(".input-icon").children("i");
							jQuery(element).closest(".form-group").removeClass("has-error").addClass("has-success");
							icon.removeClass("fa-warning").addClass("fa-check");
						},
						submitHandler:function(form)
						{
							features_array.push(jQuery("#ux_txt_your_name").val());
							features_array.push(jQuery("#ux_txt_email_address").val());
							features_array.push(jQuery("#ux_txtarea_feature_request").val());
							jQuery.post(url,
							{
								data :JSON.stringify(features_array),
								param: "gm_feature_requests",
								action: "feature_requests",
								_wp_nonce:"<?php echo $send_request;?>"
							},
							function()
							{
								overlay_loading("feature_request");
								setTimeout(function()
								{
									remove_overlay();
									window.location.reload();
								}, 3000);
							});
						}
					});
					<?php
					break;

				case "gm_premium_editions":
					?>
					jQuery("#ux_li_premium_editions").addClass("active");
					<?php
				break;

				case "gm_system_information":
					?>
					jQuery("#ux_li_system_information").addClass("active");
					jQuery.getSystemReport = function (strDefault, stringCount, string, location)
					{
						var o = strDefault.toString();
						if (!string)
						{
							string = "0";
						}
						while (o.length < stringCount)
						{
							if (location == "undefined")
							{
								o = string + o;
							}
							else
							{
								o = o + string;
							}
						}
						return o;
					};

					jQuery(".system-report").click(function ()
					{
						var report = "";
						jQuery(".custom-form-body").each(function ()
						{
							jQuery("h3.form-section", jQuery(this)).each(function ()
							{
								report = report + "\n### " + jQuery.trim(jQuery(this).text()) + " ###\n\n";
							});
							jQuery("tbody > tr", jQuery(this)).each(function ()
							{
								var the_name = jQuery.getSystemReport(jQuery.trim(jQuery(this).find("strong").text()), 25, " ");
								var the_value = jQuery.trim(jQuery(this).find("span").text());
								var value_array = the_value.split(", ");
								if (value_array.length > 1)
								{
									var temp_line = "";
									jQuery.each(value_array, function (key, line) {
										var tab = ( key == 0 ) ? 0 : 25;
										temp_line = temp_line + jQuery.getSystemReport("", tab, " ", "f") + line + "\n";
									});
									the_value = temp_line;
								}
								report = report + "" + the_name + the_value + "\n";
							});
						});
						try
						{
							jQuery("#ux_system_report").slideDown();
							jQuery("#ux_system_report textarea").val(report).focus().select();
							return false;
						} catch (e)
						{
							console.log(e);
						}
						return false;
					});

					jQuery("#ux_btn_system_report").click(function ()
					{
						if(jQuery("#ux_btn_system_report").text() == "Close System Report!")
						{
							jQuery("#ux_system_report").slideUp();
							jQuery("#ux_btn_system_report").html("Get System Report!");
						}
						else
						{
							jQuery("#ux_btn_system_report").html("Close System Report!");
							jQuery("#ux_btn_system_report").removeClass("system-report");
							jQuery("#ux_btn_system_report").addClass("close-report");
						}
					});
					<?php
				break;
			}
		}
		?>
		</script>
		<?php
	}
}
?>