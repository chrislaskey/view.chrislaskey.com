<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * View API
 *
 * @author: Chris Laskey
 * @source: http://chrislaskey.com
 * @version: 0.2.0
 * @updated: 2012.01.06
 *
 * A relatively well behaved simple HTTP API. The concept is to utilize the
 * HTTP verbs (POST, GET, PUT, etc) and rely on resource mapping via smart
 * URI segments.
 *
 * Verification
 * ------------
 * API access is verified by an accountKey value.
 * This value is tied to accounts in the database.
 *
 * Can be passed as a GET argument via the last URI segment to any command
 * or through the classic get argument (?accountKey=<value>). In POST actions
 * the key can alternatively be passed via POST variable.
 *
 * API Mapping
 * -----------
 * Note: Not all paths are implemented. See code below to verify functionality
 * (hint: search for Todo: items). Unimplemented APIs will return
 * HTTP 501 Method not Found status codes.
 *
 * /images
 *		POST -> add image
 *		GET -> list all image information in JSON
 * /images/<value>
 *		GET -> return image
 * /tags
 *		POST -> add tag
 *		GET -> list all tag information in JSON
 * /tags/<value>
 *		GET -> return tag information
 *
 * Information
 * -----------
 * For more information on RESTful APIs see:
 *	http://timelessrepo.com/haters-gonna-hateoas
 */

