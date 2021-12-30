<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CL_URI extends CI_URI {

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Truncates the URI segment array at a set inveral
	 * Returns the shortened uri as a string
	*/
	
	function uri_depth($num){
		$CI = &get_instance();
		$uri = array_slice($CI->uri->segment_array(), 0, $num);
		return '/'.implode($uri, '/');
	}
	
	/**
	 * Similar to uri_depth, this removes the given length from the URI
	 * And returns the shortened uri as a string
	*/
	
	function uri_remove($num){
		$CI = &get_instance();
		$uri = array_slice($CI->uri->segment_array(), 0, count($CI->uri->segment_array())-$num);
		return '/'.implode($uri, '/');
	}
	
	/**
	 * Codeigniter 2.0 does not always preppend a "/" to uri_string() anymore. This fixes it.
	 */
	function uri_string(){
		return ($this->uri_string != NULL && strpos($this->uri_string, '/') !== 0) ? '/'.$this->uri_string : $this->uri_string;
	}
	
}

/* End of file CL_URI.php */