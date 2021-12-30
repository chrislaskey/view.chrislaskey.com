<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sources extends CI_Controller {
	
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		$pagedata = array();
		$this->load->model('view_images_model');
		
		$pagedata['sources'] = $this->view_images_model->return_website_basenames();
		$this->load->view('site/sources', $pagedata);
	}
	
}

/* End of file sources.php */