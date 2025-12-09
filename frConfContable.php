<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//Carga configuracion contable
	$strSQL = "SELECT * FROM Configuracion_Contable";
	//---------------------------------------------------
	//Carga Listado sucurales
	$strSUC = "SELECT Sucursal FROM Configuracion_Contable";
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuracion contable</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConfContable.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:520px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 210)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 516, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración contable</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-bottom:7px">
						<div style="float:left" class="fgreen"><strong>Configuración por sucursal</strong></div>
						<div style="float:right; width:340px; margin-top:9px;" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; width:185px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:100px; text-align:left" class="fcont">Sucursal:</td>
									<td align="right">
										<select name="Sucursal" id="tx1" style="width:100%;" class="txboxo" onchange="txId_Change()">
											<option value=""></option>
											<?=$cbLoad=LoadConfTab($link, $strSUC)?>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Copias compra:</td>
									<td align="right">
										<select name="Copias_Compra" id="tx2" style="width:100%;" class="txboxo" onchange="">
											<option value=""></option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Copias venta:</td>
									<td align="right">
										<select name="Copias_Venta" id="tx3" style="width:100%;" class="txboxo" onchange="">
											<option value=""></option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Prefijo:</td>
									<td align="right">
										<input name="Prefijo_Fact" id="tx10" maxlength="30" class="txboxo" style="width:100%; text-align:left" value="" />
									</td>
								</tr>
							</table>
						</div>
						<div style="float:right; width:305px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:135px; text-align:left" class="fcont">Inicio de consecutivo:</td>
									<td align="right"><input name="Inicio_Consecutivo" id="tx4" maxlength="30" class="txboxo" style="width:165px; text-align:left" value="" onkeydown="return Onlynum(this, event)"/></td>
								</tr>
								<tr class="trtxco">
									<td style="width:135px; text-align:left" class="fcont">Fin de consecutivo:</td>
									<td align="right"><input name="Fin_Consecutivo" id="tx5" maxlength="30" class="txboxo" style="width:165px; text-align:left" value="" onkeydown="return Onlynum(this, event)"/></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Resolución facturación:</td>
									<td align="right"><input name="Resolucion" id="tx6" maxlength="40" class="txboxo" style="width:165px; text-align:left" value="" onkeydown="return Onlynum(this, event)"/></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Inicio resolución:</td>
									<td align="right">
										<select name="cbYear" id="cbYear" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays0()" >
											<option value="">Año</option>
											<?php
												//Captura año actual
												$nyear = date('Y');
												for($z = $nyear - 5; $z <= $nyear + 5; $z++)
												{
											?>
												<option value="<?=$z?>"><?=$z?></option>
											<?php } ?>
										</select>
										<select name="cbMonth" id="cbMonth" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays0()" >
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
										<select name="cbDay" id="cbDay" style="width:50px;" class="txboxo">
											<option value="">Día</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Vencimiento resolución:</td>
									<td align="right">
										<select name="cbYear1" id="cbYear1" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays1()" >
											<option value="">Año</option>
											<?php
												//Captura año actual
												$nyear = date('Y');
												for($z = $nyear - 5; $z <= $nyear + 5; $z++)
												{
											?>
												<option value="<?=$z?>"><?=$z?></option>
											<?php } ?>
										</select>
										<select name="cbMonth1" id="cbMonth1" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays1()" >
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
										<select name="cbDay1" id="cbDay1" style="width:50px;" class="txboxo">
											<option value="">Día</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Aplicable para:</td>
									<td align="right">
										<select name="Tipo_Resolucion" id="tx9" style="width:100%;" class="txboxo" onchange="">
											<option value=""></option>
											<option value="compra">Compra</option>
											<option value="venta">Venta</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="Modi_Conf()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcont" style="width:88px;" onclick="Export_Config('<?=$sGenSet[0]?>')" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Fecha_Inicio" id="tx7" value=""  />
						<input type="hidden" name="Fecha_Final" id="tx8" value=""  />
					</div>
					<div style="clear:both"></div>
					<div id="tabConta" style="height:164px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="650px">
							<tr class="bgcol_6 fwhite" id="trConTit">
								<td style="width:70px; text-align:left; vertical-align:middle;" class="celrow">Sucursal</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Copias compra</td>
								<td style="width:80px; text-align:left; vertical-align:middle;" class="celrow">Copias venta</td>
								<td style="width:120px; text-align:left; vertical-align:middle;" class="celrow">Inicio consecutivo</td>
								<td style="width:120px; text-align:left; vertical-align:middle;" class="celrow">Fin consecutivo</td>
								<td style="width:130px; text-align:left; vertical-align:middle;" class="celrow">Resolución facturación</td>
								<td style="width:120px; text-align:left; vertical-align:middle;" class="celrow">Inicio resolución</td>
								<td style="width:120px; text-align:left; vertical-align:middle;" class="celrow">Vencimiento resolución</td>
								<td style="text-align:left; vertical-align:middle;" class="celrow">Tipo de Resolución</td>
								<td style="text-align:left; vertical-align:middle;" class="celrow">Prefijo de Facturación</td>
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
