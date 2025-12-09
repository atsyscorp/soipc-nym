<?php
//ARCHIVO FUNCIONES PANTALLA TASAS
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "ShowTasas":
		$calFun = ShowTasas();
		break;

	default:
}
//--------------------------------------------
//Funcion formato de precios
function FPrice($sPr)
{
	if($sPr > 999)
	{
		$lprecio = "$". number_format($sPr, 0, $GLdecsepa, $GLmilsepa);
	} else if($sPr > 99) {
		$lprecio = "$". number_format($sPr, 1, $GLdecsepa, $GLmilsepa);
	} else {
		$lprecio = "$". number_format($sPr, 2, $GLdecsepa, $GLmilsepa);
	} 
	return $lprecio;
}
//--------------------------------------------------------
//Funcion caja de tasas
function TasaBox($sBack, $sHei, $sCur, $sBuy, $sSell)
{
	$sfun = '<div style="width:100%" class="'. $sBack. '">
					<div style="width:34%; float:left; text-align:left">
						<div style="float:left; width:51.5%; text-align:center"><img src="banderas/'. $sCur. '.png" style="width:100%; height:auto; margin:0%" /></div>
						<div style="float:left; width:38.5%; text-align:left; margin-left:3%; margin-top:0%" class="txgen_1 txblack"><span style="font-size:3.6vw"><b>'.$sCur.'</b></span></div>
						<div style="clear:both"></div>
					</div>
					<div style="width:33%; float:left; text-align:center; margin-top:0%" class="txgen_1 txblack"><span style="font-size:3.6vw"><b>'.$sBuy.'</b></span></span>
					</div>						
					<div style="width:33%; float:left; text-align:center; margin-top:0%" class="txgen_1 txblack"><span style="font-size:3.6vw"><b>'.$sSell.'</b></span></span>
					</div>						
					<div style="clear:both"></div>
				</div>';
	//----------------------------------------------------
	return $sfun;
}

//Funcion para actualizacion de tasas en tiempo real
function ShowTasas()
{
	$sSuc =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	//-----------------------------------------------
	//Dolar
	$strSQLU = "SELECT * FROM Tasas Where Sucursal = '". $sSuc. "' AND Estacion='01' AND Moneda='USD'";
	$u=mysqli_query($link, $strSQLU) or die(mysqli_error($link));
	while($nu=mysqli_fetch_array($u)){
		echo TasaBox('bgcol_3 dlin_4', '', $nu[2], FPrice($nu[5]), FPrice($nu[7]));
	}
	//Euro
	$strSQLE = "SELECT * FROM Tasas Where Sucursal = '". $sSuc. "' AND Estacion='01' AND Moneda='EUR'";
	$e=mysqli_query($link, $strSQLE) or die(mysqli_error($link));
	while($ne=mysqli_fetch_array($e)){
		echo TasaBox('bgcol_3 dlin_4', '', $ne[2], FPrice($ne[5]), FPrice($ne[7]));
	}
	//El resto de monedas
	$strSQLG = "SELECT * FROM Tasas Where Sucursal = '". $sSuc. "' AND Estacion='01' AND Moneda<>'EUR' AND Moneda<>'USD' AND Moneda<>'TUSD' Order by Moneda";
	$g=mysqli_query($link, $strSQLG) or die(mysqli_error($link));
	$k = 0;
	while($ng=mysqli_fetch_array($g)){
			echo TasaBox('bgcol_3 dlin_4', '', $ng[2], FPrice($ng[5]), FPrice($ng[7]));
	}
}



?>