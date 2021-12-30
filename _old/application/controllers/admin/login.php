<?php

class Login extends Controller {

	function Login(){
		parent::Controller();
		$this->load->library('session');
		$this->load->library('access');
	}
	
	function index(){
		if( $this->access->is_logged_in() ){ header('location:/admin'); }
		elseif( $this->uri->segment(1) == 'logout' ){ ($this->access->is_logged_in()) ? $this->logout() : header('location:/login'); }
		else{
			
			if( $this->input->post('login') != NULL ){ $this->log_in(); }
			else{ $this->show_login(); }
			
		}
	}
	
	function show_login(){
		$pagedata = array();
		$this->load->helper('commonfunctions');
		$this->load->view('admin/login.php', $pagedata);
	}
	
	function log_in(){
		$this->load->model('view_users_model');
		$username = strtolower($this->input->post('username'));
		$password = strtolower($this->input->post('password'));
		
		if( $username == NULL ){ $this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Username Required.')); }
		elseif( $password == NULL ){ $this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Password Required.')); }
		else{
			
			$user_id = $this->view_users_model->return_user_id($username, $password);
			
			if( $user_id === FALSE || $user_id === NULL ){
				
				$this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Incorrect username and password combination'));
				
			}else{
				
				if( $this->view_users_model->update_login($user_id) ){
					
					header('location:/admin'); return;
					
				}else{
					
					$this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Error updating login data.'));
					
				}
				
			}
			
		}
		
		header('location:/login');
	}
	
	function logout(){
		$session = array('access_admin' => NULL);
		$this->session->set_userdata($session);
		$this->session->set_flashdata('message', array('type'=>'success', 'message'=>'Successfully logged out.'));
		header('location:/login');
	}
	
}

/* End of file login.php */