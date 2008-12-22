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

class Options extends Auth_Controller {

	function __construct()
	{
		parent::Auth_Controller();	
	}
	
	function index()
	{
		$this->load->helper('file');
		$this->load->helper('inflector');
		$data->page_name = 'Options';
		$theme_folder = get_dir_file_info(BASEPATH.'application/views/themes', FALSE, TRUE);
		foreach ($theme_folder as $key => $value) {
			if (is_dir(BASEPATH.'application/views/themes/'.$key)) {
				$themes[$key]->folder = $key;
				$themes[$key]->name = humanize($key);
			}
		}	
		$data->themes = $themes;

		if ($_POST) {
			$this->load->library('validation');
			$fields['lifestream_title'] = 'Lifestream Title';
			$fields['admin_email'] = 'Admin Email';
			$fields['new_password'] = 'New Password';
			$fields['new_password_confirm'] = 'New Password Confirm';
			$fields['per_page'] = 'Items Per Page';
			
			$this->validation->set_fields($fields);

	        $rules['lifestream_title']	= "trim|required";	
	        $rules['admin_email']	= "trim|required|valid_email";	
	        $rules['new_password']	= "trim|matches[new_password_confirm]";	
	        $rules['new_password_confirm']	= "trim";	
	        $rules['per_page']	= "numeric";	
	        $this->validation->set_rules($rules);
			if ($this->validation->run() == FALSE) {	
				$data->errors = $this->validation->error_string;
			    $this->load->view('admin/_header', $data);
			    $this->load->view('admin/options', $data);
		    	$this->load->view('admin/_footer');   			
			} else {
				//set new password if required
				if ($this->validation->new_password && $this->validation->new_password != '') {
					$password = md5($this->validation->new_password);
					$this->db->update('users', array('user_pass' => $password), array('ID' => $this->data->user->ID));
				}
				//set admin email
				$this->db->update('users', array('user_email' => $this->validation->admin_email), array('ID' => $this->data->user->ID));
				
				unset($_POST['new_password']);
				unset($_POST['new_password_confirm']);
				//save options
				foreach ($_POST as $key => $value) {
					$option_array[$key]->option_name = $key;	
					$option_array[$key]->option_value = $value;	
				}
				foreach ($option_array as $option) {
					$this->option_model->add_option($option);
				}
			    header('Location: '.$this->config->item('base_url').'admin/options');   
			}
		} else {
		    $this->load->view('admin/_header', $data);
		    $this->load->view('admin/options', $data);
		    $this->load->view('admin/_footer');   			
		}
         
	}
}
?>