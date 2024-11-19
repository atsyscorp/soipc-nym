<?php
//ARCHIVO FUNCIONES FROPSANULA.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "Gen_Find_Anula":
		$calFun = Gen_Find_Anula();
		break;
	case "List_Cierres":
		$calFun = List_Cierres();
		break;
	case "Anula_Cierres":
		$calFun = List_Cierres();
		break;

	default:
}
//-------------------------------------------------
//Funcion para buscar y poner registros en controles
function Gen_Find_Anula()
{
	$strSQL =$_GET['strSQL'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	$mensaje = '';
	$sregm = '';
	if($count == 1){
		$ifi=mysqli_num_fields($p);
		while($n=mysqli_fetch_array($p)){
			for($i = 1; $i <= $ifi; $i++)
			{
				if($i == $ifi){
					$sregm = $sregm. $n[$i - 1]; 
				} else {
					$sregm = $sregm. $n[$i - 1]. '.|.'; 
				}
			}
		}
		//-------------------------------------------------
		$mensaje = $sregm;	//Mensaje exitoso
		echo $mensaje;
	} else {
		echo $mensaje;
	}
}
//-------------------------------------------
//Funcion para generar listado de cierre anulados
function List_Cierres()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="284px">
					<tr class="bgcol_6 fwhite">
						<td style="text-align:left" class="celrow">Listado fechas abiertas</td>
					</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 1);
	//---------------------------------------------------
	echo '</table>'; 	
}
//------------------------------------------
//Funcion para eliminar cierre de ventanilla
function Anula_Cierres()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
   	$link = Conectarse();
	mysqli_query($link, $strSQL);
}









?>