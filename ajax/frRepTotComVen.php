<?php
//ARCHIVO FUNCIONES FRREPTOTCOMVEN.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "RepTot":
		$calFun = RepTot();
		break;


	default:
}
//-------------------------------------------
function RepTot()
{
	$strFEC =$_GET['swhere'];
	$strSUC =$_GET['stable']; 
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	$funval = '';
	//-------------------------------------------------
	//Hace consulta de monedas
	$strSQLM = "SELECT DISTINCT Moneda FROM Operacion_Ventanilla WHERE ". $strFEC. $strSUC;
	$p=mysqli_query($link, $strSQLM) or die(mysqli_error($link));
	//-------------------------------------------------
	$k = 0;
	while($n=mysqli_fetch_array($p)){
		if($k==0){
			$sclas = "trwhite";
			$k=1;
		} else {
			$sclas = "trgray";
			$k=0;
		}
		$funval = $funval. '<tr valign="middle" class="fcont '. $sclas. '">';
		//-------------------------------------------------------------------------
		//Moneda
		$funval = $funval. '<td class="celrow" align="left">'. $n[0]. '</td>';
		//--------------------------------------------
		//Consulta totales
		$strSQLC = "SELECT SUM(Cantidad) FROM Operacion_Ventanilla WHERE Moneda = '". $n[0] ."' AND ". $strFEC ." AND Estado_Operacion = 'ACTIVO' AND Codigo_Operacion = '140'". $strSUC;
		$funval = $funval. '<td class="celrow" align="left">'. number_format(ReadSerie_1($link, $strSQLC), 2, $GLdecsepa, $GLmilsepa) .'</td>';
		$strSQLV = str_replace('140', '141', $strSQLC);
		$funval = $funval. '<td class="celrow" align="left">'. number_format(ReadSerie_1($link, $strSQLV), 2, $GLdecsepa, $GLmilsepa) .'</td>';
		//-------------------------------------------------------------------------
		$funval = $funval. '</tr>';
	}
	echo $funval;
}
?>