<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura variables para saber si es ventana de declarante
	//o beneficiario y documento de ambos
	$var[1]= (isset($_GET['var1'])) ? $_GET['var1'] : ''; //--> Dec o Ben
	$var[2]= (isset($_GET['var2'])) ? $_GET['var2'] : ''; //--> Acceso usuario
	//Variables de caja
	$var[3]= (isset($_GET['var3'])) ? $_GET['var3'] : ''; //--> Sucursal
	$var[4]= (isset($_GET['var4'])) ? $_GET['var4'] : ''; //--> Caja
	$var[5]= (isset($_GET['var5'])) ? $_GET['var5'] : ''; //--> Usuario
	//--------------------------------------------------
	//Variables que indican si viene desde compra o venta
	$var[6]= (isset($_GET['var6'])) ? $_GET['var6'] : ''; //--> Identificacion Declarante
	$var[7]= (isset($_GET['var7'])) ? $_GET['var7'] : ''; //--> Identificación Beneficiario
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion cambiaria
	$getExcSet = ExcSet($link);
	//Carga listado de hoteles
	$strSQL = "Select * From XConf_Hoteles Order By Nombre_Hotel";
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Conocimiento de Cliente</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/webcam.js" type="text/javascript"></script>
<script src="scripts/frOpsClientes.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="frOpsClientes_Load('<?=$var[6]?>', '<?=$var[7]?>')">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:654px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 274)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 650, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div id="dpup3" style="position:relative; top:0px; left:0px">
				<div id="frActividad" style="position:absolute; z-index:60; top:140px; left:7px; width:640px; overflow:hidden; visibility:hidden" class="drod_1 dlin_1 dsom_3 bgcol_2">
					<div class="bgcol_6" style="overflow:hidden">
						<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Consulta de ocupación o actividad</div>
						<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="hidcap('frActividad'); hidcap('dbloc')"><img src="images/close.png" style="height:20px; width:auto" /></div>
					</div>
					<div style="margin:8px">
						<div>
							<div class="fcont" align="left">Digite el código o la palabra clave de la ocupación que busca:</div>
							<div style="margin-top:3px"><input name="txFAct" id="txFAct" maxlength="50" class="txbox" style="width:615px; text-align:left" value="" onkeyup="txAct_Find()" /></div>
						</div>
						<div style="margin-top:10px">
							<div align="left" style="padding:4px; border-bottom:none" class="fcont bgcol drod_4 dlin_3">Haga clic sobre la actividad buscada para enviar a conocimiento del cliente</div>
							<div style="height:134px; overflow:auto" class="dlin_1 bgcol_1" align="left">
								<table cellpadding="0" cellspacing="0" id="lstOAList" width="605px">					
									<tr class="bgcol_6 fwhite">
										<td style="width:66px; text-align:left" class="celrow">Código</td>
										<td style="width:539px; text-align:left" class="celrow">Ocupación o actividad</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="dpup4" style="position:relative; top:0px; left:0px">
				<div id="frPics" style="position:absolute; z-index:65; top:200px; left:7px; width:640px; overflow:hidden; visibility:hidden" align="center" class="fcont">Clic sobre la imagen para cerrar
				<img id="imPics1" src="" class="drod_1 dlin_1 dsom_3" style="width:540px; height:auto; cursor:pointer; margin-top:7px" onclick="hidcap('frPics'); hidcap('dbloc')" />
				</div>
			</div>			
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Conocimiento clientes</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="padding:4px; padding-bottom:4px; overflow:hidden" class="bgcol dlin_4">
					<div style="float:left; margin-right:3px"><input name="btdec" id="btdec" type="button" value="Información del declarante" class="btcont" style="width:170px;" onclick="hidcap('dfrBen'); hidcap('dfrFind'); Go_Dec()" /></div>
					<div style="float:left; margin-right:3px"><input name="btben" id="btben" type="button" value="Información del beneficiario" class="btcontdis" style="width:170px;" onclick="hidcap('dfrFind'); viscap('dfrBen'); Go_Ben(); " disabled="disabled" /></div>
					<div style="float:left; margin-right:10px"><input name="btlook" id="btlook" type="button" value="Consulta de clientes" class="btcont" style="width:170px;" onclick="hidcap('dfrBen'); viscap('dfrFind')" /></div>
				</div>
				<div style="clear:both"></div>
				<!-- Div de informacion del beneficiario -->
				<div id="dfrBen" style="position:relative; height:0px; left:0px; visibility:hidden; z-index:100">
					<iframe id="frBen" src="frOpsClientesBen.php" frameborder="0" scrolling="no" style="width:654px; height:613px; overflow:hidden"></iframe>
				</div>
				<div id="dfrFind" style="position:relative; height:0px; left:0px; visibility:hidden; z-index:110">
					<iframe id="frFind" src="frOpsClientesFind.php" frameborder="0" scrolling="no" style="width:654px; height:613px; overflow:hidden"></iframe>
				</div>
				<div style="margin:8px">
					<div style="margin-top:8px">
						<div style="float:left; margin-right:8px" class="fgreen"><strong>Información del declarante</strong></div>
						<div style="float:left" class="fcont"><input type="radio" name="rbDecben" id="rbDecben" value="mismo" checked="checked" onclick="rbDecBen_Clic()">Mismo beneficiario <input type="radio" name="rbDecben" id="rbDecben" value="diferente" onclick="rbDecBen_Clic()">Diferente beneficiario</div>
						<div style="float:right; width:202px; margin-top:9px" class="dlin_4"></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:8px; overflow:hidden">
						<div style="float:left; width:312px; overflow:hidden">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Identificación:</td>
									<td align="left"><input name="Documento" id="tx2" maxlength="20" class="txboxo" style="width:120px; text-align:left; margin-right:4px" value="" onkeypress="return txID_Press(this, event, '<?=$sExcSet[2]?>', '<?=$sExcSet[6]?>')" oninput="txCODDoc_TextChanged()" /><span class="fcont">DV</span><input name="DV" id="tx3" maxlength="1" class="txboxdis" style="width:20px; text-align:left; margin-left:4px" value="" readonly="true" onkeydown="" oninput="txCODDoc_TextChanged()" /><div style="float:right; padding-top:0px; text-align:left"><input name="btfind" id="btfind" type="button" value="" class="btcont" style="width:24px; height:23px; background-image:url(images/look.png); background-repeat:no-repeat; background-size:100%" onclick="cmCODFind_Click('<?=$sExcSet[2]?>', '<?=$sExcSet[6]?>')" /></div>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Tipo documento:</td>
									<td align="right">
										<select name="Tipo_Documento" id="tx4" style="width:204px" class="txboxo" onchange="cbDoc_Change()" >
											<option value=""></option>
											<?=$cbTD=LoadConfTab($link, "SELECT Tipo_Documento FROM XConf_TiposDoc WHERE Tipo_Cliente = 'DEC' OR Tipo_Cliente = 'AMB'")?>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Primer nombre:</td>
									<td align="right"><input name="Nombre_1" id="tx5" maxlength="40" class="txboxo" style="width:198px; text-align:left" value="" onkeypress="return txNameAdd_Press(this, event)" oninput="txName_Change()"  /></td>					
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Segundo nombre:</td>
									<td align="right"><input name="Nombre_2" id="tx6" maxlength="40" class="txbox" style="width:198px; text-align:left" value="" onkeypress="return txNameAdd_Press(this, event)" oninput="txName_Change()" /></td>					
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Primer apellido:</td>
									<td align="right"><input name="Apellido_1" id="tx7" maxlength="40" class="txboxo" style="width:198px; text-align:left" value="" onkeypress="return txNameAdd_Press(this, event)" oninput="txName_Change()" /></td>					
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Segundo apellido:</td>
									<td align="right"><input name="Apellido_2" id="tx8" maxlength="40" class="txbox" style="width:198px; text-align:left" value="" onkeypress="return txNameAdd_Press(this, event)" oninput="txName_Change()" /></td>					
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Nombre completo:</td>
									<td align="right"><textarea name="Nombre_Completo" cols="0" class="txboxdis" id="tx9" style="width:198px; overflow:auto; height:74px; resize:none; margin-bottom:3px" disabled="disabled" onkeydown="return txNameAdd_Press(this, event)"></textarea></td>					
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Nacionalidad:</td>
									<td align="right">
										<select name="Nacionalidad" id="Nacionalidad" style="width:204px" class="txboxo" onchange="" >
											<option value=""></option>
											<?=$cbNA=LoadConfTab($link, "SELECT * FROM XConf_Paises")?>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Ciudad:</td>
									<td align="right">
										<input name="Ciudad" id="Ciudad" maxlength="40" class="txboxo" style="width:198px; text-align:left" value="" onkeypress="return txNameAdd_Press(this, event)" />
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left; vertical-align:middle" class="fcont">Teléfono fijo:
										<div style="float:right; margin-right:8px; font-size:18px; height:22px; width:22px; text-align:center" class="btcont" onclick="menusol('dHotels'); document.getElementById('lookHotel').focus()"><b>H</b></div>
											<div style="position:relative">
											<div id="dHotels" style="position:absolute; left:77px; top:-120px; width:233px; height:100px; overflow:auto; z-index:30; visibility:hidden;" class="dlin_3 drod_1 bgcol_1">
												<div style="text-align:center; margin:2px">
													<input name="lookHotel" id="lookHotel" maxlength="40" class="txbox" style="width:203px; text-align:left" value="" onkeyup="txHotel_Find()" />
												</div>
												<table cellpadding="0" cellspacing="0" width="100%" id="lstHotel">
													<?
														while($n=mysqli_fetch_array($p)){
													?>
													<tr class="fcont trwhite" style="cursor:pointer; text-align:left" onclick="selHotel('<?=$n[0]?>', '<?=$n[1]?>', '<?=$n[2]?>')"><td class="celrow"><?=$n[0]?></td></tr>
													<? } ?>
												</table>
											</div>
											</div>
										</div>
									</td>
									<td align="right">
										<select name="Indicativo" id="tx11" style="width:40px" class="txbox" onkeydown="return cbCliDel(this, event)">
											<option value=""></option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
										</select>
										<input name="Telefono" id="tx12" maxlength="9" class="txbox" style="width:152px; text-align:left; margin-left:3px" value="" onkeydown="return Onlynum(this, event)" />
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Celular:</td>
									<td align="right"><input name="Celular" id="tx13" maxlength="10" class="txbox" style="width:198px; text-align:left" value="" onkeydown="return Onlynum(this, event)" /></td>					
								</tr>
							</table>
						</div>
						<div style="float:right; width:312px; overflow:hidden">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Dirección</td>
									<td align="right">
										<select name="cbAdd1" id="cbAdd1" style="width:64px" class="txbox" onkeydown="return cbCliDel(this, event)">
											<option value=""></option>
											<?=$cbAd1=LoadConfTab($link, "SELECT * FROM XConf_Catastro")?>
										</select>
										<input name="txAdd1" id="txAdd1" maxlength="52" class="txboxo" style="width:128px; text-align:left; margin-left:3px" value="" onkeypress="Adress_Len()" />
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">residencia:</td>
									<td align="right">
										<select name="cbAdd2" id="cbAdd2" style="width:64px" class="txbox" onkeydown="return cbCliDel(this, event)">
											<option value=""></option>
											<?=$cbAd2=LoadConfTab($link, "SELECT * FROM XConf_Catastro")?>
										</select>
										<input name="txAdd2" id="txAdd2" maxlength="54" class="txbox" style="width:128px; text-align:left; margin-left:3px" value="" onkeypress="return txNameAdd_Press(this, event)" />
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Departamento:</td>
									<td align="right">
										<select name="Departamento" id="tx15" style="width:204px" class="txboxo" onchange="cbDep_Change()" >
											<option value=""></option>
											<?=$cbDep=LoadConfTab($link, "SELECT DISTINCT Departamento FROM XConf_Ciudades")?>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Ciudad:</td>
									<td align="right">
										<select name="Ciudad_1" id="Ciudad_1" style="width:204px" class="txboxo" onchange="" >
											<option value=""></option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Correo electrónico:</td>
									<td align="right"><input name="Email" list ="defaultEmails" autocomplete="off" id="tx18" maxlength="45" class="txboxo" style="width:198px; text-align:left" value="" /></td>					
