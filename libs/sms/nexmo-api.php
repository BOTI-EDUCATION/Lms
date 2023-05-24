<?php

function sendSms($message, $phoneNumber) {
	
	$phoneNumber = str_replace(' ','',$phoneNumber);
	$phoneNumber = str_replace('-','',$phoneNumber);

	$phoneNumber = preg_replace('/^6/', '06', $phoneNumber);
	$phoneNumber = preg_replace('/^00212/', '0', $phoneNumber);
	$phoneNumber = preg_replace('/^212/', '0', $phoneNumber);
	$phoneNumber = str_replace('+212','0',$phoneNumber);
	
    $phoneNumber = '212' . substr($phoneNumber, 1);
	
    $curl = curl_init();

    $url = 'https://rest.nexmo.com/sms/json?' . http_build_query( array(
            'api_key' =>  Config::get('sms-api-key'),
            'api_secret' => Config::get('sms-api-secret'),
            'from' => Config::get('sms-sender'),
            'to' => $phoneNumber,
            'text' => $message,
            // 'type' => "unicode"
        ));

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ));

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $decoded_response = json_decode($response, true);

    if($decoded_response['messages'][0]['status'] == 0){
        file_put_contents("debug.log", date("Y-m-d H:i:s") .  " | SMS SENT | FROM:  " . Config::get('sms-sender') . ". | TO : " . $phoneNumber . " | MESSAGE : " . $message . " | MESSAGE ID : "  . $decoded_response['messages'][0]["message-id"] .  PHP_EOL, FILE_APPEND);
        // return array("message-id" => $decoded_response['messages'][0]["message-id"]);
        return array("message-id" => $decoded_response);
    }
    return array("error" => $decoded_response['messages'][0]['error-text']);

}