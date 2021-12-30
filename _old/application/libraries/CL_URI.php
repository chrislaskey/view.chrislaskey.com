<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CL_URI extends CI_URI {

	function CL_URI(){
		parent::CI_URI();
	}

	/* Truncates the URI segment array at a set inveral
	 * Returns the shortened uri as a string
	*/

	function uri_depth($num){
		$CI = &get_instance();
		$uri = array_slice($CI->uri->segment_array(), 0, $num);
		return '/'.implode($uri, '/');
	}

	/* Similar to uri_depth, this removes the given length from the URI
	 * And returns the shortened uri as a string
	*/

	function uri_remove($num){
		$CI = &get_instance();
		$uri = array_slice($CI->uri->segment_array(), 0, count($CI->uri->segment_array())-$num);
		return '/'.implode($uri, '/');
	}
	
}

/* End of file CL_URI.php */