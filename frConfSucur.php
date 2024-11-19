<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//Carga Listado de ciudades
	$strCIT = "SELECT Ciudad FROM XConf_Ciudades";
	//---------------------------------------------------
	//Carga informacion de sucursales
	$strSQL = "SELECT * FROM Sucursales";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuración de sucursales</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConfSucur.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:570px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 235)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 566, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración de sucursales</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; width:249px; overflow:visible">
							<div>
								<div style="float:left" class="fgreen"><strong>Información general</strong></div>
								<div style="float:right; width:127px; margin-top:9px;" class="dlin_4"></div>
								<div style="clear:both"></div>
							</div>
							<table style="width:100%; margin-top:7px" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:110px; text-align:left" class="fcont">Código sucursal:</td>
									<td align="right"><input name="Codigo_Sucursal" id="tx1" maxlength="3" class="txboxo" style="width:135px; text-align:left; font-weight:bold" value="" onkeyup="txId_Change()" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Nombre sucursal:</td>
									<td align="right"><input name="Nombre" id="tx2" maxlength="50" class="txboxo" style="width:135px; text-align:left" value="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Matrícula mercan.:</td>
									<td align="right"><input name="Matricula" id="tx3" maxlength="15" class="txboxo" style="width:135px; text-align:left" value="" onkeydown="return Onlynum(this, event)" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Dirección:</td>
									<td align="right"><input name="Direccion" id="tx4" maxlength="50" class="txboxo" style="width:135px; text-align:left" value="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Teléfono:</td>
									<td align="right"><input name="Telefono" id="tx5" maxlength="20" class="txboxo" style="width:135px; text-align:left" value="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Ciudad:</td>
									<td align="right">
										<select name="Ciudad" id="tx6" style="width:141px;" class="txboxo">
											<option value=""></option>
											<?=$cbLoad=LoadConfTab($link, $strCIT)?>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Dirección IP:</td>
									<td align="right"><input name="IP_Adress" id="tx15" maxlength="100" class="txboxo" style="width:135px; text-align:left" value="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Encargado:</td>
									<td align="right"><input name="Encargado" id="tx7" maxlength="20" class="txbox" style="width:135px; text-align:left" value="" /></td>
								</tr>
							</table>
						</div>
						<div style="float:right; width:291px; overflow:visible">
							<div>
								<div style="float:left" class="fgreen"><strong>Información facturación</strong></div>
								<div style="float:right; width:145px; margin-top:9px;" class="dlin_4"></div>
								<div style="clear:both"></div>
							</div>
							<table style="width:100%; margin-top:7px" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="text-align:left; width:130px" class="fcont">Cantidad estaciones:</td>
									<td align="right">
										<select name="Cajas" id="tx8" style="width:163px;" class="txboxo">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Resol. facturación:</td>
									<td align="right"><input name="Resolucion" id="tx9" maxlength="20" class="txboxo" style="width:157px; text-align:left" value="" onkeydown="return Onlynum(this, event)" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Prefijo facturación:</td>
									<td align="right"><input name="Prefijo_Facturacion" id="tx10" maxlength="10" class="txboxo" style="width:157px; text-align:left" value="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Fecha resolución:</td>
									<td align="right">
										<select name="cbYear" id="cbYear" style="width:54px; margin-right:1px" class="txboxo" onchange="getmdays()" >
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
										<select name="cbMonth" id="cbMonth" style="width:55px; margin-right:1px" class="txboxo" onchange="getmdays()" >
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
										<select name="cbDay" id="cbDay" style="width:46px;" class="txboxo">
											<option value="">Día</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Inicio facturación:</td>
									<td align="right"><input name="Inicio_Factura" id="tx12" maxlength="10" class="txboxo" style="width:157px; text-align:left" value="" onkeydown="return Onlynum(this, event)" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Fin facturación:</td>
									<td align="right"><input name="Finaliza_Factura" id="tx13" maxlength="10" class="txboxo" style="width:157px; text-align:left" value="" onkeydown="return Onlynum(this, event)" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Observaciones:</td>
									<td align="right"><textarea name="Observaciones" cols="0" class="txbox" id="tx14" style="width:157px; overflow:auto; height:46px; resize:none;"></textarea></td>
								</tr>
							</table>
						</div>
						<div style="clear:both"></div>		
					</div>
					<div style="margin-top:10px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="Sucur_Accept()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="Modi_Sucur()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btdelete" id="btdelete" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Sucur()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Fecha_Resolucion" id="tx11" value=""  />
						<!-- Ocultos para las otras dos tablas -->
						<input type="hidden" name="Sucursal" id="txA1" value=""  />
						<input type="hidden" name="Copias_Compra" id="txA2" value="2"  />
						<input type="hidden" name="Copias_Venta" id="txA3" value="2"  />
						<input type="hidden" name="Fin_Consecutivo" id="txA4" value=""  />
						<input type="hidden" name="Resolucion" id="txA5" value=""  />
						<input type="hidden" name="Fecha_Final" id="txA6" value=""  />
						<!-- Consecutivos -->
						<input type="hidden" name="Identificacion" id="txB1" value=""  />
						<input type="hidden" name="Sucursal" id="txB2" value=""  />
						<input type="hidden" name="Documento" id="txB3" value=""  />
						<input type="hidden" name="Codigo" id="txB4" value=""  />
						<input type="hidden" name="Area" id="txB5" value="VENTANILLA"  />
						<input type="hidden" name="Prefijo" id="txB6" value=""  />
						<input type="hidden" name="Consecutivo" id="txB7" value=""  />
						<input type="hidden" name="Formato" id="txB8" value=""  />
						<input type="hidden" name="Tabla" id="txB9" value=""  />
					</div>
					<div style="clear:both"></div>
					<div id="tabSucur" style="height:168px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="1570px">
							<tr class="bgcol_6 fwhite" id="trSucTit">
								<td style="width:95px; text-align:left; vertical-align:middle;" class="celrow">Código sucursal</td>
								<td style="width:130px; text-align:left; vertical-align:middle;" class="celrow">Nombre</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Matrícula</td>
								<td style="width:130px; text-align:left; vertical-align:middle;" class="celrow">Dirección</td>
								<td style="width:90px; text-align:left; vertical-align:middle;" class="celrow">Teléfono</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Ciudad</td>
								<td style="width:120px; text-align:left; vertical-align:middle;" class="celrow">Encargado</td>
								<td style="width:90px; text-align:left; vertical-align:middle;" class="celrow">Cantidad cajas</td>
								<td style="width:85px; text-align:left; vertical-align:middle;" class="celrow">Resolución</td>
								<td style="width:70px; text-align:left; vertical-align:middle;" class="celrow">Prefijo</td>
								<td style="width:100px; text-align:left; vertical-align:middle;" class="celrow">Fecha resolución</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Inicio factura</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Fin factura</td>
								<td style="width:140px; text-align:left; vertical-align:middle;" class="celrow">Observaciones</td>
								<td style="text-align:left; vertical-align:middle;" class="celrow">Dirección IP</td>
							</tr>
							<? $listLoad = LoadTable($link, $strSQL, 'true', 0); ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
