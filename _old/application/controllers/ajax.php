<?php

class Ajax extends Controller {

	function Ajax(){
		parent::Controller();
	}
	
	function index(){
		$pagedata = array();
		$this->load->helper('commonfunctions');
		
		if( $this->input->post('ajax') == TRUE ){
			
			if( $this->uri->segment(2) == 'nsfw' ){
				$this->nsfw();
			}
			
		}else{ echo ''; return FALSE; }
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