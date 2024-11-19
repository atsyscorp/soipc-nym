<?php
//ARCHIVO FUNCIONES FRREPCONSOPS.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
include("../General.php");
//-----------------------------------------------------------------
ob_start();
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "LoadRepTable":
		$calFun = LoadRepTable();
		break;
	case "GenRepCons":
		$calFun = GenRepCons();
		break;
	case "AddTotals":
		$calFun = AddTotals();
		break;
	case "ModArqueo":
		$calFun = ModArqueo();
		break;
	case "InicioRep":
		$calFun = InicioRep();
		break;


	default:
}
//--------------------------------------------
//Funcion de espara para inicio de reporte
function InicioRep()
{
	sleep(2);
	echo 'Iniciando reporte...';
	sleep(2);
}
//-------------------------------------------
//Funcion consulta de tablas
function LoadRepTable()
{
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	//--------------------------------------------------
	$strSQL =$_GET['swhere'];
	$iField =$_GET['stable']; 
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	$mensaje = '';
	//-------------------------------------------------
	//Consulta
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = mysqli_num_fields($p);
	//Encabezado de tabla
	$mensaje = '<table cellpadding="0" cellspacing="0">
					<tr class="bgcol_6 fwhite">';
	for ($j = 0; $j <= $i - 1; $j++) {
		if(mysqli_fetch_field_direct($p, $j)->type == 'real')
		{
			$mensaje = $mensaje. '<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">'. mysqli_fetch_field_direct($p, $j)->name. '</td>';
		} else {
			$mensaje = $mensaje. '<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">'. mysqli_fetch_field_direct($p, $j)->name. '</td>';
		}
	}
	$mensaje = $mensaje. '</tr>';
	//-------------------------------------------
	//Datos de tabla
	while($n=mysqli_fetch_array($p)){
		if($n[intval($iField)] == 'ANULADO')
		{
			$mensaje = $mensaje. '<tr valign="middle" class="fwhite bgcol_5">';
		} else {
			$mensaje = $mensaje. '<tr valign="middle" class="fcont trnone">';
		}
		for ($j = 0; $j <= $i - 1; $j++) {
			if(mysqli_fetch_field_direct($p, $j)->type == 'real')
			{
				$mensaje = $mensaje. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($n[$j], 2, $GLdecsepa, $GLmilsepa). '</td>';
			} else {
				$mensaje = $mensaje. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px" title="'. $n[$j]. '">'. $n[$j]. '</td>';
			}
		}
		$mensaje = $mensaje. '</tr>';
	}
	$mensaje = $mensaje. '</table>';
	echo $mensaje;
}
//-----------------------------------------
$iRow; //Contador de filas en reporte
//Funcion que recibe variables para generar reporte
function GenRepCons()
{
	set_time_limit(0); 
	ob_implicit_flush(true);
	ob_end_flush();	
	//--------------------------------------------
	$stSuc=$_GET['stSuc'];
	$stCaj=$_GET['stCaj'];
	$sSucN=$_GET['sSucN'];
	$sCajN=$_GET['sCajN'];
	$sType=$_GET['sType'];
	$SucVal=$_GET['SucVal'];
	$sIni=$_GET['sIni'];
	$sFin=$_GET['sFin'];
	$sUser=$_GET['sCajero'];
	//--------------------------------------
	$link=Conectarse();
	//--------------------------------------
	//Encabesado de tabla
	$funval = '<table id="lstTot_Tab" cellpadding="0" cellspacing="0">
									<tr class="bgcol_6 fwhite">							
										<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Sucursal</td>							
										<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Estación</td>							
										<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Fecha</td>							
										<td class="celrow" style="width:72px; text-align:left; max-width:72px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:72px">Último cierre</td>							
										<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Moneda</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Saldo inicial</td>							
										<td class="celrow" style="width:75px; text-align:right; max-width:75px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:75px">Precio entradas</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Cantidad entradas</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Cantidad salidas</td>							
										<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Saldo cierre</td>							
										<td class="celrow" style="width:95px; text-align:right; max-width:95px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:95px">Valor saldo</td>							
										<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Medio pago</td>							
										<td class="celrow" style="width:85px; text-align:left; max-width:85px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:85px">Banco</td>							
										<td class="celrow" style="width:75px; text-align:left; max-width:75px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:75px">Cuenta</td>							
									</tr>
';	
	//------------------------------------------------------
	//Dependiendo de tipo genera reporte
	$GLOBALS['iRow'] = 1;
	if($SucVal == 'TODAS')
	{
		if($sType == 'TOTAL') {	//Total empresa
			$funval = $funval. RepSucXEstY($link, $stSuc, $stCaj, $sSucN, $sCajN, $sIni, $sFin, $sUser);
		} else if ($sType == 'SUCURSAL') {	//Por sucursales
			$strSQL1 = "SELECT Codigo_Sucursal FROM Sucursales WHERE Codigo_Sucursal<>'GEN'";
			$sRset1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
			while($nRset1=mysqli_fetch_array($sRset1)){
				$funval = $funval. RepSucXEstY($link, " AND Sucursal='". $nRset1[0] ."'", $stCaj, $nRset1[0], $sCajN, $sIni, $sFin, $sUser);
			}
		} else if ($sType == 'CAJA') {	//Por estaciones
        	$strSQL2 = "SELECT Codigo_Sucursal, Cajas FROM Sucursales WHERE Codigo_Sucursal<>'GEN'";
			$sRset2=mysqli_query($link, $strSQL2) or die(mysqli_error($link));
			while($nRset2=mysqli_fetch_array($sRset2)){
            	$iCaja2 = intval($nRset2[1]);
				if($iCaja2 != 0)
				{
					for ($i = 1; $i <= $iCaja2; $i++) {
						$sCajaX = "0". $i;			
						$funval = $funval. RepSucXEstY($link, " AND Sucursal='". $nRset2[0] ."'", " AND Estacion='". $sCajaX ."'", $nRset2[0], $sCajaX, $sIni, $sFin, $sUser);
					}	
				}
			}
		}
	} else {
		if($sCajN == 'TODAS')
		{
			if($sType == 'SUCURSAL') {	//Por sucursales
				$funval = $funval. RepSucXEstY($link, $stSuc, $stCaj, $sSucN, '', $sIni, $sFin, $sUser);
			} else if ($sType == 'CAJA') {	//Por estaciones
	        	$strSQL3 = "SELECT Cajas FROM Sucursales WHERE Codigo_Sucursal='". $sSucN ."'";
				$sRset3=mysqli_query($link,$strSQL3) or die(mysqli_error($link));
				while($nRset3=mysqli_fetch_array($sRset3)){
            		$iCaja1 = intval($nRset3[0]);
					if($iCaja1 != 0)
					{
						for ($i = 1; $i <= $iCaja1; $i++) {
							$sCajaN = "0". $i;			
							$funval = $funval. RepSucXEstY($link, " AND Sucursal='". $sSucN ."'", " AND Estacion='". $sCajaN ."'", $sSucN, $sCajaN, $sIni, $sFin, $sUser);
						}	
					}
				}
			}
		} else {
			$funval = $funval. RepSucXEstY($link, $stSuc, $stCaj, $sSucN, $sCajN, $sIni, $sFin, $sUser);
		}
	}
	//-------------------------------------------
	//Termina
	$funval = $funval. '</table>';
	echo $funval;
}
//------------------------------------------
//Funcion de generación de reporte
function RepSucXEstY($link, $stSuc, $stCaj, $sSucN, $sCajN, $sIni, $sFin, $sUser)
{
	$varfun = '';
	//-------------------------------------
	//Captura fecha ultimo cierre
	$sCloseD = '';
	$strSQLC = "SELECT MAX(Fecha) FROM Cierres_Ventanilla WHERE Fecha < '". $sIni. "'". $stSuc. $stCaj;
	$sRsetC=mysqli_query($link, $strSQLC) or die(mysqli_error($link));
	while($nRsetC=mysqli_fetch_array($sRsetC)){
		if($nRsetC[0] != ''){
			$sCloseD = $nRsetC[0];
		} else {
			$sCloseD = $sIni;
		}
	}
	//-------------------------------------------------------
	//Reporte cop efectivo
	//$GLOBALS['iRow'] = 1;
	$varfun = $varfun. AddItemR($link, $sSucN, $sCajN, 'COP', 'EFECTIVO', '', $sCloseD, '', $sUser, $sIni, $sFin);
	//-------------------------------------------------------
	//Reporte cop bancos 
	//Cuentas bancarias
	$sRsetB=mysqli_query($link, 'SELECT * FROM Cuentas_Bancarias') or die(mysqli_error($link));
	while($nRsetB=mysqli_fetch_array($sRsetB)){
		$varfun = $varfun. AddItemR($link, $sSucN, $sCajN, 'COP', 'BANCOS', $nRsetB[0], $sCloseD, $nRsetB[1], $sUser, $sIni, $sFin);
	}
	//-------------------------------------------------------
	//Reporte monedas 
    $strSQLM = "SELECT Moneda FROM XConf_Monedas WHERE Moneda <> 'COP'";
	$sRsetM=mysqli_query($link, $strSQLM) or die(mysqli_error($link));
	while($nRsetM=mysqli_fetch_array($sRsetM)){
		$varfun = $varfun. AddItemR($link, $sSucN, $sCajN, $nRsetM[0], 'EFECTIVO', '', $sCloseD, '', $sUser, $sIni, $sFin);
	}
	//-------------------------------------------------------
	return $varfun;
}
//-------------------------------------------------------------------
//Funcion para generar registro de reporte
function AddItemR($link, $sSucN, $sCajN, $sCur, $sMedPay, $sCoun, $sClose, $sBank, $sUser, $sIni, $sFin)
{
	$varfun = '';
	//-----------------------------------------------------
	//Agrega columnas iniciales
	//$varfun = '<tr valign="middle" class="fcont trnone">';
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sSucN.'</td>';
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sCajN.'</td>';
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sIni.'</td>';
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sClose.'</td>';
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sCur.'</td>';
	//Variables para registro de cierre
	$varcol[0] = str_replace("-", "", $sIni). $sSucN. $sCajN. $sCur. $sMedPay. $sCoun;
	$varcol[1] = $sSucN;
	$varcol[2] = $sCajN;
	$varcol[3] = $sUser;
	$varcol[4] = $sIni;
	$varcol[5] = $sClose;
	$varcol[6] = $sCur;
	//-----------------------------------------------------
	//String de sucursal
	$sSucSR = '';
	if($sSucN != ''){$sSucSR = " AND Sucursal = '". $sSucN. "'";}
	//String de Caja
    $sCajSR = '';
	if($sCajN!= ''){$sCajSR = " AND Estacion = '". $sCajN. "'";}
	//String de Bancos - Cuentas
	$sCouI = '';
	if($sMedPay == 'BANCOS'){$sCouI  = " AND Cuenta = '". $sCoun. "'";}
	//----------------------------------------------------------------------------------
	//Captura saldo del cierre
	$strSQLI = "SELECT SUM(Cantidad_Saldo_Cierre), AVG(Precio_Promedio_Entradas) FROM Cierres_Ventanilla WHERE Fecha = '". $sClose. "' AND Moneda = '". $sCur. "' AND Medio_Pago = '". $sMedPay. "'". $sCouI. $sSucSR. $sCajSR;
	$sRsetI=mysqli_query($link, $strSQLI) or die(mysqli_error($link));
	while($nRsetI=mysqli_fetch_array($sRsetI)){
		//Saldo inicial
		if($nRsetI[0] == 0 || $nRsetI[0] == ''){
			$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">0</td>';
			$varcol[7] = 0;
		} else {
			$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($nRsetI[0], 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), ((isset($GLmilsepa)) ? $GLmilsepa : '.')) .'</td>';
			$varcol[7] = $nRsetI[0];
		}
		//Captura precio promedio 
		if($nRsetI[1] == 0 || $nRsetI[1] == ''){
			$varcol[8] = 0;
		} else {
			$varcol[8] = $nRsetI[1];
		}
	}
	//---------------------------------------------------------------------------------
	//Hace consulta de entradas y salidas de las diferentes monedas
	if($sCur == 'COP')	//Moneda local
	{
		$varcol[8] = 1;
		$varcol[11] = 1;
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:75px">1</td>';
		//-----------------------------------------------------------------------------
        $dNSale = 0;
		//Compras Salidas
        $strSQLNC = "SELECT SUM(Caja_Nacional), SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."' AND Estado_Operacion = 'ACTIVO' AND Medio_Pago = '". $sMedPay ."'". $sCouI. $sSucSR. $sCajSR;
		$vCS = explode('.|.', CalSubTot($link, $strSQLNC));
		$dNSale = $dNSale + $vCS[0];
		//Traslados Salidas
		$strSQLNT = "SELECT SUM(Cantidad), SUM(Valor) FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '143' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."'  AND Moneda = '". $sCur ."' AND Estado = 'ACTIVO' AND Medio_Pago = '". $sMedPay ."'". $sCouI. $sSucSR. $sCajSR;
		$vTS = explode('.|.', CalSubTot($link, $strSQLNT));
		$dNSale = $dNSale + $vTS[0];
		//Pagos Salidas
		$strSQLNP = "SELECT SUM(Total_Pagar), SUM(Subtotal) FROM Egresos_Ventanilla WHERE Codigo_Movimiento = '144' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."' AND Estado = 'ACTIVO' AND Medio_Pago = '". $sMedPay ."'". $sCouI. $sSucSR. $sCajSR;
		$vPS = explode('.|.', CalSubTot($link, $strSQLNP));
		$dNSale = $dNSale + $vPS[0];
		//------------------------------------------------------------------------------
		//Pone valor de salidas en tabla
		$varcol[12] = $dNSale;
		$varcol[13] = $dNSale;
		//-----------------------------------------------------------------------------
		$dNEntra = 0;
		//Ventas Entradas
		$strSQLNV = "SELECT SUM(Caja_Nacional), SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '141' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."' AND Estado_Operacion = 'ACTIVO' AND Medio_Pago = '". $sMedPay ."'". $sCouI. $sSucSR. $sCajSR;
		$vVE = explode('.|.', CalSubTot($link, $strSQLNV));
		$dNEntra = $dNEntra + $vVE[0];
		//Traslados Entradas
		$strSQLNE = "SELECT SUM(Cantidad), SUM(Valor) FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '142' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."'  AND Moneda = '". $sCur ."' AND Estado = 'ACTIVO' AND Medio_Pago = '". $sMedPay ."'". $sCouI. $sSucSR. $sCajSR;
		$vTE = explode('.|.', CalSubTot($link, $strSQLNE));
		$dNEntra = $dNEntra + $vTE[0];
		//-----------------------------------------------------------------------------
        //Pone valor de Entradas y salidas
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($dNEntra, 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), ((isset($GLmilsepa)) ? $GLmilsepa : '.')) .'</td>';
		$varcol[9] = $dNEntra;
		$varcol[10] = $dNEntra;
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($dNSale, 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
        //-----------------------------------------------------------------------------
		//Calcula y Pone saldo final
		$varcol[14] = $varcol[7] + $varcol[9] - $varcol[12];
		$varcol[15] = $varcol[7] + $varcol[10] - $varcol[13];
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($varcol[14], 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:95px">'. number_format($varcol[15], 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
	//------------------------------------------------------------------------------
	} else {	//Divisas
		$dDCEntra = 0;
		$dDVEntra = 0;
		//Compras Entradas
		$strSQLDC = "SELECT SUM(Cantidad), SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."' AND Moneda = '". $sCur ."' AND Estado_Operacion = 'ACTIVO'". $sSucSR. $sCajSR;
		$vCE = explode('.|.', CalSubTot($link, $strSQLDC));
		$dDCEntra = $dDCEntra + $vCE[0];
        $dDVEntra = $dDVEntra + $vCE[1];
		//Traslados Entradas
		$strSQLTE = "SELECT SUM(Cantidad), SUM(Valor) FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '142' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."' AND Moneda = '". $sCur ."' AND Estado = 'ACTIVO'". $sSucSR. $sCajSR;
		$vDE = explode('.|.', CalSubTot($link, $strSQLTE));
		$dDCEntra = $dDCEntra + $vDE[0];
        $dDVEntra = $dDVEntra + $vDE[1];
		//-----------------------------------------------------------------------------
        //Pone valor de Entradas y precio promedio
		//Precio promedio
		if($dDCEntra != 0){$varcol[8] = round($dDVEntra / $dDCEntra , 2);}
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:75px">'. number_format($varcol[8], 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($dDCEntra, 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
		$varcol[9] = $dDCEntra;
		$varcol[10] = $dDVEntra;
		//------------------------------------------------------------------------------
		$dDCSale = 0;
		$dDVSale = 0;
		//Ventas Salidas
		$strSQLDV = "SELECT SUM(Cantidad), SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '141' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."' AND Moneda = '". $sCur ."' AND Estado_Operacion = 'ACTIVO'". $sSucSR. $sCajSR;
		$vVS = explode('.|.', CalSubTot($link, $strSQLDV));
        $dDCSale = $dDCSale + $vVS[0];
        $dDVSale = $dDVSale + $vVS[1];
		//Traslados Salidas
		$strSQLTS = "SELECT SUM(Cantidad), SUM(Valor) FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '143' AND Fecha >= '". $sIni ."' AND Fecha <= '". $sFin ."'  AND Moneda = '". $sCur ."' AND Estado = 'ACTIVO'". $sSucSR. $sCajSR;
		$vDS = explode('.|.', CalSubTot($link, $strSQLTS));
        $dDCSale = $dDCSale + $vDS[0];
        $dDVSale = $dDVSale + $vDS[1];
		//------------------------------------------------------------------------------
		//Pone valor y cantidad de salidas y precio promedio
		if($dDCSale != 0){
			$varcol[11] = round($dDVSale / $dDCSale, 2);
		} else {
			$varcol[11] = 0;
		}
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($dDCSale, 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
		$varcol[12] = $dDCSale;
		$varcol[13] = $dDVSale;
		//------------------------------------------------------------------------------
		//Calcula y Pone saldo final
		$varcol[14] = $varcol[7] + $varcol[9] - $varcol[12];
		$varcol[15] = $varcol[14] * $varcol[8];
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px">'. number_format($varcol[14], 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
		$varfun = $varfun. '<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:95px">'. number_format($varcol[15], 2, ((isset($GLdecsepa)) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : '.')) .'</td>';
	}
	//---------------------------------------
	//Medio de pago
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sMedPay.'</td>';
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sBank.'</td>';
	$varfun = $varfun. '<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">'.$sCoun.'</td>';
	$varcol[16] = $sMedPay;
	$varcol[17] = $sBank;
	$varcol[18] = $sCoun;
	//---------------------------------------
	//Crea tx oculto con valores para cierre
	$sveccl = '';
	for ($i = 0; $i <= 18; $i++) {
		$sveccl = $sveccl. $varcol[$i] . ".|.";
	}
	$varfun = $varfun. '<input type="hidden" name="txo'. $GLOBALS['iRow'] .'" id="txo'. $GLOBALS['iRow'] .'" value="'. $sveccl .'"  />';
	//---------------------------------------
	$varfun = $varfun. '</tr>';
	//---------------------------------------
	//Si no hay saldos, retorna vacio, en caso contrario
	//el valor de la fila
	if($varcol[7] == 0 && $varcol[9] == 0 && $varcol[12] == 0)
	{
		return '';	
	} else if($varcol[14] < 0) {
		$GLOBALS['iRow'] = $GLOBALS['iRow'] + 1;
		return '<tr valign="middle" class="fwhite bgcol_5">'. $varfun;
	} else {
		$GLOBALS['iRow'] = $GLOBALS['iRow'] + 1;
		return '<tr valign="middle" class="fcont trnone">'. $varfun;
	}
}
//------------------------------------------------------
//Procedimiento para calculo de totales de entrada o salida 
function CalSubTot($link, $strSQL)
{
	//En vector se guarda el valor y la cantidad
	//-----------------------------------------------------------
	$funval = '';
	//-----------------------------------------------------------
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		if($n[0] == 0 || $n[0] == '')
		{
			$funval = '0.|.0';		
		} else {
			$funval = $n[0] .'.|.'. $n[1];		
		}
	}
	//--------------------------------------
	return $funval;
}
//---------------------------------------------------
//Funcion para agregar totales a tablas
function AddTotals()
{
	$strSQL =$_GET['strSQL'];
	$link = Conectarse();	
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = mysqli_num_fields($p);
	while($n=mysqli_fetch_array($p)){
		for ($j = 0; $j <= $i - 1; $j++) {
			if($n[$j] != '')
			{
				echo '<tr class="fcont trnone"><td class="celrow">'. mysqli_fetch_field_direct($p, $j)->name .'</td><td style="text-align:right" class="celrow">'. number_format($n[$j], 2, (isset($GLdecsepa) ? $GLdecsepa : '.'), (isset($GLmilsepa) ? $GLmilsepa : ',')) .'</td></tr>';
			} else {
				echo '<tr class="fcont trnone"><td class="celrow">'. mysqli_fetch_field_direct($p, $j)->name .'</td><td style="text-align:right" class="celrow">0</td></tr>';		
			} 
		}
	}
}
//-------------------------------------------
//Funcion para modificar arqueo en el cierre
function ModArqueo()
{
	$stable =$_GET['stable'];	//Captura el valor de cierre
	$swhere =$_GET['swhere'];	
	//----------------------------------------------------
	$link = Conectarse();	
	//----------------------------------------------------
	$strSQL = "UPDATE Arqueo_Ventanilla SET Saldo_Inicial='". $stable ."', Compras='0', Entradas='0', Ventas='0', Salidas='0', Saldo_Final='". $stable ."' WHERE ". $swhere;
	mysqli_query($link, $strSQL);
}
ob_flush();
?>