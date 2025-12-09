<?php
//ARCHIVO FUNCIONES FRCONFSERIE.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_Serie":
		$calFun = UpDate_Serie();
		break;

	default:
}
//---------------------------------------------------------
//funcion para actualizar tabla de tasas
function UpDate_Serie()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="875px">
							<tr class="bgcol_6 fwhite" id="trSerTit">
								<td style="width:45px; text-align:left" class="celrow">Id</td>
								<td style="width:50px; text-align:left" class="celrow">Sucursal</td>
								<td style="width:185px; text-align:left" class="celrow">Documento</td>
								<td style="width:50px; text-align:left" class="celrow">Código</td>
								<td style="width:85px; text-align:left" class="celrow">Area</td>
								<td style="width:55px; text-align:left" class="celrow">Prefijo</td>
								<td style="width:85px; text-align:left" class="celrow">Consecutivo</td>
								<td style="width:120px; text-align:left" class="celrow">Formato</td>
								<td style="text-align:left" class="celrow">Tabla</td>
							</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 2);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>