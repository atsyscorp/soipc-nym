<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	// Include General
	include('../General.php');
	// Inicia Conexion bd
	$link=Conectarse();
	//-----------------------------------------------------
	session_start();
	//Captura las variables de Sesion
	$cliente = $_SESSION['Id_Cliente_e'];
	$factura = $_SESSION['Id_Factura_e'];
	// Consulta si las variables de Sesion vienen vacias
	if(($cliente == '') || ($factura == '')){
		header("Location: error.php");
		exit;
	}
	// Consulta de nombre de cliente
	$strSQL1 = "SELECT * FROM Clientes WHERE Identificacion = '". $cliente ."'";
	$p1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
	$q1=mysqli_fetch_array($p1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Encuesta Respondida - New York Money</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0 , minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, height=device-height, minimal-ui">
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
	<div class="gracias">
		<div class="gracias-contenedor">
			<div class="gracias-logo">
				<h1>
					<a href="http://www.newyorkmoney.com.co">
						<img src="images/logo-nym.png">
					</a>
				</h1>
			</div>
			<div class="gracias-nombre">
				<strong>¡Muchas Gracias <?=$q1['Nombre_1']?>!</strong>
			</div>
			<div class="gracias-contenido">
				Te agradecemos el tiempo que te has tomado para responder a nuestra encuesta de satisfacción. Recuerda que si deseas recibir más información, contáctanos al teléfono <a href="tel:6014322480">6014322480</a> o al correo <a href="mailto:atencionalcliente@newyorkmoney.com.co">atencionalcliente@newyorkmoney.com.co</a>.<p></p>
				<?php
				// Asigna url de reseña segun la sucursal que el usuario usara
				//--------------------------------------------------------------------
				// Se realiza la consulta de la sucursal
				$sucursalurl = '';
				$strSQL2 = "SELECT Sucursal FROM Operacion_Ventanilla WHERE Identificacion = '". $factura ."' AND Documento_Declarante = '". $cliente ."'";
				$p2=mysqli_query($link, $strSQL2) or die(mysqli_error($link));
				$q2=mysqli_fetch_array($p2);
				if($q2['Sucursal'] == 'A48'){
					$sucursalurl = 'https://goo.gl/XWxRzf';
				} else if($q2['Sucursal'] == 'G20'){
					$sucursalurl = 'https://goo.gl/JYUUj6';
				} else if($q2['Sucursal'] == 'A01'){
					$sucursalurl = 'https://goo.gl/FsYZjN';
				} else if($q2['Sucursal'] == 'CA2'){
					$sucursalurl = 'https://goo.gl/dbnRrV';
				} else if($q2['Sucursal'] == 'U18'){
					$sucursalurl = 'https://goo.gl/kQ8YaA';
				} else if($q2['Sucursal'] == 'U48'){
					$sucursalurl = 'https://goo.gl/kQ8YaA';
				} else if($q2['Sucursal'] == 'S82'){
					$sucursalurl = 'https://goo.gl/b2rw1e';
				} else {
					$sucursalurl = 'http://www.newyorkmoney.com.co/';
				}
				?>
				<a href="<?=$sucursalurl?>" target="_blank" rel="noopener noreferrer">Te agradeceríamos si también nos das tu calificación en Google. No te tomará más de 30 segundos. <img src="images/google.png" style="width: 25px;display: inline-block;vertical-align: middle;">  <span class="fcont_star"></span><span class="fcont_star"></span><span class="fcont_star"></span><span class="fcont_star"></span><span class="fcont_star"></span>   </a><p></p>

				Además, te invitamos a visitar nuestra página <a href="//www.newyorkmoney.com.co" target="_blank">www.newyorkmoney.com.co</a>.
			</div>
			<a href="//www.newyorkmoney.com.co" target="_blank">
				<div class="gracias-boton">
					Ir a página web
				</div>
			</a>
			<div class="gracias-privacidad"><strong>Aviso de Privacidad:</strong> Estos datos no serán utilizados para fines comerciales y sólo tienen por objetivo mejorar la prestación del servicio.</div>
		</div>
	</div>
</body>
</html>