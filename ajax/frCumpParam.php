<?php
//ARCHIVO FUNCIONES FRCUMPPARAM.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_cbSeg":
		$calFun = UpDate_cbSeg();
		break;
	case "UpDate_Param":
		$calFun = UpDate_Param();
		break;
	default:
}
//---------------------------------------------------------
//funcion para actualizar combo de segmentos
function UpDate_cbSeg()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$listLoad = LoadConfTab($link, $strSQL);
}
//----------------------------------------------
//funcion para actualizar tabla de tasas
function UpDate_Param()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="750px">
							<tr class="bgcol_6 fwhite" id="trParTit">
								<td style="width:100px; text-align:left" class="celrow">Operación</td>
								<td style="width:95px; text-align:left" class="celrow">Grupo segmento</td>
								<td style="width:95px; text-align:left" class="celrow">Segmento</td>
								<td style="width:80px; text-align:left" class="celrow">Frecuencia</td>
								<td style="width:80px; text-align:left" class="celrow">Acumulado día</td>
								<td style="width:80px; text-align:left" class="celrow">Acumulado mes</td>
								<td style="width:80px; text-align:left" class="celrow">Tope diario</td>
								<td style="text-align:left" class="celrow">Tope mensual</td>
							</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 2);
	//---------------------------------------------------
	echo '</table>'; 	
}


?>