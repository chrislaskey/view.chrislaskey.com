<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->library('access');
		$this->access->require_login();
	}
	
	function index(){
		$this->load->library('session');
		
		if( $this->uri->segment(3) == 'ajax' ){
			if( $this->uri->segment(4) == 'add-tag' && $this->input->post('ajax') == TRUE ){ $this->_add_tag(); }
		}else{
			if( isset($_POST['submit']) && $_POST['submit'] != NULL ){
				$this->_save();
				header('location:/admin/capture');
			}else{ $this->show_index(); }
		}
	}
	
	function show_index(){
		$pagedata = array();
		$this->load->model('view_tags_model');
		$pagedata['tags'] = $this->_return_tags();
		$this->load->view('admin/capture', $pagedata);
	}
	
	function _add_tag(){
		$this->load->model('view_tags_model');
		if( $this->view_tags_model->add_tag( $_POST['new_tag'] ) ){
			echo $this->_return_tags();
		}else{ echo FALSE; return FALSE; }
	}
	
	function _return_tags(){
		$this->load->model('view_tags_model');
		$tags = $this->view_tags_model->return_tags();
		$tag_list = array();
		if(count($tags) > 0){
			foreach($tags as $tag){
				$tag_list[] = '<li><a class="tag" href="#'.$tag->tag_id.'" rel="'.htmlentities($tag->tag_name).'">'.htmlentities($tag->tag_name).' <span class="tag_count">('.$tag->tag_count.')</span></a></li>'."\n\r";
			}
		}else{ $tag_list[] = '<li>There are no tags to show.</li>'; }
		return implode($tag_list);
	}
	
	function _save(){
		
		//Verify inputs
		$image_url = $_POST['image_url'];
		$image_website = $_POST['image_website'];
		if( $image_url == NULL ){
			create_message('error', 'Image URL required.'); return;
		}elseif( $image_website == NULL ){
			create_message('error', 'Image Website required.'); return;
		}
		
		//Verify extension
		$pathinfo = pathinfo($image_url);
		if( $this->input->post('image_force_jpg') != NULL ){ $image_extension = 'jpg'; }
		else{
			$image_extension = strtolower($pathinfo['extension']);
			$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
			if( !in_array($image_extension, $allowed_extensions) ){
				create_message('error', 'Unsupported file type.'); return;
			}unset($pathinfo);
		}
		
		//Get data
		$ch = curl_init($image_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$raw_data = curl_exec($ch);
		curl_close ($ch);
		
		//Verify data exists
		if( $raw_data == NULL ){
			create_message('error', 'Image Website required.'); return;
		}
		
		//Create SQL entry, return id
		$this->load->model('view_images_model');
		$image_id = $this->view_images_model->add_image();
		if( !is_numeric($image_id) ){
			create_message('error', 'Error creating database entry.'); return;
		}
		
		//Save data to image
		$image_original = FILE_DIRECTORY . 'originals/' . $image_id . '.' . $image_extension;
		if( !is_dir(DOCUMENT_ROOT . FILE_DIRECTORY . 'originals') ){ mkdir(DOCUMENT_ROOT . FILE_DIRECTORY . 'originals', 0775); }
		$fp = fopen(DOCUMENT_ROOT . $image_original,'x');
		fwrite($fp, $raw_data);
		fclose($fp); unset($fp, $raw_data);
		
		//Verify data is valid image
		if( ! getimagesize(DOCUMENT_ROOT . FILE_DIRECTORY . 'originals/' . $image_id . '.' . $image_extension) ){
			create_message('error', 'Error validating original image.'); return;
		}
		
		//Create thumbnail directory
		$image_directory = FILE_DIRECTORY . floor($image_id / 100) . '/';
		if( !is_dir(DOCUMENT_ROOT . $image_directory) ){ mkdir(DOCUMENT_ROOT . $image_directory, 0775); }
		
		//Get Original Image information
		list($image_original_width, $image_original_height, $image_original_type, $image_original_attr) = getimagesize(DOCUMENT_ROOT.$image_original);
		unset($image_original_type, $image_original_attr);
		
		//Setup Thumbnail Class
		$config = array();
		$config['folder_source'] = DOCUMENT_ROOT.FILE_DIRECTORY.'originals/';
		$config['folder_target'] = DOCUMENT_ROOT.FILE_DIRECTORY.floor($image_id / 100).'/';
		$this->load->library('image', $config);
		unset($config);
		
		//Create Thumbs
		$this->image->create_thumbnail_by_height($image_id.'.'.$image_extension, 400, $image_id.'_height_400.jpg');
		$this->image->create_thumbnail_by_height($image_id.'.'.$image_extension, 60, $image_id.'_height_60.jpg');
		$this->image->create_thumbnail_by_width($image_id.'.'.$image_extension, 160, $image_id.'_width_160.jpg');
		
		//Verify thumbnail exist.
		if( !file_exists(DOCUMENT_ROOT.FILE_DIRECTORY.floor($image_id / 100).'/'.$image_id.'_height_400.jpg') ){
			create_message('error', 'Error creating 400 height thumbnail.'); return;
		}
		if( !file_exists(DOCUMENT_ROOT.FILE_DIRECTORY.floor($image_id / 100).'/'.$image_id.'_height_60.jpg') ){
			create_message('error', 'Error creating 60 height thumbnail.'); return;
		}
		if( !file_exists(DOCUMENT_ROOT.FILE_DIRECTORY.floor($image_id / 100).'/'.$image_id.'_width_160.jpg') ){
			create_message('error', 'Error creating 160 width thumbnail.'); return;
		}
		
		//Get information for DB
		list($image_width, $image_height, $image_type, $image_attr) = getimagesize(DOCUMENT_ROOT.FILE_DIRECTORY.floor($image_id / 100).'/'.$image_id.'_height_400.jpg');
		unset($image_type, $image_attr);

		//Assign Set and save to DB
		$url = parse_url($image_website);
		$image_website_basename = ( isset($url['host']) ) ? $url['host'] : $image_website;
		$data = array(
			'image_active' => $this->input->post('image_active'),
			'image_nsfw' => $this->input->post('image_nsfw'),
			'image_directory' => $image_directory,
			'image_extension' => $image_extension,
			'image_height' => $image_height,
			'image_width' => $image_width,
			'image_url' => $image_url,
			'image_website' => $image_website,
			'image_website_basename' => $image_website_basename,
			'date_added' => unix_to_date_time(@mktime()),
			'date_edited' => unix_to_date_time(@mktime())
		);
		if( !$this->view_images_model->update_image($image_id, $data) ){
			create_message('error', 'Error updating database.'); return;
		}unset($data);
		
		//Save to appropriate time set
		$this->load->model('view_times_model');
		$month_name = date('F', @mktime());;
		$month_number = date('m', @mktime());
		$year = date('Y', @mktime());
		$time_slug = $month_number.'-'.$year;
		$unix_time = strtotime($month_name.' '.$year);
		
		if( !$this->view_times_model->is_time($time_slug) ){
			$time = array();
			$time['time_slug'] = $time_slug;
			$time['time_name'] = $month_name.' '.$year;
			$time['time_unix_time'] = $unix_time;
			if( !$this->view_times_model->add_time($time) ){
				create_message('error', 'Error creating time.'); return;
			}unset($time);
		}
		if( !$this->view_times_model->add_image_to_time($image_id, $time_slug) ){
			create_message('error', 'Error adding image to time.'); return;
		}
		
		//Save to appropriate tag sets
		$tags = $this->input->post('tags');
		$tags = ( $tags != NULL ) ? explode(',', $tags) : array();
		if( count($tags) > 0 ){
			$this->load->model('view_tags_model');
			foreach($tags as $tag){
				if( !$this->view_tags_model->add_image_to_tag($image_id, $tag) ){
					create_message('error', 'Error adding image to tag.'); return;
				}
			}
			
			//Clean Empty Tags
			$this->view_tags_model->delete_empty_tags();
		}
		
		//* Else, unlink everything and clean the DB
		
	}
	
}

/* End of file capture.php */