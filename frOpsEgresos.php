<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura de variables
	$var[1]=$_GET['var1'];
	$var[2]=$_GET['var2'];
	$var[3]=$_GET['var3'];
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion cambiaria
	$getExcSet = ExcSet($link);
	//---------------------------------------------------
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Egresos ventanilla</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsEgresos.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="frOpsEgresos_Load()">
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
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Egresos ventanilla</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div class="bgcol dlin_3 drod_1" style="padding:6px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr style="height:16px">
								<td style="width:74px; text-align:left; vertical-align:middle" class="fcont">Consecutivo:</td>
								<td align="left" width="72px"><input name="Consecutivo" id="tx8" maxlength="15" class="txlabel fgreen" style="width:72px; text-align:left; font-weight:bold" type="text" value="<?=$sSerie=ReadSerie($link, "Select Consecutivo From XConf_Consecutivos Where Codigo ='144' AND Sucursal = '". $var[1]. "'")?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Sucursal:</td>
								<td align="left" width="40px"><input name="Sucursal" id="tx4" maxlength="15" class="txlabel fgreen" style="width:40px; text-align:left; font-weight:bold" type="text" value="<?=$var[1]?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Cajero:</td>
								<td align="left" width="232px"><input name="Cajero" id="tx6" maxlength="70" class="txlabel fgreen" style="width:232px; text-align:left; font-weight:bold" type="text" value="<?=$var[3]?>" disabled="disabled"  /></td>								
								<td></td>
							</tr>
							<tr style="height:16px">
								<td style="width:74px; text-align:left; vertical-align:middle" class="fcont">Fecha:</td>
								<td align="left" width="72px"><input name="Fecha" id="tx7" maxlength="15" class="txlabel fgreen" style="width:72px; text-align:left; font-weight:bold" type="text" value="" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Estación:</td>
								<td align="left" width="40px"><input name="Estacion" id="tx5" maxlength="15" class="txlabel fgreen" style="width:40px; text-align:left; font-weight:bold" type="text" value="<?=$var[2]?>" disabled="disabled"  /></td>								
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont"></td>
								<td align="left" width="232px"></td>								
								<td></td>
							</tr>
						</table>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:8px">
						<div style="float:left" class="fgreen"><strong>Identificación de tercero</strong></div>
						<div style="float:right; width:448px; margin-top:9px" class="dlin_4"></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:5px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:116px; text-align:left" class="fcont">Identificación NIT:</td>
								<td align="left" width="102px"><input name="Documento_Tercero" id="tx9" maxlength="50" class="txboxo" style="width:102px; text-align:left" value="" onkeyup="FindTer()" /></td>
								<td style="width:12px"></td>
								<td style="width:116px; text-align:left" class="fcont">Nombre tercero:</td>
								<td align="left" width="278px"><input name="Nombre_Tercero" id="tx10" maxlength="100" class="txboxo" style="width:278px; text-align:left" value="" /></td>
							</tr>
						</table>					
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:8px">
						<div style="float:left" class="fgreen"><strong>Descripción de la operación</strong></div>
						<div style="float:right; width:432px; margin-top:9px" class="dlin_4"></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:5px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:72px; text-align:left" class="fcont">Cuenta PUC:</td>
								<td align="right" width="112px"><input name="PUC" id="tx11" maxlength="50" class="txbox" style="width:105px; text-align:left" value="" /></td>
								<td style="width:62px; text-align:right" class="fcont">Concepto:</td>
								<td align="right" width="325px"><input name="Descripcion" id="tx12" maxlength="250" class="txboxo" style="width:325px; text-align:left" value="" /></td>
								<td></td>
							</tr>
						</table>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden">
						<div style="float:left; width:292px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Valores del comprobante</strong></div>
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:85px; text-align:left" class="fcont">Subtotal:</td>
										<td align="right"><input name="Subtotal" id="tx15" maxlength="15" class="txboxo" style="width:188px; text-align:right" value="0" onkeydown="return Onlynum(this, event)" oninput="txCalc('tx15'); txChange_Num('tx15')"  /></td>
									</tr>
									<tr class="trtxco">
										<td style="width:85px; text-align:left" class="fcont">Valor IVA:</td>
										<td align="right">
											<select name="cbEVIva" id="cbEVIva" style="width:60px" class="txbox" onchange="TaxCalc('cbEVIva', 'tx16', 'tx15')" >
												<option value="0">0</option>
												<?=$cbIva=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Taxes WHERE Tipo_Impuesto = 'IVA'")?>
											</select>
											<input name="Valor_IVA" id="tx16" maxlength="15" class="txbox" style="width:124px; text-align:right" value="0" onkeydown="return Onlynum(this, event)" oninput="txCalc('tx16'); txChange_Num('tx16')" /></td>
									</tr>
									<tr class="trtxco">
										<td style="width:85px; text-align:left" class="fcont">Rte. Fuente:</td>
										<td align="right">
											<select name="cbEVRteFte" id="cbEVRteFte" style="width:60px" class="txbox" onchange="TaxCalc('cbEVRteFte', 'tx17', 'tx15')">
												<option value="0">0</option>
												<?=$cbFte=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Retencion")?>
											</select>
											<input name="Rete_Fuente" id="tx17" maxlength="15" class="txbox" style="width:124px; text-align:right" value="0" onkeydown="return Onlynum(this, event)" oninput="txCalc('tx17'); txChange_Num('tx17')" /></td>
									</tr>
									<tr class="trtxco">
										<td style="width:85px; text-align:left" class="fcont">Rte. ICA:</td>
										<td align="right">
											<select name="cbEVRteIca" id="cbEVRteIca" style="width:60px" class="txbox" onchange="TaxCalc('cbEVRteIca', 'tx18', 'tx15')" >
												<option value="0">0</option>
												<?=$cbIca=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Taxes WHERE Tipo_Impuesto = 'RETENCION ICA'")?>
											</select>
											<input name="Rete_ICA" id="tx18" maxlength="15" class="txbox" style="width:124px; text-align:right" value="0" onkeydown="return Onlynum(this, event)" oninput="txCalc('tx18'); txChange_Num('tx18')" /></td>
									</tr>
									<tr class="trtxco">
										<td style="width:85px; text-align:left" class="fcont">Rte. IVA:</td>
										<td align="right">
											<select name="cbEVRteIva" id="cbEVRteIva" style="width:60px" class="txbox" onchange="TaxCalc('cbEVRteIva', 'tx19', 'tx16')" >
												<option value="0">0</option>
												<?=$cbRva=LoadConfTab($link, "SELECT Tasa_Impuesto FROM XConf_Taxes WHERE Tipo_Impuesto = 'RETENCION IVA'")?>
											</select>
											<input name="Rete_IVA" id="tx19" maxlength="15" class="txbox" style="width:124px; text-align:right" value="0" onkeydown="return Onlynum(this, event)" oninput="txCalc('tx19'); txChange_Num('tx19')" /></td>
									</tr>
									<tr class="trtxco">
										<td style="width:85px; text-align:left" class="fcont">Total a pagar:</td>
										<td align="right"><input name="Total_Pagar" id="tx20" maxlength="15" class="txboxdis" style="width:188px; text-align:right" value="0" type="text" readonly="true" /></td>
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
											<select name="Medio_Pago" id="tx21" style="width:172px" class="txboxo" onchange="cbEVMedPay_Change()" >
												<option value="EFECTIVO">EFECTIVO</option>
												<option value="BANCOS">BANCOS</option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Instrumento:</td>
										<td align="right">
											<select name="Instrumento" id="tx22" style="width:172px" class="txboxo" onchange="DisBtn()" >
												<option value="EFECTIVO">EFECTIVO</option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Nombre banco:</td>
										<td align="right">
											<select name="Banco" id="tx23" style="width:172px" class="txboxdis" disabled="disabled" onchange="cbEVBank_Change()" >
												<option value=""></option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Número cuenta:</td>
										<td align="right">
											<select name="Cuenta" id="tx24" style="width:172px" class="txboxdis" disabled="disabled" >
												<option value=""></option>
											</select>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Número cheque:</td>
										<td align="right"><input name="Codigo_Instrumento" id="tx25" maxlength="45" class="txboxdis" style="width:166px; text-align:left" value="" type="text" disabled="disabled" />
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Centro costos:</td>
										<td align="right">
											<select name="Centro_Costos" id="tx26" style="width:172px" class="txboxo" onchange="DisBtn()" >
												<option value=""></option>
												<?=$cbCte=LoadConfTab($link, "SELECT * FROM XConf_CostCenter")?>
											</select>
										</td>
									</tr>
								</table>	
							</div>
						</div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden; padding-bottom:3px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcont" style="width:88px;" onclick="Accept_Egreso()" /></div>
						<div style="float:left; margin-right:10px"><input name="btprint" id="btprint" type="button" value="Imprimir" class="btcontdis" style="width:88px;" onclick="cmEVPrint_Click()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btgobil" id="btgobil" type="button" value="Ir a Cheque" class="btcontdis" style="width:96px;" onclick="GoToCheque('1')" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value=""  />
						<input type="hidden" name="Codigo_Movimiento" id="tx2" value="144" />
						<input type="hidden" name="Tipo_Movimiento" id="tx3" value="PAGOS DESDE VENTANILLA" />
						<input type="hidden" name="Estado" id="tx27" value="ACTIVO" />
						<input type="hidden" name="Moneda" id="tx13" value="<?=$sExcSet[3]?>" />
						<input type="hidden" name="Precio" id="tx14" value="1" />
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
	    <iframe name="frPrint1" id="frPrint1" align="left" frameborder="0" style="width:0px; height:0px;" src=""></iframe>
	</div>
</div>
</body>
</html>
