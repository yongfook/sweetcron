<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tumblr_com {
	
	//sample class for tumblr

	function pre_db($item, $original)
	{
		$feed = $original->get_feed();
		$feed = parse_url($feed->get_link());
		$username = explode('.', $feed['host']);

		$tumblr_username = $username[0];
				
		$item_permalink = $item->item_permalink; // Gets the permalink from $item
		$item_permalink_array = explode("/", $item_permalink); // Boom!
		$tumblr_id = end($item_permalink_array); // uses explode() and the last element in the array to find the Post ID

		$URL = ( "http://$tumblr_username.tumblr.com/api/read/?id=$tumblr_id" ); // Uses the Tumblr API to get teh XML for the post
		$c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
    	$tumblr_xml = curl_exec($c);
        curl_close($c);
		
		$xml = new SimpleXMLElement($tumblr_xml);
		$tumblr_post = $xml->posts[0]->post->attributes(); // Creates an array of attributes of the post
		$type = $tumblr_post['type']; // Finally, gets the type!
		
		
		//Put the type into the item object
		$item->item_data['type'] = (string)($type); // Cast it to a String to avoid unserialize errors
		return $item;
	}
	
	function pre_display($item)
	{
		return $item;
	}


}
?>