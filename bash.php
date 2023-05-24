<?php

header("Access-Control-Allow-Origin: *");

// -- Start Session if not already started
if ((function_exists('session_status') && session_status() == PHP_SESSION_NONE) || session_id() == '')
	session_start();


define('_basepath', rtrim(dirname(__DIR__), '\\/').'/');

require_once _basepath.'includes/autoload.php';
require_once _basepath.'includes/functions.php';
require_once _basepath.'includes/mails.php';
require_once _basepath.'includes/sms.php';

ini_set('date.timezone', 'Etc/GMT-1'); // Timezone to GMT + 1
if (Config::get('display-errors'))
	ini_set('display_errors', 'on');
