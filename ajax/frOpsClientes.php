<?php
//ARCHIVO FUNCIONES FROPSCLIENTES.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "UpDate_City":
		$calFun = UpDate_City();
		break;
	case "Find_Act":
		$calFun = Find_Act();
		break;
	case "Find_Pict":
		$calFun = Find_Pict();
		break;
	case "Find_Client":
		$calFun = Find_Client();
		break;
	case "Find_Hotel":
		$calFun = Find_Hotel();
		break;

	default:
}
//--------------------------------------------------
//funcion para actualizar isntrumentos en seleccion de medio de pago
function UpDate_City()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$listLoad = LoadConfTab($link, $strSQL);
}
//---------------------------------------------
//Funcion para buscar actividades
function Find_Act()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', '1');
}
//---------------------------------------------
//Funcion para buscar hoteles
function Find_Hotel()
{
	$strSQL = $_GET['strSQL'];
	$link = Conectarse();	
	//-----------------------------------------------------
	$funval = '';
	$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		$funval = $funval. '<tr class="fcont trwhite" style="cursor:pointer; text-align:left" onclick="selHotel(&#39;'. $n[0] .'&#39;, &#39;'. $n[1] .'&#39;, &#39;'. $n[2] .'&#39;)"><td class="celrow">'. $n[0] .'</td></tr>';
	}
	echo $funval;
}
//---------------------------------------------
//Funcion para buscar imagenes de cliente
function Find_Pict()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$listLoad_1 = LoadTable_1($link, $strSQL, 'true', '0');
}
//------------------------------------------
//Funcion para buscar buscar listado de clientes
function Find_Client()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$cliLoad = LoadTable($link, $strSQL, 'true', '0');
}




?>