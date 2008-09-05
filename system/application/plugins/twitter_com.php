<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Twitter_com {
	
	//sample class for twitter

	function pre_db($item, $original)
	{
        $original_publisher = $original->get_permalink();
		$twitter_username = explode('/', $original_publisher);
		$twitter_username = $twitter_username[3];

		//remove username from front of posts
		$item->item_title = trim(str_replace($twitter_username.':', '', $item->item_title));

		//filter out @replies (set as unpublished)
		if (substr($item->item_title, 0, 1) == '@') {
			$item->item_status = 'draft';		
		}
		
		//remove item_content as it's just the same as the title anyway
		$item->item_content = '';
		return $item;
	}
	
	function pre_display($item)
	{
		return $item;
	}


}
?>