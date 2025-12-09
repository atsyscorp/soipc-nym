<?php
	//Solicitud token autenticación SIIGO
	//----------------------------------------------------------------------
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	//----------------------------------------------------------------------
	$userName = 'gerencia@newyorkmoney.com.co';
	$accessKey = 'ZWUxMjBhOTYtOGVjNC00OWM1LTk0NDAtOTE3NGQyOTAzNjU3OipqNmdYc3tCdEY=';
	//$password = '1kAVrwV|6M';
	//----------------------------------------------------------------------
	$url = 'https://api.siigo.com/auth';
	$authRequest['username'] = $userName;
	$authRequest['access_key'] = $accessKey;
	$authJson = json_encode($authRequest);
	//--------------------------------------------------------------------
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $authJson);
	$headers = array(
		"Content-type: application/json",
		//la siguiente linea fue implementado por juan camilo el 21/11/23 por actualziacion de siigo
		"Partner-ID: SOIPCNYM",
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//------------------------------------------------------------------
	$result = curl_exec($ch);
	curl_close($ch);
	//------------------------------------------------------------------
	$siigoJson = json_decode($result, true);
	$authToken = $siigoJson['access_token'];
	//echo $authToken;
?>