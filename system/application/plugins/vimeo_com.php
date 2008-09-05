<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vimeo_com {
	
	//sample class for vimeo

	function pre_db($item, $original)
	{
		return $item;
	}
	
	function pre_display($item)
	{
		$link = $item->item_data['permalink'];
		$link = parse_url($link);
		$link = substr($link['path'], 1);
		$item->item_data['video'] = '<object width="212" height="159"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id='.$link.'&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />	<embed src="http://www.vimeo.com/moogaloop.swf?clip_id='.$link.'&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="212" height="159"></embed></object>';
		return $item;
	}


}
?>