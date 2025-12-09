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
	case "UpDate_Alerts":
		$calFun = UpDate_Alerts();
		break;
	default:
}
//----------------------------------------------
//funcion para actualizar tabla de Usuarios
function UpDate_Alerts()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="1170px">
							<tr class="bgcol_6 fwhite" id="trSucTit">
								<td style="width:100px; max-width:100px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Identificación</td>
								<td style="width:110px; max-width:100px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Creado por</td>
								<td style="width:80px; max-width:80px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Fecha</td>
								<td style="width:200px; max-width:200px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Título</td>
								<td style="width:370px; max-width:370px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Contenido</td>
								<td style="width:80px; max-width:80px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Sucursal</td>
								<td style="width:170px; max-width:170px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Usuario</td>
								<td style="width:60px; max-width:60px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Leído</td>
							</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable_3($link, $strSQL, 'true', 0);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>