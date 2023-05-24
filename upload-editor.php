<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */


define('_basepath', rtrim(dirname(__FILE__), '\\/') . '/');


define('_school_alias', !(substr($_SERVER['SCRIPT_NAME'], 0, 3) == '/p/') ? "boti" : explode('/', $_SERVER['SCRIPT_NAME'])[2]);


require_once _basepath . 'includes/autoload.php';
require_once _basepath . 'includes/functions.php';

error_reporting(E_ALL | E_STRICT);
require('classes/uploadhandler.php');

if (!file_exists('assets/schools/' . _school_alias . '/editor/')) {
    mkdir('assets/schools/' . _school_alias . '/editor/');
}

$upload_handler = new UploadHandler(array(
    'upload_dir' => 'assets/schools/' . _school_alias . '/editor/',
    'upload_url' => URL::absolute(URL::base('../assets/schools/' . _school_alias . '/editor/')),
));
