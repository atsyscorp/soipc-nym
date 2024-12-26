<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura variables para saber si es ventana de declarante
	//o beneficiario y documento de ambos
	$var[1]= (isset($_GET['var1'])) ? $_GET['var1'] : ''; //--> Id Declarante
	$var[2]= (isset($_GET['var2'])) ? $_GET['var2'] : ''; //--> Id Cliente
	$var[3]= (isset($_GET['var3'])) ? $_GET['var3'] : ''; //--> Nuevo Cliente
	//------------------------------------------------------
	//Variables de caja --> Sucursal, caja y usuario
	$var[4]=(isset($_GET['var4'])) ? $_GET['var4'] : '';
	$var[5]=(isset($_GET['var5'])) ? $_GET['var5'] : '';
	$var[6]=(isset($_GET['var6'])) ? $_GET['var6'] : '';
	//---------------------------------------------------
	//Captura variables publicas
	$link = Conectarse();
	//---------------------------------------------------
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Compra de Divisas</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsCompra.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="frOpsCompra_Load('<?=$var[1]?>'); UpTime()">
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
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_9" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Compra de divisas</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div class="bgcol dlin_3 drod_1" style="padding:6px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr style="height:16px">
								<td style="width:74px; text-align:left; vertical-align:middle" class="fcont">Consecutivo:</td>
								<td align="left" width="56px"><input name="Consecutivo" id="tx11" maxlength="15" class="txlabel fgreen" style="width:56px; text-align:left; font-weight:bold" type="text" value="<?=$sSerie=ReadSerie($link, "Select Consecutivo From XConf_Consecutivos Where Codigo ='140' AND Sucursal = '". $var[4]. "'")?>" disabled="disabled"  /></td>								
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
								<td align="left" width="56px"><input name="Prefijo" id="tx12" maxlength="15" class="txlabel fgreen" style="width:56px; text-align:left; font-weight:bold" type="text" value="<?=$sSerie=ReadSerie($link, "Select Prefijo From XConf_Consecutivos Where Codigo ='140' AND Sucursal = '". $var[4]. "'")?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Estación: </td>
								<td align="left" width="36px"><input name="Estacion" id="tx5" maxlength="15" class="txlabel fgreen" style="width:36px; text-align:left; font-weight:bold" type="text" value="<?=$var[5]?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Hora:</td>
								<td align="left" width="72px"><input name="Hora" id="tx8" maxlength="15" class="txlabel fgreen" style="width:72px; text-align:left; font-weight:bold" type="text" value="" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Cliente:</td>
								<td align="left" width="190px"><input name="Nombre_Completo" id="tx20" maxlength="100" class="txlabel fgreen" style="width:190px; text-align:left; font-weight:bold" type="text" value="<?=(isset($sBen)) ? $sBen[8] : NULL?>" disabled="disabled"  /></td>
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
												<option value="9001">9001</option>
												<?=$cbNumeral=LoadConfTab($link, "SELECT Numeral FROM XConf_Numerales Where Operacion = 'COMPRA'")?>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont">Moneda (Divisa):</td>
										<td align="right">
											<select name="Moneda" id="tx34" style="width:155px" class="txboxo" onchange="cbCurr_Change('<?=$var[4]?>', '<?=$var[5]?>')" >
												<option value=""></option>
												<?=$cbMoneda=LoadConfTab($link, "SELECT Moneda FROM Tasas Where Estacion='". $var[5]. "' AND Sucursal='". $var[4]. "'")?>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont"><input name="chOOPsinIva" id="chOOPsinIva" type="checkbox" value="" onchange="ChPrice('chOOPsinIva', 'tx35', '<?=$var[4]?>', '<?=$var[5]?>')" tabindex="-1" />Precio sin IVA:</td>
										<td align="right"><input name="Precio_Sin_Iva" id="tx35" maxlength="10" class="txboxdis" style="width:149px; text-align:right" value="0" type="text" onkeydown="return OnlynumDec(this, event)" oninput="txCanP_Change('tx35'); txChange_Num('tx35')" disabled="disabled" />
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont"><input name="chOOPconIva" id="chOOPconIva" type="checkbox" value="" onchange="ChPrice('chOOPconIva', 'tx36', '<?=$var[4]?>', '<?=$var[5]?>')" tabindex="-1" />Precio con IVA:</td>
										<td align="right"><input name="Precio_Con_Iva" id="tx36" maxlength="10" class="txboxdis" style="width:149px; text-align:right" value="0" type="text" onkeydown="return OnlynumDec(this, event)" oninput="txCanP_Change('tx36'); txChange_Num('tx36')" disabled="disabled" />
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:135px; text-align:left" class="fcont">Cantidad compra:</td>
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
											<select name="Medio_Pago" id="tx49" style="width:172px" class="txboxo" onchange="cbMedPay_Change()" tabindex="-1" >
												<option value="EFECTIVO">EFECTIVO</option>
												<option value="BANCOS">BANCOS</option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Instrumento:</td>
										<td align="right">
											<select name="Instrumento" id="tx50" style="width:172px" class="txboxo" onchange="" tabindex="-1" >
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
										<td style="width:130px; text-align:left" class="fcont">Tasa retención fuente:</td>
										<td style="width:138px" align="right">
											<select name="cbOORteTax" id="cbOORteTax" style="width:138px" class="txboxdis" onchange="cbOORteTax_Change()" tabindex="-1" disabled="disabled" >
												<option value="0">0</option>
												<?=$cbFte=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Retencion")?>
											</select>
										</td>
										<td style="width:24px; text-align:left" class="fgreen"><b>(%)</b></td>
										<td style="width:136px; text-align:left" class="fcont">Base retención fuente:</td>
										<td style="width:156px" align="right">
											<select name="cbOORteBase" id="cbOORteBase" style="width:156px" class="txboxdis" disabled="disabled" onchange="disbtn('btaccept'); disbtn('btprint');">
												<option value="NINGUNO">NINGUNO</option>
												<option value="MARGEN">MARGEN</option>
												<option value="VALOR DE DIVISAS">VALOR DE DIVISAS</option>
											</select>
										</td>
										<td></td>
									</tr>
								</table>
							</div>
						</div>
					</div>				
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden; padding-bottom:3px">
						<div style="float:left; margin-right:10px"><input name="btclient" id="btclient" type="button" value="Ver Cliente" class="btcontdis" style="width:88px;" onclick="Go_Client('<?=$var[4]?>', '<?=$var[5]?>', '<?=$var[6]?>', '<?=$var[1]?>', '<?=$var[2]?>')" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btcalcop" id="btcalcop" type="button" value="Calcular Operación" class="btcont" style="width:120px;" onclick="cmCOOCalc_Click()" /></div>
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" disabled="disabled" onclick="cmAccept_Click('<?=$sExcSet[0]?>')"/></div>
						<div style="float:left; margin-right:10px"><input name="btprint" id="btprint" type="button" value="Imprimir" class="btcontdis" style="width:88px;" onclick="cmPrint_Click()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value=""  />
						<input type="hidden" name="Codigo_Operacion" id="tx2" value="140" />
						<input type="hidden" name="Tipo_Operacion" id="tx3" value="COMPRA DE DIVISAS" />
						<input type="hidden" name="Ano" id="tx9" value="" />
						<input type="hidden" name="Ano_Mes" id="tx10" value="" />
						<input type="hidden" name="Estado_Operacion" id="tx13" value="ACTIVO" />
						<input type="hidden" name="Documento_Beneficiario" id="tx14" value="<?=(isset($sBen)) ? $sBen[0] : NULL?>" />
						<input type="hidden" name="Tipo_Documento" id="tx15" value="<?=(isset($sBen)) ? $sBen[3] : NULL?>" />
						<input type="hidden" name="Nombre_1" id="tx16" value="<?=(isset($sBen)) ? $sBen[4] : NULL?>" />
						<input type="hidden" name="Nombre_2" id="tx17" value="<?=(isset($sBen)) ? $sBen[5] : NULL?>" />
						<input type="hidden" name="Apellido_1" id="tx18" value="<?=(isset($sBen)) ? $sBen[6] : NULL?>" />
						<input type="hidden" name="Apellido_2" id="tx19" value="<?=(isset($sBen)) ? $sBen[7] : NULL?>" />
						<input type="hidden" name="Nacionalidad" id="tx21" value="<?=(isset($sBen)) ? $sBen[9] : NULL?>" />
						<?php
							//Validacion telefono
							if(isset($sBen[11])) {
								if($sBen[11] != '') {
									$telb = $sBen[11];
								} else {
									$telb = $sBen[12];
								}
							}
						?>
						<input type="hidden" name="Telefono" id="tx22" value="<?=(isset($telb)) ? $telb : NULL?>" />
						<input type="hidden" name="Direccion" id="tx23" value="<?=(isset($sBen)) ? $sBen[13] : NULL?>" />
						<input type="hidden" name="Departamento" id="tx24" value="<?=(isset($sBen)) ? $sBen[14] : NULL?>" />
						<input type="hidden" name="Ciudad" id="tx25" value="<?=(isset($sBen)) ? $sBen[15] : NULL?>" />
						<input type="hidden" name="Ocupacion" id="tx26" value="<?=(isset($sBen)) ? $sBen[16] : NULL?>" />
						<input type="hidden" name="Grupo_Segmento" id="tx27" value="<?=(isset($sBen)) ? $sBen[18] : NULL?>" />
						<input type="hidden" name="Segmento" id="tx28" value="<?=(isset($sBen)) ? $sBen[19] : NULL?>" />
						<input type="hidden" name="Origen_Fondos" id="tx29" value="<?=(isset($sBen)) ? $sBen[20] : NULL?>" />
						<input type="hidden" name="Nuevo_Cliente" id="tx30" value="<?=$var[3]?>" />
						<input type="hidden" name="Documento_Declarante" id="tx31" value="<?=$var[1]?>" />
						<input type="hidden" name="Nombre_Declarante" id="tx32" value="<?=(isset($sDec)) ? $sDec[8] : NULL?>" />
						<input type="hidden" name="Margen" id="tx40" value="0" />
						<input type="hidden" name="Ingreso" id="tx41" value="0" />
						<input type="hidden" name="Rete_ICA" id="tx44" value="0" />
						<input type="hidden" name="Rete_IVA" id="tx45" value="0" />
						<input type="hidden" name="GMF" id="tx46" value="0" />
						<input type="hidden" name="Valor_Descontado" id="tx48" value="" />
						<input type="hidden" name="Alerta_Sistema" id="tx54" value="0" />
						<input type="hidden" name="Alerta_Cajero" id="tx55" value="0" />
						<input type="hidden" name="Alerta_Total" id="tx56" value="0" />
						<input type="hidden" name="Moneda_Local" id="txCurr" value="<?=(isset($sExcSet)) ? $sExcSet[3] : NULL?>" />
					</div>
					<div style="clear:both"></div>
					<div class="bgcol dlin_3 drod_1" style="padding:6px; margin-top:7px; overflow:hidden">
						<div style="float:left; width:82px; margin-left:0px; margin-right:5px">
							<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Subtotal:</div>
							<div style="text-align:center"><input name="Valor" id="tx38"  class="txlabel fgreen" style="width:76px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
						</div>
						<div style="float:left; width:96px; margin-left:5px; margin-right:5px">
							<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Valor en USD:</div>
							<div style="text-align:center"><input name="Valor_En_USD" id="tx39"  class="txlabel fgreen" style="width:90px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
						</div>
						<div style="float:left; width:112px; margin-left:5px; margin-right:5px">
							<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">IVA descontable:</div>
							<div style="text-align:center"><input name="IVA" id="tx42"  class="txlabel fgreen" style="width:106px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
						</div>
						<div style="float:left; width:120px; margin-left:5px; margin-right:5px">
							<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Retención fuente:</div>
							<div style="text-align:center"><input name="Rete_Fuente" id="tx43"  class="txlabel fgreen" style="width:114px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
						</div>
						<div style="float:left; width:128px; margin-left:5px; margin-right:0px">
							<div style="margin-bottom:3px; text-align:center; font-weight:bold;" class="fcont">Total pagar compra:</div>
							<div style="text-align:center"><input name="Caja_Nacional" id="tx47"  class="txlabel fgreen" style="width:122px; text-align:center; font-weight:bold" type="text" value="0" disabled="disabled" /></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if(isset($sTaxSet)) { ?>
			<?php for ($i = 1; $i <= $sTaxSet[1]; $i++) {	//Esto se hace por que no se puede imprimir window.print desde loop en javascript ?>
				<iframe name="frPrint<?=$i?>" id="frPrint<?=$i?>" align="left" frameborder="0" style="width:0px; height:0px;" src=""></iframe>
			<?php } ?>
		<?php } ?>
	</div>
</div>
</body>
</html>
