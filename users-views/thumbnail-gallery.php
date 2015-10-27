<div id="gallery_master_thumbnails_<?php echo $unique_id;?>">
	<?php
	foreach($pics_array as $pic)
	{
		?>
		<div class="gm-thumbnail-container gallery-master-thumbnail<?php echo $unique_id;?>">
			<a href="<?php echo GALLERY_MASTER_MAIN_URL.esc_attr($pic["upload_url"]);?>" class="gallery-master<?php echo $unique_id;?>" data-title="<?php echo esc_attr(stripcslashes(urldecode($pic["image_title"])));?>" data-desc="<?php echo esc_attr(stripcslashes(urldecode($pic["image_description"])));?>">
				<?php
				if(isset($pic["is_video"]))
				{
					?>
					<div class="gallery-master-css<?php echo $unique_id;?> gallery-master-iframe-thumb<?php echo $unique_id;?>">
						<iframe src="<?php echo esc_attr($pic["upload_url"]); ?>" title="<?php echo esc_attr(stripcslashes(urldecode($pic["alt_text"])));?>" alt="<?php echo esc_attr(stripcslashes(urldecode($pic["alt_text"])));?>"></iframe>
					<?php
				}
				else
				{
					?>
					<div class="gallery-master-css<?php echo $unique_id;?> imgLiquidFill<?php echo $unique_id;?>">
						<img src="<?php echo GALLERY_MASTER_MAIN_URL.esc_attr($pic["thumbnail_url"]);?>" alt="<?php echo esc_attr(stripcslashes(urldecode($pic["alt_text"])));?>" id="ux_gm_file_<?php echo intval($pic["gallery_id"]);?>" name="ux_gm_file_<?php echo intval($pic["gallery_id"]);?>"/>
					<?php
				}
				?>
				</div>
				<?php
				if(($show_title == "show"  || $show_desc == "show") && ($pic["image_title"] != "" || $pic["image_description"] != ""))
				{
					?>
					<div style="clear: both"></div>
					<div class="gm-thumbnail-description">
						<?php
						if($show_title == "show" && $pic["image_title"] != "")
						{
							?>
							<h2><?php echo esc_attr(stripcslashes(urldecode($pic["image_title"])));?></h2>
						<?php
						}
						if ($show_desc == "show" && $pic["image_description"] != "")
						{
							?>
							<p>
								<?php echo stripcslashes(htmlspecialchars_decode(urldecode($pic["image_description"])));?>
							</p>
						<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</a>
		</div>
		<?php
	}
	?>
</div>