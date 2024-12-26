<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura de variables
	$var[1] = (isset($_GET['var1'])) ? $_GET['var1'] : NULL;
	$var[2] = (isset($_GET['var2'])) ? $_GET['var2'] : NULL;
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion cambiaria
	$getExcSet = ExcSet($link);
	//---------------------------------------------------
	//Carga combo monedas
	$strSQL = "SELECT Moneda FROM XConf_Monedas WHERE Moneda <> '". $sExcSet[3] ."'";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Precios compra y venta</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsTasas.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:286px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 93)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 282, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Precios compra y venta</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div>
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:125px; text-align:left" class="fcont">Seleccione moneda:</td>
								<td align="right">
									<select name="Moneda" id="tx3" style="width:144px; margin-left:10px" class="txboxo" onchange="Moneda_OnChange('<?=$var[1]?>','<?=$var[2]?>')">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSQL)?>
									</select>
								</td>
							</tr>						
							<tr class="trtxco">
								<td style="width:125px; text-align:left" class="fcont"><input name="chTOTipoBase" id="chTOTipoBase" type="checkbox" value="" onchange="chTipoBase_Changed()" />Tipo de base IVA:</td>
								<td align="right">
									<select name="Tipo_Base" id="tx4" style="width:144px; margin-left:10px" class="txboxdis" disabled="disabled" onchange="SelTypeBase()">
										<option value=""></option>
										<option value="SUCURSAL">SUCURSAL</option>
										<option value="TOTAL EMPRESA">TOTAL EMPRESA</option>
									</select>
								</td>
							</tr>						
							<tr class="trtxco">
								<td style="width:125px; text-align:left" class="fcont"><input name="chTOBase" id="chTOBase" type="checkbox" value="" onchange="chBase_Changed()" />Precio base IVA:</td>
								<td align="right"><input name="Precio_Base" id="tx5" maxlength="15" class="txboxdis" style="width:138px; text-align:right" value="0" onkeydown="" disabled="disabled" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx5')"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="width:125px; text-align:left" class="fcont">Precio de compra:</td>
								<td align="right"><input name="Precio_Compra" id="tx6" maxlength="15" class="txboxo" style="width:138px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx6')" /></td>
							</tr>						
							<tr class="trtxco">
								<td style="width:125px; text-align:left" class="fcont">Tipo precio de venta:</td>
								<td align="right">
									<select name="Tipo_Venta" id="tx7" style="width:144px; margin-left:10px" class="txboxo" >
										<option value=""></option>
										<option value="CON IVA">CON IVA</option>
										<option value="SIN IVA">SIN IVA</option>
									</select>
								</td>
							</tr>						
							<tr class="trtxco">
								<td style="width:125px; text-align:left" class="fcont">Precio de venta:</td>
								<td align="right"><input name="Precio_Venta" id="tx8" maxlength="15" class="txboxo" style="width:138px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx8')" /></td>
							</tr>						
						</table>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="Accept_Tasas()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="Modi_Tasas()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:73px;" onclick="closewin()" /></div>
						<input type="hidden" name="Sucursal" id="Sucursal" value="<?=$var[1]?>"  />
						<input type="hidden" name="Estacion" id="Estacion" value="<?=$var[2]?>" />
						<input type="hidden" name="Sucursal" id="tx1" value="<?=$var[1]?>"  />
						<input type="hidden" name="Estacion" id="tx2" value="<?=$var[2]?>" />
					</div>
					<div style="clear:both"></div>
					<div id="tabTasas" style="height:125px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="555px">
							<tr class="bgcol_6 fwhite">
								<td style="width:50px; text-align:left" class="celrow">Sucursal</td>
								<td style="width:50px; text-align:left" class="celrow">Estación</td>
								<td style="width:55px; text-align:left" class="celrow">Moneda</td>
								<td style="width:80px; text-align:left" class="celrow">Tipo base</td>
								<td style="width:80px; text-align:left" class="celrow">Precio base</td>
								<td style="width:90px; text-align:left" class="celrow">Precio compra</td>
								<td style="width:70px; text-align:left" class="celrow">Tipo venta</td>
								<td style="width:80px; text-align:left" class="celrow">Precio venta</td>
							</tr>
							<?=$listLoad = LoadTable($link, "Select * From Tasas Where Sucursal = '".$var[1]. "' And Estacion = '". $var[2]. "'", 'true', 2)?>
						</table>				
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
