<?php
//ARCHIVO FUNCIONES FRCONFCOSTCEN.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_Costs":
		$calFun = UpDate_Costs();
		break;
	default:
}
//----------------------------------------------
//funcion para actualizar tabla de Usuarios
function UpDate_Costs()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="265px">
						<tr class="bgcol_6 fwhite" id="trTasTit">
							<td style="width:265px; text-align:left; vertical-align:middle;" class="celrow">Centros de costos</td>
						</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 0);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>