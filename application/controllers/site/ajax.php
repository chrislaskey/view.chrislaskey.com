<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	function __construct(){
		parent::__construct();
	}
	
	function index(){
		$pagedata = array();
		
		if( $this->input->post('ajax') == TRUE ){
			
			if( $this->uri->segment(2) == 'nsfw' ){
				$this->nsfw();
			}
			
		}else{ header("HTTP/1.0 404 Not Found"); show_404(); exit(); }
	}
	
	function nsfw(){
		session_start();
		if( $this->input->post('nsfw') == 1 ){ $filter = 1; }
		else{ $filter = 0; }
		
		$_SESSION['nsfw'] = $filter;
		echo 'success'; return TRUE;
	}
	
}

/* End of file ajax.php */