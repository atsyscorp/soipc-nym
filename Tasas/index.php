<?php
	//--------------------------------------------
	//VERSION LITE PARA PANTALLAS PEQUEÑAS
	//--------------------------------------------
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("../General.php");
	//Captura IP
	$link=Conectarse();
	$ipadrs = $_SERVER['REMOTE_ADDR'];
	//Consulta sucursal por IP
	$s=mysqli_query($link, "SELECT * FROM Sucursales where IP_Adress = '$ipadrs'") or die(mysqli_error($link));
	$counts=mysqli_num_rows($s);
	if($counts==0)
	{
		header("location:no_autorizado.php");
	} else {
		//Consulta datos de sucursal
		while($n=mysqli_fetch_array($s)){
			for ($i = 0; $i <= 10; $i++) {
				$varsuc[$i] = $n[$i];
			}
		}
	}
	//--------------------------------------------------------
	//Consulta cantidad de monedas
	$strSQLC = "SELECT * FROM Tasas Where Sucursal = '". $varsuc[0]. "' AND Estacion='01'";
	$c=mysqli_query($link, $strSQLC) or die(mysqli_error($link));
	$ncur=mysqli_num_rows($c);
	//--------------------------------------------------------
	//Consulta frases de tablero
//	$fi = 0;
//	$strSQLF = "Select * From Tablero WHERE Estado = 'Activo' Order By Identificacion";
//	$f=mysqli_query($link, $strSQLF) or die(mysqli_error($link));
//	while($fr=mysqli_fetch_array($f)){
//		$varfas[$fi] = $fr[Contenido];
//		$fi++;
//	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tasas Compra y Venta New York Money</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
<script src="tasas.js" type="text/javascript"></script>
</head>
<body onload="ConsTasas('<?=$varsuc[0]?>', '<?=$ncur?>');" style="width:100%; height:100%; border:none">
<div style="width:100%; height:100%; position:fixed; overflow:hidden; top:0px; left:0px">
	<div style="width:100%; height:100%; overflow:hidden;">
		<div style="width:100%; height:100%; overflow:hidden; float:left" class="bgcol_1">
			<div style="height:97%; width:97%; margin:auto; margin-top:1.5%">
				<div style="width:100%; text-align:center; margin-bottom:5px" class="bgcol_2 txgen_1 txgreen dlin_1"><span style="font-size:7.5vw">0% Commission</span></div>
				<div style="width:100%; margin-bottom:3px">
					<div style="width:34%; float:left; text-align:center;" class="txgen_1 txwhite"><span style="font-size:3.5vw"><b>Moneda</b></span><br /><span style="font-size:3.1vw">Currency</span></div>
					<div style="width:33%; float:left; text-align:center;" class="txgen_1 txwhite"><span style="font-size:3.5vw"><b>Compramos</b></span><br /><span style="font-size:3.1vw">We buy</span></div>
					<div style="width:33%; float:left; text-align:center;" class="txgen_1 txwhite"><span style="font-size:3.5vw"><b>Vendemos</b></span><br /><span style="font-size:3.1vw">We sell</span></div>
					<div style="clear:both"></div>
				</div>
				<div id="dTasas" style="width:100%; overflow:hidden;"></div>
				<div style="width:100%; text-align:center; margin-top:3px;" class="bgcol_2 txgen_1 txgreen dlin_2"><span style="font-size:7.5vw">Comisión 0%</span></div>
			</div>
		</div>
		<div style="clear:both"></div>
	</div>
</div>
</body>
</html>
