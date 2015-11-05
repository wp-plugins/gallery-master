<script type="text/javascript">
	<?php
	switch($gallery_type)
	{
		case "only_images":
			switch($theme)
			{
				case "thumbnails":
					?>
					jQuery(".imgLiquidFill<?php echo $unique_id;?>").imgLiquid({fill: true});
					<?php
				break;

				case "masonry":
					?>
					var container<?php echo $unique_id;?> = jQuery("#gallery_master_masonry_<?php echo $unique_id;?>");

					container<?php echo $unique_id;?>.imagesLoaded(function()
					{
						container<?php echo $unique_id;?>.masonry(
							{
								itemSelector : ".masonry-item",
								isFitWidth: true,
								isAnimated: true,
								isResizable: true
							});
					});
					<?php
				break;
			}
		break;
	}
	?>
	jQuery(".gallery-master<?php echo $unique_id;?>").lightGallery({
		caption : true,
		desc : true,
		closable : true
	});
</script>