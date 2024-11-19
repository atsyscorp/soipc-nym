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
	<title>Encuesta - New York Money</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0 , minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, height=device-height, minimal-ui">
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
	<div class="encuesta">
		<div class="encuesta-contenedor">
			<div class="encuesta-logo">
				<h1>
					<a href="http://www.newyorkmoney.com.co">
						<img src="images/logo-nym.png">
					</a>
				</h1>
			</div>
			<div class="encuesta-nombre">
				<strong>¡Hola <?=$q1['Nombre_1']?>!</strong>
			</div>
			<div class="encuesta-texto">
				Te agradecemos por tomarte un momento para responder a nuestra encuesta de satisfacción. Tu opinión es muy importante para nosotros. 
			</div>
			<div class="encuesta-contenido">
				<form action="gracias1.php" method="POST">
					<div class="encuesta-pregunta">
						<div class="encuesta-pregunta-titulo">
							1. Califica cómo ha sido tu experiencia cambiando tus divisas en New York Money.
						</div>
						<div class="encuesta-pregunta-pregunta">
							<div>
								<input type="radio" name="rdsatisfaccion" id="Excelente" value="Excelente" class="pregunta-input" required>
								<label for="Excelente" class="pregunta-label">Excelente</label>
							</div>
							<div>
								<input type="radio" name="rdsatisfaccion" id="Bueno" value="Bueno" class="pregunta-input">
								<label for="Bueno" class="pregunta-label">Bueno</label>
							</div>
							<div>
								<input type="radio" name="rdsatisfaccion" id="Regular" value="Regular" class="pregunta-input">
								<label for="Regular" class="pregunta-label">Regular</label>
							</div>
							<div>
								<input type="radio" name="rdsatisfaccion" id="Malo" value="Malo" class="pregunta-input">
								<label for="Malo" class="pregunta-label">Malo</label>
							</div>
						</div>
					</div>
					<div class="encuesta-pregunta">
						<div class="encuesta-pregunta-titulo">
							2. ¿Cómo podemos mejorar para la próxima vez que cambies tus divisas con nosotros?
						</div>
						<div class="encuesta-pregunta-pregunta">
							<textarea name="txsatisfaccion" cols="0" id="txsatisfaccion" maxlength="650" placeholder="Escribe aquí tu respuesta" required></textarea>
						</div>
					</div>
					<input class="encuesta-boton" type="submit" value="Enviar">
				</form>
			</div>
		</div>
	</div>
</body>
</html>