<?php
ini_set('max_execution_time', 300); // Configura el tiempo máximo de ejecución a 300 segundos (5 minutos)
date_default_timezone_set('America/Bogota');
// Incluye los archivos necesarios
require_once 'siigoAPIClient.php';
require_once 'traitDatabaseCon.php';
require_once 'traitParametros.php';
require_once 'logger.php';

$link = connectToDatabase();
$authToken = getSiigoAuthToken();

$billDoc = getElectronicInvoice($_REQUEST['bid'], $authToken);

print_r($billDoc); die;