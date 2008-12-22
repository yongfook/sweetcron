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

class Write extends Auth_Controller {

	function __construct()
	{
		parent::Auth_Controller();	
	}
	
	function index()
    {
    	if ($this->uri->segment(3) == 'edit') {
    		if ($this->input->post('referer')) {
    			$data->referer = $this->input->post('referer');
    		} else {
    			$data->referer = $_SERVER['HTTP_REFERER'];
    		}
    		$data->editing = TRUE;	
    		//get item
    		$data->item = $this->item_model->get_edit_item_by_id($this->uri->segment(4));
    		if (isset($data->item->item_tags[0])) {
    			foreach ($data->item->item_tags as $tag) {
    				$tags[] = $tag->name;	
    			}
    			$data->tag_string = implode(', ', $tags);
    		}
    		$new_post->item_data = $data->item->item_data;
    	}
		$data->page_name = 'Write';
        if ($_POST) {
			$this->load->library('validation');
	        $rules['title']	= "trim|required|xss_clean";	
	        $rules['date']	= "trim|xss_clean";	
	        $rules['content']	= "trim|xss_clean";	
	        $rules['tags']	= "trim|xss_clean";	
	        $this->validation->set_rules($rules);
			if ($this->validation->run() == FALSE) {	
				$data->errors = $this->validation->error_string;
			    $this->load->view('admin/_header', $data);
			    $this->load->view('admin/write', $data);				
			} else {      
				//prepare data
                if (!isset($data->editing)) {
                	$new_post->item_data = array();                	
                }
                if ($this->validation->tags) {
                    $tags = explode(',', $this->validation->tags);
                    foreach ($tags as $key => $value) {
                        $tags[$key] = trim($value);
                    }
                    if (isset($tags[0])) {
                        $new_post->item_data['tags'] = $tags;	
                    }
                }

                $new_post->item_title = $this->validation->title;		
                if (!$this->validation->content) {
                	$new_post->item_content = '';
                } else {
                	$new_post->item_content = $this->validation->content;
                }

                if ($this->input->post('save_edit') == 'true') {
                	//save edits
                	if ($this->input->post('timestamp') == 'make_current') {
                		$new_post->item_date = time();	
                	} elseif ($this->input->post('timestamp') == 'make_current_publish') {
                		$new_post->item_status = 'publish';	
                		$new_post->item_date = time();	
                	}
                	$this->item_model->update_item($new_post, $data->item);
                	header('Location: '.$this->input->post('referer'));	
                } else {
                	//add new item
                	$new_post->item_name = url_title($this->validation->title);
                	$new_post->item_date = time();
	                if ($this->input->post('draft') == 'true') {
	                    $new_post->item_status = 'draft';   
	                }
	                $this->item_model->add_blog_post($new_post);
                	header('Location: '.$this->config->item('base_url').'admin/items');	
                }
            }
        } else {
    	    $this->load->view('admin/_header', $data);
    	    $this->load->view('admin/write', $data);
        }
	    $this->load->view('admin/_footer');           
    }

}
?>