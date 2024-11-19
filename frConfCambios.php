<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion cambiaria
	$getExcSet = ExcSet($link);
	//-------------------------------------
	//String consulta monedas
	$strSQL = "SELECT Moneda FROM XConf_Monedas";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuracion cambiaria</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConfCambios.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="frConfCambios_Load('<?=$sExcSet[3]?>')">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:502px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 201)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 498, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración cambiaria</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-bottom:7px">
						<div style="float:left" class="fgreen"><strong>Información general</strong></div>
						<div style="float:right; width:360px; margin-top:9px;" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; width:236px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:150px; text-align:left" class="fcont">Tope pago efectivo (USD):</td>
									<td align="right"><input name="Tope_Efectivo" id="tx1" maxlength="10" class="txboxo" style="width:80px; text-align:center" value="<?=number_format($sExcSet[0], 0, $GLdecsepa, $GLmilsepa)?>" onkeydown="return Onlynum(this, event)" oninput="txChange_Num('tx1')" /></td>
								</tr>
								<tr class="trtxco">
									<td style="width:150px; text-align:left" class="fcont">Código reporte UIAF:</td>
									<td align="right"><input name="Codigo_UIAF" id="tx2" maxlength="10" class="txboxo" style="width:80px; text-align:center" value="<?=$sExcSet[1]?>" onkeydown="return Onlynum(this, event)" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Digitalización documentos:</td>
									<td align="right">
										<select name="Foto_Documentos" id="tx3" style="width:86px;" class="txboxo">
											<option value=""></option>
											<option value="SI" <? if($sExcSet[2] == 'SI'){echo 'selected="selected"';}?> >SI</option>
											<option value="NO" <? if($sExcSet[2] == 'NO'){echo 'selected="selected"';}?>>NO</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Moneda local:</td>
									<td align="right">
										<select name="Moneda_Local" id="tx4" style="width:86px;" class="txboxo">
											<option value=""></option>
											<?=$cbLoad=LoadConfTab($link, $strSQL)?>
										</select>
									</td>
								</tr>						
							</table>
						</div>
						<div style="float:right; width:236px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Digitalización de huella:</td>
									<td align="right">
										<select name="Huella_Digital" id="tx5" style="width:86px;" class="txboxo">
											<option value=""></option>
											<option value="SI" <? if($sExcSet[4] == 'SI'){echo 'selected="selected"';}?> >SI</option>
											<option value="NO" <? if($sExcSet[4] == 'NO'){echo 'selected="selected"';}?>>NO</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Digitalización firma:</td>
									<td align="right">
										<select name="Firma_Digital" id="tx6" style="width:86px;" class="txboxo">
											<option value=""></option>
											<option value="SI" <? if($sExcSet[5] == 'SI'){echo 'selected="selected"';}?> >SI</option>
											<option value="NO" <? if($sExcSet[5] == 'NO'){echo 'selected="selected"';}?>>NO</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="width:150px; text-align:left" class="fcont">Actualización cliente (días):</td>
									<td align="right"><input name="Actualiza_Cliente" id="tx7" maxlength="10" class="txboxo" style="width:80px; text-align:left" value="<?=$sExcSet[6]?>" onkeydown="return Onlynum(this, event)" /></td>
								</tr>
							</table>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Guardar" class="btcont" style="width:88px;" onclick="Save_Clic()" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="CodUiaf" id="CodUiaf" value="<?=$sExcSet[1]?>"  />
						<div style="clear:both"></div>
					</div>
					<div class="bgcol dlin_3 drod_1" style="padding:6px; margin-top:10px">
						<div class="fgreen"><b>Tipos de moneda</b></div>
						<table style="width:100%; margin-top:7px" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:70px; text-align:left" class="fcont">Abreviatura:</td>
								<td style="width:72px; text-align:right" class="fcont"><input name="Moneda" id="txA1" maxlength="4" class="txboxo" style="width:67px; text-align:center" value="" onkeyup="txCurrC_Change()" /></td>
								<td style="width:60px; text-align:center" class="fcont">Código:</td>
								<td style="width:72px; text-align:right" class="fcont"><input name="Codigo" id="txA2" maxlength="2" class="txboxo" style="width:67px; text-align:center" value="" onkeydown="return Onlynum(this, event)" /></td>
							</tr>
							<tr>
								<td style="width:70px; text-align:left" class="fcont">Nombre:</td>
								<td style="width:72px; text-align:right" class="fcont" colspan="3"><input name="Nombre" id="txA3" maxlength="50" class="txboxo" style="width:97%; text-align:center" value="" onkeyup="" /></td>
								<td style="width:102px; text-align:right" class="fcont"><input name="btaccept4" id="btaccept4" type="button" value="Ingresar" class="btcontdis" style="width:88px;" onclick="Reg_Accept('txA2', 'XConf_Monedas', 'A', '4', '3')" disabled="disabled" /></td>
								<td style="text-align:right" class="fcont"><input name="btdelete4" id="btdelete4" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Reg('txA1', 'Moneda', 'XConf_Monedas', 'txA2', '4')" disabled="disabled" /></td>
							</tr>
						</table>
					</div>					
					<div style="margin-top:10px; overflow:hidden">
						<div style="float:left; width:236px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:7px" class="fgreen"><strong>Numerales cambiarios compra</strong></div>
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:120px; text-align:left" class="fcont">Numeral cambiario:</td>
										<td style="text-align:right" class="fcont"><input name="Numeral" id="txB1" maxlength="6" class="txboxo" style="width:90px; text-align:left" value="" onkeyup="FindConf('txB1', 'txB2', '2', '2', 'SELECT Descripcion FROM XConf_Numerales WHERE Numeral=')" onkeydown="return Onlynum(this, event)"/></td>
									</tr>
								</table>				
								<div style=" margin-top:4px">
									<textarea name="Descripcion" cols="0" class="txbox" id="txB2" style="width:214px; overflow:auto; height:62px; resize:none;"></textarea>
								</div>
								<div style="margin-top:7px; padding-bottom:4px">
									<div style="float:left; margin-right:10px"><input name="btaccept2" id="btaccept2" type="button" value="Ingresar" class="btcontdis" style="width:88px;" onclick="Reg_Accept('txB2', 'XConf_Numerales', 'B', '2', '3')" disabled="disabled" /></div>
									<div style="float:left;"><input name="btdelete2" id="btdelete2" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Reg('txB1', 'Numeral', 'XConf_Numerales', 'txB2', '2')" disabled="disabled" /></div>
									<input type="hidden" name="Operacion" id="txB3" value="COMPRA"  />
									<div style="clear:both"></div>
								</div>
							</div>
						</div>
						<div style="float:right; width:236px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:7px" class="fgreen"><strong>Numerales cambiarios venta</strong></div>
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:120px; text-align:left" class="fcont">Numeral cambiario:</td>
										<td style="text-align:right" class="fcont"><input name="Numeral" id="txC1" maxlength="6" class="txboxo" style="width:90px; text-align:left" value="" onkeyup="FindConf('txC1', 'txC2', '3', '3', 'SELECT Descripcion FROM XConf_Numerales WHERE Numeral=')" onkeydown="return Onlynum(this, event)"/></td>
									</tr>
								</table>				
								<div style=" margin-top:4px">
									<textarea name="Descripcion" cols="0" class="txbox" id="txC2" style="width:214px; overflow:auto; height:62px; resize:none;"></textarea>
								</div>
								<div style="margin-top:7px; padding-bottom:4px">
									<div style="float:left; margin-right:10px"><input name="btaccept3" id="btaccept3" type="button" value="Ingresar" class="btcontdis" style="width:88px;" onclick="Reg_Accept('txC2', 'XConf_Numerales', 'C', '3', '3')" disabled="disabled" /></div>
									<div style="float:left;"><input name="btdelete3" id="btdelete3" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Reg('txC1', 'Numeral', 'XConf_Numerales', 'txC2', '3')" disabled="disabled" /></div>
									<input type="hidden" name="Operacion" id="txC3" value="VENTA"  />
									<div style="clear:both"></div>
								</div>
							</div>
						</div>
						<div style="clear:both"></div>						
					</div>					
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
