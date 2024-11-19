<?php
// Solicitud token autenticaci¨®n SIIGO
// ----------------------------------------------------------------------
ini_set('memory_limit', '2048M');
set_time_limit(0); 
// ----------------------------------------------------------------------
$userName = 'gerencia@newyorkmoney.com.co';
$accessKey = 'ZWUxMjBhOTYtOGVjNC00OWM1LTk0NDAtOTE3NGQyOTAzNjU3OipqNmdYc3tCdEY=';
// ----------------------------------------------------------------------
$url = 'https://api.siigo.com/auth';
$authRequest['username'] = $userName;
$authRequest['access_key'] = $accessKey;
$authJson = json_encode($authRequest);
// --------------------------------------------------------------------
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $authJson);
$headers = array(
    "Content-type: application/json",
    // la siguiente l¨ªnea fue implementada por Juan Camilo el 21/11/23 por actualizaci¨®n de Siigo
    "Partner-ID: SOIPCNYM",
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// ------------------------------------------------------------------
$result = curl_exec($ch);
curl_close($ch);
// ------------------------------------------------------------------
$siigoJson = json_decode($result, true);
$authToken = $siigoJson['access_token'];

// Verificar si se obtuvo el token correctamente
if (isset($authToken)) {
    // Obtener las formas de pago
    $paymentUrl = "https://api.siigo.com/v1/payment-types?document_type=FV";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $paymentUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken", // Usamos el token que obtuvimos antes
        "Partner-ID: SOIPCNYM" // Usamos el mismo Partner-ID
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta JSON de formas de pago
    $paymentTypes = json_decode($response, true);

    echo "<h2>Formas de Pago</h2>";
    if (!empty($paymentTypes)) {
        echo "<pre>";
        print_r($paymentTypes);
        echo "</pre>";
    } else {
        echo "No se encontraron formas de pago.";
    }

    // Obtener los tipos de documentos
    $documentUrl = "https://api.siigo.com/v1/document-types?type=FV";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $documentUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer $authToken", // Usamos el token que obtuvimos antes
        "Partner-ID: SOIPCNYM" // Usamos el mismo Partner-ID
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta JSON de tipos de documentos
    $documentTypes = json_decode($response, true);

    echo "<h2>Tipos de Documentos</h2>";
    if (!empty($documentTypes)) {
        echo "<pre>";
        print_r($documentTypes);
        echo "</pre>";
    } else {
        echo "No se encontraron tipos de documentos.";
    }
} else {
    echo "Error al obtener el token de autenticaci¨®n.";
}
?>
