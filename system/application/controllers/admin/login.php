<?php
class Login extends Public_Controller {

    function __construct() {
        parent::Public_Controller();
        if (!$this->data->user === FALSE) {
            header('Location: '.$this->config->item('base_url').'admin');
        }
    }

    function index() {
    	$data->page_name = 'Login';
        $this->load->view('admin/_header', $data);
        if ($_POST) {
            $this->load->library('validation');
            $rules['username']	= "required|trim";
            $rules['password']	= "required|trim";
            $this->validation->set_rules($rules);    
            if ($this->validation->run() == FALSE) {
                $data->errors = $this->validation->error_string;
                $this->load->view('admin/login', $data);
            } else {
                //passed validation but need to check if can log in
                if (!$this->auth->try_login(array('user_login' => $this->validation->username, 'user_pass' => md5($this->validation->password)))) {
                    //authentication error
                    $data->errors = 'Usernames / Password incorrect';
                    $this->load->view('admin/login', $data);
                } else {
                    header('Location: '.$this->config->item('base_url').'admin');
                }
            }
        } else {
            $this->load->view('admin/login');            
        }
        
        $this->load->view('admin/_footer');
    }

    function forgot() {
    	$data->page_name = 'Password Reset';
        $this->load->view('admin/_header', $data);
        if ($_POST) {
            $this->load->library('validation');
            $rules['email']	= "required|trim|valid_email|callback__is_admin_email";
            $this->validation->set_rules($rules);    
            if ($this->validation->run() == FALSE) {
                $data->errors = $this->validation->error_string;
                $this->load->view('admin/forgot', $data);
            } else {
                //change activation key and send a mail
                $key = substr(md5(time().rand(1,100)),0,10);
                $user->user_activation_key = $key;
                $this->db->update('users', $user, array('user_email' => $this->validation->email));
                $link = $this->config->item('base_url').'admin/login/reset_password/'.$key;
                //send email
				$this->load->library('email');
				
				$this->email->from($this->validation->email, 'Sweetcron');
				$this->email->to($this->validation->email); 
				
				$this->email->subject('[Sweetcron] Reset Password');
				$this->email->message('You have initiated a password reset request.

Click this link to reset your password:

'.$link.'

If you feel you have received this message in error, ignore this message and do not click the link.');	
				
				$this->email->send();
                $data->success = TRUE;
                $this->load->view('admin/forgot',$data);            
            }
        } else {
            $this->load->view('admin/forgot');            
        }
        
        $this->load->view('admin/_footer');
    }
    
    function reset_password($key)
    {
        if ($user = $this->db->get_where('users', array('user_activation_key' => $key))->row()) {            
            //reset the activation key
            $key = substr(md5(time().rand(1,100)),0,10);
            $edited->user_activation_key = $key;
            //reset users password
			$password = substr(md5(time().rand(1,100).$this->config->item('lifestream_title')), 0, 8);
			$edited->user_pass = md5($password);
            $this->db->update('users', $edited, array('user_email' => $user->user_email));
            
            $this->load->library('email');
				
			$this->email->from($user->user_email, 'Sweetcron');
			$this->email->to($user->user_email); 
			
			$this->email->subject('[Sweetcron] New Password');
			$this->email->message('You initiated and confirmed a password reset request.

Your login details are as follows:

Username: '.$user->user_login.'
Password: '.$password.'

Thanks and have fun!');	
			
			$this->email->send();

            die('Your password was reset - please check your email');
        } else {
            die("Uh uh uh, you didn't say the magic word!");   
        }
    }
    
    function _is_admin_email($email)
    {
        if ($this->db->get_where('users', array('user_email' => $email))->row()) {
            return true;   
        } else {
			$this->validation->set_message('_is_admin_email', 'That email is not registered with Sweetcron');
            return false;   
        }
    }
    
    function bye()
    {
        $this->auth->logout();   
    }
}
?>