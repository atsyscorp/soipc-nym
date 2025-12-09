<?php
	error_reporting(E_ALL ^ E_NOTICE);
   	include("General.php");
	//Captura variables de inicio
	$idalert=$_GET['var0'];
	//Valida Acceso a archivo
	if($idalert == ''){
		header("location:index.php");
	}
	//----------------------------------------------
	$link = Conectarse();
	$p = mysqli_query($link, "SELECT * FROM Alertas_Usuarios Where Identificacion = '$idalert'") or die(mysqli_error($link));
	while($n = mysqli_fetch_array($p)){
		$idgen = $n[Identificacion_General];
		$titulo = $n[Titulo];
		$fecha = $n[Fecha];
		$sucursal = $n[Sucursal];
		$usuario = $n[Usuario];
		$content = $n[Contenido];
	}
	//-----------------------------------------------
	//Busca nombre de usuario
	$u = mysqli_query($link, "SELECT Nombre FROM Usuarios Where Identificacion = '$usuario'") or die(mysqli_error($link));
	while($us=mysqli_fetch_array($u)){$uname = $us[Nombre];}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alertas Usuarios</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body class="bodygen">
<div style="width:100%; overflow:hidden" class="fcont" align="center">
	<div style="width:620px; overflow:hidden; text-align:justify; margin-top:1px; padding:15px;" class="dlin_3">
		<div style="margin-bottom:3px; text-align:right">Referencia No. <?=$idgen?></div>
		<div style="font-size:27px; font-weight:bold; margin-bottom:3px"><?=$titulo?></div>
		<div style="margin-bottom:3px"><b>Sucursal: </b><?=$sucursal?></div>
		<div style="margin-bottom:3px"><b>Usuario: </b><?=$uname?></div>
		<div style="margin-bottom:20px"><?=$fecha?></div>
		<div style="margin-bottom:20px" class="dlin_4"></div>
		<div><?=$content?></div>		
	</div>
</div>
</body>
</html>
