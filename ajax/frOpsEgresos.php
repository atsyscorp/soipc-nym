<?php
//ARCHIVO FUNCIONES FROPSEGRESOS.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_MedPay":
		$calFun = UpDate_MedPay();
		break;
	case "RegTercero":
		$calFun = RegTercero();
		break;
	default:
}
//--------------------------------------------------
//funcion para actualizar isntrumentos en seleccion de medio de pago
function UpDate_MedPay()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$listLoad = LoadConfTab($link, $strSQL);
}
//--------------------------------------------------
//Funcion para registrar tercero
function RegTercero()
{
	$tercero =$_GET['strSQL'];
	$varset = explode('.|.', $tercero);
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	$p=mysqli_query($link, "SELECT * FROM Terceros Where Identificacion = '". $varset[0] ."'") or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	if($count==0){
		$Sql = "INSERT INTO Terceros VALUES ('". $varset[0]. "', '', '', '". $varset[1]. "')";
		//Registro en base de datos	
		mysqli_query($link, $Sql);
	}
}
?>