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
									<i class="icon-settings"></i><?php _e("System Information",gallery_master); ?>
								</div>
							</div>
							<div class="portlet-body form">
								<form id="ux_frm_system_information">
									<div class="form-body">
										<div class="layout-system-report" id="ux_system_report">
											<textarea id="ux_txtarea_system_report" name="ux_txtarea_system_report" readonly="readonly"></textarea>
										</div>
										<div class="form-actions">
											<div class="btn-set pull-right">
												<button type="button" class="btn red-sunglo  system-report" name="ux_btn_system_report"  id="ux_btn_system_report"><?php _e("Get System Report!",gallery_master)?></button>
											</div>
										</div>
										<div class="custom-form-body">
											<h3 class="form-section"><?php _e("Server Information",gallery_master); ?></h3>
											<table class="table table-striped table-bordered table-hover" >
												<thead class="align-thead-left">
													<tr>
														<th class="custom-table-th-left" style="width: 40% !important;">
															<?php _e("Environment Key",gallery_master); ?>
														</th>
														<th class="custom-table-th-right">
															<?php _e("Environment Value",gallery_master); ?>
														</th>
													</tr>
												</thead>
												<tbody>
												<tr>
													<td>
														<strong><?php _e("Home URL",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo home_url(); ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("Site URL",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo site_url(); ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("WP Version",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo site_url(); ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("WP Multisite Enabled",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php if (is_multisite()) echo "Yes"; else echo "No"; ?></span>
													</td>
												</tr>
												<?php
												$request["cmd"] = "_notify-validate";
												$params = array(
													"sslverify" 	=> false,
													"timeout" 		=> 60,
													"user-agent"	=> "Wp-Gallery-Bank",
													"body"			=> $request
												);
												$response = wp_remote_post( "https://www.paypal.com/cgi-bin/webscr", $params );
												?>
												<tr>
													<td>
														<strong><?php _e("WP Remote Post",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo (! is_wp_error($response)) ? "Success" : "Failed";?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("Web Server Info",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo esc_html($_SERVER["SERVER_SOFTWARE"]);?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP Version",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php if (function_exists("phpversion")) echo esc_html(phpversion());?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("MySQL Version",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php global $wpdb; echo $wpdb->db_version();?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("WP Debug Mode",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php if (defined("WP_DEBUG") && WP_DEBUG) echo "Yes"; else echo "No"; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("WP Language",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php if (defined("WPLANG") && WPLANG) echo WPLANG; else _e("Default"); ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("WP Max Upload Size",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo size_format(wp_max_upload_size()); ?></span>
													</td>
												</tr>
												<?php if (function_exists("ini_get")) : ?>
													<tr>
														<td>
															<strong><?php _e("PHP Max Script Execute Time",gallery_master); ?> :</strong>
														</td>
														<td>
															<span><?php echo ini_get("max_execution_time"); ?></span>
														</td>
													</tr>
													<tr>
														<td>
															<strong><?php _e("PHP Max Input Vars",gallery_master); ?> :</strong>
														</td>
														<td>
															<span><?php echo ini_get("max_input_vars"); ?></span>
														</td>
													</tr>
													<tr>
														<td>
															<strong><?php _e("SUHOSIN Installed",gallery_master); ?> :</strong>
														</td>
														<td>
															<span><?php echo extension_loaded("suhosin") ? "Yes" : "No";?></span>
														</td>
													</tr>
												<?php endif; ?>
												<tr>
													<td>
														<strong><?php _e("Default Timezone",gallery_master); ?> :</strong>
													</td>
													<td>
																<span>
																<?php
																$timezone = date_default_timezone_get();
																if ("UTC" !== $timezone) {
																	echo sprintf("Default timezone is %s - it should be UTC", $timezone);
																} else {
																	echo sprintf("Default timezone is %s", $timezone);
																}
																?>
																</span>
													</td>
												</tr>
												<?php
												global $wpdb, $gb;
												// Get MYSQL Version
												$sql_version = $wpdb->get_var("SELECT VERSION() AS version");
												// GET SQL Mode
												$my_sql_info = $wpdb->get_results("SHOW VARIABLES LIKE \"sql_mode\"");
												if (is_array($my_sql_info)) $sqlmode = $my_sql_info[0]->Value;
												if (empty($sqlmode)) $sqlmode = "Not set";
												// Get PHP Safe Mode
												if (ini_get("safemode")) $safemode = "On";
												else $safemode = "Off";
												// Get PHP allow_url_fopen
												if (ini_get("allow-url-fopen")) $allowurlfopen = "On";
												else $allowurlfopen = "Off";
												// Get PHP Max Upload Size
												if (ini_get("upload_max_filesize")) $upload_maximum = ini_get("upload_max_filesize");
												else $upload_maximum = "N/A";
												// Get PHP Output buffer Size
												if (ini_get("pcre.backtrack_limit")) $backtrack_lmt = ini_get("pcre.backtrack_limit");
												else $backtrack_lmt = "N/A";
												// Get PHP Max Post Size
												if (ini_get("post_max_size")) $post_maximum = ini_get("post_max_size");
												else $post_maximum = "N/A";
												// Get PHP Memory Limit
												if (ini_get("memory_limit")) $memory_limit = ini_get("memory_limit");
												else $memory_limit = "N/A";
												// Get actual memory_get_usage
												if (function_exists("memory_get_usage")) $memory_usage = round(memory_get_usage() / 1024 / 1024, 2) . " MByte";
												else $memory_usage = "N/A";
												// required for EXIF read
												if (is_callable("exif_read_data")) $exif = "Yes" . " ( V" . substr(phpversion("exif"), 0, 4) . ")";
												else $exif = "No";
												// required for meta data
												if (is_callable("iptcparse")) $iptc = "Yes";
												else $iptc = "No";
												// required for meta data
												if (is_callable("xml_parser_create")) $xml = "Yes";
												else $xml = "No";
												?>
												<tr>
													<td>
														<strong><?php _e("Operating System",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo PHP_OS; ?>&nbsp;(<?php echo(PHP_INT_SIZE * 8) ?>&nbsp;Bit)</span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("Memory usage",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $memory_usage; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("SQL Mode",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $sqlmode; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP Safe Mode",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo PHP_VERSION; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP Allow URL fopen",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $allowurlfopen; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP Memory Limit",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $memory_limit; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP Max Post Size",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $post_maximum; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PCRE Backtracking Limit",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $backtrack_lmt; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP Exif support",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $exif; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP IPTC support",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $iptc; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<strong><?php _e("PHP XML support",gallery_master); ?> :</strong>
													</td>
													<td>
														<span><?php echo $xml; ?></span>
													</td>
												</tr>
												</tbody>
											</table>
										</div>
										<div class="custom-form-body">
											<h3 class="form-section"><?php _e("Active  Plugin Information",gallery_master); ?></h3>
											<table class="table table-striped table-bordered table-hover">
												<thead class="align-thead-left">
												<tr>
													<th class="custom-table-th-left" style="40% !important;">
														<?php _e("Plugin Key",gallery_master); ?>
													</th>
													<th class="custom-table-th-right">
														<?php _e("Plugin Value",gallery_master); ?>
													</th>
												</tr>
												</thead>
												<tbody>
												<?php
												$active_plugins = (array)get_option("active_plugins", array());
												if (is_multisite())
													$active_plugins = array_merge($active_plugins, get_site_option("active_sitewide_plugins", array()));
												$get_plugins = array();
												foreach ($active_plugins as $plugin) {
													$plugin_data = @get_plugin_data(WP_PLUGIN_DIR . "/" . $plugin);
													$version_string = "";
													if (!empty($plugin_data["Name"])) {
														$plugin_name = $plugin_data["Name"];
														if (!empty($plugin_data["PluginURI"])) {
															$plugin_name = "<tr><td><strong>" . $plugin_name . " :</strong></td><td><span>". "By " . $plugin_data["Author"] . "<br/> Version " . $plugin_data["Version"] . $version_string."</span></td></tr>";
														}
														echo $plugin_name;
													}
												}
												?>
												</tbody>
											</table>
										</div>
										<?php
										global $wp_version;
										if($wp_version >= 3.4) {
											$active_theme = wp_get_theme ();
											?>
											<div class="custom-form-body">
												<h3 class="form-section"><?php _e("Active Theme Information",gallery_master); ?></h3>
												<table class="table table-striped table-bordered table-hover">
													<thead class="align-thead-left">
													<tr>
														<th style="width: 40% !important;" class="custom-table-th-left">
															<?php _e ( "Theme Key" , gallery_master ); ?>
														</th>
														<th class="custom-table-th-right">
															<?php _e ( "Theme Value" , gallery_master ); ?>
														</th>
													</tr>
													</thead>
													<tbody>
													<tr>
														<td>
															<strong><?php _e ( "Theme Name" , gallery_master ); ?> :</strong>
														</td>
														<td>
															<span><?php echo $active_theme->Name; ?></span>
														</td>
													</tr>
													<tr>
														<td>
															<strong><?php _e ( "Theme Version" , gallery_master ); ?> :</strong>
														</td>
														<td>
															<span><?php echo $active_theme->Version;?></span>
														</td>
													</tr>
													<tr>
														<td>
															<strong><?php _e ( "Author URL" , gallery_master ); ?> :</strong>
														</td>
														<td>
														<span><a href="<?php echo $active_theme->{"Author URI"}; ?>"
														         target="_blank"><?php echo $active_theme->{"Author URI"}; ?></a></span>
														</td>
													</tr>
													</tbody>
												</table>
											</div>
										<?php
										}
										?>
										<div class="custom-form-body">
											<h3 class="form-section"><?php _e("GB Library Information",gallery_master); ?></h3>
											<table class="table table-striped table-bordered table-hover" >
												<thead class="align-thead-left">
												<tr>
													<th style="width: 40% !important;" class="custom-table-th-left">
														<?php _e("GD Key",gallery_master); ?>
													</th>
													<th class="custom-table-th-right">
														<?php _e("GD Value",gallery_master); ?>
													</th>
												</tr>
												</thead>
												<tbody>
												<?php
												if(!function_exists("get_gd_settings"))
												{
													function get_gd_settings($bool)
													{
														if ($bool)
															return "Yes";
														else
															return "No";
													}
												}
												if (function_exists("gd_info"))
												{
													$information = gd_info();
													$key = array_keys($information);
													for ($i = 0; $i < count($key); $i++)
													{
														if (is_bool($information[$key[$i]]))
															echo "<tr><td><strong>" . $key[$i] . " :</strong></td><td><span>" . get_gd_settings($information[$key[$i]]) . "</span> </td></tr>";
														else
															echo "<tr><td><strong>" . $key[$i] . " :</strong></td><td><span>" . $information[$key[$i]] . "</span></td></tr>";
													}
												}
												else
												{
													echo "<h4>" . "No GD support" . "!</h4>";
												}
												?>
												</tbody>
											</table>
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