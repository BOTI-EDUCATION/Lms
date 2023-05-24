<?php

/**
 * Validation class
 */
class Validation
{

    /**
     * validator config
     *
     * @var array
     */
    private $config;

    /**
     * messages
     *
     * @var array
     */

    private $messages = array(
        'required' => 'Le champs %label% est obligatoire.',
        'int' => 'Le champs %label% doit etre entier',
        'alpha' => 'Le champs %label% doit etre alphabetique',
        'alphanum' => 'Le champs %label% doit etre alphanumérique',
        'email' => 'Cet email n\'est pas valide',
        'tel' => 'numero de telephone invalid',
        'text' => 'Le champs %label% ne doit pas contenir des caractères specials',
        'date' => 'Le champs %label% n\'est pas une date valide',
    );

    /**
     * messages
     *
     * @var array
     */

    private $message = array();
    /**
     *
     * @param type description
     */
    public function __construct($config)
    {
        if ($config && is_array($config)) {
            $this->config = $config;
        } else {
            throw new \Exception("Error Processing validator, your config is uncorrect", 1);
        }
    }

    /**
     * validate int
     *
     * @param string/int $item
     * @return return boolean
     */
    public function int($item)
    {
        return is_integer($item) || trim($item) === '';
    }

    /**
     * validate text
     *
     * @param string $item
     * @return return boolean
     */
    public function text($item)
    {
        return preg_match('@^[\'’ôéèêçà\d\w%:&^$#!\?\s~\*\'"/.\(\)/,;\+\-\@]+$@i', $item) || trim($item) === '';
    }
    /**
     * validate text
     *
     * @param string $item
     * @return return boolean
     */
    public function html($item)
    {
        return $this->xss_clean($item) === $item || trim($item) === '';
    }

    /**
     * validate tel
     *
     * @param string $item
     * @return return boolean
     */
    public function tel($item)
    {
        return preg_match('/^[\d\s\+]{6,15}$/i', $item) || trim($item) === '';
    }

    /**
     * validate date
     *
     * @param string $item
     * @return return boolean
     */
    public function date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date || trim($date) === '';
    }

    /**
     * validate alpha num
     *
     * @param string $item
     * @return return boolean
     */
    public function alphanum($item)
    {
        return ctype_alnum($item) || trim($item) === '';
    }

    /**
     * validate alpha
     *
     * @param string $item
     * @return return boolean
     */
    public function alpha($item)
    {
        return ctype_alpha($item) || trim($item) === '';
    }

    /**
     * validate alpha
     *
     * @param string $item
     * @return return boolean
     */
    public function required($item)
    {
        $item = trim($item);
        return strlen($item) > 0;
    }

    /**
     * validate email
     *
     * @param string $item
     * @return return boolean
     */
    public function email($item)
    {
        $pattern = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

        return preg_match($pattern, $item) || trim($item) === '';
    }

    /**
     * run validator
     *
     * @param string $item
     * @return return sting
     */
    public function getMessage($item = NULL, $seperator = NULL)
    {
        if ($item) {
            return isset($this->message[$item]) ? $this->message[$item] : 1;
        } else {
            if (!$seperator)
                return implode("<br><br>", $this->message);
            else
                return implode($seperator, $this->message);
        }
    }

    /**
     * run validator
     *
     * @param string $item
     * @return return boolean
     */
    public function run()
    {
        $return = TRUE;
        foreach ($this->config as $field => $config) {
            $filters = explode('|', $config['filter']);
            $label = $config['label'];
            /* cleaners */
            if (in_array('trim', $filters) && is_array($_POST[$field])) {
                foreach ($_POST[$field] as $key => $post)
                    $_POST[$field][$key] = trim($post);
            } elseif (in_array('trim', $filters)) {
                $_POST[$field] = trim($_POST[$field]);
            }

            if (in_array('strip_tags', $filters) && is_array($_POST[$field])) {
                foreach ($_POST[$field] as $key => $post)
                    $_POST[$field][$key] = strip_tags($post);
            } elseif (in_array('strip_tags', $filters)) {
                $_POST[$field] = strip_tags($_POST[$field]);
            }

            if (in_array('xss_clean', $filters) && is_array($_POST[$field])) {
                foreach ($_POST[$field] as $key => $post)
                    $_POST[$field][$key] = $this->xss_clean($post);
            } elseif (in_array('xss_clean', $filters)) {
                $_POST[$field] = $this->xss_clean($_POST[$field]);
            }
            /* cleaners */

            foreach ($filters as $key => $filter) {
                if (in_array($filter, array('trim', 'strip_tags', 'xss_clean'))) {
                    continue;
                }

                $valid = true;
                if (is_array($_POST[$field])) {
                    foreach ($_POST[$field] as $key => $post)
                        $valid = $this->{$filter}($_POST[$field][$key]);
                } else {
                    $valid = $this->{$filter}($_POST[$field]);
                }

                if (!$valid) {
                    $this->message[$field]  = str_replace('%label%', $label, $this->messages[$filter]);
                }

                $return  = $return && $valid;
            }
        }
        return $return;
    }

    public function xss_clean($data)
    {
        # Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        # Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        # Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        # Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        # Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        return $data;
    }
}

