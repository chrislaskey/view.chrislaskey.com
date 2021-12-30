<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->library('access');
		//* var_dump($this->uri->uri_depth(1)); exit();
		$this->access->require_login();
	}
	
	function index(){
		
		$pagedata = array();
		
		$file = APPPATH . 'views' . $this->uri->uri_string();
		
		if( file_exists( $file . '.php') ){
			
			//If the url_string.php file exists
			$this->load->view($this->uri->uri_string(), $pagedata);
			
		}elseif( file_exists( remove_trailing_slash($file) . '.php') ){
			
			//If the url_string has a trailing slash, remove it and see if file exists
			$this->load->view(remove_trailing_slash($this->uri->uri_string()),  $pagedata);
			
		}elseif( file_exists( $file . '/index.php' ) ){
			
			//If the url_string is a folder, look for index.php
			//(note: folder/example1/index.php is trumped by folder/example1.php by the previous elseif clause.)
			$this->load->view($this->uri->uri_string() . '/index.php', $pagedata);
			
		}else{
			
			//Page cannot be found, call 404
			show_404();
			
		}
	}
}

/* End of file pages.php */