<?php
if (!is_user_logged_in())
{
	return;
}
else {
	switch ($gm_role) {
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
		if (!class_exists("tech_prodigy_save_data"))
		{
			class tech_prodigy_save_data
			{
				/**
				 * @param $tbl
				 * @param $data
				 * @return mixed
				 */
				function insert_data($tbl, $data)
				{
					global $wpdb;
					$wpdb->insert($tbl, $data);
					return $wpdb->insert_id;
				}

				/**
				 * @param $tbl
				 * @param $data
				 * @param $where
				 */
				function update_data($tbl, $data, $where)
				{
					global $wpdb;
					$wpdb->update($tbl, $data, $where);
				}

				/**
				 * @param $tbl
				 * @param $where
				 */
				function delete_data($tbl, $where)
				{
					global $wpdb;
					$wpdb->delete($tbl, $where);
				}

				/**
				 * @param $tbl
				 * @param $where
				 * @param $data
				 */

				function bulk_delete_data($tbl,$where,$data)
				{
					global $wpdb;
					$wpdb->query("DELETE FROM $tbl WHERE $where IN ($data)");
				}
			}
		}

		if (!class_exists("tech_prodigy_helper_functions"))
		{
			class tech_prodigy_helper_functions
			{
				/**
				 * Function to Crop  Thumbnails for Editing Purposes
				 *
				 * @since   1.0
				 * @param $image
				 * @param $width
				 * @param $height
				 * @return array|bool
				 */

				function process_file_uploading($image, $width, $height)
				{
					$temp_image_path = GALLERY_MASTER_UPLOAD_DIR . $image;
					$temp_image_name = $image;
					list(, , $temp_image_type) = getimagesize($temp_image_path);
					if ($temp_image_type === NULL) {
						return false;
					}
					$uploaded_image_path = GALLERY_MASTER_UPLOAD_DIR . $temp_image_name;
					move_uploaded_file($temp_image_path, $uploaded_image_path);
					$type = explode(".", $image);
					$thumbnail_image_path = GALLERY_MASTER_THUMBS_DIR . preg_replace("{\\.[^\\.]+$}", "." . $type[1], $temp_image_name);

					$result = $this->process_thumbs_generation($uploaded_image_path, $thumbnail_image_path, $width, $height);
					return $result ? array($uploaded_image_path, $thumbnail_image_path) : false;
				}

				/**
				 * Generate Thumbnails for Galleries
				 *
				 * @since   1.0
				 * @param $source_image_path
				 * @param $thumbnail_image_path
				 * @param $imageWidth
				 * @param $imageHeight
				 * @return bool
				 */


				function process_thumbs_generation($source_image_path, $thumbnail_image_path, $imageWidth, $imageHeight)
				{
					list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
					$source_aspect_ratio = $source_image_width / $source_image_height;
					if ($source_image_width > $source_image_height) {
						$real_height = $imageHeight;
						$real_width = $imageHeight * $source_aspect_ratio;
					} else if ($source_image_height > $source_image_width) {
						$real_height = $imageWidth / $source_aspect_ratio;
						$real_width = $imageWidth;
					} else {
						$real_height = $imageHeight > $imageWidth ? $imageHeight : $imageWidth;
						$real_width = $imageWidth > $imageHeight ? $imageWidth : $imageHeight;
					}

					if (function_exists("wp_get_image_editor")) {
						$thumb = wp_get_image_editor($source_image_path);
						$thumb->resize($real_width * 2, $real_height * 2, true);
						$thumb->save($thumbnail_image_path);
					}
					else
					{
						$source_gd_image = false;
						switch ($source_image_type)
						{
							case IMAGETYPE_GIF:
								$source_gd_image = imagecreatefromgif($source_image_path);
							break;

							case IMAGETYPE_JPEG:
								$source_gd_image = imagecreatefromjpeg($source_image_path);
							break;

							case IMAGETYPE_PNG:
								$source_gd_image = imagecreatefrompng($source_image_path);
							break;
						}
						if ($source_gd_image === false) {
							return false;
						}
						$thumbnail_gd_image = imagecreatetruecolor($real_width * 2, $real_height * 2);

						if (($source_image_type == 1) || ($source_image_type == 3))
						{
							imagealphablending($thumbnail_gd_image, false);
							imagesavealpha($thumbnail_gd_image, true);
							$transparent = imagecolorallocatealpha($thumbnail_gd_image, 255, 255, 255, 127);
							imagecolortransparent($thumbnail_gd_image, $transparent);
							imagefilledrectangle($thumbnail_gd_image, 0, 0, $real_width * 2, $real_height * 2, $transparent);
						}
						else
						{
							$bg_color = imagecolorallocate($thumbnail_gd_image, 255, 255, 255);
							imagefilledrectangle($thumbnail_gd_image, 0, 0, $real_width * 2, $real_height * 2, $bg_color);
						}

						imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $real_width * 2, $real_height * 2, $source_image_width, $source_image_height);
						switch ($source_image_type)
						{
							case IMAGETYPE_GIF:
								imagepng($thumbnail_gd_image, $thumbnail_image_path, 9);
							break;

							case IMAGETYPE_JPEG:
								imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 100);
							break;

							case IMAGETYPE_PNG:
								imagepng($thumbnail_gd_image, $thumbnail_image_path, 9);
							break;
						}
						imagedestroy($source_gd_image);
						imagedestroy($thumbnail_gd_image);
					}

					if (!is_dir($thumbnail_image_path) && $thumbnail_image_path != '.' && $thumbnail_image_path != '..') {
						if ($source_image_type == IMAGETYPE_JPEG)
						{
							$this->optimize_jpeg($thumbnail_image_path);
						}
						elseif ($source_image_type == IMAGETYPE_PNG)
						{
							$this->optimize_png($thumbnail_image_path);
						}
					}
					return true;
				}

				/**
				 * @param $file
				 * @return bool
				 */
				function optimize_jpeg($file)
				{
					$jpeg_quality = 80; // The quality at which you would like to optimize the JPEG images. 80 is fairly standard for high-quality JPEG images
					if (!isset($jpeg_quality) || !is_numeric($jpeg_quality)) {
						return false;
					}
					if ($jpeg_quality > 100 || $jpeg_quality < 0) {
						$jpeg_quality = 80;
					}
					list($w, $h) = getimagesize($file);
					if (empty($w) || empty($h)) {
						return false;
					}
					$src = imagecreatefromjpeg($file);
					$tmp = imagecreatetruecolor($w, $h);
					imagecopyresampled($tmp, $src, 0, 0, 0, 0, $w, $h, $w, $h);
					imagejpeg($tmp, $file, $jpeg_quality);
					imagedestroy($tmp);
					return true;
				}


				/**
				 * @param $file
				 * @return bool
				 */
				function optimize_png($file)
				{
					$png_quality = 9; // The amount you would like to compress the PNG images. 9 is the maximum compression for PNG images
					if (!isset($png_quality) || !is_numeric($png_quality)) {
						return false;
					}
					if ($png_quality > 9 || $png_quality < 0) {
						$png_quality = 9;
					}
					list($w, $h) = getimagesize($file);
					if (empty($w) || empty($h))
					{
						return false;
					}
					$src = imagecreatefrompng($file);
					$tmp = imagecreatetruecolor($w, $h);
					imagealphablending($tmp, false);
					imagesavealpha($tmp, true);
					imagecopyresampled($tmp, $src, 0, 0, 0, 0, $w, $h, $w, $h);
					imagepng($tmp, $file, $png_quality);
					imagedestroy($tmp);
					return true;
				}
			}
		}
	}
}
?>