<?php
//ARCHIVO FUNCIONES FRMAIN.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "Calc_Base":
		$calFun = Calc_Base();
		break;
	case "UpDate_Tasas":
		$calFun = UpDate_Tasas();
		break;
	case "Accept_Mod_Tasas":
		$calFun = Accept_Mod_Tasas();
		break;

	default:
}
//---------------------------------------------------------
//Funcion para calcular tasa promedio empresa
function Calc_Base()
{
	$scurr =$_GET['strSQL'];
	$strSQL = "SELECT MAX(Fecha) FROM Cierres_Ventanilla";
	$link = Conectarse();
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	$mensaje = '';
	if($count == 1){
		while($n=mysqli_fetch_array($p)){$sMaxDate = $n[0];}
        $strSQLB = "SELECT SUM(Valor_Entradas), SUM(Cantidad_Entradas) FROM Cierres_Ventanilla WHERE Moneda = '". $scurr. "' AND Fecha = '". $sMaxDate. "'";
		$val=mysqli_query($link, $strSQLB) or die(mysqli_error($link));
		$countv=mysqli_num_rows($val);
		if($countv == 1){
			while($v=mysqli_fetch_array($val)){
				if($v[1] != 0)
				{					
					$mensaje = $v[0] / $v[1];

				} else {
					$mensaje = 0;
				}	
			}
		} else {
			$mensaje = 0;
		}
	} else {
		$mensaje = 0;
	}
	echo $mensaje;
}
//----------------------------------------------
//funcion para actualizar tabla de tasas
function UpDate_Tasas()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	echo $mensaje = '<table cellpadding="0" cellspacing="0" width="555px">
					<tr class="bgcol_6 fwhite" id="trTasTit">
						<td style="width:50px; text-align:left" class="celrow">Sucursal</td>
						<td style="width:50px; text-align:left" class="celrow">Estación</td>
						<td style="width:55px; text-align:left" class="celrow">Moneda</td>
						<td style="width:80px; text-align:left" class="celrow">Tipo base</td>
						<td style="width:80px; text-align:left" class="celrow">Precio base</td>
						<td style="width:90px; text-align:left" class="celrow">Precio compra</td>
						<td style="width:70px; text-align:left" class="celrow">Tipo venta</td>
						<td style="width:80px; text-align:left" class="celrow">Precio venta</td>
						</tr>';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'true', 2);
	//---------------------------------------------------
	echo '</table>'; 	
}
//---------------------------------------
//Funcion para registrar el historial de modificación de tasa
function Accept_Mod_Tasas()
{
	//Captura variables
	$var[0]=$_GET['var0'];
	for($i = 1; $i <= $var[0]; $i++)
	{
		$var[$i] = $_GET['var'.$i];
	}
	$stable =$_GET['stable'];
	//--------------------------------------------------
	$link=Conectarse();
	//Construye string de registro
	$Sql = "INSERT INTO ". $stable. " VALUES (";
	for($i = 1; $i <= $var[0]; $i++)
	{
		if($i == $var[0]){
			$Sql = $Sql. "'". $var[$i]. "'";
		} else {
			$Sql = $Sql. "'". $var[$i]. "',";
		}
	}
	//--------------------------------
	//Obtiene fecha
	date_default_timezone_set('America/Bogota');
	$sfecha = date("Y-m-d H:i:s"); 
	$Sql = $Sql. ",'','".$sfecha."')";
	//Registro en base de datos	
	mysqli_query($link, $Sql); 
	//-------------------------------------------------
	$mensaje = 10;	//Mensaje exitoso
	echo $mensaje;
}
?>