<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("../General.php");
	//---------------------------------------------------
	//Captura id de operacion
	$var[1]=$_GET['var1'];
	$var[2]=$_GET['var2'];
	//Valida Acceso a archivo
	if($var[1] == ''){
		header("location:../index.php");
	}
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//---------------------------------------------------
	// Consulta información de operacion y crea variables
    $strSQLS = "SELECT * FROM Traslados_Ventanilla WHERE Identificacion = '".  $var[1]. "'";
	$p=mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for($i = 1; $i <= 19; $i++){
			if($n[$i] == '')
			{
				$varp[$i] = '<span style="color:#FFFFFF">.</span>';
			} else {
				$varp[$i] = $n[$i];
			}
		}
	}
	//---------------------------------------------
	//Busca prefijo de sucursal
    $strSQLP = "SELECT Prefijo FROM XConf_Consecutivos WHERE Identificacion = '".  $varp[1]. $varp[3]. "'";
	$pp=mysqli_query($link, $strSQLP) or die(mysqli_error($link));
	while($np=mysqli_fetch_array($pp)){$varc = $np[Prefijo];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Traslado Ventanilla</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
<style type="text/css">
@page{
    margin-left: 10px;
    margin-right: 0px;
    margin-top: 0px;
    margin-bottom: 0px;
}
</style>
</head>
<body class="bodygen" onload="">
	<div style="width:250px; margin-top:10px; margin-left:0px">
		<div style="text-align:center"><img src="../images/Logo_Factura.png" style="width:200px; height:auto" /></div>
		<div style="text-align:center">NIT 800.132.527-8</div>
		<div style="text-align:center">RÉGIMEN COMÚN</div>
		<?
			if($varp[2] == 'INGRESO'){
				$sdepara = ' DE ';
				$sordes = 'Origen';
				$semp = 'Cajero principal que recibe';
			} else {
				$sdepara = ' A ';
				$sordes = 'Destino';
				$semp = 'Cajero principal que envía';
			}
		?>
		<div style="text-align:center; border-bottom:#000000 solid 1px; border-top:#000000 solid 1px; padding-bottom:4px; padding-top:4px"><?=$varp[2]. $sdepara. $varp[9]?></div>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:5px">
			<tr style="height:25px; vertical-align:middle">
				<td style="text-align:center; width:135px"><?=$varp[6]?></td>
				<td style="text-align:center; width:115px; font-weight:bold; font-size:15px"><?=$varc. "-". $varp[7]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">SUCURSAL</td>
				<td style="text-align:left"><?=$varp[3]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Concepto</td>
				<td style="text-align:left"><?=$varp[2]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;"><?=$sordes?></td>
				<td style="text-align:left"><?=$varp[9]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Descripción</td>
				<td style="text-align:left"></td>
			</tr>
		</table>
		<div style="text-align:center; margin-top:5px; margin-bottom:20px"><?=$varp[18]?></div>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:5px">
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; width:135px">Moneda</td>
				<td style="text-align:left; width:115px; font-weight:bold;"><?=$varp[11]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Precio</td>
				<td style="text-align:left; font-weight:bold"><?=number_format($varp[12], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Cantidad</td>
				<td style="text-align:left; font-weight:bold">$<?=number_format($varp[13], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Valor</td>
				<td style="text-align:left;">$<?=number_format($varp[14], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Tipo</td>
				<td style="text-align:left;"><?=$varp[15]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Cuenta</td>
				<td style="text-align:left;"><?=$varp[17]?></td>
			</tr>
		</table>
		<div style="text-align:center; border-bottom:#000000 solid 1px; margin-top:60px"></div>
		<div style="text-align:center;"><?=$semp?></div>
		<?
			if($varp[2] == 'EGRESO'){
		?>
		<div style="text-align:center; border-bottom:#000000 solid 1px; margin-top:60px"></div>
		<div style="text-align:center;">Transportado por</div>
		<div style="text-align:center; border-bottom:#000000 solid 1px; margin-top:60px"></div>
		<div style="text-align:center;">Recibido por</div>
		<? } ?>
	</div>
</body>
<script>
window.onload = function() {
	GenPrint1('<?=$var[2]?>');
};
</script>
</html>
