<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->library('access');
	}
	
	function index(){
		if( $this->uri->segment(1) == 'logout' || $this->uri->segment(2) == 'logout' ){ ($this->access->is_logged_in()) ? $this->logout() : header('location:/login'); }
		elseif( $this->access->is_logged_in() ){ header('location:/admin'); }
		else{
			
			if( $this->input->post('login') != NULL ){ $this->log_in(); }
			else{ $this->show_login(); }
			
		}
	}
	
	function show_login(){
		$pagedata = array();
		$this->load->view('admin/login.php', $pagedata);
	}
	
	function log_in(){
		$this->load->model('view_users_model');
		$username = strtolower($this->input->post('username'));
		$password = strtolower($this->input->post('password'));
		
		if( $username == NULL ){ create_message('error', 'Username Required.'); }
		elseif( $password == NULL ){ create_message('error', 'Password Required.'); }
		else{
			
			$user_id = $this->view_users_model->return_user_id($username, $password);
			
			if( $user_id === FALSE || $user_id === NULL ){
				
				create_message('error', 'Incorrect username and password combination.');
				
			}else{
				
				if( $this->view_users_model->update_login($user_id) ){
					
					header('location:/admin'); return;
					
				}else{
					
					create_message('error', 'Error updating login data.');
					
				}
				
			}
			
		}
		
		header('location:/login');
	}
	
	function logout(){
		$session = array('access_admin' => NULL);
		$this->session->set_userdata($session);
		create_message('success', 'Successfully logged out.');
		header('location:/login');
	}
	
}

/* End of file login.php */