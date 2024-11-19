<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//Carga tabla de cuentas 
	$strSQL = "SELECT * FROM Cuentas_Bancarias";
	//Carga combo de bancos
	$strSQB = "SELECT Nombre_Banco FROM XConf_Bancos";
	//---------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuración cuentas bancarias</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConfBanks.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:464px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 182)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 460, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración cuentas bancarias</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-bottom:7px">
						<div style="float:left" class="fgreen"><strong>Información de cuenta</strong></div>
						<div style="float:left; width:308px; margin-top:9px; margin-left:10px" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; margin-top:4px" class="fgreen"><strong>Número de cuenta:</strong></div>
						<div style="float:right;">
							<input name="Numero_Cuenta" id="tx1" maxlength="50" class="txboxo" style="width:320px; text-align:left" value="" onkeyup="txId_Change()" />						
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; width:218px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:48px; text-align:left" class="fcont">Banco:</td>
									<td align="right">
										<select name="Banco" id="tx2" style="width:160px" class="txboxo" onchange="cbBank_Change()">
											<option value=""></option>
											<?=$cbBank=LoadConfTab($link, $strSQB)?>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="width:48px; text-align:left" class="fcont">NIT:</td>
									<td align="right">
										<input name="NIT_Banco" id="tx3" maxlength="50" class="txboxdis" style="width:154px; text-align:left" value="" disabled="disabled" />
									</td>
								</tr>
								<tr class="trtxco">
									<td style="width:48px; text-align:left" class="fcont">Código:</td>
									<td align="right">
										<input name="Codigo_Banco" id="tx4" maxlength="50" class="txboxdis" style="width:154px; text-align:left" value="" disabled="disabled" />
									</td>
								</tr>
							</table>
						</div>
						<div style="float:right; width:218px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:85px; text-align:left" class="fcont">Tipo cuenta:</td>
									<td align="right">
										<select name="Tipo_Cuenta" id="tx5" style="width:145px" class="txbox" onchange="">
											<option value=""></option>
											<option value="CORRIENTE">CORRIENTE</option>
											<option value="AHORROS">AHORROS</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="width:85px; text-align:left" class="fcont">Sucursal:</td>
									<td align="right">
										<input name="Sucursal" id="tx6" maxlength="50" class="txbox" style="width:139px; text-align:left" value=""/>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="width:85px; text-align:left" class="fcont">Cuenta Puc:</td>
									<td align="right">
										<input name="Cuenta_Puc" id="tx7" maxlength="50" class="txbox" style="width:139px; text-align:left" value=""/>
									</td>
								</tr>
							</table>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin-top:7px">
						<div style="float:left" class="fgreen"><strong>Datos de contacto</strong></div>
						<div style="float:left; width:330px; margin-top:9px; margin-left:10px" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:56px; text-align:left" class="fcont">Contacto:</td>
								<td align="left">
									<input name="Contacto" id="tx8" maxlength="50" class="txbox" style="width:145px; text-align:left; margin-left:5px" value=""/>
								</td>
								<td style="width:64px; text-align:right" class="fcont">Teléfonos:</td>
								<td align="right">
									<input name="Telefono" id="tx9" maxlength="50" class="txbox" style="width:145px; text-align:left; margin-left:5px" value=""/>
								</td>
							</tr>
						</table>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<div class="fcont" style="margin-bottom:5px">Observaciones:</div>
						<div><textarea name="Observaciones" cols="0" class="txbox" id="tx10" style="width:442px; overflow:auto; height:30px; resize:none;"></textarea></div>
					</div>
					<div style="margin-top:10px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="Count_Accept()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="Modi_Count()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btdelete" id="btdelete" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Count()" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div id="tabBanks" style="height:125px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="930px">
							<tr class="bgcol_6 fwhite" id="trTasTit">
								<td style="width:85px; text-align:left; vertical-align:middle;" class="celrow">Número</td>
								<td style="width:90px; text-align:left; vertical-align:middle;" class="celrow">Banco</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">NIT Banco</td>
								<td style="width:65px; text-align:left; vertical-align:middle;" class="celrow">Código</td>
								<td style="width:75px; text-align:left; vertical-align:middle;" class="celrow">Tipo cuenta</td>
								<td style="width:85px; text-align:left; vertical-align:middle;" class="celrow">Sucursal</td>
								<td style="width:95px; text-align:left; vertical-align:middle;" class="celrow">Cuenta Puc</td>
								<td style="width:75px; text-align:left; vertical-align:middle;" class="celrow">Contacto</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Teléfonos</td>
								<td style="width:200px; text-align:left; vertical-align:middle;" class="celrow">Observaciones</td>
								<? $listLoad = LoadTable($link, $strSQL, 'true', 0); ?>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
