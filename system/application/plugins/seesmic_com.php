<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Seesmic_com {
	
	//sample class for seesmic

	function pre_db($item, $original)
	{
		return $item;
	}
	
	function pre_display($item)
	{
	    //seesmic has a "hidden" image...
	    $item->item_data['image'] = $item->item_data['enclosures'][0]->thumbnails[0];
	    
	    //get video id
	    $videoid = explode('/', $item->item_data['permalink']);
	    $videoid = $videoid[4];

	    //create teh video
	    $item->item_data['video'] = '<object width="435" height="355"><param name="movie" value="http://seesmic.com/embeds/wrapper.swf"/><param name="bgcolor" value="#666666"/><param name="allowFullScreen" value="true"/><param name="allowScriptAccess" value="always"/><param name="flashVars" value="video='.$videoid.'&version=threadedplayer"/><embed src="http://seesmic.com/embeds/wrapper.swf" type="application/x-shockwave-flash" flashVars="video='.$videoid.'&version=threadedplayer" allowFullScreen="true" bgcolor="#666666" allowScriptAccess="always" width="435" height="355"></embed></object>';
		return $item;
	}


}
?>