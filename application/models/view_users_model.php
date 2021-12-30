<?php

class View_users_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function return_user_id($username, $password){
		$sql = 'SELECT user_id FROM view_users WHERE username = ? AND password = ? LIMIT 1';
		$resource = $this->db->query($sql, array($username, md5($password)));
		if( $resource->num_rows() > 0 ){ return $resource->row()->user_id; }
		else{ return FALSE; }
	}

	function return_user_id_by_key($key){
		$sql = 'SELECT user_id FROM view_users WHERE account_key = ? LIMIT 1';
		$resource = $this->db->query($sql, array($key));
		if( $resource->num_rows() > 0 ){ return $resource->row()->user_id; }
		else{ return FALSE; }
	}
	
	/*
	function verify_key($key){
		$sql = 'SELECT user_id, username, screen_name FROM view_users WHERE account_key = ? LIMIT 1';
		$resource = $this->db->query($sql, array($key));
		if( $resource->num_rows() > 0 ){ return $resource->row(); }
		else{ return FALSE; }
	}
	*/
	
	function update_login($id){
		if( !is_numeric($id) ){ return FALSE; }
		$CI = &get_instance();
		$CI->load->library('session');
		
		$sql = 'SELECT * FROM view_users WHERE user_id = ? LIMIT 1';
		$resource = $this->db->query($sql, array($id));
		
		if( $resource->num_rows() > 0 ){
			
			$session = array();
			$session['access_admin'] = array();
			$session['access_admin']['screen_name'] = $resource->row()->screen_name;
			$session['access_admin']['logged_in'] = 1;
			$session['access_admin']['member_id'] = $id;
			$CI->session->set_userdata($session);
			return TRUE;
			
		}else{ return FALSE; }
		
	}
	
}

/* End of file view_users_model.php */
