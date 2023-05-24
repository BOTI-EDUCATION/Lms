<?php
ini_set('display_errors', 'on');

// -- Start Session if not already started
if ((function_exists('session_status') && session_status() == PHP_SESSION_NONE) || session_id() == '') {
	session_set_cookie_params(60 * 60 * 24, dirname($_SERVER['SCRIPT_NAME']));
	session_start();
}

define('_basepath', rtrim(dirname(__FILE__), '\\/') . '/');
define('_school_alias', !(substr($_SERVER['SCRIPT_NAME'], 0, 3) == '/p/') ? "boti" : explode('/', $_SERVER['SCRIPT_NAME'])[2]);


require_once _basepath . 'includes/autoload.php';
require_once _basepath . 'includes/functions.php';
require_once _basepath . 'includes/mails.php';
require_once _basepath . 'includes/sms.php';



//ini_set('date.timezone', 'UTC'); // Default Timezone to GMT
ini_set('date.timezone', 'Etc/GMT-1'); // Timezone to GMT + 1


if (roleIs('admin')) {
	Config::set('admin', 'admin');
}

if (roleIs('pick_agent_sortie')) {
	Config::set('admin', 'pick');
}

if (roleIs('professeur')) {
	Config::set('admin', 'teacher');
}


if (roleIs('eleve')) {
	if (isset($_SESSION['promotion_actuelle']))
		unset($_SESSION['promotion_actuelle']);
	Config::set('admin', 'student');
}


if (roleIs('parent')) {
	if (isset($_SESSION['promotion_actuelle']))
		unset($_SESSION['promotion_actuelle']);
	Session::getInstance()->unsetCurUser();
	URL::redirect(URL::link('login'));
	Config::set('admin', 'parent');
}



$app = array();
$app['url'] = array();
$app['url']['base'] = URL::base();
$app['url']['link'] = URL::link();


$promotion = Models\Promotion::promotion_actuelle();
$_SESSION['promotion_actuelle'] = $promotion->get('ID');

// promotion month should npot changed this code 
$months_list = array(0 => 'Frais Annuels', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre', 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet');
$zero_months_list  =  ($promotion->getArray('FncMonths', true) ? array_filter($months_list, fn ($key) => $key == 0 || in_array((int)$key, $promotion->getArray('FncMonths', true)), ARRAY_FILTER_USE_KEY) : []);
$zero_months_keys = array_keys($zero_months_list);
define('_zero_months_list', $zero_months_list);

define('_zero_months_keys', $zero_months_keys);
unset($zero_months_list[0]);
unset($zero_months_keys[0]);
define('_months_list', $zero_months_list);
define('_months_keys', $zero_months_keys);



define('_show_site', Models\Site::query()->count() > 1);

// dd(_show_site);

if (Config::get('display-errors'))
	ini_set('display_errors', 'on');


try {

	if (Request::isAdmin()) {

		if (!file_exists(_basepath . Config::get('admin') . '/' . Request::getView() . '.php')) {
			throw new Exception("Request Not found", 404);
		}
		
		exit(include _basepath . Config::get('admin') . '/' . Request::getView() . '.php');
	}

	if (Request::isApi()) {
		if (!file_exists(_basepath . Config::get('api') . '/' . Request::getView() . '.php')) {
			throw new Exception("Request Not found", 404);
		}
		exit(include _basepath . Config::get('api') . '/' . Request::getView() . '.php');
	}

	if (Request::isPartApi()) {

		if (!file_exists(_basepath . Config::get('partapi') . '/' . Request::getView() . '.php')) {
			throw new Exception("Request Not found", 404);
		}
		exit(include _basepath . Config::get('partapi') . '/' . Request::getView() . '.php');
	}


	if (Request::isCsmApi()) {
		if (!file_exists(_basepath . Config::get('csmapi') . '/' . Request::getView() . '.php')) {
			throw new Exception("Request Not found", 404);
		}
		exit(include _basepath . Config::get('csmapi') . '/' . Request::getView() . '.php');
	}


	if (Request::isCron()) {
		if (!file_exists(_basepath . Config::get('cron') . '/' . Request::getView() . '.php')) {
			throw new Exception("Request Not found", 404);
		}
		exit(include _basepath . Config::get('cron') . '/' . Request::getView() . '.php');
	}


	if (!file_exists(_basepath . Request::getView() . '.php')) {
		throw new Exception("Request Not found", 404);
	}

	exit(include _basepath . Request::getView() . '.php');
} catch (Exception $e) {

	if (Request::getView() == 'picks') {
		if (Session::getInstance()->isLoggedIn() && roleIs('professeur')) {
			Session::getInstance()->unsetCurUser();
			URL::redirect(URL::link('login'));
		}
	}
	dd($e->getMessage(), $e->getTrace());
	logErrorToCSM($e->getMessage(), $e->getMessage() . ' : ' . json_encode($e->getTrace()));
	if (Request::isAjax()) {
		dd($e->getMessage());
	}
	ob_end_clean();
	if ($e->getCode() == 404) {
		loadView('errors/error_404', ['exception' => $e], 'null_layout');
	} else {
		loadView('errors/error_500', ['exception' => $e]);
	}
} catch (ErrorException $e) {
	ob_end_clean();
}
