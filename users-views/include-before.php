<style>
	.gallery-master-thumbnail<?php echo $unique_id;?>
	{
		margin: <?php echo $margin;?> !important;
		padding: <?php echo $padding;?> !important;
		display: inline-block !important;
	}

	.gallery-master-css<?php echo $unique_id;?>
	{
		border: <?php echo $border_style;?> !important;
		cursor: pointer;
	}

	<?php
	switch($theme)
	{
		case "thumbnails":
			?>
			.gallery-master-iframe-thumb<?php echo $unique_id;?>
			{
				max-width: <?php echo $thumb_width;?>px !important;
				height: <?php echo $thumb_height;?>px !important;
				box-sizing: border-box !important;
			}

			.gallery-master-iframe-thumb<?php echo $unique_id;?> iframe
			{
				margin-bottom: 0px !important;
				display: block !important;
				width: 100%;
				height: 100%;
			}

			.imgLiquidFill<?php echo $unique_id;?>
			{
				width: 100%;
				height: <?php echo $thumb_height;?>px !important;
				box-sizing: border-box !important;
			}
			.gm-thumbnail-container
			{
				max-width: <?php echo $thumb_width;?>px !important;
			}

			.gm-thumbnail-description h2
			{
				color: <?php echo $title_color;?> !important;
			}

			.gm-thumbnail-description p
			{
				color: <?php echo $desc_color;?> !important;
			}
			<?php
		break;

		case "masonry":
			?>
			.gallery-masonry-thumb<?php echo $unique_id;?>
			{
				width: 100%;
				max-width: <?php echo $thumb_width;?>px !important;
				box-sizing: border-box !important;
			}

			.gallery-masonry-thumb<?php echo $unique_id;?> iframe,
			.gallery-masonry-thumb<?php echo $unique_id;?> img
			{
				border-radius : 0px !important;
				margin-bottom: 0px !important;
				display: block !important;
				width: 100%;
			}

			.gm-thumbnail-text > h2
			{
				color: <?php echo $title_color;?> !important;
			}

			.gm-thumbnail-text > p
			{
				color: <?php echo $desc_color;?> !important;
			}
			<?php
		break;
	}
	?>
</style>

<?php
if($show_title == "show" || $show_desc == "show")
{
	if($gallery_details["gallery_title"] != "")
	{
		?>
		<h3><?php echo stripcslashes(urldecode($gallery_details["gallery_title"]));?></h3>
		<?php
	}
	if($gallery_details["gallery_description"] != "")
	{
		?>
		<p><?php echo stripcslashes(htmlspecialchars_decode(urldecode($gallery_details["gallery_description"])));?></p>
		<?php
	}
}
?>

