<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura variables para saber si es ventana de declarante
	//o beneficiario y documento de ambos
	$var[1]=(isset($_GET['var1'])) ? $_GET['var1'] : NULL; //--> Id Declarante
	$var[2]=(isset($_GET['var2'])) ? $_GET['var2'] : NULL; //--> Id Cliente
	$var[3]=(isset($_GET['var3'])) ? $_GET['var3'] : NULL; //--> Nuevo Cliente
	//---------------------------------------------------
	//Variables de caja --> Sucursal, caja y usuario
	$var[4]=(isset($_GET['var4'])) ? $_GET['var4'] : NULL;
	$var[5]=(isset($_GET['var5'])) ? $_GET['var5'] : NULL;
	$var[6]=(isset($_GET['var6'])) ? $_GET['var6'] : NULL;
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//Carga configuracion cambiaria
	$getExcSet = ExcSet($link);
	//Carga configuracion contable
	$getTaxSet = TaxSet($link, $var[4]);
	//---------------------------------------------------
	//Busca informacion de declarante y beneficiario
	//Declarante
	$strSQLD = "SELECT * FROM Clientes WHERE Identificacion='". $var[1]. "'";
	$p=mysqli_query($link, $strSQLD) or die(mysqli_error($link));
	while($pn=mysqli_fetch_array($p)){
		for ($i = 0; $i <= 29; $i++) {
			$sDec[$i] = $pn[$i];
		}
	}
	//Beneficiario
	$strSQLB = "SELECT * FROM Clientes WHERE Identificacion='". $var[2]. "'";
	$b=mysqli_query($link, $strSQLB) or die(mysqli_error($link));
	while($bn=mysqli_fetch_array($b)){
		for ($i = 0; $i <= 29; $i++) {
			$sBen[$i] = $bn[$i];
		}
	}
	//---------------------------------------------
	//Valida consecutivo y fecha resolucion de facturacion
	$DoCon = '';
	$DoCoC = '';
	$DoCoF = '';
	$sDCMs = '';
	$sSerie1 = ReadSerie_1($link, "Select Consecutivo From XConf_Consecutivos Where Codigo ='141' AND Sucursal = '". $var[4]. "'");
	//terminación de conseuctivo
	if(is_numeric($sTaxSet[3]))
	{
		$dVen = $sTaxSet[3] - $sSerie1;
		if($dVen <= 100)
		{
			$sDCMs = "<b>Llegando al límite de numeración autorizada. ". $dVen. " facturas restantes.</b>";
            $DoCoC = 'SI';
        }
	}	
	//Fecha de resolucion
   	$ahora = time();
   	$date = date_create($ahora);
	$feccrea = $sTaxSet[5];		
	$datef = strtotime($feccrea);
	$ddiff = intval(($datef - $ahora)/ (60*60*24));	//Calcula diferencia de fechas
	if($ddiff <= 5){
		$ddiff = $ddiff + 1;
		$sDCMs = "<b>La resolución de facturación de la DIAN se vence en ". $ddiff. " días.</b>";
        $DoCoF = 'SI';
	}
	//Validacion con variable final
	if($DoCoC != '' || $DoCoF != ''){$DoCon = 'SI';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Venta de Divisas</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsVenta.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="frOpsVenta_Load('<?=$var[1]?>', '<?=$DoCon?>'); UpTime()">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen"> 
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:614px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 257)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 610, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div id="dpup3" style="position:relative; top:0px; left:0px">
				<?=$msj_1=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 610, 2, $sDCMs, "hidcap('dMsj2')", '', '', 2, 'hidden')?>
			</div>
			<div id="dpup4" style="position:relative; top:0px; left:0px">
				<div id="frOpsCambio" style="position:absolute; z-index:60; width:296px; left:159px; top:100px; visibility:hidden;">
					<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
						<div class="bgcol_6" style="overflow:hidden">
							<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Cambio operación venta</div>
							<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="Close_Cam()"><img src="images/close.png" style="height:20px; width:auto" /></div>
						</div>
						<div style="margin:8px">
							<div class="drod_1 dlin_3" style="margin-bottom:5px">
								<div style="margin:2px">
									<div class="fgreen" style="font-size:18px; float:left; padding:2px"><b>Neto a recibir:</b></div>
									<input name="txCANeto" id="txCANeto" maxlength="20" class="txlabel fgreen" style="width:130px; text-align:right; font-weight:bold; font-size:18px; float:right" type="text" value="0" disabled="disabled"  />								
									<div style="clear:both"></div>
								</div>
							</div>						
							<div class="drod_1 dlin_1 bgcol_1" style="margin-bottom:5px">
								<div style="margin:2px">
									<div class="fgreen" style="font-size:18px; float:left"><b>Cancelado:</b></div>
									<input name="txCACancela" id="txCACancela" maxlength="20" class="txlabel fgreen" style="width:130px; text-align:right; font-weight:bold; font-size:18px; float:right" type="text" value="" onkeydown="return CambioNum(this, event)" oninput="Calc_Cambio(); txChange_Num('txCACancela')" />								
									<div style="clear:both"></div>
								</div>
							</div>						
							<div class="drod_1 dlin_3" style="margin-bottom:5px">
								<div style="margin:2px">
									<div class="fgreen" style="font-size:18px; float:left; padding:2px"><b>Cambio:</b></div>
									<input name="txCACambio" id="txCACambio" maxlength="20" class="txlabel fgreen" style="width:130px; text-align:right; font-weight:bold; font-size:18px; float:right" type="text" value="0" disabled="disabled"  />								
									<div style="clear:both"></div>
								</div>
							</div>						
							<div style="text-align:left"><input name="cmCACancel" id="cmCACancel" type="button" value="Salir" class="btcont" style="width:88px;" onclick="Close_Cam()" /></div>
						</div>		
					</div>		
				</div>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Venta de divisas</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div class="bgcol dlin_3 drod_1" style="padding:6px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr style="height:16px">
								<td style="width:74px; text-align:left; vertical-align:middle" class="fcont">Consecutivo:</td>
								<td align="left" width="56px"><input name="Consecutivo" id="tx11" maxlength="15" class="txlabel fgreen" style="width:56px; text-align:left; font-weight:bold" type="text" value="<?=$sSerie=ReadSerie($link, "Select Consecutivo From XConf_Consecutivos Where Codigo ='141' AND Sucursal = '". $var[4]. "'")?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Sucursal: </td>
								<td align="left" width="36px"><input name="Sucursal" id="tx4" maxlength="15" class="txlabel fgreen" style="width:36px; text-align:left; font-weight:bold" type="text" value="<?=$var[4]?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Fecha:</td>
								<td align="left" width="72px"><input name="Fecha" id="tx7" maxlength="15" class="txlabel fgreen" style="width:72px; text-align:left; font-weight:bold" type="text" value="" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Cajero:</td>
								<td align="left" width="190px"><input name="Cajero" id="tx6" maxlength="70" class="txlabel fgreen" style="width:190px; text-align:left; font-weight:bold" type="text" value="<?=$var[6]?>" disabled="disabled"  /></td>								
								<td></td>
							</tr>
							<tr style="height:16px">
								<td style="width:74px; text-align:left; vertical-align:middle" class="fcont">Prefijo:</td>
								<td align="left" width="56px"><input name="Prefijo" id="tx12" maxlength="15" class="txlabel fgreen" style="width:56px; text-align:left; font-weight:bold" type="text" value="<?=$sSerie=ReadSerie($link, "Select Prefijo From XConf_Consecutivos Where Codigo ='141' AND Sucursal = '". $var[4]. "'")?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Estación: </td>
								<td align="left" width="36px"><input name="Estacion" id="tx5" maxlength="15" class="txlabel fgreen" style="width:36px; text-align:left; font-weight:bold" type="text" value="<?=$var[5]?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Hora:</td>
								<td align="left" width="72px"><input name="Hora" id="tx8" maxlength="15" class="txlabel fgreen" style="width:72px; text-align:left; font-weight:bold" type="text" value="" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Cliente:</td>
								<td align="left" width="190px"><input name="Nombre_Completo" id="tx20" maxlength="100" class="txlabel fgreen" style="width:190px; text-align:left; font-weight:bold" type="text" value="<?=(isset($sBen)) ? $sBen[8] : NULL; ?>" disabled="disabled" /></td>
								<td></td>
							</tr>
						</table>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden">
						<div style="float:left; width:292px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Datos de la operación</strong></div>
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont">Numeral cambiario:</td>
										<td align="right">
											<select name="Numeral" id="tx33" style="width:155px" class="txboxo" onchange="" >
												<option value="9002">9002</option>
												<?=$cbNumeral=LoadConfTab($link, "SELECT Numeral FROM XConf_Numerales Where Operacion = 'VENTA'")?>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont">Moneda (Divisa):</td>
										<td align="right">
											<select name="Moneda" id="tx34" style="width:155px" class="txboxo" onchange="cbCurr_Change('<?=$var[4]?>', '<?=$var[5]?>')" >
												<option value=""></option>
												<?=$cbMoneda=LoadConfTab($link, "SELECT DISTINCT(Moneda) FROM Tasas Where Sucursal='". $var[4]. "'")?>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont"><input name="chOVPsinIva" id="chOVPsinIva" type="checkbox" value="" onchange="ChPrice('chOVPsinIva', 'tx35', '<?=$var[4]?>', '<?=$var[5]?>')" disabled="disabled" tabindex="-1" />Precio sin IVA:</td>
										<td align="right"><input name="Precio_Sin_Iva" id="tx35" maxlength="10" class="txboxdis" style="width:149px; text-align:right" value="0" type="text" onkeydown="return OnlynumDec(this, event)" oninput="txPrecios_Changed(); txChange_Num('tx35')" disabled="disabled" />
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont"><input name="chOVPconIva" id="chOVPconIva" type="checkbox" value="" onchange="ChPrice('chOVPconIva', 'tx36', '<?=$var[4]?>', '<?=$var[5]?>')" disabled="disabled" tabindex="-1" />Precio con IVA:</td>
										<td align="right"><input name="Precio_Con_Iva" id="tx36" maxlength="10" class="txboxdis" style="width:149px; text-align:right" value="0" type="text" onkeydown="return OnlynumDec(this, event)" oninput="txPrecios_Changed(); txChange_Num('tx36')" disabled="disabled" />
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont">Cantidad venta:</td>
										<td align="right"><input name="Cantidad" id="tx37" maxlength="20" class="txboxo" style="width:149px; text-align:right" value="0" onkeydown="return Onlynum(this, event)" oninput="txCanP_Change('tx37'); txChange_Num('tx37')" onfocus="txCant_Enter()"  /></td>
									</tr>
								</table>
							</div>
						</div>
						<div style="float:right; width:292px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Forma de pago</strong></div>
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Medio de pago:</td>
										<td align="right">
											<select name="Medio_Pago" id="tx49" style="width:172px" class="txboxo" onchange="cbMedPay_Change()" tabindex="-1">
												<option value="EFECTIVO">EFECTIVO</option>
												<option value="BANCOS">BANCOS</option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Instrumento:</td>
										<td align="right">
											<select name="Instrumento" id="tx50" style="width:172px" class="txboxo" onchange="" tabindex="-1">
												<option value="EFECTIVO">EFECTIVO</option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Nombre banco:</td>
										<td align="right">
											<select name="Banco" id="tx51" style="width:172px" class="txboxdis" disabled="disabled" onchange="cbBank_Change()" >
												<option value=""></option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Número cuenta:</td>
										<td align="right">
											<select name="Cuenta" id="tx52" style="width:172px" class="txboxdis" disabled="disabled" >
												<option value=""></option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Número cheque:</td>
										<td align="right"><input name="Codigo_Instrumento" id="tx53" maxlength="45" class="txboxdis" style="width:166px; text-align:left" value="" type="text" disabled="disabled" />
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden" class="dlin_1 drod_1">
						<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
							<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Impuestos</strong></div>
							<div style="margin-bottom:7px">
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:96px; text-align:left" class="fcont">Tasa de IVA:</td>
										<td style="width:80px" align="left">
											<select name="cbOVTasaIva" id="cbOVTasaIva" style="width:72px" class="txboxdis" onchange="txPrecios_Changed()" tabindex="-1" disabled="disabled">
												<option value="0">0</option>
												<?=$cbIVA=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Taxes WHERE Tipo_Impuesto='IVA'")?>
											</select>
										</td>
										<td style="width:116px; text-align:left" class="fcont">Retención fuente:</td>
										<td style="width:80px" align="left">
											<select name="cbOVRteTax" id="cbOVRteTax" style="width:72px" class="txboxdis" onchange="cbOORteTax_Change()" tabindex="-1" disabled="disabled">
												<option value="0">0</option>
												<?=$cbFte=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Retencion")?>
											</select>
										</td>
										<td style="width:126px; text-align:left" class="fcont">Base de retención:</td>
										<td style="width:130px" align="left">
											<select name="cbOVRteBase" id="cbOVRteBase" style="width:130px" class="txboxdis" disabled="disabled" onchange="disbtn('btaccept'); disbtn('btprint');">
												<option value="NINGUNO">NINGUNO</option>
												<option value="MARGEN">MARGEN</option>
												<option value="VALOR DE DIVISAS">VALOR DE DIVISAS</option>
											</select>
										</td>
										<td></td>
									</tr>						
									<tr class="trtxco">
										<td style="width:88px; text-align:left" class="fcont">Retención ICA:</td>
										<td style="width:80px" align="left">
											<select name="cbOVRteIca" id="cbOVRteIca" style="width:72px" class="txboxdis" onchange="" tabindex="-1" disabled="disabled">
												<option value="0">0</option>
												<?=$cbICA=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Taxes WHERE Tipo_Impuesto='RETENCION ICA'")?>
											</select>
										</td>
										<td style="width:116px; text-align:left" class="fcont">Retención de IVA:</td>
										<td style="width:80px" align="left">
											<select name="cbOVRteIva" id="cbOVRteIva" style="width:72px" class="txboxdis" onchange="" tabindex="-1" disabled="disabled">
												<option value="0">0</option>
												<?=$cbRti=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Taxes WHERE Tipo_Impuesto='RETENCION IVA'")?>
											</select>
										</td>
										<td style="width:126px; text-align:left" class="fcont">Cuatro X mil (GMF):</td>
										<td style="width:130px" align="left">
											<select name="cbOVGmf" id="cbOVGmf" style="width:130px" class="txboxdis" onchange="" tabindex="-1" disabled="disabled">
												<option value="0">0</option>
												<?=$cbRti=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Taxes WHERE Tipo_Impuesto='GMF'")?>
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden; padding-bottom:3px">
						<div style="float:left; margin-right:10px"><input name="btclient" id="btclient" type="button" value="Ver Cliente" class="btcontdis" style="width:88px;" onclick="Go_Client('<?=$var[4]?>', '<?=$var[5]?>', '<?=$var[6]?>', '<?=$var[1]?>', '<?=$var[2]?>')" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btcalcop" id="btcalcop" type="button" value="Calcular Operación" class="btcont" style="width:120px;" onclick="cmCOOCalc_Click()" /></div>
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" disabled="disabled" onclick="cmAccept_Click()"/></div>
						<div style="float:left; margin-right:10px"><input name="btprint" id="btprint" type="button" value="Imprimir" class="btcontdis" style="width:88px;" onclick="cmPrint_Click()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value=""  />
						<input type="hidden" name="Codigo_Operacion" id="tx2" value="141" />
						<input type="hidden" name="Tipo_Operacion" id="tx3" value="VENTA DE DIVISAS" />
						<input type="hidden" name="Ano" id="tx9" value="" />
						<input type="hidden" name="Ano_Mes" id="tx10" value="" />
						<input type="hidden" name="Estado_Operacion" id="tx13" value="ACTIVO" />
						<input type="hidden" name="Documento_Beneficiario" id="tx14" value="<?=$sBen[0]?>" />
						<input type="hidden" name="Tipo_Documento" id="tx15" value="<?=$sBen[3]?>" />
						<input type="hidden" name="Nombre_1" id="tx16" value="<?=$sBen[4]?>" />
						<input type="hidden" name="Nombre_2" id="tx17" value="<?=$sBen[5]?>" />
						<input type="hidden" name="Apellido_1" id="tx18" value="<?=$sBen[6]?>" />
						<input type="hidden" name="Apellido_2" id="tx19" value="<?=$sBen[7]?>" />
						<input type="hidden" name="Nacionalidad" id="tx21" value="<?=$sBen[9]?>" />
						<?php
							//Validacion telefono
							if(isset($sBen)) {
								if($sBen[11] != '') {
									$telb = $sBen[11];
								} else {
									$telb = $sBen[12];
								}
							}
						?>
						<input type="hidden" name="Telefono" id="tx22" value="<?=$telb?>" />
						<input type="hidden" name="Direccion" id="tx23" value="<?=$sBen[13]?>" />
						<input type="hidden" name="Departamento" id="tx24" value="<?=$sBen[14]?>" />
						<input type="hidden" name="Ciudad" id="tx25" value="<?=$sBen[15]?>" />
						<input type="hidden" name="Ocupacion" id="tx26" value="<?=$sBen[16]?>" />
						<input type="hidden" name="Grupo_Segmento" id="tx27" value="<?=$sBen[18]?>" />
						<input type="hidden" name="Segmento" id="tx28" value="<?=$sBen[19]?>" />
						<input type="hidden" name="Origen_Fondos" id="tx29" value="<?=$sBen[20]?>" />
						<input type="hidden" name="Nuevo_Cliente" id="tx30" value="<?=$var[3]?>" />
						<input type="hidden" name="Documento_Declarante" id="tx31" value="<?=$var[1]?>" />
						<input type="hidden" name="Nombre_Declarante" id="tx32" value="<?=$sDec[8]?>" />
						<input type="hidden" name="Ingreso" id="tx41" value="0" />
						<input type="hidden" name="Valor_Descontado" id="tx48" value="" />
						<input type="hidden" name="Alerta_Sistema" id="tx54" value="0" />
						<input type="hidden" name="Alerta_Cajero" id="tx55" value="0" />
						<input type="hidden" name="Alerta_Total" id="tx56" value="0" />
						<input type="hidden" name="Moneda_Local" id="txCurr" value="<?=$sExcSet[3]?>" />
						<input type="hidden" name="txOVPType" id="txOVPType" value="" />
					</div>
					<div class="bgcol dlin_3 drod_1" style="padding:6px; margin-top:7px; overflow:hidden">
						<div>
							<div style="float:left; width:124px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Precio base IVA:</div>
								<div style="text-align:center"><input name="lbOVBaseIva" id="lbOVBaseIva" class="txlabel fgreen" style="width:118px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:116px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Intermediación:</div>
								<div style="text-align:center"><input name="Margen" id="tx40" class="txlabel fgreen" style="width:110px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:106px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Subtotal:</div>
								<div style="text-align:center"><input name="Valor" id="tx38" class="txlabel fgreen" style="width:100px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:106px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Valor en USD:</div>
								<div style="text-align:center"><input name="Valor_En_USD" id="tx39" class="txlabel fgreen" style="width:100px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:106px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Valor de IVA:</div>
								<div style="text-align:center"><input name="IVA" id="tx42" class="txlabel fgreen" style="width:100px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
						</div>
						<div style="clear:both"></div>
						<div style="margin-top:5px">
							<div style="float:left; width:124px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Retención fuente:</div>
								<div style="text-align:center"><input name="Rete_Fuente" id="tx43" class="txlabel fgreen" style="width:118px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:116px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Retención ICA:</div>
								<div style="text-align:center"><input name="Rete_ICA" id="tx44" class="txlabel fgreen" style="width:110px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:106px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Retención IVA:</div>
								<div style="text-align:center"><input name="Rete_IVA" id="tx45" class="txlabel fgreen" style="width:100px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:106px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Cuatro x Mil:</div>
								<div style="text-align:center"><input name="GMF" id="tx46" class="txlabel fgreen" style="width:100px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
							<div style="float:left; width:106px; margin-left:0px; margin-right:5px">
								<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Neto a recibir:</div>
								<div style="text-align:center"><input name="Caja_Nacional" id="tx47" class="txlabel fgreen" style="width:100px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
			for ($i = 1; $i <= $sTaxSet[1]; $i++) {	//Esto se hace por que no se puede imprimir window.print desde loop en javascript
		?>
	    <iframe name="frPrint<?=$i?>" id="frPrint<?=$i?>" align="left" frameborder="0" style="width:0px; height:0px;" src=""></iframe>
		<? } ?>
	</div>
</div>
</body>
</html>
