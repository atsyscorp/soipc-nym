<?php
//ARCHIVO FUNCIONES FRCONFSEGMENTOS.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_Segs":
		$calFun = UpDate_Segs();
		break;
	default:
}
//----------------------------------------------
//funcion para actualizar tabla de segmentos
function UpDate_Segs()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="100%">
							<tr class="bgcol_6 fwhite" id="trSegTit">
								<td style="width:250px; text-align:left" class="celrow">Segmento de mercado</td>
								<td style="text-align:left" class="celrow">Grupo de segmentación</td>
							</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 0);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>