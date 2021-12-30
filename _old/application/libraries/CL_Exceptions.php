<?php

class CL_Exceptions extends CI_Exceptions{

	function CL_Exceptions(){
		parent::CI_Exceptions();
	}

	function show_404($page = ''){
		
		$heading = "Page Not Found | Error 404";
		$message = "The page you requested was not found.";
		
		log_message('error', '404 Page Not Found --> '.$page);
		echo $this->show_error($heading, $message, 'error_404', 404);
		
	}
}

/* End of file CL_Exceptions */