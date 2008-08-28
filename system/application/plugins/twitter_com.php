<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Twitter_com {
	
	//sample class for twitter

	function pre_db($item)
	{
		//all we want to do before twitter stuff gets added to the db is to remove the username from the front of the title / content
		$username = 'yongfook';
		$item->item_title = str_replace($username.':', '', $item->item_title);
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