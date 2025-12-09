<?php
//ARCHIVO FUNCIONES FRUSERS.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_Conta":
		$calFun = UpDate_Conta();
		break;
	default:
}
//----------------------------------------------
//funcion para actualizar tabla de Usuarios
function UpDate_Conta()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="650px">
							<tr class="bgcol_6 fwhite" id="trConTit">
								<td style="width:70px; text-align:left; vertical-align:middle;" class="celrow">Sucursal</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Copias compra</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Copias venta</td>
								<td style="width:120px; text-align:left; vertical-align:middle;" class="celrow">Fin consecutivo</td>
								<td style="width:130px; text-align:left; vertical-align:middle;" class="celrow">Resolución facturación</td>
								<td style="text-align:left; vertical-align:middle;" class="celrow">Vencimiento resolución</td>
							</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 0);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>