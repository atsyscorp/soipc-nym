<?php

$url = 'https://siigonube.siigo.com:50050/connect/token';

// Create a new cURL resource
$ch = curl_init($url);

// Setup request to send json via POST
/*$paymentData = [];
$paymentData['event'] = 'transaction.updated';
$paymentData['data']['transaction']['id'] = '01-1532941443-49201';
$paymentData['data']['transaction']['amount_in_cents'] = '31720000';
$paymentData['data']['transaction']['reference'] = '2020102603443684';
$paymentData['data']['transaction']['customer_email'] = 'ravella90@gmail.com';
$paymentData['data']['transaction']['currency'] = 'COP';
$paymentData['data']['transaction']['payment_method_type'] = 'VISA';
$paymentData['data']['transaction']['redirect_url'] = 'https://dondurazno.com';
$paymentData['data']['transaction']['status'] = 'APPROVED';
$paymentData['data']['transaction']['shipping_address'] = null;
$paymentData['data']['transaction']['payment_link_id'] = null;
$paymentData['data']['transaction']['payment_source_id'] = null;
$paymentData['sent_at'] = '2018-07-20T16:45:05.000Z';

$payload = json_encode($paymentData);*/

/*$postRequest = array(
	"grant_type=s112pempresa2#",
	"username=EMPRESA2CAPACITACION\empresa2@apionmicrosoft.com",
	"password=s112pempresa2#",
	"scope=WebApi offline_access"
);*/
/*$postRequest = array(
	'grant_type' => 'password',
	'username' => 'EMPRESA2CAPACITACION\empresa2@apionmicrosoft.com',
	'password' => 's112pempresa2#',
	'scope' => 'WebApi offline_access',
);*/
$postRequest = 'grant_type=password&username=EMPRESA2CAPACITACION\empresa2@apionmicrosoft.com&password=s112pempresa2#&scope=WebApi offline_access';

// Attach encoded JSON string to the POST fields
//curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);


// Set the content type to application/json
$headers = array(
	"Content-type: application/x-www-form-urlencoded",
	"Accept: application/json",
	"Authorization: Basic U2lpZ29XZWI6QUJBMDhCNkEtQjU2Qy00MEE1LTkwQ0YtN0MxRTU0ODkxQjYx"
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the POST request
$result = curl_exec($ch);

// Close cURL resource
curl_close($ch);

echo $result;




?>