function flashMessage()
{
    return  new FlashMessages();
}


function alais_string($string)
{
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

    return strtolower(preg_replace('/[^A-Za-z0-9\_]/', '', $string)); // Removes special chars.
}






function fcm_send($tokens, $title, $body, $pageTo = 'eleve', $resource =  null, $eleveId = null)
{


    if (is_null($tokens)) {
        return;
    }


    try {

        $message =  array(
            "title" => $title,
            'body'     => $body,
            'page' => $pageTo ? $pageTo : null,
            'resource' => $resource ?: null,
            'eleve_id' => $eleveId ?: null,
            "click_action" => "FCM_PLUGIN_ACTIVITY",
        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_access_key = Models\Config::getByAlias('firebase-server-api-access-key')->get('Value');

        $token_ids = array_values(array_unique($tokens));
        $array_registration_ids =  array_chunk($token_ids, 1000);

        foreach ($array_registration_ids as $registration_ids) {

            $fields = array(
                'registration_ids' => $registration_ids,
                'notification'    => $message,
                'data' => [
                    'page' => $pageTo ? $pageTo : null,
                    'resource' => $resource ?: null,
                    'eleve_id' => $eleveId ?: null,
                ]
            );

            $headers = array(
                'Authorization:key = ' . $api_access_key,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_exec($ch);
            curl_close($ch);
        }
    } catch (Exception $e) {
    }
}

function fcm_web_send($tokens, $title, $body)
{
    if (is_null($tokens)) {
        return;
    }
    try {

        $message =  array(
            "title" => $title,
            "body"  => $body,

        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_access_key = Config::get('web-firebase-server-api-access-key');
        $token_ids = array_values(array_unique($tokens));
        $array_registration_ids =  array_chunk($token_ids, 1000);

        foreach ($array_registration_ids as $registration_ids) {
            $fields = array(
                'registration_ids' => $registration_ids,
                'notification'    => $message,
            );

            $headers = array(
                'Authorization:key = ' . $api_access_key,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result =  curl_exec($ch);
            curl_close($ch);
        }
    } catch (Exception $e) {
    }
}

function _snf($tokens, $title, $body, $pageTo = null, $resource =  null)
{

    try {

        $message =  array(
            "title" => $title,
            'body'     => $body,
            'icon'    => 'myicon',
            'sound' => 'mySound',
            "click_action" => "FCM_PLUGIN_ACTIVITY",
            'page' => $pageTo ? $pageTo : null,
            'resource' => $resource ?: null,
        );
        $url = 'https://fcm.googleapis.com/fcm/send';
        if (isset($tokens['ios'])) {
            $token_ids = array_values(array_unique($tokens['ios']));
            $array_registration_ids =  array_chunk($token_ids, 1000);
            foreach ($array_registration_ids as $registration_ids) {
                # code...
                $fields = array(
                    'registration_ids' => $registration_ids,
                    'notification'    => $message
                );

                $api_access_key = Models\Config::getByAlias('firebase-server-api-access-key')->get('Value');
                $headers = array(
                    // 'Authorization:key = '.Config::get('firebase-server-api-access-key'),
                    'Authorization:key = ' . $api_access_key,
                    'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);

                curl_close($ch);
            }
        }

        if (isset($tokens['android'])) {
            $token_ids = array_values(array_unique($tokens['android']));
            $array_registration_ids =  array_chunk($token_ids, 1000);
            foreach ($array_registration_ids as $registration_ids) {
                $fields = array(
                    'registration_ids' =>  $registration_ids,
                    'notification' => $message,
                    "data" => $message
                );
                $api_access_key = Models\Config::getByAlias('firebase-server-api-access-key')->get('Value');

                $headers = array(
                    // 'Authorization:key = '.Config::get('firebase-server-api-access-key'),
                    'Authorization:key = ' . $api_access_key,
                    'Content-Type: application/json'
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);

                curl_close($ch);
            }
        }
    } catch (Exception $e) {
    }
}


function loadView($view, $data = array(), $layout = '_layout')
{
    global $app;
    ob_start();
    extract($data);
    if (Request::isAdmin() && Config::get('extranet')) {
        include _basepath . (Config::get('extranet') . '/') . 'pages/_layouts/' . $layout . '.php';
        exit;
    }

    include _basepath . ((Request::isAdmin() && Config::has('admin') && Config::get('admin')) ? Config::get('admin') . '/' : '') . 'pages/_layouts/' . $layout . '.php';

    $result = ob_get_clean();

    echo $result;
    exit;
}

function getView($view, $data = array(), $layout = '_layout')
{
    global $app;
    ob_start();
    extract($data);
    if (Request::isAdmin() && Config::get('extranet')) {
        include _basepath . (Config::get('extranet') . '/') . 'pages/' . $layout . '.php';
        exit;
    }

    include _basepath . ((Request::isAdmin() && Config::has('admin') && Config::get('admin')) ? Config::get('admin') . '/' : '') . 'pages/_layouts/' . $layout . '.php';

    $result = ob_get_clean();

    return $result;
}

function cf_token()
{
    if (!isset($_SESSION['cf_token_rexpired']) || $_SESSION['cf_token_rexpired']) {
        $token = base64_encode(sha1(time() . rand()));
        $_SESSION['cf_token'] = $token;
        $_SESSION['cf_token_rexpired'] = FALSE;
    } else {
        $token = $_SESSION['cf_token'];
    }

    return $token;
}

function cf_fields()
{
    return "<input type='hidden' name='cf_token' value='" . cf_token() . "'/>";
}

function getLetterFromNumber($num)
{
    $numeric = $num % 26;
    $letter = chr(65 + $numeric);
    $num2 = intval($num / 26);
    if ($num2 > 0) {
        return getLetterFromNumber($num2 - 1) . $letter;
    } else {
        return $letter;
    }
}


function assets($path)
{
    return !is_bool(strpos($path, "http")) ? $path : URL::base() . "assets/{$path}";
}

function hash_encode($param, $hashlength = null)
{
    loadLib('hashids');
    $hashKey = \Config::get('hashsecretkey');
    $hashKey .= 'Encode:)';
    $hashids = new \Hashids\Hashids($hashKey, $hashlength ? $hashlength : \Config::get('hashlength'), \Config::get('hashchars'));
    return $hashids->encode($param);
}

function hash_decode($param, $hashlength = null)
{
    loadLib('hashids');
    $hashKey = \Config::get('hashsecretkey');
    $hashKey .= 'Encode:)';
    $hashids = new \Hashids\Hashids($hashKey, $hashlength ? $hashlength : \Config::get('hashlength'), \Config::get('hashchars'));
    $hashidsDecode = $hashids->decode($param);
    return $hashidsDecode[0];
}

function verify_token($token, $refresh = TRUE)
{
    $sess_token = $_SESSION['cf_token'];
    $_SESSION['cf_token_rexpired'] = true;

    // refresh the token
    if ($refresh) {
        cf_token();
    }

    return $sess_token === $token;
}


function loadView404()
{
    loadView('erreur404');
}

function loadViewError($params)
{
    loadView('erreur', $params);
}


function httpPost($url, $data, $headers = [])
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}


function httpGet($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_POST, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function executeLink($url)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    
    curl_close($ch);

    return $result;

    /*	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
*/
}


function getJson($string, $table = false)
{
    if ($table)
        return json_decode($string, true);
    else
        return json_decode($string);
}

function sendAsJson($response)
{
    exit(json_encode($response));
}

function sendAsyncResultAsJson($result)
{
    $response = array();
    $response  = $result;
    echo json_encode($response);
}

function sendResultAsJson($result)
{
    $response = array();
    $response = $result;
    sendAsJson($response);
}

function filter_scripts($posts = NULL)
{
    if (!$posts)
        $posts = $_POST;

    foreach ($_POST as $key => $post) {
        if (is_array($post)) {
            // recursive
            // print_r($post);exit;
            // filter_scripts($post);
        } else {
            $_POST[$key] = preg_replace('%<script.*>.+(<\s*/\s*script\s*>)?%i', "", $post);
        }
    }
}

function  logErrorToCSM($error, $trace)
{
    return  httpPost(
        'http://boti.education/csm/api/logError',
        [
            "school_alias" => _school_alias,
            "page" => $_SERVER['REQUEST_URI'],
            "error" => $error,
            "message" => $trace,
            "date" => date('Y-m-d H:i:s')
        ],
        [
            'token: fHJWlnWJaizocriwMkj9UaZRl5UHXJzZBF9xpObq',
        ]
    );
}

function is_arabic($string)
{
    if (preg_match('/\p{Arabic}/u', $string))
        return true;
    return false;
}

/*@MM*/
function secure_csrf($posts = NULL)
{
    if (!$posts)
        $posts = $_POST;

    /* If a form sent a post request */
    if (count($posts)) {
        if (!isset($posts['cf_token']))
            throw new Exception("Forbidden Request !", 403);

        if (!verify_token($posts['cf_token']))
            throw new Exception("Forbidden Request, your token has expired !", 403);
    }
}

function send_notification_firebase($tokens, $message, $device)
{
    $url = 'https://fcm.googleapis.com/fcm/send';

    if ($device == 'ios')
        $fields = array(
            'registration_ids' => $tokens,
            'notification'    => $message
        );
    else
        $fields = array(
            'registration_ids' => $tokens,
            'notification'    => $message,
            "data" => $message
        );

    $headers = array(
        'Authorization:key = ' . Models\Config::getByAlias('firebase-server-api-access-key')->get('Value'),
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}


function snf_merge_tokens($first_array, $second_array)
{

    $first_android_tokens = isset($first_array['android']) ? $first_array['android'] : array();
    $second_android_tokens = isset($second_array['android']) ? $second_array['android'] : array();


    $first_ios_tokens =  isset($first_array['ios']) ? $first_array['ios'] : array();
    $second_ios_tokens =   isset($second_array['ios']) ? $second_array['ios'] : array();

    $android_tokens =  $first_android_tokens +   $second_android_tokens;
    $ios_tokens =  $first_ios_tokens +   $second_ios_tokens;

    return  array(
        'android' =>  $android_tokens,
        'ios' =>  $ios_tokens,
    );
}


function loadLib($lib, $required = false)
{
    $libPath = _basepath . 'libs/' . $lib . '/load.php';
    if (!file_exists($libPath)) {
        $libPath = _basepath . 'libs/' . $lib . '/vendor/autoload.php';
    }
    if (!file_exists($libPath)) {
        throw new \Exception('Library ' . $lib . ' could not be loaded, either folder missing, or load file not configured');
    }
    if ($required)
        require_once $libPath;
    else
        include_once $libPath;
}

function roleIs()
{
    $args = func_get_args();
    return in_array(Session::getInstance()->getCurRoleAlias(), $args);
}

function isAllowed($alais)
{
    return \Models\Params::isAllowed($alais);
}


function ParamsValue($alais)
{
    return \Models\Params::getValue($alais);
}

function ParamsText($alais)
{
    $param = \Models\Params::getByAlias($alais);
    return $param ? $param->get('Value') : null;
}

// Special Chars to HTML Entities
function html($txt, $decode = false)
{
    if ($decode)
        return html_entity_decode($txt, ENT_COMPAT, 'UTF-8');
    else
        return htmlentities($txt, ENT_COMPAT, 'UTF-8');
}

function truncate_text($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false)
{
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it’s an “empty element” with or without xhtml-conform closing slash (f.e.)
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                    // if tag is a closing tag (f.e.)
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag (f.e. )
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate’d text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length + $content_length > $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left + $entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if ($total_length >= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if ($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '';
        }
    }
    return strip_tags($truncate);
}

function getMimeType($filename)
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filename);
    finfo_close($finfo);
    return $mime;
}

function encodeURIComponent($str)
{
    $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
    return strtr(rawurlencode($str), $revert);
}

function __($key, $replace = null)
{
    echo Models\Translation::translate($key, $replace);
}

function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}

function format_phone($phoneNumber)
{
    $phoneNumber = str_replace(' ', '', $phoneNumber);
    $phoneNumber = str_replace('-', '', $phoneNumber);
    $phoneNumber = preg_replace('/^6/', '06', $phoneNumber);
    $phoneNumber = preg_replace('/^00212/', '0', $phoneNumber);
    $phoneNumber = preg_replace('/^212/', '0', $phoneNumber);
    $phoneNumber = str_replace('+212', '0', $phoneNumber);

    return $phoneNumber;
}

function dd()
{
    exit(var_dump(func_get_args()));
}

function sendResponse($resContent, $resCode = 200, $resHeader = array())
{
    if (!empty($resHeader)) {
        foreach ($resHeader as $key => $value) {
            $header = $key . ': ' . $value;
            header($header, false);
        }
    }
    http_response_code($resCode);
    $resContent = is_string($resContent) ? $resContent : json_encode($resContent);
    exit($resContent);
}

function excel_styling($activeSheet, $options)
{
    $header_style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd7dcfa')
        ),
        'font'  => array(
            'bold'  => true,
            'size'  => 14,
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
        )
    );

    $body_style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
        'font'  => array(
            'bold'  => true,
        )
    );

    $left_style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ),
    );

    if (in_array('header', array_keys($options))) {
        foreach ($options['header'] as $header)
            $activeSheet->getStyle($header)->applyFromArray($header_style);
    }
    if (in_array('header_left', array_keys($options))) {
        foreach ($options['header_left'] as $header_left) {
            $activeSheet->getStyle($header_left)->applyFromArray($header_style);
            $activeSheet->getStyle($header_left)->applyFromArray($left_style);
        }
    }
    if (in_array('body', array_keys($options))) {
        foreach ($options['body'] as $body)
            $activeSheet->getStyle($body)->applyFromArray($body_style);
    }
    if (in_array('body_left', array_keys($options))) {
        foreach ($options['body_left'] as $body_left) {
            $activeSheet->getStyle($body_left)->applyFromArray($body_style);
            $activeSheet->getStyle($body_left)->applyFromArray($left_style);
        }
    }
}


