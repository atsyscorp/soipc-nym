<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura de variables
	$var[1]=$_GET['var1'];
	$var[2]=$_GET['var2'];
	$var[3]=$_GET['var3'];
	$var[4]=$_GET['var4'];	//--> Origen
	//---------------------------------------------------
	//Carga combo de sucurasales
	$strSQLS = "SELECT Codigo_Sucursal FROM Sucursales";
	//---------------------------------------------------
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title>Reporte de movimiento ventanilla</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frRepConsOps.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="TabSize(); frRepConsOps_Load('<?=$var[4]?>', '<?=$var[1]?>', '<?=$var[2]?>')">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
	<div align="center" style="width:100%; margin-top:0px; height:100%">
		<div align="center" style="width:100%; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 5)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 650, 5, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div id="dpup3" style="position:relative; top:0px; left:0px; z-index:65">
				<div style="width:100%; position:absolute; top:100px; visibility:hidden" align="center" id="dProg">
					<div style="margin:auto; width:300px" class="drod_1 dlin_1 dsom_3 bgcol_2">
						<div style="margin:10px; font-weight:bold" class="fgreen" id="dPrMsg">Iniciando proceso...</div>					
					</div>
				</div>
			</div>
			<div class="bgcol_2" align="left" style="width:100%; overflow:hidden">
				<div style="margin:0px">
					<div class="dlin_1">
					<div style="margin-top:7px; margin-left:8px">
						<div style="float:left; width:525px; margin-right:15px">
							<div>
								<div style="float:left" class="fgreen"><strong>Criterios de reporte</strong></div>
								<div style="float:left; width:393px; margin-top:9px; margin-left:10px" class="dlin_4"></div>
								<div style="clear:both"></div>
							</div>
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:7px">
								<tr class="fcont">
									<td style="width:80px; text-align:left">Sucursal:</td>
									<td style="width:80px">Estación:</td>
									<td style="width:182px">Fecha inicial:</td>
									<td style="width:183px">Fecha final:</td>
								</tr>
								<tr class="trtxco">
									<td align="left">
										<select name="cbCVSucur" id="tx1" style="width:72px" class="txboxo" onchange="cbSuc_Change()">
											<option value=""></option>
											<option value="TODAS">TODAS</option>
											<?=$cbLoad=LoadConfTab($link, $strSQLS)?>
										</select>
									</td>
									<td align="left">
										<select name="cbCVCaja" id="tx2" style="width:72px" class="txboxo" onchange="cbCaja_Change()">
											<option value=""></option>
											<option value="TODAS">TODAS</option>
										</select>
									</td>
									<td align="left">
										<select name="cbYear" id="cbYear" style="width:58px; margin-right:2px" class="txboxo" onchange="getmdays(); disbtn('btclose')" >
											<option value="">Año</option>
											<?php
												//Captura año actual
												$nyear = date('Y');
												for($z = $nyear - 5; $z <= $nyear; $z++)
												{
											?>
												<option value="<?=$z?>"><?=$z?></option>
											<? } ?>
										</select>
										<select name="cbMonth" id="cbMonth" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays(); disbtn('btclose')" >
											<option value="">Mes</option>
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
										</select>
										<select name="cbDay" id="cbDay" style="width:50px;" class="txboxo" onchange="disbtn('btclose')" >
											<option value="">Día</option>
										</select>
									</td>
									<td align="left">
										<select name="cbYear1" id="cbYear1" style="width:58px; margin-right:2px" class="txboxo" onchange="getmdays_1(); disbtn('btclose')" >
											<option value="">Año</option>
											<?php
												//Captura año actual
												$nyear = date('Y');
												for($z = $nyear - 5; $z <= $nyear; $z++)
												{
											?>
												<option value="<?=$z?>"><?=$z?></option>
											<? } ?>
										</select>
										<select name="cbMonth1" id="cbMonth1" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays_1(); disbtn('btclose')" >
											<option value="">Mes</option>
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
										</select>
										<select name="cbDay1" id="cbDay1" style="width:50px;" class="txboxo" onchange="disbtn('btclose')" >
											<option value="">Día</option>
										</select>
										<input type="hidden" name="txsProg" id="txsProg" value="Iniciando proceso..." />
									</td>
								</tr>			
							</table>
						</div>
						<div style="float:left; width:350px">
							<div>
								<div style="float:left" class="fgreen"><strong>Agrupación de resultados</strong></div>
								<div style="float:right; width:195px; margin-top:9px" class="dlin_4"></div>
								<div style="clear:both"></div>
							</div>
							<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:7px">
								<tr class="fcont">
									<td style="width:115px; text-align:left"><input type="radio" name="rbAgrupa" id="rbAgrupa" value="Estación" checked="checked" onclick="">Por estaciones</td>
									<td style="text-align:left"><input type="radio" name="rbAgrupa" id="rbAgrupa" value="Total empresa" onclick="">Total empresa</td>
								</tr>
								<tr style="height:25px; vertical-align:middle" class="fcont">
									<td style="width:115px; text-align:left"><input type="radio" name="rbAgrupa" id="rbAgrupa" value="Sucursal" onclick="">Por sucursales</td>
									<td></td>
								</tr>
							</table>
						</div>
						<div style="clear:both"></div>
					</div>				
					<div style="clear:both"></div>
					<div style="margin:8px; padding-bottom:5px; overflow:hidden">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Generar reporte" class="btcont" style="width:120px;" onclick="cmAccept_Clic('<?=$var[4]?>', '<?=$var[3]?>')" /></div>
						<div style="float:left; margin-right:10px"><input name="btclose" id="btclose" type="button" value="Cierre ventanilla" class="btcontdis" style="width:120px;" onclick="cmClose_Clic('<?=$var[3]?>')" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcontdis" style="width:120px;" onclick="cmExport_Clic('<?=$var[3]?>', '<?=$sGenSet[0]?>', '<?=$var[4]?>', '<?=$sGenSet[1]?>')" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:120px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div class="dlin_8">
						<input name="bttot" id="bttot" type="button" value="Totales reporte" class="bttabsel" style="width:120px;" onclick="Tab_Change('dTot', 'bttot')"/>					
						<input name="btcom" id="btcom" type="button" value="Compra divisas" class="bttab" style="width:120px;" onclick="Tab_Change('dComp', 'btcom')"/>					
						<input name="btven" id="btven" type="button" value="Venta divisas" class="bttab" style="width:120px;" onclick="Tab_Change('dVent', 'btven')"/>					
						<input name="btaju" id="btaju" type="button" value="Traslados" class="bttab" style="width:120px;" onclick="Tab_Change('dTras', 'btaju')"/>					
						<input name="btegr" id="btegr" type="button" value="Pagos ventanilla" class="bttab" style="width:120px;" onclick="Tab_Change('dPago', 'btegr')"/>					
					</div>
					</div>
					<div style="margin:0px; position:relative; overflow:visible;" id="dTabs">
						<div style="position:relative; top:0px; left:0px; width:100%;" id="dTot">
							<div style="padding:3px; padding-left:7px; margin-bottom:2px; border-top:none" class="fgreen bgcol_4 dlin_3"><strong>Saldos moneda nacional y extranjera</strong></div>
							<div id="dTot_Tab" class="dlin_1 bgcol_1" style="overflow:auto">
								<table id="lstTot_Tab" cellpadding="0" cellspacing="0">
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
								</table>
							</div>
							<div style="margin-top:5px" id="dTotals1">
								<div style="float:left; width:260px; margin-right:7px">
									<div style="padding:3px; padding-left:7px; margin-bottom:2px;" class="fgreen bgcol_4 dlin_3"><strong>Totales compra y venta</strong></div>
									<div id="dTotComVen" class="dlin_1 bgcol_1" style="overflow:auto; height:154px"></div>
								</div>							
								<div style="float:left; width:260px; margin-right:7px">
									<div style="padding:3px; padding-left:7px; margin-bottom:2px;" class="fgreen bgcol_4 dlin_3"><strong>Totales pagos y traslados</strong></div>
									<div id="dTotPagTrs" class="dlin_1 bgcol_1" style="overflow:auto; height:154px"></div>
								</div>							
								<?
									if($var[4] == 'CIERRE'){	
								?>
								<div style="float:left; width:490px; margin-right:10px">
									<div style="padding:3px; padding-left:7px; margin-bottom:2px;" class="fgreen bgcol_4 dlin_3"><strong>Firmas cajeros</strong></div>
									<div id="dTotSign" class="dlin_1 bgcol_1" style="overflow:auto; height:154px">
										<div style="float:left; width:230px; margin-left:5px">
											<div style="height:113px; text-align:center" class="dlin_4"></div>
											<div style="margin-top:3px;" class="fcont">Cajero principal</div>
											<div style="margin-top:3px;" class="fcont"><?=$var[3]?></div>
										</div>	
										<div style="float:right; width:230px; margin-right:5px">
											<div style="height:113px; text-align:center" class="dlin_4"></div>
											<div style="margin-top:3px;" class="fcont">Cajero auxiliar</div>
											<div style="margin-top:3px;" class="fcont"></div>
										</div>
										<div style="clear:both"></div>	
									</div>
								</div>
								<? } ?>
								<div style="clear:both"></div>							
							</div>
						</div>
						<div style="position:absolute; top:0px; left:0px; visibility:hidden; width:100%" id="dComp">
							<div style="padding:3px; padding-left:7px; margin-bottom:2px; border-top:none" class="fgreen bgcol_4 dlin_3"><strong>Operaciones compra de divisas</strong></div>
							<div id="dComp_Tab" class="dlin_1 bgcol_1" style="overflow:auto"><iframe name="frComp_Tab" id="frComp_Tab" align="left" frameborder="0" style="width:100%; height:100%; overflow:visible"></iframe></div>
						</div>
						<div style="position:absolute; top:0px; left:0px; visibility:hidden; width:100%" id="dVent">
							<div style="padding:3px; padding-left:7px; margin-bottom:2px; border-top:none" class="fgreen bgcol_4 dlin_3"><strong>Operaciones venta de divisas</strong></div>
							<div id="dVent_Tab" class="dlin_1 bgcol_1" style="overflow:auto"><iframe name="frVent_Tab" id="frVent_Tab" align="left" frameborder="0" style="width:100%; height:100%; overflow:visible"></iframe></div>
						</div>
						<div style="position:absolute; top:0px; left:0px; visibility:hidden; width:100%" id="dTras">
							<div style="padding:3px; padding-left:7px; margin-bottom:2px; border-top:none" class="fgreen bgcol_4 dlin_3"><strong>Traslados de recursos</strong></div>
							<div id="dTras_Tab" class="dlin_1 bgcol_1" style="overflow:auto"><iframe name="frTras_Tab" id="frTras_Tab" align="left" frameborder="0" style="width:100%; height:100%; overflow:visible"></iframe></div>
						</div>
						<div style="position:absolute; top:0px; left:0px; visibility:hidden; width:100%" id="dPago">
							<div style="padding:3px; padding-left:7px; margin-bottom:2px; border-top:none" class="fgreen bgcol_4 dlin_3"><strong>Pagos desde ventanilla</strong></div>
							<div id="dPago_Tab" class="dlin_1 bgcol_1" style="overflow:auto"><iframe name="frPago_Tab" id="frPago_Tab" align="left" frameborder="0" style="width:100%; height:100%; overflow:visible"></iframe></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
