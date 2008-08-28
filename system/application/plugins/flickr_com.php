<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Flickr_com {
	
	//sample class for flickr

	function pre_db($item)
	{
		$remove_this = 'yongfook posted a photo:';
		$item->item_content = trim(str_replace($remove_this, '', $item->item_content));
		//flickr-specific tag handling
		foreach ($item->item_data['categories'] as $key => $value) {
			$item->item_data['tags'][$key] = $value->term;	
		}
		return $item;
	}
	
	function pre_display($item)
	{
		return $item;
	}


}
?>