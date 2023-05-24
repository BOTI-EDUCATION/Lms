<?php

/**
 * Created by PhpStorm.
 * User: Ilyass
 * Date: 5/18/16
 * Time: 12:43 PM
 */

function smsBOTI($num, $message)
{

	// This would return the message id if the message successfully sent,
	// otherwise it would return null, means the message didn't sent
	$smsID = sendSms($message, $num);

	return $smsID;
}





//SEND SMS VIA CASANET
function smsCasaNet($phoneNumber, $message)
{

	$url =  'https://www.sms.ma/mcms/sendsms/';

	// dd(config::get('sms-default-login'), Config::get('sms-default-password'));
	// school config / our default config
	$username =  trim(Config::get('sms_login')) ?: Config::get('sms-default-login');
	$password = trim(Config::get('sms_password')) ?: Config::get('sms-default-password');

	$phoneNumber = format_phone($phoneNumber);

	$url .= "?login=$username&password=$password";
	$url .= '&msisdn_to=+' . $phoneNumber;
	$url .= '&body=' . $message;
	$url = str_replace(' ', '%20', $url);

	//dd(array("Message Result" => $url), "SMS");
	return executeLink($url);
}
