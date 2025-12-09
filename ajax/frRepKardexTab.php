<?php
//ARCHIVO FUNCIONES FRREPKARDEX.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
include("../General.php");
//-----------------------------------------------------------------
//ob_start();
//-----------------------------------------
//Funcion que recibe variables para generar reporte
//function GenRepKardex()
//{
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
//	ob_implicit_flush(true);
//	ob_end_flush();	
	//--------------------------------------------
	$stSuc=$_GET['stSuc'];
	$stCaj=$_GET['stCaj'];
	$sSucN=$_GET['sSucN'];
	$sCajN=$_GET['sCajN'];
	$sType=$_GET['sType'];
	$SucVal=$_GET['SucVal'];
	$sIni=$_GET['sIni'];
	$sFin=$_GET['sFin'];
	$sInc=$_GET['sInc'];
	$sCur=$_GET['sCur'];
	$sExp=$_GET['sExp'];
	//--------------------------------------
	$link=Conectarse();
	//--------------------------------------
	//Genera encabezado
	$cabex = '';
	if($sExp != ''){
		$cabex = '<span class="fcont" style="font-size:18px">Kardex de divisas<br />
				<span class="fcont" style="font-size:16px">Sucursal: '. $SucVal .'<br />
				<span class="fcont" style="font-size:16px">Estación: '. $sCajN .'<br />
				<span class="fcont" style="font-size:16px">Moneda: '. $sCur .'<br />
				<span class="fcont" style="font-size:16px">Fecha inicial: '. $sIni .'<br />
				<span class="fcont" style="font-size:16px">Fecha final: '. $sFin .'<br />
				<span class="fcont" style="font-size:16px">Agrupación por: '. $sType .'<p></p>';
	}
	//----------------------------------------
	//Encabesado de tabla
	$funval = '<tr class="bgcol_6 fwhite">							
										<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Sucursal</td>							
										<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Estación</td>							
										<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Fecha</td>							
										<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Moneda</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Saldo inicial</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Total compras</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Precio compras</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Total ventas</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Precio ventas</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Otras entradas</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Otras salidas</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Saldo cierre</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Validación</td>							
									</tr>';
	//----------------------------------------------------------------------------
	//Obtiene la diferencia de fechas
	$dateI = strtotime($sIni);
	$dateF = strtotime($sFin);
	$diff= round(($dateF - $dateI) / 86400);	 
	$k = $diff;
	$j = 0;
