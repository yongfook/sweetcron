<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Qik_com {
	
	//sample class for qik
	
	function pre_db($item, $original)
	{
		return $item;
	}
	
	function pre_display($item)
	{
	    //get streamname
	    $streamname = explode('/', $item->item_data['enclosures'][0]->link);
        $streamname = str_replace('.flv','',$streamname[4]);
        
        //get video id
        $videoid = explode('/', $item->item_data['permalink']);
        $videoid = $videoid[4];
        
        //get username
        $username = explode('/', $item->feed_url);
        $username = $username[3];

	    $item->item_data['video'] = '<object width="425" height="319"><param name="movie" value="http://qik.com/swfs/qik_player.swf?streamname='.$streamname.'&vid='.$videoid.'&playback=false&polling=false&user='.$username.'&displayname='.$username.'&safelink='.$username.'&userlock=true&islive=&username=anonymous" ></param><param name="wmode" value="transparent" ></param><param name="allowScriptAccess" value="always" ><embed src="http://qik.com/swfs/qik_player.swf?streamname='.$streamname.'&vid='.$videoid.'&playback=false&polling=false&user='.$username.'&displayname='.$username.'&safelink='.$username.'&userlock=true&islive=&username=anonymous" type="application/x-shockwave-flash" wmode="transparent" width="425" height="319" allowScriptAccess="always"></embed></object>';
		return $item;
	}


}
?>