<?php 

/**
 * Controller Class 
 */

class ContentController
{
	
	function __construct()
	{
		// Session::getInstance()->requireLogin(true);
		if(Request::isPost()){

			$_SESSION['flash_error'] = NULL;
			$_SESSION['previous_post'] = NULL;
		}
	}

	function index(){
		
		$data = array(); 
		$data['navKey'] = 'inscription';
		return loadView('inscription', isset($data) ? $data : NULL, '_layout-inscription' );
	}

}


/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$id = Request::getArgs(1);
// $args['id'] = $id;

#call the proper action
try {
	
	if(!method_exists('ContentController', $action))
		throw new Exception("Error Processing Request", 1);
	
	$controller = new ContentController;
	$controller->{$action}($id);
	
} catch (Exception $e) {
	
	print_r($e);exit;
	
	if (function_exists('http_response_code'))
		http_response_code(404);
	loadView404();
}