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

class Install extends Controller {

	function __construct()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->library('sweetcron');
		$this->sweetcron->compatibility_check();
		$this->sweetcron->install_check();
		$data->page_name = 'Install';

		if ($_POST) {
			$this->load->library('validation');
	        $rules['lifestream_title']	= "trim|required";	
	        $rules['username']	= "trim|required|min_length[5]|max_length[20]";	
	        $rules['email']	= "trim|required|valid_email";	
	        $this->validation->set_rules($rules);
			if ($this->validation->run() == FALSE) {	
				$data->errors = $this->validation->error_string;  			
			} else {
				$this->load->helper('file');
				$sql = read_file(BASEPATH.'utilities/install.sql');
				$sql = str_replace('%DB_PREFIX%', $this->db->dbprefix, $sql);
				$sql = explode('CREATE', $sql);
				unset($sql[0]);
				//install sql and pray to baby jebus				   
				foreach ($sql as $snippet) {
					$this->db->query('CREATE'.$snippet);	
				}
				//populate options
				$options->lifestream_title = array('option_name' => 'lifestream_title', 'option_value' => $this->validation->lifestream_title);
				$options->admin_email = array('option_name' => 'admin_email', 'option_value' => $this->validation->email);
				$options->per_page = array('option_name' => 'per_page', 'option_value' => 9);
				$options->cron_type = array('option_name' => 'cron_type', 'option_value' => 'pseudo');
				$cron_key = substr(md5(time().rand(1,100).$this->validation->lifestream_title.time()), 0, 8);
				$options->cron_key = array('option_name' => 'cron_key', 'option_value' => $cron_key);
				$options->theme = array('option_name' => 'theme', 'option_value' => 'sandbox');
				$this->load->model('option_model');
				foreach ($options as $option) {
					$this->option_model->add_option($option);	
				}
				
				//add user
				$password = substr(md5(time().rand(1,100).$this->validation->lifestream_title), 0, 8);
				$user->user_login = $this->validation->username;
				$user->user_pass = md5($password);
				$user->user_email = $this->validation->email;
				$user->user_activation_key = md5(time());
				$this->db->insert('users', $user);
				
				//send email
				$url = $this->config->item('base_url');
				$this->load->library('email');
				
				$this->email->from($this->validation->email, 'Sweetcron');
				$this->email->to($this->validation->email); 
				
				$this->email->subject('[Sweetcron] Welcome to Sweetcron');
				$this->email->message('You have successfully installed Sweetcron!

Your login details are as follows:

Username: '.$this->validation->username.'
Password: '.$password.'

Your Sweetcron site: '.$url.'

Thanks and have fun!');	
				
				$this->email->send();
				$data->success = TRUE;
				$data->password = $password;
			}			
		}

	    $this->load->view('admin/_header', $data);
	    $this->load->view('admin/install', $data);
	    $this->load->view('admin/_footer');     		
	}
}
?>