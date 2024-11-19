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
// ----------------------
session_start();
//Captura las variables de Sesion
$cliente = $_SESSION['Id_Cliente_e'];
$factura = $_SESSION['Id_Factura_e'];
//Captura las respuestas del formulario
$rdsatisfaccion = $_POST['rdsatisfaccion'];
$txsatisfaccion = $_POST['txsatisfaccion'];
//-----------------------------------------------------
// Valida si las variables de Sesion estan vacias
if(($cliente == '') || ($factura == '')){
	header("Location: error.php"); 
	exit; 
}
//-----------------------------------------------------
// Valida si las respuestas estan vacias
if(($rdsatisfaccion == '') || ($txsatisfaccion == '')){
	header("Location: error.php");
	exit; 
}
//-----------------------------------------------------
//Se realiza la consulta de informacion del cliente
$strSQL1 = "SELECT * FROM Clientes WHERE Identificacion = '". $cliente ."'";
$p1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
$q1=mysqli_fetch_array($p1);
//-----------------------------------------------------
//Se realiza la consulta de la informacion de la factura
$strSQL2 = "SELECT * FROM Operacion_Ventanilla WHERE Identificacion = '". $factura ."'";
$p2=mysqli_query($link, $strSQL2) or die(mysqli_error($link));
$q2=mysqli_fetch_array($p2);
//-----------------------------------------------------
//Se inserta el registro en la base de datos
$regid = DateId().rand(0, 9999);
$strSQL3 = "INSERT INTO Encuestas VALUES('". $regid ."','". $factura ."','". $q2['Tipo_Operacion'] ."','". $q2['Sucursal'] ."','". $q2['Cajero'] ."','". $q2['Fecha'] ."','". $q2['Hora'] ."','". $q2['Consecutivo'] ."','". $q2['Moneda'] ."','". $q2['Cantidad'] ."','". $cliente ."','". $q1['Nombre_Completo'] ."','". $q1['Telefono'] ."','". $q1['Celular'] ."','". $q1['EMail'] ."','". $rdsatisfaccion ."','". $txsatisfaccion ."')";
$p3 = mysqli_query($link, $strSQL3) or die(mysqli_error($link));
//Redirecciona a pagina de gracias
header("Location: gracias.php"); 
?>