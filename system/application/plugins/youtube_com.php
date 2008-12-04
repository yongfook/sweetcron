<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Youtube_com {
	
	//sample class for youtube
	
	function pre_db($item, $original)
	{

		//youtube stuffs the content with a bunch of things I don't want!
		//so lets get rid of it...
		$content = explode('Author:', $item->item_content);
		$item->item_content = $content[0];
		
		//looky, youtube has an image too
		$item->item_data['image'] = $item->item_data['enclosures'][0]->thumbnails[0];
		return $item;
	}
	
	function pre_display($item)
	{
		$link = $item->item_data['permalink'];
		$link = str_replace('?v=', '/v/', $link);
				
		$item->item_data['video'] = '<object width="212" height="178"><param name="movie" value="'.$link.'&hl=en&fs=1&showsearch=0"></param><param name="allowFullScreen" value="true"></param><embed src="'.$link.'&hl=en&fs=1&showsearch=0" type="application/x-shockwave-flash" allowfullscreen="true" width="212" height="178"></embed></object>';
		return $item;
	}


}
?>