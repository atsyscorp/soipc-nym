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
	case "RepValida":
		$calFun = RepValida();
		break;


	default:
}
//-------------------------------------------
function RepValida()
{
	$strFEC =$_GET['swhere'];
	$strSUC =$_GET['stable']; 
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	$funval = '';
	//-------------------------------------------------
	//Abre las fechas
	$svec = explode(".|.", $strFEC);
	$dateI = strtotime($svec[0]);
	$dateF = strtotime($svec[1]);
	$diff= round(($dateF - $dateI) / 86400);	 
	$k = $diff;
	$j = 0;
	for($i = 0; $i <= $k; $i++){
		$funval = '';
		$dDatV = date('Y-m-d', strtotime($svec[0]. ', +'. $i .' days'));
		//-----------------------------------------------------------
		if($j==0){
			$sclas = "trwhite";
			$j=1;
		} else {
			$sclas = "trgray";
			$j=0;
		}
		//Compras
        $strSQLC = "SELECT COUNT(Identificacion) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140'". $strSUC ." AND Fecha = '". $dDatV ."'";
        $sTot[1] = ReadSerie_1($link, $strSQLC);
		//Ventas
		$strSQLV = str_replace('140', '141', $strSQLC);
		$sTot[2] = ReadSerie_1($link, $strSQLV);
		//Traslados
        $strSQLT = "SELECT COUNT(Identificacion) FROM Traslados_Ventanilla WHERE Fecha ='". $dDatV."'". $strSUC;  
		$sTot[3] = ReadSerie_1($link, $strSQLT);
		//Egresos
        $strSQLP = "SELECT COUNT(Identificacion) FROM Egresos_Ventanilla WHERE Fecha ='". $dDatV."'". $strSUC;  
		$sTot[4] = ReadSerie_1($link, $strSQLP);
		//----------------------------------------------------------------------
		//Hace consulta de cirre y nombre de cajero
        $strSQLI = "SELECT COUNT(Identificacion) FROM Cierres_Ventanilla WHERE Fecha ='". $dDatV."'". $strSUC;  
		$sTot[5] = ReadSerie_1($link, $strSQLI);
        $strSQLE = "SELECT DISTINCT Cajero FROM Cierres_Ventanilla WHERE Fecha ='". $dDatV."'". $strSUC;  
		$sTot[6] = ReadSerie_1($link, $strSQLE);
		//----------------------------------------------------------------------
        if($sTot[1] != "0" || $sTot[2] != "0" || $sTot[3] != "0" || $sTot[4] != "0" || $sTot[5] != "0"){
			if($sTot[5] == "0")
			{
				$funval = $funval. '<tr valign="middle" class="fwhite bgcol_5">';
				$sTot[5] = 'NO';
			} else {
				$funval = $funval. '<tr valign="middle" class="fcont '. $sclas .'">';
				$sTot[5] = 'OK';
			}
			$funval = $funval. '<td class="celrow" align="left">'. $dDatV .'</td>';
			for($l = 1; $l <= 6; $l++){
				if($l != 6){
					$funval = $funval. '<td class="celrow" align="center">'. $sTot[$l] .'</td>';
				} else {
					$funval = $funval. '<td class="celrow" align="left">'. $sTot[$l] .'</td>';
				}
			}
		}
		$funval = $funval. '</tr>';
		//-------------------------------------------------
		echo $funval;
	}
}
?>