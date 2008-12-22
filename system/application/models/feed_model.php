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

class Feed_model extends Model {

	function __construct()
	{
		parent::Model();	
	}
	
	function _process($feeds)
	{
		if ($feeds) {
			
			foreach ($feeds as $key => $value) {
				$feeds[$key]->item_count = $this->db->select('ID')->get_where('items', array('item_status !=' => 'deleted', 'item_feed_id' => $feeds[$key]->feed_id))->num_rows();
			}
			
			return $feeds;
			
		} else {
			return array();	
		}
	}
	
	function get_feeds()
	{
		return $this->_process($this->db->get('feeds')->result());	
	}

	function count_active_feeds()
	{
		return $this->db->get_where('feeds', array('feed_status' => 'active'))->num_rows();	
	}

	function get_active_feeds($group = FALSE)
	{
		if ($group) {
			return $this->_process($this->db->group_by('feed_domain')->get_where('feeds', array('feed_status' => 'active'))->result());	
		} else {
			return $this->_process($this->db->get_where('feeds', array('feed_status' => 'active'))->result());	
		}
	}
	
	function add_feed($feed)
	{
		$this->db->insert('feeds', $feed);
		//om nom nom
	}
	
	function delete_feed($feed_id)
	{
		$this->db->update('feeds', array('feed_status' => 'deleted'), array('feed_id' => $feed_id));	
	}
	
}
?>