function ControllerRole()
{
    Session::getInstance()->requireLogin(true);
    $args = func_get_args();
    if (!in_array(Session::getInstance()->getCurRoleAlias(), $args)) {
        URL::redirect(URL::admin('/'));
        exit();
    } 
}


//----------------------------------------------------------------------Error Report Page
function errorPage($errors)
{
    global $depth;
    if (!$errors) return;
    if (!is_array($errors)) $errors = array($errors);
?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php
        $pageTitle = 'Message d\'erreur';
        ?>
        <style>
            * {
                padding: 0;
                margin: 0;
            }

            body {}

            #error-container {
                max-width: 960px;
                margin: 80px auto 0;
                padding: 20px 30px 30px;
                box-shadow: 0 1px 5px #666;
                background: #fff;
                border-radius: 3px;
            }

            .errors {
                font-size: 1.2em;
                padding-left: 25px;
                margin-bottom: 20px;
            }

            .errors li {
                margin: 0 0 10px;
            }

            #link-back {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <section id="error-container">
            <ul class="errors">
                <?php foreach ($errors as $error) { ?>
                    <li><?php echo $error ?></li>
                <?php } ?>
            </ul>
            <div id="link-back">
                <a href="#" onClick="history.go(-1); return false;">Retour</a>
            </div>
        </section>
    </body>

    </html>

<?php
    exit;
}
