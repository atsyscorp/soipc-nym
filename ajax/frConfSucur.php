<?php
//ARCHIVO FUNCIONES FRCONFSUCUR.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_Sucur":
		$calFun = UpDate_Sucur();
		break;
	default:
}
//----------------------------------------------
//funcion para actualizar tabla de Usuarios
function UpDate_Sucur()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="1570px">
							<tr class="bgcol_6 fwhite" id="trSucTit">
								<td style="width:95px; text-align:left; vertical-align:middle;" class="celrow">Código sucursal</td>
								<td style="width:130px; text-align:left; vertical-align:middle;" class="celrow">Nombre</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Matrícula</td>
								<td style="width:130px; text-align:left; vertical-align:middle;" class="celrow">Dirección</td>
								<td style="width:90px; text-align:left; vertical-align:middle;" class="celrow">Teléfono</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Ciudad</td>
								<td style="width:120px; text-align:left; vertical-align:middle;" class="celrow">Encargado</td>
								<td style="width:90px; text-align:left; vertical-align:middle;" class="celrow">Cantidad cajas</td>
								<td style="width:85px; text-align:left; vertical-align:middle;" class="celrow">Resolución</td>
								<td style="width:70px; text-align:left; vertical-align:middle;" class="celrow">Prefijo</td>
								<td style="width:100px; text-align:left; vertical-align:middle;" class="celrow">Fecha resolución</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Inicio factura</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Fin factura</td>
								<td style="width:140px; text-align:left; vertical-align:middle;" class="celrow">Observaciones</td>
								<td style="text-align:left; vertical-align:middle;" class="celrow">Dirección IP</td>
							</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 0);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>