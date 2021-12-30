<?php

class Sets extends Controller {

	function Sets(){
		parent::Controller();
	}
	
	function index(){
		$pagedata = array();
		$this->load->helper('commonfunctions');
		
		if( $this->uri->segment(2) == 'by-time' ){
			$this->load->model('view_times_model');
			$pagedata['sets'] = $this->view_times_model->return_sets();
			$this->load->view('site/sets', $pagedata);
		}elseif( $this->uri->segment(2) == 'by-tag' ){
			$this->load->model('view_tags_model');
			$pagedata['sets'] = $this->view_tags_model->return_sets();
			$this->load->view('site/sets', $pagedata);
		}else{ header('location:/'); }
	}
}

/* End of file sets.php */