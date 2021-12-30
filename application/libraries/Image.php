<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image {

	//Class Variables
	private $image;
	private $folder;
	private $CI;
	
	//Controller
	public function __construct($config = array()){
		$this->CI = &get_instance();
		$this->CI->load->helper('commonfunctions');
		$this->folder_source = (isset($config['folder_source']) && $config['folder_source'] != NULL) ? add_trailing_slash($config['folder_source']) : DOCUMENT_ROOT.'/files';
		$this->folder_target = (isset($config['folder_target']) && $config['folder_target'] != NULL) ? add_trailing_slash($config['folder_target']) : DOCUMENT_ROOT.'/files';
	}
	
	//Interface
	public function set_folder_target($folder){
		if( is_dir($folder) ){
			$this->folder_target = add_trailing_slash($folder);
			return TRUE;
		}else{ return FALSE; }
	}
	
	public function set_folder_source($folder){
		if( is_dir($folder) ){
			$this->folder_source = add_trailing_slash($folder);
			return TRUE;
		}else{ return FALSE; }
	}
	
	public function convert_to_jpg($image, $quality = 100){
		$full_path = $this->folder_source . $image;
		$info = pathinfo($full_path);
		if( !isset($info['filename']) ){ //Pathinfo doesn't include 'filename' key until php 5.2.0
			$info['filename'] = substr($image, 0, strrpos($image,'.'));
		}
		
		if( file_exists($full_path) ){
			if( strtolower($info['extension']) == 'jpg' && strcmp('jpg', $info['extension']) !== 0 ){
				return rename($full_path, $this->folder_target.$info['filename'].'.jpg' );
			}elseif( strtolower($info['extension']) == 'jpeg' ){
				return rename($full_path, $this->folder_target.$info['filename'].'.jpg' );
			}elseif( strtolower($info['extension']) == 'png' ){
				if( $new = imagecreatefrompng($full_path) ){
					$success = imagejpeg($new, $this->folder_target.$info['filename'].'.jpg', $quality);
					imagedestroy($new);
					unset($new);
					return TRUE;
				}else{ return FALSE; }
			}elseif( strtolower($info['extension']) == 'gif' ){
				if( $new = imagecreatefromgif($full_path) ){
					$success = imagejpeg($new, $this->folder_target.$info['filename'].'.jpg', $quality);
					imagedestroy($new);
					unset($new);
					return TRUE;
				}else{ return FALSE; }
			}
		}else{ return FALSE; }
	}
	
	public function create_thumbnail_by_height($image, $height, $name, $quality = 100){
		$full_path = $this->folder_source . $image;
		$name = (strpos($name, '.jpg') !== FALSE) ? substr($name, 0, strrpos($name, '.jpg')) : $name;
		$quality = (!is_numeric($quality) || $quality < 1 || $quality > 100) ? 100 : round($quality);
		$info = pathinfo($full_path);
		if( !isset($info['filename']) ){ //Pathinfo doesn't include 'filename' key until php 5.2.0
			$info['filename'] = substr($image, 0, strrpos($image,'.'));
		}
		
		if( file_exists($full_path) ){
			
			//Create Resource
			if( strtolower($info['extension']) == 'jpg' || strtolower($info['extension']) == 'jpeg'){
				if( !($resource = @imagecreatefromjpeg($full_path)) ){ return FALSE; }
			}elseif( strtolower($info['extension']) == 'png' ){
				if( !($resource = @imagecreatefrompng($full_path)) ){ return FALSE; }
			}elseif( strtolower($info['extension']) == 'gif' ){
				if( !($resource = @imagecreatefromgif($full_path)) ){ return FALSE; }
			}else{ return FALSE; }
			
			//Determine Values
			$image_height = imagesy($resource);
			$image_width = imagesx($resource);
			
			$ratio = $image_height / $height;
			$width = round($image_width / $ratio);
			
			//Create Thumbnail
			if( $image_height > $height ){
				
				$thumb = imagecreatetruecolor($width, $height);
				imagecopyresampled($thumb, $resource, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
				 
				imagejpeg($thumb, $this->folder_target.$name.'.jpg', $quality);
				imagedestroy($thumb);
				unset($thumb);
				
			}else{ //If original is smaller than thumb, save as thumb
				
				copy($full_path, $this->folder_target.$name.'.jpg');
				
			}
			
			//Return
			imagedestroy($resource);
			unset($resource);
			return TRUE;
			
		}else{ return FALSE; }
	}
	
	public function create_thumbnail_by_width($image, $width, $name, $quality = 100){
		$full_path = $this->folder_source . $image;
		$name = (strpos($name, '.jpg') !== FALSE) ? substr($name, 0, strrpos($name, '.jpg')) : $name;
		$quality = (!is_numeric($quality) || $quality < 1 || $quality > 100) ? 100 : round($quality);
		$info = pathinfo($full_path);
		if( !isset($info['filename']) ){ //Pathinfo doesn't include 'filename' key until php 5.2.0
			$info['filename'] = substr($image, 0, strrpos($image,'.'));
		}
		
		if( file_exists($full_path) ){
			
			//Create Resource
			if( strtolower($info['extension']) == 'jpg' || strtolower($info['extension']) == 'jpeg'){
				$resource = imagecreatefromjpeg($full_path);
			}elseif( strtolower($info['extension']) == 'png' ){
				$resource = imagecreatefrompng($full_path);
			}elseif( strtolower($info['extension']) == 'gif' ){
				$resource = imagecreatefromgif($full_path);
			}else{ return FALSE; }
			
			//Determine Values
			$image_height = imagesy($resource);
			$image_width = imagesx($resource);
			
			$ratio = $image_width / $width;
			$height = round($image_height / $ratio);
			
			//Create Thumbnail
			if( $image_height > $height ){
				
				$thumb = imagecreatetruecolor($width, $height);
				imagecopyresampled($thumb, $resource, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
				 
				imagejpeg($thumb, $this->folder_target.$name.'.jpg', $quality);
				imagedestroy($thumb);
				unset($thumb);
				
			}else{ //If original is smaller than thumb, save as thumb
				
				copy($full_path, $this->folder_target.$name.'.jpg');
				
			}
			
			//Return
			imagedestroy($resource);
			unset($resource);
			return TRUE;
			
		}else{ return FALSE; }
	}
	
}

/* End of file image.php */