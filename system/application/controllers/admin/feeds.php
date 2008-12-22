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

class Feeds extends Auth_Controller {

	function __construct()
	{
		parent::Auth_Controller();	
	}
	
	function index()
	{
		$data->page_name = 'Feeds';
		$data->feeds = $this->feed_model->get_active_feeds();
	    $this->load->view('admin/_header', $data);
	    $this->load->view('admin/feeds', $data);            
	    $this->load->view('admin/_footer');            
	}
	
	function add()
	{
		$data->page_name = 'Add Feed';
		if ($_POST) {
			if ($this->input->post('url') == 'http://') {
				$_POST['url'] = '';
			}
			$this->load->library('validation');
	        $rules['url']	= "trim|required|callback__test_feed";	
	        $this->validation->set_rules($rules);
			if ($this->validation->run() == FALSE) {	
				$data->errors = $this->validation->error_string;
			    $this->load->view('admin/_header', $data);
			    $this->load->view('admin/feed_add', $data);				
			} else {
				$new->feed_title = $this->simplepie->get_title();
				$new->feed_icon = $this->simplepie->get_favicon();
				$new->feed_url = $this->validation->url;

				$new->feed_status = 'active';
				
				//use permalink because sometimes feed is on subdomain which screws up plugin compatibility
				$url = parse_url($this->simplepie->get_permalink());
				if (!$url['host']) {
				    $url = parse_url($this->validation->url);    
                }
				if (substr($url['host'], 0, 4) == 'www.') {
					$new->feed_domain = substr($url['host'], 4);
				} else {
					$new->feed_domain = $url['host'];					
				}
                if (!$new->feed_icon) {
                    $new->feed_icon = 'http://'.$new->feed_domain.'/favicon.ico';
                }
				$this->feed_model->add_feed($new);
				header('Location: '.$this->config->item('base_url').'admin/feeds');	
			}
		} else {			
		    $this->load->view('admin/_header', $data);
		    $this->load->view('admin/feed_add', $data);            
		}
	    $this->load->view('admin/_footer');    		
	}
	
	function delete($feed_id)
	{
		$this->feed_model->delete_feed($feed_id);
		header('Location: '.$this->config->item('base_url').'admin/feeds');				
	}
	
	function _test_feed($url)
	{
		$this->simplepie->set_feed_url(prep_url($url));
		$this->simplepie->enable_cache(FALSE);
		$this->simplepie->init();
		//check if already in the db
		if ($this->db->get_where('feeds', array('feed_url' => $url))->row()) {
		    //if it was a deleted feed just reactivate it and forward to feed page
		    $feed = $this->db->get_where('feeds', array('feed_url' => $url))->row();
		    if ($feed->feed_status == 'deleted') {
		        $this->db->update('feeds', array('feed_status' => 'active'), array('feed_id' => $feed->feed_id));
		        header('Location: '.$this->config->item('base_url').'admin/feeds');  
		        exit(); 
            } else {
			    $this->validation->set_message('_test_feed', 'You already added that feed...');
			    return false;				
            }
		} else if ($this->simplepie->error()) {
			$this->validation->set_message('_test_feed', $this->simplepie->error());
			return false;	
		} else {
			//looks like the feed is ok
			return true;	
		}
	}
}
?>