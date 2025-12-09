<?php
	//Solicitud token autenticación SIIGO
	//----------------------------------------------------------------------
	//https://siigonube.siigo.com/
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	//----------------------------------------------------------------------
	$userName = 'gerencia@newyorkmoney.com.co';
	$accessKey = 'ZWUxMjBhOTYtOGVjNC00OWM1LTk0NDAtOTE3NGQyOTAzNjU3OipqNmdYc3tCdEY=';
	//----------------------------------------------------------------------
	//$url = 'https://siigonube.siigo.com:50050/connect/token';
	$url = 'https://integrations.siigo.com/auth/connect/token';
	$ch = curl_init($url);
	$postRequest = 'grant_type=password&username='.$username.'&password='.$password.'&scope=WebApi offline_access';
	//--------------------------------------------------------------------
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);
	$headers = array(
		"Accept: application/json",
		"Partner-ID: SOIPCNYM"
	);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//------------------------------------------------------------------
	$result = curl_exec($ch);
	curl_close($ch);
	//------------------------------------------------------------------
	$siigoJson = json_decode($result, true);
	$authToken = $siigoJson['access_token'];
	//echo $authToken;
?>