//------------------------------------------
//Funcion de generación de reporte
function RepSucXEstY($link, $stSuc, $stCaj, $sSucN, $sCajN, $sDateK, $sInc, $sCur)
{
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	ob_implicit_flush(true);
	ob_end_flush();	
	//-------------------------------------------------------
	$vartot = '';
	//-------------------------------------------------------
    $strSQLM = "SELECT DISTINCT Moneda FROM Cierres_Ventanilla WHERE Moneda <> 'COP'". $stSuc. $sCur;
	$sRsetM=mysqli_query($link, $strSQLM) or die(mysqli_error($link));
	while($nRsetM=mysqli_fetch_array($sRsetM)){
		usleep(1000);
		$varfun = '';
		$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sSucN.'</td>';
		$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sCajN.'</td>';
		$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sDateK.'</td>';
		$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$nRsetM[0].'</td>';
		//-----------------------------------------------------
		//Captura saldo inicial y final
		$dSIni = 0;
		$dSFin = 0;
		$strSQLI = "SELECT SUM(Cantidad_Saldo_Inicial), SUM(Cantidad_Saldo_Cierre) FROM Cierres_Ventanilla WHERE Fecha = '". $sDateK ."' AND Moneda = '". $nRsetM[0] ."'". $stSuc. $stCaj;
		$sRsetI=mysqli_query($link, $strSQLI) or die(mysqli_error($link));
		while($nRsetI=mysqli_fetch_array($sRsetI)){
			if($nRsetI[0] == 0 || $nRsetI[0] == ''){
				$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">0</td>';
			} else {
				$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($nRsetI[0], 2, $GLdecsepa, $GLmilsepa) .'</td>';
			}
			$dSFin = $nRsetI[1];
			$dSIni = $nRsetI[0];
		}
		//----------------------------------------------------------------------------------
        //Captura los totales de compra, ventas y traslados
        //Compras
        $strSQLDC = "SELECT SUM(Cantidad), SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140' AND Fecha = '". $sDateK ."' AND Moneda = '". $nRsetM[0] ."' AND Estado_Operacion = 'ACTIVO'". $stSuc. $stCaj;
		$vCE = explode('.|.', CalSubTot($link, $strSQLDC));
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($vCE[0], 2, $GLdecsepa, $GLmilsepa) .'</td>';
		if($vCE[0] != 0){
			$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format(round($vCE[1] / $vCE[0], 2), 2, $GLdecsepa, $GLmilsepa) .'</td>';
		} else {
			$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">0</td>';
		}
        //Ventas
        $strSQLDV = "SELECT SUM(Cantidad), SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '141' AND Fecha = '". $sDateK ."' AND Moneda = '". $nRsetM[0] ."' AND Estado_Operacion = 'ACTIVO'". $stSuc. $stCaj;
		$vVE = explode('.|.', CalSubTot($link, $strSQLDV));
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($vVE[0], 2, $GLdecsepa, $GLmilsepa) .'</td>';
		if($vVE[0] != 0){
			$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format(round($vVE[1] / $vVE[0], 2), 2, $GLdecsepa, $GLmilsepa) .'</td>';
		} else {
			$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">0</td>';
		}
		//Traslados Entradas
        $strSQLTE = "SELECT SUM(Cantidad), SUM(Valor) FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '142' AND Fecha = '". $sDateK ."' AND Moneda = '". $nRsetM[0] ."' AND Estado = 'ACTIVO'". $stSuc. $stCaj;
		$vDE = explode('.|.', CalSubTot($link, $strSQLTE));
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($vDE[0], 2, $GLdecsepa, $GLmilsepa) .'</td>';
		//Traslados Salidas
        $strSQLTS = "SELECT SUM(Cantidad), SUM(Valor) FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '143' AND Fecha = '". $sDateK ."' AND Moneda = '". $nRsetM[0] ."' AND Estado = 'ACTIVO'". $stSuc. $stCaj;
		$vDS = explode('.|.', CalSubTot($link, $strSQLTS));
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($vDS[0], 2, $GLdecsepa, $GLmilsepa) .'</td>';
		//-----------------------------------------------------------------
        // Validación de cierres
        $dValC = 0;
        $dValC = $dSIni + $vCE[0] - $vVE[0] + $vDE[0] - $vDS[0];
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($dSFin, 2, $GLdecsepa, $GLmilsepa) .'</td>';
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($dValC, 2, $GLdecsepa, $GLmilsepa) .'</td>';
		//---------------------------------------
		$varfun = $varfun. '</tr>';
		//---------------------------------------
		if(round($dValC, 2) != round($dSFin, 2)){
			$varfun = '<tr valign="middle" class="fwhite bgcol_5">'. $varfun;
		} else if($dSIni == 0 && $vCE[0] == 0 && $vVE[0] == 0 && $vDE[0] == 0 && $vDS[0] == 0) { 
			if($nRsetM[0] == 'USD' && $sInc == 'SI'){
				$varfun = '<tr valign="middle" class="fcont trgray">'. str_replace('USD', 'NO OPS', $varfun);
			} else {
				$varfun = '';
			}
		} else {
			$varfun = '<tr valign="middle" class="fcont trnone">'. $varfun;
		}
		//$vartot = $vartot. $varfun;		
		echo $varfun;
	}
	//return $vartot;
}
//------------------------------------------------------
//Procedimiento para calculo de totales de entrada o salida 
function CalSubTot($link, $strSQL)
{
	//En vector se guarda el valor y la cantidad
	//-----------------------------------------------------------
	$funvalT = '';
	//-----------------------------------------------------------
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		if($n[0] == 0 || $n[0] == '')
		{
			$funvalT = '0.|.0';		
		} else {
			$funvalT = $n[0] .'.|.'. $n[1];		
		}
	}
	//--------------------------------------
	return $funvalT;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kardex de Divisas</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
