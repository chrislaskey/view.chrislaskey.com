<?php

class Sources extends Controller {
	
	function Sources(){
		parent::Controller();
	}
	
	function index(){
		$pagedata = array();
		$this->load->helper('commonfunctions');
		$this->load->model('view_images_model');
		
		$pagedata['sources'] = $this->view_images_model->return_website_basenames();
		$this->load->view('site/sources', $pagedata);
	}
	
}

/* End of file sources.php */