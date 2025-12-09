<?php
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
	$strSQLC = "Select * From Tasas Where Sucursal = '". $varsuc[0]. "' AND Estacion='01'";
	$c=mysqli_query($link, $strSQLC) or die(mysqli_error($link));
	$ncur=mysqli_num_rows($c);
	//--------------------------------------------------------
	//Consulta frases de tablero
	$fi = 0;
	$strSQLF = "Select * From Tablero WHERE Estado = 'Activo' Order By Identificacion";
	$f=mysqli_query($link, $strSQLF) or die(mysqli_error($link));
	while($fr=mysqli_fetch_array($f)){
		$varfas[$fi] = $fr[Contenido];
		$fi++;
	}	
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
<body onload="ConsTasas('<?=$varsuc[0]?>', '<?=$ncur?>'); Lhoyday(); UpTimeL()" style="width:100%; height:100%; border:none">
<div style="width:100%; height:100%; position:fixed; overflow:hidden; top:0px; left:0px">
	<div style="width:100%; height:50%; overflow:hidden;">
		<div style="width:50%; height:100%; overflow:hidden; float:left" class="bgcol_1">
			<div style="height:97%; width:97%; margin:auto; margin-top:1.5%">
				<div style="width:100%; text-align:center; margin-bottom:5px" class="bgcol_2 txgen_1 txgreen dlin_1"><span style="font-size:6vw">0% Commission</span></div>
				<div style="width:100%; margin-bottom:3px">
					<div style="width:34%; float:left; text-align:center;" class="txgen_1 txwhite"><span style="font-size:2.5vw"><b>Moneda</b></span><br /><span style="font-size:2.1vw">Currency</span></div>
					<div style="width:33%; float:left; text-align:center;" class="txgen_1 txwhite"><span style="font-size:2.5vw"><b>Compramos</b></span><br /><span style="font-size:2.1vw">We buy</span></div>
					<div style="width:33%; float:left; text-align:center;" class="txgen_1 txwhite"><span style="font-size:2.5vw"><b>Vendemos</b></span><br /><span style="font-size:2.1vw">We sell</span></div>
					<div style="clear:both"></div>
				</div>
				<div id="dTasas" style="width:100%; overflow:hidden;"></div>
				<div style="width:100%; text-align:center; margin-top:3px;" class="bgcol_2 txgen_1 txgreen dlin_2"><span style="font-size:6vw">Comisión 0%</span></div>
			</div>
		</div>
		<div style="width:50%; height:100%; overflow:hidden; float:right; background-color:#FBF9F7">
			<div style="height:100%; width:100%; margin:auto; margin-top:1.5%">
				<div style="width:100%; text-align:center; margin-bottom:5px; margin-left:3%" class="txgen_1 txgreen">
					<div id="dFecha" style="float:left; font-size:2.1vw; margin-right:1%"></div>
					<div style="float:left; font-size:2.1vw; margin-right:1%"> - </div>
					<div id="dHora" style="float:left; font-size:2.1vw"></div>
					<div style="clear:both"></div>
				</div>
				<div style="width:100%; margin-bottom:1%; margin-top:5%; text-align:center">
					<img src="images/Cuad_12.png" style="width:100%; height:auto" />
				</div>
				<!--
				<div style="width:100%; margin-bottom:1%; text-align:center">
					<img src="images/logoNYM.png" style="width:90%; height:auto" />
				</div>
				<div style="width:100%; margin-top:2%; text-align:center">
					<span style="text-align:center; width:90%; font-size:1.25vw" class="txgen_1 txgray">
						YENES DOLARES EUROS LIBRAS ESTERLINAS FRANCOS SUIZOS<br />
						BOLIVARES REALES BRASILEROS PESOS MEXICANOS PESOS ARGENTINOS
					</span>
				</div>
				-->
			</div>			
		</div>
		<div style="clear:both"></div>
	</div>
	<div style="width:100%; height:50%; overflow:hidden;" class="">
		<div style="width:50%; height:100%; overflow:hidden; float:left" class="">
			<div style="height:95%; width:90%; margin:auto; margin-top:10%" class="">
				<div style="margin:5%">
					<?=$varfas[0]?>
				</div>
			</div>
		</div>	
		<div style="width:50%; height:100%; overflow:hidden; float:right" class="bgcol_1">
			<div style="width:90%; margin:auto; margin-top:10%; overflow:hidden" class="bgcol_2">
				<div style="margin:5%">
					<?=$varfas[1]?>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