</head>
<body class="bodygen" style="margin:0px 0px 0px 0px">
	<?=$cabex?>
	<table id="lstTot_Tab" cellpadding="0" cellspacing="0">
	<?=$funval?>
	<?
	for($i = 0; $i <= $k; $i++){
		$dDatV = date('Y-m-d', strtotime($sIni. ', +'. $i .' days'));
		//-----------------------------------------------------------
		//Dependiendo de tipo genera reporte
		if($SucVal == 'TODAS')
		{
			if($sType == 'TOTAL') {	//Total empresa
	?>
	<?=RepSucXEstY($link, $stSuc, $stCaj, $sSucN, $sCajN, $dDatV, $sInc, $sCur)?>
	<?php
			} else if ($sType == 'SUCURSAL') {	//Por sucursales
				$strSQL1 = "SELECT Codigo_Sucursal FROM Sucursales WHERE Codigo_Sucursal<>'GEN'";
				$sRset1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
				while($nRset1=mysqli_fetch_array($sRset1)){
	?>
	<?=RepSucXEstY($link, " AND Sucursal='". $nRset1[0] ."'", $stCaj, $nRset1[0], $sCajN, $dDatV, $sInc, $sCur)?>
	<?
				}
			} else if ($sType == 'CAJA') {	//Por estaciones
				$strSQL2 = "SELECT Codigo_Sucursal, Cajas FROM Sucursales WHERE Codigo_Sucursal<>'GEN'";
				$sRset2=mysqli_query($link, $strSQL2) or die(mysqli_error($link));
				while($nRset2=mysqli_fetch_array($sRset2)){
					$iCaja2 = intval($nRset2[1]);
					if($iCaja2 != 0)
					{
						for ($z = 1; $z <= $iCaja2; $z++) {
							$sCajaX = "0". $z;			
	?>
	<?=RepSucXEstY($link, " AND Sucursal='". $nRset2[0] ."'", " AND Estacion='". $sCajaX ."'", $nRset2[0], $sCajaX, $dDatV, $sInc, $sCur)?>
	<?
						}	
					}
				}
			}
		} else {
			if($sCajN == 'TODAS')
			{
				if($sType == 'SUCURSAL') {	//Por sucursales
	?>
	<?=RepSucXEstY($link, $stSuc, $stCaj, $sSucN, '', $dDatV, $sInc, $sCur)?>
	<?
				} else if ($sType == 'CAJA') {	//Por estaciones
					$strSQL3 = "SELECT Cajas FROM Sucursales WHERE Codigo_Sucursal='". $sSucN ."'";
					$sRset3=mysqli_query($link, $strSQL3) or die(mysqli_error($link));
					while($nRset3=mysqli_fetch_array($sRset3)){
						$iCaja1 = intval($nRset3[0]);
						if($iCaja1 != 0)
						{
							for ($z = 1; $z <= $iCaja1; $z++) {
								$sCajaN = "0". $z;			
	?>
	<?=RepSucXEstY($link, " AND Sucursal='". $sSucN ."'", " AND Estacion='". $sCajaN ."'", $sSucN, $sCajaN, $dDatV, $sInc, $sCur)?>
	<?
							}	
						}
					}
				}
			} else {
	?>
	<?=RepSucXEstY($link, $stSuc, $stCaj, $sSucN, $sCajN, $dDatV, $sInc, $sCur)?>
	<?
			}
		}
	}
	?>
</table>
</body>
</html>