<datalist id="defaultEmails">
  <option value="NS">
  <option value="@hotmail.com">
  <option value="@outlook.com">
  <option value="@icloud.com">
  <option value="@yahoo.com">
  <option value="@gmail.com">
</datalist>
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Grupo segmento:</td>
									<td align="right">
										<select name="Grupo_Segmento" id="tx19" style="width:204px" class="txboxo" onchange="cbGroup_Change()" >
											<option value=""></option>
											<?=$cbSeg=LoadConfTab($link, "SELECT DISTINCT Grupo FROM Segmentos_Mercado ORDER BY Grupo DESC")?>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Segmento:</td>
									<td align="right">
										<select name="Segmento" id="Segmento" style="width:204px" class="txboxo" onchange="" >
											<option value=""></option>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Ocupación:</td>
									<td align="right">
										<input name="btfindoc" id="btfindoc" type="button" value="" class="btcont" style="width:24px; height:23px; background-image:url(images/look.png); background-repeat:no-repeat; background-size:100%; margin-right:3px" onclick="GoFindAct()" />
										<select name="Ocupacion" id="tx17" style="width:173px" class="txboxo" onchange="" >
											<option value=""></option>
											<?=$cbOcu=LoadConfTab($link, "SELECT Actividad FROM XConf_Actividades")?>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Origen de fondos:</td>
									<td align="right">
										<select name="Origen_Fondos" id="tx21" style="width:204px" class="txboxo" onchange="" >
											<option value=""></option>
											<?=$cbOrf=LoadConfTab($link, "SELECT * FROM XConf_OrigenFondos")?>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Fecha nacimiento:</td>
									<td align="right">
										<select name="cbYear" id="cbYear" style="width:65px; margin-right:3px" class="txboxo" onchange="getmdays()" >
											<option value="">Año</option>
											<?php
												//Captura año actual
												$nyear = date('Y');
												for($z = $nyear - 90; $z <= $nyear - 1; $z++)
												{
											?>
											<option value="<?=$z?>"><?=$z?></option>
											<?php } ?>
										</select>
										<select name="cbMonth" id="cbMonth" style="width:65px; margin-right:3px" class="txboxo" onchange="getmdays()" >
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
										<select name="cbDay" id="cbDay" style="width:60px;" class="txboxo" onchange="" >
											<option value="">Día</option>
										</select>
									</td>
								</td>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Persona PEP's:</td>
									<td align="right">
										<select name="Persona_Politica" id="tx27" style="width:204px" class="txboxo" onchange="" >
											<option value="NO">NO</option>
											<option value="SI">SI</option>
										</select>
									</td>
								</tr>	
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Nombre contacto:</td>
									<td align="right"><input name="Contacto" id="tx28" maxlength="50" class="txbox" style="width:198px; text-align:left" value="" /></td>					
								</tr>
								<tr class="trtxco">
									<td style="width:108px; text-align:left" class="fcont">Paretesco - Tele.:</td>
									<td align="right">
										<input name="Parentesco" id="tx29" maxlength="50" class="txbox" style="width:94px; text-align:left" value="" />
										<input name="Tel_Contacto" id="tx30" maxlength="15" class="txbox" style="width:94px; text-align:left" value="" />
									</td>					
								</tr>
							</table>	
						</div>
					</div>					
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden; padding-bottom:3px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="cmAccept_Clic('<?=$sExcSet[2]?>')" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="cmModif_Clic()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btgocom" id="btgocom" type="button" value="Ir a Compra" class="btcontdis" style="width:88px;" onclick="cmGo_Ops('frOpsCompra', '<?=$var[2]?>', '<?=$var[3]?>', '<?=$var[4]?>', '<?=$var[5]?>', '<?=$sExcSet[2]?>')" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btgoven" id="btgoven" type="button" value="Ir a Venta" class="btcontdis" style="width:88px;" onclick="cmGo_Ops('frOpsVenta', '<?=$var[2]?>', '<?=$var[3]?>', '<?=$var[4]?>', '<?=$var[5]?>', '<?=$sExcSet[2]?>')" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value="" class="txboxo" />
						<input type="hidden" name="Nacionalidad" id="tx10" value="" class="txboxo" />
						<input type="hidden" name="Direccion" id="tx14" value="" class="txboxo" />
						<input type="hidden" name="Ciudad" id="tx16" value="" class="txboxo" />
						<input type="hidden" name="Segmento" id="tx20" value="" class="txboxo" />
						<input type="hidden" name="Observaciones" id="tx22" value="" />
						<input type="hidden" name="Fecha_Ingreso" id="tx23" value="" class="txboxo" />
						<input type="hidden" name="Fecha_Operacion" id="tx24" value="" />
						<input type="hidden" name="Estado" id="tx25" value="NORMAL" />
						<input type="hidden" name="Contador" id="tx26" value="1" />
						<input type="hidden" name="txCODNew" id="txCODNew" value="0" />
						<input type="hidden" name="txDifBCh" id="txDifBCh" value="" />
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:8px; overflow:hidden; padding-bottom:0px">
						<div>
							<div style="float:left" class="fgreen"><strong>Digitalización de imagenes</strong></div>
							<div style="float:right; width:478px; margin-top:9px" class="dlin_4"></div>
						</div>
						<div style="clear:both"></div>
						<div style="margin-top:7px">
							<div style="float:left; width:270px; overflow:hidden" class="bgcol_1">
								<div style="padding:4px; border-bottom:none" class="fcont bgcol drod_4 dlin_3">Clic sobre icono para capturar y guardar</div>
								<div style="height:114px; position:relative" class="dlin_1 bgcol_1">
									<div style="position:absolute; left:2px; top:2px; z-index:5"><input name="btphoto" id="btphoto" type="button" value="" class="btcontdis" style="width:26px; height:25px; background-image:url(images/pictures.png); background-repeat:no-repeat; background-size:100%; margin-right:3px" onclick="cmCODCap_Click()" disabled="disabled" /></div>
									<div id="my_camera" style="position:absolute; left:0px; top:0px; z-index:1; height:114px; width:270px"></div>
								</div>
							</div>
							<div style="float:right; width:358px; overflow:hidden" class="bgcol_1">
								<div style="padding:4px; border-bottom:none" class="fcont bgcol drod_4 dlin_3">Imagenes del cliente (Clic para ampliar)</div>
								<div style="height:114px; position:relative" class="dlin_1 bgcol_1">
									<div style="float:left; height:114px; width:100px" class="bgcol dlin_7">
										<div class="fwhite bgcol_6" style="padding:3px;">Seleccione</div>
										<div style="overflow:auto; height:92px"><table id="lstCODList" cellpadding="0" cellspacing="0" width="100%"></table></div>
									</div>
									<div style="float:right; height:114px; width:250px; text-align:center" id="results"></div>
								</div>							
							</div>
						</div>
					</div>	
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
