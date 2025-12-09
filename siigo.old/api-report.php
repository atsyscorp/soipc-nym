<?php
	header('Content-Type: text/html; charset=ISO-8859-1');
	error_reporting(E_ALL ^ E_NOTICE);
	//----------------------------------------------------
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	//----------------------------------------------------
	$apiMode = 'production'; //test | production
	if($apiMode == 'test'){
		if (!($link=mysqli_connect("162.219.251.8","soipcnym_remotefindme","79FindMe79", "soipcnym_dbase"))){
	      echo "Error conectando a la base de datos.";
	      exit();
	   	}
	} else {
		if (!($link=mysqli_connect("localhost","soipcnym_dbase","$79Nym79$", "soipcnym_dbase"))){
	      echo "Error conectando a la base de datos.";
	      exit();
	   	}
	}
	//----------------------------------------------------
	//Formato de fecha para mostrar
	setlocale(LC_TIME, 'es_ES');
	date_default_timezone_set('America/Bogota');
	//--------------------------------------------
	//Valida parámetros
	$repDate = '';
	if(!isset($_GET['repdate']) || $_GET['repdate'] == ''){
		echo 'Defina la fecha para la generación del reporte';
		exit();
	} else {
		$repDate = $_GET['repdate'];
	}
	//--------------------------------------------
	//Datos en el reporte
	$strSQ0 = "SELECT * FROM Factura_Electronica WHERE DATE(LastUpdate)='".$repDate."' ORDER BY LastUpdate ASC";
	$p0=mysqli_query($link, $strSQ0) or die(mysqli_error($link));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="ISO-8859-1">
<title>Reporte envío facturas SIIGO Api - New York Money</title>
<META NAME="robots" CONTENT="noindex, nofollow"></META>
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
</head>
<body>
	<h1>REPORTE ENVÍO FACTURAS A SIIGO - API</h1>
	<table cellspacing="1" cellpadding="1" border="1">
		<thead>
			<tr>
				<td style="width: 70px">Identificación de Registro</td>
				<td style="width: 120px">Identificación Factura</td>
				<td style="width: 120px">Tipo de Proceso</td>
				<td style="width: 120px">Estado de Envío</td>
				<td style="width: 120px">Última Actualización</td>
				<td style="width: 250px">Respuesta Api SIIGO</td>
			</tr>
		</thead>
		<tbody>
			<?php
				while($q0=mysqli_fetch_array($p0)){
			?>
			<tr>
				<td><?=$q0['RowId']?></td>
				<td><?=$q0['InvoiceId']?></td>
				<td><?=$q0['SendProcess']?></td>
				<td><?=$q0['SendStatus']?></td>
				<td><?=$q0['LastUpdate']?></td>
				<td><?=$q0['ApiResponse']?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>
</body>
</html>