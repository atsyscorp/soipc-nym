<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	// Include General
	include('../General.php');
	// Inicia Conexion bd
	$link=Conectarse();
	//-----------------------------------------------------
	session_start();
	$cliented = $_SESSION['Id_Cliente_d'];
	// Consulta si la variable de Sesion viene vacia
	if($cliented == ''){
		header("Location: error.php");
		exit;
	}
	// Consulta de nombre de cliente
	$strSQL1 = "SELECT * FROM Clientes WHERE Identificacion = '". $cliented ."'";
	$p1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
	$q1=mysqli_fetch_array($p1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0 , minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, height=device-height, minimal-ui">
	<title>Desuscripción Exitosa - New York Money</title>
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
	<div class="desuscribir">
		<div class="desuscribir-contenedor">
			<div class="desuscribir-logo">
				<h1>
					<a href="http://www.newyorkmoney.com.co">
						<img src="images/logo-nym.png">
					</a>
				</h1>
			</div>
			<div class="desuscribir-nombre">
				<strong>¡Hola <?=$q1['Nombre_1']?>!</strong>
			</div>
			<div class="desuscribir-contenido">
				Te has desuscrito satisfactoriamente y ya no recibirás más notificaciones de New York Money. Si deseas recibir más información, contáctanos al teléfono <a href="tel:6014322480">6014322480</a> o al correo <a href="mailto:atencionalcliente@newyorkmoney.com.co">atencionalcliente@newyorkmoney.com.co</a>.<br/>
				Te invitamos a visitar nuestra página <a href="http://www.newyorkmoney.com.co">www.newyorkmoney.com.co</a>.
			</div>
			<a href="http://www.newyorkmoney.com.co">
				<div class="desuscribir-boton">
					Ir a página web
				</div>
			</a>
		</div>
	</div>
</body>
</html>