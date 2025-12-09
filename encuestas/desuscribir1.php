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
//Captura la variable id cliente 
$cliented = $_GET['cliente'];
//------------------------------------------------------------
// Valida si la variable es vacia
if($cliented == ''){
	header("Location: error.php"); 
	exit;
}
//-----------------------------------------------------------
// Valida que el id cliente exista
$strSQL1 = "SELECT * FROM Clientes WHERE Identificacion = '". $cliented ."'";
$p1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
$count=mysqli_num_rows($p1);
// Si no existe redirecciona a pagina de error
if($count == 0){
	header("Location: error.php"); 
	exit;
} else {
	session_start();
	$_SESSION['Id_Cliente_d'] = $cliented;
	//Consulta si ya existe registro de desuscrito
	// En caso que el usuario de F5, de esta manera no generar un doble registro dentro de la bd
	$strSQL2 = "SELECT * FROM Desuscritos WHERE Id_Cliente = '". $cliented ."'";
	$p2=mysqli_query($link, $strSQL2) or die(mysqli_error($link));
	$count2=mysqli_num_rows($p2);
	if($count2 == 0){
		//crea registro
		$strSQL3 = "INSERT INTO Desuscritos VALUES('". $cliented ."')";
		$p3=mysqli_query($link, $strSQL3) or die(mysqli_error($link));
	}
	// Redirecciona a pagina de informacion
	header("Location: desuscribir.php");
}
?>