<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blogsearch_google_com {
	
	//sample class for google blog search

	function pre_db($item, $original)
	{
        //add publisher data
        $original_publisher = $original->get_item_tags(SIMPLEPIE_NAMESPACE_DC_11, 'publisher');
        if (isset($original_publisher[0]['data'])) {
            $item->item_data['publisher'] = $original_publisher[0]['data'];            
        }
		return $item;
	}
	
	function pre_display($item)
	{
		return $item;
	}


}
?>