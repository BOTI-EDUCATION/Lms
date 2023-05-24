<?php

use Models\User;

/**
 * Controller Class 
 */

class ContentController
{
	function __construct()
	{
		Session::getInstance()->requireLogin(true);

		if (Request::isPost()) {
			$_SESSION['flash_error'] = NULL;
			$_SESSION['previous_post'] = NULL;
		}
	}

	function index()
	{
		if (!roleIs('admin')) {
			URL::admin();
		}

		return loadView('lms/admin', [], 'null_layout');
	}

	function teaching()
	{
		return loadView('lms/borne', [], 'null_layout');
	}
}

/* Router options */
$action = Request::getArgs(0) ? Request::getArgs(0) : 'index';
$id = Request::getArgs(1);
$controller = new ContentController;


if (!method_exists('ContentController', $action))
	$controller->index();


$controller->{$action}($id);
