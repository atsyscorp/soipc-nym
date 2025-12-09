<?php  
//=============================================================
// Cabezeras
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
error_reporting(E_ALL ^ E_NOTICE);
// Include General
include('../General.php');
// Inicia Conexion bd
$link=Conectarse();
$regid = DateId().rand(0, 9999);
$strSQL3 = "INSERT INTO Desuscritos VALUES('". $regid ."')";
$p3 = mysqli_query($link, $strSQL3) or die(mysqli_error($link));
?>