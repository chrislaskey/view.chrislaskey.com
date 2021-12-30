<?php

class Remove extends Controller {

	function Remove(){
		parent::Controller();
		$this->load->library('session');
		$this->load->library('access');
		$this->access->require_login();
	}
	
	function index(){
		$pagedata = array();
		$this->load->helper('commonfunctions');
		$this->load->model('view_images_model');
		
		if( $this->uri->segment(3) && $this->view_images_model->is_image($this->uri->segment(3)) ){
			
			if( $this->uri->segment(4) == 'confirmed' && $this->input->post('submit') != null ){
				if( $this->remove_image() === TRUE ){
					header('location:'.$this->uri->uri_depth(2).'/success');
				}else{
					header('location:'.$this->uri->uri_depth(3));
				}
			}else{
				$this->show_confirm();
			}
		
		}elseif( $this->uri->segment(3) == 'success' ){
			$this->show_success();
		}else{
			$this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Cannot remove image, image does not exist.'));
			header('location:/');
		}
	}
	
	function show_confirm(){
		$pagedata = array();
		$this->load->view('admin/remove', $pagedata);
	}
	
	function remove_image(){
		$id = $this->uri->segment(3);
		if( is_numeric($id) ){
			
			//Load models and helpers
			$this->load->helper('commonfunctions');
			$this->load->model('view_images_model');
			$this->load->model('view_times_model');
			$this->load->model('view_tags_model');
			
			//Get Image Data
			if( !$image = $this->view_images_model->return_image($id) ){
				$this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Error retreiving image data from database.'));
				return FALSE;
			}
			
			//Remove Database References
			if( !$this->view_images_model->delete_image($id) ){
				$this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Error deleting image from database.'));
				return FALSE;
			}
			
			if( !$this->view_times_model->delete_lookup($id) ){
				$this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Error unlinking image from time set.'));
				return FALSE;
			}
			
			if( !$this->view_tags_model->delete_lookup($id) ){
				$this->session->set_flashdata('message', array('type'=>'error', 'message'=>'Error unlinking image from tag set.'));
				return FALSE;
			}
			
			//Remove Files
			$path = DOCUMENT_ROOT.FILE_DIRECTORY;
			$dir = floor($id/100);
			$success = TRUE;
			
			$files = array();
			$files['400'] = $path.$dir.'/'.$id.'_height_400.jpg';
			$files['60'] = $path.$dir.'/'.$id.'_height_60.jpg';
			$files['160'] = $path.$dir.'/'.$id.'_width_160.jpg';
			$files['original'] = $path.'originals/'.$id.'.'.$image->image_extension;
			
			foreach($files as $file){
				if( file_exists($file) ){
					if( !unlink($file) ){ $success = FALSE; }
				}else{ $success = FALSE; }
			}unset($files, $file);
			
			return $success;
			
		}else{ return FALSE; }
	}
	
	function show_success(){
		$pagedata = array();
		$this->load->view('admin/remove', $pagedata);
	}
	
}

/* End of file remove.php */