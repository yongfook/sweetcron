<?php if (!defined('BASEPATH')) exit('No direct access allowed.');
/**
 * Sweetcron
 *
 * Self-hosted lifestream software based on the CodeIgniter framework.
 *
 * Copyright (c) 2008, Yongfook and Egg & Co.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * 	* Redistributions of source code must retain the above copyright notice, this list of
 * 	  conditions and the following disclaimer.
 *
 * 	* Redistributions in binary form must reproduce the above copyright notice, this list
 * 	  of conditions and the following disclaimer in the documentation and/or other materials
 * 	  provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package Sweetcron
 * @copyright 2008, Yongfook and Egg & Co.
 * @author Yongfook
 * @link http://sweetcron/ Sweetcron
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

class Sweetcron_item {
	
    function __construct() {
    }

	//return item feed components
	function get_feed_id()
	{
		return $this->feed_id;	
	}

	function get_feed_title()
	{
		return $this->feed_title;	
	}

	function get_feed_icon()
	{
		return $this->feed_icon;	
	}

	function get_feed_url()
	{
		return $this->feed_url;	
	}

	function get_feed_data()
	{
		return $this->feed_data;	
	}

	function get_feed_status()
	{
		return $this->feed_status;	
	}

	function get_feed_domain()
	{
		return $this->feed_domain;	
	}

	function get_feed_class()
	{
		return $this->feed_class;	
	}

	//return item components
	
	function get_id()
	{
	   return $this->ID;    
    }
    
	function get_date()
	{
		return $this->item_date;	
	}

	function get_nice_date()
	{
		return $this->nice_date;	
	}

	function get_human_date()
	{
		return $this->human_date;	
	}

	function get_content()
	{
		return $this->item_content;	
	}
	
	function get_title()
	{
		return $this->item_title;	
	}

	function get_permalink()
	{
		return $this->item_permalink;	
	}

	function get_original_permalink()
	{
		return $this->item_original_permalink;	
	}

	function get_status()
	{
		return $this->item_status;	
	}

	function get_name()
	{
		return $this->item_name;	
	}

	function get_parent()
	{
		return $this->item_parent;	
	}

	function get_data()
	{
		return $this->item_data;	
	}
	
	//"has" conditionals for item data
	function has_content()
	{
		if (isset($this->item_content) && $this->item_content != '') {
			return true;	
		}
	}

	function has_permalink()
	{
		if (isset($this->item_permalink)) {
			return true;	
		}
	}

	function has_original_permalink()
	{
		if (isset($this->item_original_permalink)) {
			return true;	
		}
	}

	function has_video()
	{
		if (isset($this->item_data['video'])) {
			return true;	
		}
	}

	function has_audio()
	{
		if (isset($this->item_data['audio'])) {
			return true;	
		}
	}
	
	function has_image()
	{
		if (isset($this->item_data['image']) && !empty($this->item_data['image'])) {
			return true;	
		}
	}

	function has_tags()
	{
		if (isset($this->item_tags[0])) {
			return true;	
		}
	}

	function has_tag($query = NULL)
	{
		$query = strtolower($query);
		if (isset($this->item_tags[0])) {
			foreach ($this->item_tags as $tag) {
				if ($tag->slug == $query) {
					return true;	
				}
			}
		}
	}

	function has_data($key = NULL)
	{
		if (isset($this->item_data[$key])) {
            return true;
		}
	}
	
	//get item data
	function get_video()
	{
		if ($this->has_video()) {
			return $this->item_data['video'];	
		}
	}

	function get_audio()
	{
		if ($this->has_audio()) {
			return $this->item_data['audio'];	
		}
	}

	function get_image()
	{
		if ($this->has_image()) {
			return $this->item_data['image'];	
		}
	}

	function get_tags()
	{
		if ($this->has_tags()) {
			return $this->item_tags;	
		} else {
			return array();	
		}
	}

}
?>