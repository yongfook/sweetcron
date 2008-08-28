<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*****
* ErkanaAuth is an easy to use, non-invasive, use authentication library
* @author     Michael Wales
* @email      webmaster@michaelwales.com
* @filename   auth.php
* @title      ErkanaAuth
* @url        http://www.michaelwales.com/
* @version    2.0
*****/
class Auth {

    var $db_table = 'users';
    var $db_userid = 'ID';
    
    var $CI;

    function __construct() {
        $this->CI =& get_instance();
        log_message('debug', 'Authorization class initialized.');

        $this->CI->load->database();
    }

    /***
    Determines whether the passed condition is valid to login a user, if so - sets a session variable containing the user's ID
    * @param    $condition array    The condition to query the database for
    * @return   boolean
    ***/
    function try_login($condition = array()) {
        $query = $this->CI->db->getwhere($this->db_table, $condition, 1, 0);

        if ($query->num_rows != 1) { return FALSE; }

        $row = $query->row();
        $this->CI->session->set_userdata(array('user_id' => $row->ID, 'call_user' => $row->user_login));

        return $row;
    }


    /***
    Returns an object containing user information via the user's ID
    * @param    $id integer         The user's ID
    * @return   object              Upon valid user
    * @return   FALSE               Upon invalid user
    ***/
    function get_user($id = FALSE) {
        if ($id == FALSE) $id = $this->CI->session->userdata('user_id');
        if ($id == FALSE) return FALSE;

        $condition = array(($this->db_table . '.' . $this->db_userid) => $id);

        $query = $this->CI->db->getwhere($this->db_table, $condition, 1, 0);

        $row = ($query->num_rows() == 1) ? $query->row() : FALSE;

        return $row;
    }

    /***
    Logs out a user by deleting their session variables
    * @return   null
    ***/
    function logout() {
        $this->CI->session->set_userdata(array('user_id' => FALSE));
        header('Location: '.$this->CI->config->item('base_url'));
    }
    
    function is_logged()
    {
        if ($this->CI->data->user === FALSE) {
            return false;   
        } else {
            return true;   
        }
    }
    
    
    // use $this->auth->permit('super_admin') at the start of non-visual functions to prevent hackery
    // parameter can be a string role or an array of roles
    // if user is not of correct role they see an error page - they would only see this if they were 
    // trying something malicious, i.e. trying to access functions that their role doesn't allow
    function permit($permitted_role = null)
    {
        if ($permitted_role) {
            if (is_array($permitted_role)) {
                if (!in_array($this->CI->data->user->user_role, $permitted_role)) {
                    return false;                 
                } else {
                    return true;   
                }
            } else {
                if ($this->CI->data->user->user_role != $permitted_role) {
                    return false;
                } else {
                    return true;
                }   
            }
        }
    }

}