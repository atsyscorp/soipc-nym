<?php
//ARCHIVO FUNCIONES FROPSAJUSTES.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "Add_OrgDest":
		$calFun = Add_OrgDest();
		break;


	default:
}
//--------------------------------------------------
//funcion para actualizar isntrumentos en seleccion de medio de pago
function Add_OrgDest()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$listLoad = LoadConfTab($link, $strSQL);
}





?>