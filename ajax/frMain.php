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
	case "IniSesion":
		$calFun = IniSesion();
		break;
	case "EndSesion":
		$calFun = EndSesion();
		break;
	case "ShowAlerts":
		$calFun = ShowAlerts();
		break;
	case "ChanAlert":
		$calFun = ChanAlert();
		break;
	case "ShowChat":
		$calFun = ShowChat();
		break;
	case "ShowLogs":
		$calFun = ShowLogs();
		break;
	case "LoadOpsInf":
		$calFun = LoadOpsInf();
		break;
	case "ShowSals":
		$calFun = ShowSals();
		break;
	case "ShowTrasa":
		$calFun = ShowTrasa();
		break;
	case "SucCount":
		$calFun = SucCount();
		break;
	case "SucCount_1":
		$calFun = SucCount_1();
		break;
	case "ShowTasasCV":
		$calFun = ShowTasasCV();
		break;
	case "Tasa_Alert":
		$calFun = Tasa_Alert();
		break;
	case "Tasa_Alert_Mod":
		$calFun = Tasa_Alert_Mod();
		break;


	default:
}
//-----------------------------------------------------------------
//Funcion de inicio de Sesion
function IniSesion()
{
	//Captura variables
	for($i = 0; $i <= 6; $i++)
	{
		$var[$i] = $_GET['var'.$i];
	}
	//-------------------------------------------------
	//Captura IP 
	$ipadrs = $_SERVER['REMOTE_ADDR'];
   	$link=Conectarse();
	//--------------------------------------------------	
	
	//Valida si existe Sesion del mismo usuario con la misma ip. En caso no existe, hace registro de Sesion
   	$link=Conectarse();
	$p = mysqli_query($link, "SELECT * FROM Sesion where Identificacion_Usuario = '$var[1]' AND Ip_Adress = '$ipadrs'") or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	if($count == 0){
		//---------------------------------------------------------
		$Sql="INSERT INTO Sesion VALUES ('$var[0]', '$var[1]', '$var[2]', '$var[3]', '$var[4]', '$var[5]', '$var[6]', '$ipadrs')";
   		//Actualiza la base de datos
		mysqli_query($link, $Sql); 
	}
}
//-----------------------------------------------------------------
//Funcion para cerrar Sesion
function EndSesion()
{
	//Captura variables
	$var[0]=$_GET['var0'];
	//Elimina registro
	$Sql="DELETE FROM Sesion where Identificacion='$var[0]'";
   	$link=Conectarse();
	mysqli_query($link, $Sql); 
}
//-----------------------------------------------------------------
function ShowAlerts()
{
	$funval = '<div style="padding:4px" class="fgreen dlin_4"><b>Alertas de usuario</b></div>';
	//Captura variables
	$stable =$_GET['stable'];
	$swhere =$_GET['swhere'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link, "SELECT * FROM ". $stable ." WHERE ". $swhere) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		$funval = $funval. '<a href="Alertas.php?var0='.$n[Identificacion].'" target="_blank"><div style="padding:4px" class="fgreen dlin_4 trnone" onclick="ChanAlert('. $n[Identificacion]. ')">'. $n[Titulo]. '<br /><span class="fcont" style="font-size:10px"> ('. $n[Fecha]. ')</span></div></a>';			
	}
	//--------------------------------------------------
	echo $funval;
}
//-----------------------------------------------------------------
//Funcion para mostrar listado de traslados pendientes
function ShowTrasa()
{
	$funval = '<div style="padding:4px;" class="fgreen dlin_4"><b>Ajustes pendientes</b></div>';
	//Captura variables
	$stable =$_GET['stable'];
	$swhere =$_GET['swhere'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p = mysqli_query($link, "SELECT * FROM Traslados_Ventanilla_Temp WHERE ". $swhere) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		$funval = $funval. '<div style="padding:4px; cursor:pointer" class="fgreen dlin_4 trnone" onclick="ShowAjuste(this)" name="'. $n[Identificacion].'.|.'.$stable .'">Desde '. $n[Sucursal]. '<br /><span class="fcont" style="font-size:12px"><b>'. $n[Moneda]. '</b> '. number_format($n[Cantidad], 2, $GLdecsepa, $GLmilsepa). '</span></div>';			
	}
	echo $funval;
}
//-----------------------------------------------------------------
//Funcion para cambiar estado de alerta a leido = SI
function ChanAlert()
{
	$sid =$_GET['var0'];
	//---------------------------------------------
	$Sql="UPDATE Alertas_Usuarios SET Leido='SI' WHERE Identificacion = '$sid'";
	$link=Conectarse();
    mysqli_query($link, $Sql); 
}
//----------------------------------------------
//Funcion para mostrar registros de char
function ShowChat()
{
	$funval = '';
	//Captura variables
	$stable =$_GET['stable'];
	$swhere =$_GET['swhere'];
	$sparam = explode("|", $stable);
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta los ultimos registros
	$p = mysqli_query($link, "SELECT * FROM Chat WHERE ". $swhere. " Order By Identificacion asc limit ". $sparam[0]. ", ". $sparam[1]) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		$funval = $funval. '<div style="margin:4px" class="fcont"><b class="fgreen">'. $n[Sucursal]. ' - '. $n[Usuario]. '</b> '. $n[Contenido]. '<br /><span style="font-size:10px">('.$n[Hora]. ')</span></div>';
	}
	//--------------------------------------------------
	echo $funval;
}
//----------------------------------------------
//Funcion para mostrar registros de char
function ShowLogs()
{
	$funval = '';
	//Captura variables
	$stable =$_GET['stable'];
	$swhere =$_GET['swhere'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta los ultimos registros
	$p=mysqli_query($link, "SELECT * FROM Sesion") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		$funval = $funval. '<div style="margin:4px" class="fcont"><b class="fgreen">'. $n[Sucursal]. ' - '. $n[Nombre]. '</b><br /><span style="font-size:10px">('. $n[Fecha]. ' - '.   $n[Hora]. ')</span></div>';
	}
	//--------------------------------------------------
	echo $funval;
}
//----------------------------------------------
//Funcion para actualizar tabla de operaciones en main
function LoadOpsInf()
{
	$strSQL =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	$listLoad = LoadTable($link, $strSQL, 'false', 0);
	//---------------------------------------------------
}
//--------------------------------------------
//Funcion para regresar la cantidad de sucursales
function SucCount()
{
	//Captura variables
	$sSuc =$_GET['strSQL'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p = mysqli_query($link, "SELECT DISTINCT Sucursal FROM Arqueo_Ventanilla Where Sucursal <> '". $sSuc. "'") or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	echo $count;
}
//------------------------------------------------------
//Funcion para actualizacion de saldos en tiempo real
function ShowSals()
{
	$sSuc =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	//-----------------------------------------------
	$strSQL = "Select Distinct Sucursal From Arqueo_Ventanilla Where Sucursal <> '". $sSuc. "' Order By Sucursal";
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = 0;
	while($n=mysqli_fetch_array($p)){
		$i++;
		$SucCons = $n[0];
		echo '<div class="bgcol_6 fwhite dlin_9" style="padding:3px; padding-left:8px; cursor:pointer" onclick="MenuAccess(&#39Arq'. $i .'&#39)"><b>'. $n[0]. ' &#9660</b></div>';
		echo '<div id="Arq'.$i .'" style="height:0px; overflow:hidden; width:100%">';
		echo '<table cellpadding="0" cellspacing="0" width="100%;">';
		echo '<tr class="bgcol fcont">
							<td style="width:12%; text-align:left" class="celrow"></td>
							<td style="width:26.5%; text-align:left" class="celrow">Compra</td>
							<td style="width:26.5%; text-align:left" class="celrow">Venta</td>
							<td style="width:35%; text-align:left" class="celrow">Saldo</td>
						</tr>';

		$strSQL1 = '';
		$strSQL1 = "SELECT Moneda, FORMAT(SUM(Compras), 0) AS Compras, FORMAT(SUM(Ventas), 0) AS Ventas, FORMAT(SUM(Saldo_Final), 0) AS Saldo_Final FROM Arqueo_Ventanilla WHERE Sucursal = '". $SucCons. "' GROUP BY Moneda ORDER BY CASE WHEN Moneda = 'USD' THEN 1 WHEN Moneda = 'EUR' THEN 2 ELSE 3 END, Compras DESC";

		$listLoad = LoadTable($link, $strSQL1, 'true', 0);
		echo '</table></div>';
	}
	//---------------------------------------------
	//Consulta total empresa
	$i++;
	echo '<div class="bgcol_6 fwhite dlin_9" style="padding:3px; padding-left:8px; cursor:pointer" onclick="MenuAccess(&#39Arq'. $i .'&#39)"><b>Total Empresa &#9660</b></div>';
	echo '<div id="Arq'.$i .'" style="height:0px; overflow:hidden; width:100%">';
	echo '<table cellpadding="0" cellspacing="0" width="100%;">';
	echo '<tr class="bgcol fcont">
						<td style="width:12%; text-align:left" class="celrow"></td>
						<td style="width:26.5%; text-align:left" class="celrow">Compra</td>
						<td style="width:26.5%; text-align:left" class="celrow">Venta</td>
						<td style="width:35%; text-align:left" class="celrow">Saldo</td>
					</tr>';
	$strSQL1 = '';
	$strSQL1 = "SELECT Moneda, FORMAT(SUM(Compras), 0) AS Compras, FORMAT(SUM(Ventas), 0) AS Ventas, FORMAT(SUM(Saldo_Final), 0) AS Saldo_Final FROM Arqueo_Ventanilla WHERE Sucursal <> 'GEN' GROUP BY Moneda ORDER BY CASE WHEN Moneda = 'USD' THEN 1 WHEN Moneda = 'EUR' THEN 2 ELSE 3 END, Compras DESC";
;	
	$listLoad = LoadTable($link, $strSQL1, 'true', 0);
	//--------------------------------------------
	echo '</table></div>';
}
//--------------------------------------------
//Funcion para regresar la cantidad de sucursales
function SucCount_1()
{
	//Captura variables
	$sSuc =$_GET['strSQL'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link, "SELECT DISTINCT Sucursal From Tasas Where Sucursal <> '". $sSuc. "'") or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	echo $count;
}
//------------------------------------------------------
//Funcion para actualizacion de tasas en tiempo real
function ShowTasasCV()
{
	$sSuc =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	//-----------------------------------------------
	//Cambio#1-servidor ver" documentos claves  "cambios en el codigo"	$strSQL = "Select Distinct Sucursal From Tasas Where Sucursal <> '". $sSuc. "'  Order By Sucursal";
    $strSQL = "Select Distinct Sucursal From Tasas Where Sucursal <> '". $sSuc. "' or Sucursal = '". $sSuc. "' Order By Sucursal";
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = 0;
	while($n=mysqli_fetch_array($p)){
		$i++;
		$SucCons = $n[0];
		echo '<div class="bgcol_6 fwhite dlin_9" style="padding:3px; padding-left:8px; cursor:pointer" onclick="MenuAccess(&#39Tsa'. $i .'&#39)"><b>'. $n[0]. ' &#9660</b></div>';
		echo '<div id="Tsa'.$i .'" style="height:0px; overflow:hidden; width:100%">';
		echo '<table cellpadding="0" cellspacing="0" width="100%;">';
		echo '<tr class="bgcol fcont">
							<td style="width:15%; text-align:left" class="celrow"></td>
							<td style="width:42.5%; text-align:left" class="celrow">Compra</td>
							<td style="width:42.5%; text-align:left" class="celrow">Venta</td>
						</tr>';
		$strSQL1 = '';
		$strSQL1 = "SELECT Moneda, FORMAT(Precio_Compra, 2) AS Compra, FORMAT(Precio_Venta, 2) AS Venta FROM Tasas WHERE Sucursal = '". $SucCons. "' AND Estacion = '01' ORDER BY CASE WHEN Moneda = 'USD' THEN 1 WHEN Moneda = 'EUR' THEN 2 ELSE 3 END, Moneda ASC";
	
		$listLoad = LoadTable($link, $strSQL1, 'true', 0);
		echo '</table></div>';
	}
}
//------------------------------------
//Función para consulta de alerta de tasas
function Tasa_Alert()
{
	$sSuc =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	//-----------------------------------------------
	$strSQL = "SELECT Moneda, FORMAT(Precio_Compra, 2) AS Compra, FORMAT(Precio_Venta, 2) As Venta FROM Tasas_Mod WHERE Sucursal ='". $sSuc. "' AND Estacion='01' AND Alerta='' Order By Moneda asc";	
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	if($count != 0){
		while($n=mysqli_fetch_array($p)){
			echo '<tr><td class="celrow_1">'.$n[0].'</td><td class="celrow_1">'.$n[1].'</td><td class="celrow_1">'.$n[2].'</td></tr>';
		}
	} else {
		echo '100';	
	}
}
//----------------------------------
//Funcion para cambio de alerta de tasas
function Tasa_Alert_Mod()
{
	$sSuc =$_GET['strSQL'];
	$mensaje = '';
	$link = Conectarse();	
	//-----------------------------------------------
	$strSQL = "UPDATE Tasas_Mod SET Alerta='OK' WHERE Sucursal ='". $sSuc. "' AND Estacion='01' AND Alerta=''";	
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
}
?>