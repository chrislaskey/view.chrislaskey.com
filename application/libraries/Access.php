<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access {

	//Class Variables
	private $access;
	private $redirect;
	
	//Controller
	public function __construct($config = array()){
		$CI = &get_instance();
		$CI->load->library('session');
		$this->access = (!isset($config['access']) || $config['access'] == NULL) ? $CI->session->userdata('access_admin') : $CI->session->userdata($config['access']);
		$this->redirect = (!isset($config['redirect']) || $config['redirect'] == NULL) ? '/login' : $config['redirect'];
	}
	
	//Basic Functions
	public function is_logged_in(){
		return ( isset($this->access['logged_in']) && $this->access['logged_in'] == 1 ) ? TRUE : FALSE;
	}
	
	public function require_login(){
		if( !$this->is_logged_in() ){ header('location:'.$this->redirect); exit(); }
	}
	
	//Verify functions are used to return a TRUE / FALSE declaration.
	//Ideal for whether or not to display links.
	public function verify($access = NULL){
		if( is_numeric($access) ){ return $this->verify_by_id($access); }
		else{ return $this->verify_by_slug($access); }
	}
	
	public function verify_one($array){
		if( !is_array($array) ){ return FALSE; }
		$verified = 0;
		foreach($array as $a){
			if( is_numeric($a) ){ $verified = ($this->verify_by_id($a)) ? $verified + 1 : $verified; }
			else{ $verified = ($this->verify_by_slug($a)) ? $verified + 1 : $verified; }
		}unset($a);
		return ($verified > 0) ? TRUE : FALSE;
	}

	public function verify_all($array){
		if( !is_array($array) ){ return FALSE; }
		$count = count($array);
		$verified = 0;
		foreach($array as $a){
			if( is_numeric($a) ){ $verified = ($this->verify_by_id($a)) ? $verified + 1 : $verified; }
			else{ $verified = ($this->verify_by_slug($a)) ? $verified + 1 : $verified; }
		}unset($a);
		return ($verified == $count) ? TRUE : FALSE;
	}
	
	private function verify_by_id($id){
		if( isset($this->access['access_list'][$id]) && $this->access['access_list'][$id] != NULL ){ return TRUE; }
		else{ return FALSE; }
	}
	
	private function verify_by_slug($slug){
		if( in_array($slug, $this->access['access_list']) ){ return TRUE; }
		else{ return FALSE; }
	}
	
	//Required functions are used to redirect in the case of a FALSE return.
	//Ideal for access control of areas.
	public function required($access = NULL){
		if( is_numeric($access) ){ return $this->required_by_id($access); }
		else{ return $this->required_by_slug($access); }
	}
	
	private function required_by_id($id){
		if( isset($this->access['access_list'][$id]) && $this->access['access_list'][$id] != NULL ){ return TRUE; }
		else{ header('location:'.$this->redirect); exit(); }
	}
	
	private function required_by_slug($slug){
		if( in_array($slug, $this->access['access_list']) ){ return TRUE; }
		else{ header('location:'.$this->redirect); exit(); }
	}
	
}

/* End of file access.php */