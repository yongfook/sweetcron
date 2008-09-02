<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Flickr_com {
	
	//sample class for flickr

	function pre_db($item)
	{
	    $flickr_username = 'yongfook';
		$remove_this = $flickr_username.' posted a photo:';
		$item->item_content = trim(str_replace($remove_this, '', $item->item_content));

		//some flickr feeds have different tag formatting OMGWTF
		if (isset($item->item_data['categories'])) {
    		foreach ($item->item_data['categories'] as $key => $value) {
    			$item->item_data['tags'][$key] = $value->term;	
    		}
        }
		return $item;
	}
	
	function pre_display($item)
	{
		$item->item_data['flickr_com']['image']['500'] = substr($item->item_data['image'], 0, -6).'.jpg';
		$item->item_data['flickr_com']['image']['1024'] = substr($item->item_data['image'], 0, -5).'b.jpg';
		$item->item_data['flickr_com']['image']['original'] = substr($item->item_data['image'], 0, -5).'o.jpg';
		return $item;
	}


}
?>