<?php
//ARCHIVO FUNCIONES FRCONFBANKS.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_Banks":
		$calFun = UpDate_Banks();
		break;
	default:
}
//----------------------------------------------
//funcion para actualizar tabla de Usuarios
function UpDate_Banks()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="930px">
							<tr class="bgcol_6 fwhite" id="trTasTit">
								<td style="width:85px; text-align:left; vertical-align:middle;" class="celrow">Número</td>
								<td style="width:90px; text-align:left; vertical-align:middle;" class="celrow">Banco</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">NIT Banco</td>
								<td style="width:65px; text-align:left; vertical-align:middle;" class="celrow">Código</td>
								<td style="width:75px; text-align:left; vertical-align:middle;" class="celrow">Tipo cuenta</td>
								<td style="width:85px; text-align:left; vertical-align:middle;" class="celrow">Sucursal</td>
								<td style="width:95px; text-align:left; vertical-align:middle;" class="celrow">Cuenta Puc</td>
								<td style="width:75px; text-align:left; vertical-align:middle;" class="celrow">Contacto</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Teléfonos</td>
								<td style="width:200px; text-align:left; vertical-align:middle;" class="celrow">Observaciones</td>
							</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 0);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>