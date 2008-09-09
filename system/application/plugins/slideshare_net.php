<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Slideshare_net {
	
	//sample class for slideshare

	function pre_db($item, $original)
	{
        //overwrite item content with clean content
	    $nice_content = $original->get_item_tags('http://search.yahoo.com/mrss/', 'content');
	    $item->item_content = $nice_content[0]['child']['http://search.yahoo.com/mrss/']['description'][0]['data'];
        $embed = $original->get_item_tags('http://slideshare.net/api/1', 'embed');
        $embed = $embed[0]['data'];
        
        //strip out just the movie bit
        $embed = strip_tags($embed, '<object><param><embed>');
        $embed = str_replace(' style="margin:0px" width="425" height="355"', '', $embed);
        $embed = explode('object', $embed);
        $embed = '<object'.$embed[1].'object>';
        $item->item_data['video'] = $embed;
		return $item;
	}
	
	function pre_display($item)
	{
	    $height = '355';
	    $width = '425';
	    $video = $item->get_video();
	    $video = str_replace('355', $height, $video);
	    $video = str_replace('425', $width, $video);
	    $item->item_data['video'] = $video;
		return $item;
	}


}
?>