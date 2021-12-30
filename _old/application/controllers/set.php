<?php

class Set extends Controller {

	function Set(){
		parent::Controller();
	}
	
	function index(){
		$pagedata = array();
		$this->load->helper('commonfunctions');
		$this->load->model('view_times_model');
		$this->load->model('view_tags_model');
		
		if( $this->view_times_model->is_time($this->uri->segment(2)) ){
			$pagedata['set'] = $this->view_times_model->return_set($this->uri->segment(2), show_nsfw());
			$pagedata['tags'] = $this->view_tags_model->return_tags();
			$this->load->view('site/set', $pagedata);
		}elseif( $this->view_tags_model->is_tag($this->uri->segment(2)) ){
			$pagedata['set'] = $this->view_tags_model->return_set($this->uri->segment(2), show_nsfw());
			$pagedata['tags'] = $this->view_tags_model->return_tags();
			$this->load->view('site/set', $pagedata);
		}else{ header('location:/'); }
	}
	
}

/* End of file set.php */