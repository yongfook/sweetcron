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
 
// Controllers accessible by teh wurld
class Public_Controller extends Controller {
    function Public_Controller() {
        parent::Controller();

		//load teh cronz lib
	    $this->load->library('sweetcron');
	    $this->load->file(BASEPATH.'/application/libraries/sweetcron_item.php');
	    $this->sweetcron->compatibility_check();
	    $this->sweetcron->integrity_check();

	    //load other stuff
	    $this->load->file(BASEPATH.'/application/libraries/markdown.php');
	    $this->load->helper('text');
	    $this->load->helper('url');
	    $this->load->library('simplepie');
	    $this->load->library('page');
	    $this->load->model('feed_model');
	    $this->load->model('item_model');
	    $this->load->model('tag_model');
	    $this->load->model('option_model');
		$this->load->helper('date');
	    $this->load->library('auth');
		
		//update last access
		$option->option_name = 'last_access';
		$option->option_value = time();
		$this->option_model->add_option($option);

	    //Set config items
	    $this->option_model->load_config_options();
	    
	    //initiate pseudo-cron
	    $this->sweetcron->pseudo_cron();	    
        $this->data->user = $this->auth->get_user($this->session->userdata('user_id'));
    }
}

// Controllers accessible only by logged in users
class Auth_Controller extends Public_Controller {
    function Auth_Controller() {
        parent::Public_Controller();
	    //OMG WHO R U
        if ($this->data->user === FALSE) {
            header('Location: '.$this->config->item('base_url').'admin/login');
            exit();
        } else {
            return;
        }
    }
}