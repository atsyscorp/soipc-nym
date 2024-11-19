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
	case "UpDate_Users":
		$calFun = UpDate_Users();
		break;
	default:
}
//----------------------------------------------
//funcion para actualizar tabla de Usuarios
function UpDate_Users()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="970px">
							<tr class="bgcol_6 fwhite" id="trTasTit">
								<td style="width:86px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol(&#39td1&#39)" onmouseout="menusol(&#39td1&#39)">Identificación
									<div style="position:relative; visibility:hidden" id="td1">
										<div style=" position:absolute; left:70px; top:-15px; line-height:0.9em">
											<div onclick="User_Order(&#39Identificacion&#39, &#39asc&#39)">&#9650</div>
											<div onclick="User_Order(&#39Identificacion&#39, &#39desc&#39)">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:125px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol(&#39td2&#39)" onmouseout="menusol(&#39td2&#39)">Nombre
									<div style="position:relative; visibility:hidden" id="td2">
										<div style=" position:absolute; left:90px; top:-15px; line-height:0.9em">
											<div onclick="User_Order(&#39Nombre&#39, &#39asc&#39)">&#9650</div>
											<div onclick="User_Order(&#39Nombre&#39, &#39desc&#39)">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:100px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol(&#39td3&#39)" onmouseout="menusol(&#39td3&#39)">Clave acceso
									<div style="position:relative; visibility:hidden" id="td3">
										<div style=" position:absolute; left:75px; top:-15px; line-height:0.9em">
											<div onclick="User_Order(&#39ClaveAcceso&#39, &#39asc&#39)">&#9650</div>
											<div onclick="User_Order(&#39ClaveAcceso&#39, &#39desc&#39)">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:95px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol(&#39td4&#39)" onmouseout="menusol(&#39td4&#39)">Cargo
									<div style="position:relative; visibility:hidden" id="td4">
										<div style=" position:absolute; left:80px; top:-15px; line-height:0.9em">
											<div onclick="User_Order(&#39Cargo&#39, &#39asc&#39)">&#9650</div>
											<div onclick="User_Order(&#39Cargo&#39, &#39desc&#39)">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:85px; text-align:left; vertical-align:middle" class="celrow">Nivel acceso</td>
								<td style="width:67px; text-align:left; vertical-align:middle" class="celrow">Teléfono</td>
								<td style="width:90px; text-align:left; vertical-align:middle" class="celrow">E-mail</td>
								<td style="width:120px; text-align:left; vertical-align:middle" class="celrow">Dirección</td>
								<td style="width:200px; text-align:left; vertical-align:middle" class="celrow">Observaciones</td>
							</tr>
';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 0);
	//---------------------------------------------------
	echo '</table>'; 	
}
?>