class Api extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->library('access');
	}

	function index(){

		if( $this->uri->segment(2) == 'images' ){

			if( $_POST != NULL ){ $this->add_image(); }
			elseif( $this->uri->segment(3) != NULL ){ $this->show_image(); }
			else{ $this->show_hateoas(); }

		}elseif( $this->uri->segment(2) == 'tags' ){

			if( $_POST != NULL ){ $this->add_tag(); }
			elseif( $this->uri->segment(3) != NULL ){ $this->show_tag(); }
			else{ $this->list_tags(); }

		}else{ $this->show_hateoas(); }

	}

	//Response functions
	function show_hateoas(){
		//Utilize the HTTP 501 status code Method not Implemented
		header('Content-type:utf8', TRUE, 501);
		exit();
	}

	function response($status, $data){
		$this->load->library('JSON');
		echo $this->json->encode(array('status' => $status, 'data' => $data));
		exit();
	}

	//Utility functions
	function get_key(){
		if( $this->input->post('account_key') != NULL ){ return $this->input->post('account_key'); }
		else{ return array_pop($this->uri->segment_array()); }
	}

	//Image functions
	function add_image(){
		$this->load->model('view_users_model');

		//Verify key
		$key = $this->get_key();
		if( ! $this->view_users_model->return_user_id_by_key($key) ){
			$this->response('error', 'Could not verify key');
		}

		//Decode URIs if they have been encoded
		if( $this->input->post('image_uri_encoding') == 'base64' ){
			$_POST['image_url'] = base64_decode($_POST['image_url']);
			$_POST['image_website'] = base64_decode($_POST['image_website']);
		}

		//Verify inputs
		$image_url = $this->input->post('image_url');
		$image_website = $this->input->post('image_website');
		if( $image_url == NULL ){
			$this->response('error', 'Image URL required.');
		}elseif( $image_website == NULL ){
			$this->response('error', 'Image Website required.');
		}

		//Verify extension
		$pathinfo = pathinfo($image_url);
		if( $_POST['image_force_jpg'] == 'true' ){ $_POST['image_force_jpg'] = TRUE; }
		if( $this->input->post('image_force_jpg') === TRUE ){ $image_extension = 'jpg'; }
		else{
			$image_extension = (isset($pathinfo['extension'])) ? strtolower($pathinfo['extension']) : '';
			$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
			if( !in_array($image_extension, $allowed_extensions) ){
				if( $this->input->post('image_try_jpg_on_fail') == 'true' ){ $image_extension = 'jpg'; }
				else{ $this->response('error', 'Unsupported file type.'); }
			}unset($pathinfo);
		}

		//Get data
		$ch = curl_init($image_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		$raw_data = curl_exec($ch);
		curl_close($ch);

		//Verify data exists
		if( $raw_data == NULL ){
			$this->response('error', 'Image Url did not return any data.');
		}

		//Create SQL entry, return id
		$this->load->model('view_images_model');
		$image_id = $this->view_images_model->add_image();
		if( !is_numeric($image_id) ){
			$this->response('error', 'Error creating database entry.');
		}

		//Save data to image
		$image_original = FILE_DIRECTORY . 'originals/' . $image_id . '.' . $image_extension;
		if( !is_dir(DOCUMENT_ROOT . FILE_DIRECTORY . 'originals') ){ mkdir(DOCUMENT_ROOT . FILE_DIRECTORY . 'originals', 0775); }
		$fp = fopen(DOCUMENT_ROOT . $image_original,'x');
		fwrite($fp, $raw_data);
		fclose($fp); unset($fp, $raw_data);

		//Get original image information, verify its a valid image
		if( ! list($image_original_width, $image_original_height, , ) = getimagesize(DOCUMENT_ROOT . $image_original) ){
			$this->view_images_model->delete_image($image_id);
			$this->_unlink_image(DOCUMENT_ROOT . $image_original);
			$this->response('error', 'Error validating original image.');
		}unset($image_original_type, $image_original_attr);

		//Create thumbnail directory
		define('IMAGE_DIRECTORY', floor($image_id / 100) . '/');
		if( !is_dir(DOCUMENT_ROOT . FILE_DIRECTORY . IMAGE_DIRECTORY) ){ mkdir(DOCUMENT_ROOT . FILE_DIRECTORY . IMAGE_DIRECTORY, 0775); }

		//Setup thumbnail class
		$config = array();
		$config['folder_source'] = DOCUMENT_ROOT.FILE_DIRECTORY.'originals/';
		$config['folder_target'] = DOCUMENT_ROOT.FILE_DIRECTORY.IMAGE_DIRECTORY;
		$this->load->library('image', $config);
		unset($config);

		//Create thumbs
		$this->image->create_thumbnail_by_height($image_id.'.'.$image_extension, 400, $image_id.'_height_400.jpg');
		$this->image->create_thumbnail_by_height($image_id.'.'.$image_extension, 60, $image_id.'_height_60.jpg');
		$this->image->create_thumbnail_by_width($image_id.'.'.$image_extension, 160, $image_id.'_width_160.jpg');

		//Verify thumbnail exist.
		if( !file_exists(DOCUMENT_ROOT.FILE_DIRECTORY.IMAGE_DIRECTORY.$image_id.'_height_400.jpg') ){
			$this->view_images_model->delete_image($image_id);
			$this->_unlink_image(DOCUMENT_ROOT . $image_original);
			$this->response('error', 'Error creating 400 height thumbnail.');
		}
		if( !file_exists(DOCUMENT_ROOT.FILE_DIRECTORY.IMAGE_DIRECTORY.$image_id.'_height_60.jpg') ){
			$this->view_images_model->delete_image($image_id);
			$this->_unlink_image(DOCUMENT_ROOT . $image_original);
			$this->response('error', 'Error creating 60 height thumbnail.');
		}
		if( !file_exists(DOCUMENT_ROOT.FILE_DIRECTORY.IMAGE_DIRECTORY.$image_id.'_width_160.jpg') ){
			$this->view_images_model->delete_image($image_id);
			$this->_unlink_image(DOCUMENT_ROOT . $image_original);
			$this->response('error', 'Error creating 160 width thumbnail.');
		}

		//Get information for DB
		list($image_width, $image_height, $image_type, $image_attr) = getimagesize(DOCUMENT_ROOT.FILE_DIRECTORY.IMAGE_DIRECTORY.$image_id.'_height_400.jpg');
		unset($image_type, $image_attr);

		//Assign Set and save to DB
		$url = parse_url($image_website);
		$image_website_basename = ( isset($url['host']) ) ? $url['host'] : $image_website;
		$data = array(
			'image_active' => ($_POST['image_active'] == 'true') ? 1 : 0,
			'image_nsfw' => ($_POST['image_nsfw'] == 'true') ? 1 : 0,
			'image_directory' => FILE_DIRECTORY.IMAGE_DIRECTORY,
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
			$this->response('error', 'Error updating database.');
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
				$this->response('error', 'Error creating time.');
			}unset($time);
		}
		if( !$this->view_times_model->add_image_to_time($image_id, $time_slug) ){
			$this->response('error', 'Error adding image to time.');
		}

		// Todo: Implement tags

		//Success
		$this->response('success', 'Successfully added image.');

	}

		function _unlink_image($full_path){
			if( file_exists($full_path) ){
				return unlink($full_path);
			}else{ return FALSE; }
		}


	function show_image(){
		//Todo: implement
		$this->show_hateoas();
	}

	function list_images(){
		//Todo: implement
		$this->show_hateoas();
	}

	function add_tag(){
		//Todo: implement
		$this->show_hateoas();
	}

	function show_tag(){
		//Todo: implement
		$this->show_hateoas();
	}

	function list_tags(){
		$this->load->model('view_tags_model');
		$this->load->library('JSON');
		if( ! ($tags = $this->view_tags_model->return_tags()) ){ $tags = array(); }
		echo $this->json->encode($tags);
		return TRUE;
	}

}

/* End of file capture.php */
