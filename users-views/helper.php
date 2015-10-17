<?php
global $wpdb;
$unique_id = rand(100, 10000);

if(!function_exists("get_gallery_details"))
{
	function get_gallery_details($id,$gallery_details)
	{
		$get_single_detail = array();
		foreach ($gallery_details as $row)
		{
			if ($row->gallery_id == $id)
			{
				$get_single_detail["$row->gallery_meta_key"] = $row->gallery_meta_value;
				$get_single_detail["gallery_id"] = $row->gallery_id;
			}
		}
		return $get_single_detail;
	}
}

if(!function_exists("get_pic_details"))
{
	function get_pic_details($id,$gallery_settings)
	{
		$gallery_id_array = array();
		$images_data = array();
		foreach ($gallery_settings as $row)
		{
			if($row->parent_id == $id)
			{
				array_push($gallery_id_array, $row->gallery_id);
			}
		}
		$gallery_id_array = array_unique($gallery_id_array, SORT_REGULAR);
		foreach ($gallery_id_array as $id)
		{
			$gallery = get_gallery_details($id,$gallery_settings);
			array_push($images_data, $gallery);
		}
		return array_unique($images_data, SORT_REGULAR);
	}
}

switch($source_type)
{
	case "individual":
		$gallery_data = $wpdb->get_results
		(
			$wpdb->prepare
			(
				"SELECT * FROM " . gallery_master_meta(). " INNER JOIN ".gallery_master_galleries().
				" ON ".gallery_master_galleries().".gallery_id = ".gallery_master_meta().".gallery_id WHERE "
				.gallery_master_galleries().".parent_id = %d or " . gallery_master_galleries () . ".parent_id = %d ORDER BY RAND()",
				$gallery_id,
				0
			)
		);
		$gallery_details = get_gallery_details($gallery_id,$gallery_data);
		$pics_array = get_pic_details($gallery_id,$gallery_data);
	break;
}

$thumb_height = 160;
$thumb_width = 200;
$border_style = "2px solid #000";
$margin = "5px 5px 5px 5px";
$padding = "5px 5px 5px 5px";
$title_color = "#000";
$desc_color = "#000";
?>