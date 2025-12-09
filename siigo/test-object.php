<?php
    require_once 'class.siigo.php';
    $siigo = new Siigo();
    $authToken = $siigo->getToken();
    if ($authToken) {
        echo "Token de autenticación obtenido correctamente: " . $authToken;
    } else {
        echo "Error al obtener el token de autenticación.";
    }
?>