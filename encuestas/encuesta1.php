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
//Captura la variable id cliente y id factura
$cliente = $_GET['cliente'];
$factura = $_GET['factura'];
//------------------------------------------------------------
// Valida si las variable es vacia
if(($cliente == '') || ($factura == '')){
	header("Location: error.php"); 
	exit;
}
//-----------------------------------------------------------
// Valida que el registro exista
$strSQL1 = "SELECT * FROM Operacion_Ventanilla WHERE Identificacion = '". $factura ."' AND Documento_Declarante = '". $cliente ."'";
$p1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
$count=mysqli_num_rows($p1);
// Si no existe redirecciona a pagina de error
if($count == 0){
	// Envia a pagina error
	header("Location: error.php");
	exit;
} else {
	// Crea variables de Sesion y envia a pagina encuesta
	session_start();
	$_SESSION['Id_Cliente_e'] = $cliente;
	$_SESSION['Id_Factura_e'] = $factura;
	header("Location: encuesta.php");
}
?>