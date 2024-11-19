<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	include("General.php");
	//---------------------------------------------------
	//Carga combo de sucurasales
	$strSQLS = "SELECT Codigo_Sucursal FROM Sucursales";
	//Carga combo de Monedas
    $strSQLC = "SELECT Moneda FROM XConf_Monedas WHERE Moneda <> 'COP'";
	//---------------------------------------------------
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kardex de divisas</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frRepKardex.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="TabSize()">
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
			<div class="bgcol_2" align="left" style="width:100%; overflow:hidden">
				<div style="margin:0px">
					<div class="dlin_1">
						<div style="margin-top:7px; margin-left:8px">
							<div>
								<div style="float:left" class="fgreen"><strong>Criterios de reporte</strong></div>
								<div style="float:left; width:87%; margin-top:9px; margin-left:10px" class="dlin_4"></div>
								<div style="clear:both"></div>
							</div>
							<div style="margin-top:10px">
								<div style="float:left; width:142px; margin-right:15px">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr class="trtxco">
											<td style="width:60px; text-align:left" class="fcont">Sucursal:</td>
											<td align="right">
												<select name="cbCVSucur" id="tx1" style="width:80px;" class="txboxo" onchange="cbSuc_Change()">
													<option value=""></option>
													<option value="TODAS">TODAS</option>
													<?=$cbLoad=LoadConfTab($link, $strSQLS)?>
												</select>
											</td>
										</tr>
										<tr class="trtxco">
											<td style="width:60px; text-align:left" class="fcont">Estación:</td>
											<td align="right">
												<select name="cbCVCaja" id="tx2" style="width:80px;" class="txboxo" onchange="cbCaja_Change()">
													<option value=""></option>
													<option value="TODAS">TODAS</option>
												</select>
											</td>
										</tr>
										<tr class="trtxco">
											<td style="width:60px; text-align:left" class="fcont">Moneda:</td>
											<td align="right">
												<select name="cbCVCurr" id="tx3" style="width:80px;" class="txboxo" onchange="">
													<option value=""></option>
													<option value="TODAS">TODAS</option>
													<?=$cbLoadC=LoadConfTab($link, $strSQLC)?>
												</select>
											</td>
										</tr>
									</table>
								</div>
								<div style="float:left; width:255px; margin-right:15px">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr class="trtxco">
											<td style="width:75px; text-align:left" class="fcont">Fecha inicial:</td>
											<td align="right">
												<select name="cbYear" id="cbYear" style="width:58px; margin-right:2px" class="txboxo" onchange="getmdays()" >
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
												<select name="cbMonth" id="cbMonth" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays()" >
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
												<select name="cbDay" id="cbDay" style="width:50px;" class="txboxo" onchange="" >
													<option value="">Día</option>
												</select>
											</td>									
										</tr>
										<tr class="trtxco">
											<td style="width:75px; text-align:left" class="fcont">Fecha final:</td>
											<td align="right">
												<select name="cbYear1" id="cbYear1" style="width:58px; margin-right:2px" class="txboxo" onchange="getmdays_1()" >
													<option value="">Año</option>
													<?php
														//Captura año actual
														$nyear = date('Y');
														for($z = $nyear - 5; $z <= $nyear; $z++)
														{
													?>
														<option value="<?=$z?>"><?=$z?></option>
													<?php } ?>
												</select>
												<select name="cbMonth1" id="cbMonth1" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays_1()" >
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
												<select name="cbDay1" id="cbDay1" style="width:50px;" class="txboxo" onchange="" >
													<option value="">Día</option>
												</select>
											</td>
										</tr>
										<tr class="trtxco">
											<td style="width:75px; text-align:left" class="fcont">Agrupación:</td>
											<td align="right">
												<select name="cbCVGroup" id="tx4" style="width:173px;" class="txboxo" onchange="">
													<option value=""></option>
												</select>
											</td>
										</tr>										
									</table>
								</div>			
								<div style="float:left; width:235px; margin-right:15px">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr class="trtxco">
											<td style="width:155px; text-align:left" class="fcont">Incluir días sin operaciones:</td>
											<td align="right">
												<select name="cbCVSin" id="tx5" style="width:70px;" class="txboxo" onchange="">
													<option value="SI">SI</option>
													<option value="NO">NO</option>
												</select>
											</td>
										</tr>
									</table>
								</div>
								<div style="clear:both"></div>
							</div>
						</div>
						<div style="clear:both"></div>
						<div style="margin:8px; padding-bottom:3px; overflow:hidden">
							<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Generar kardex" class="btcont" style="width:120px;" onclick="cmAccept_Clic()" /></div>
							<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcont" style="width:120px;" onclick="cmExport_Clic('<?=$sGenSet[0]?>')" /></div>
							<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:120px;" onclick="closewin()" /></div>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin:0px; margin-top:7px; position:relative; overflow:visible;" id="dTabs">
						<div style="position:relative; top:0px; left:0px; width:100%;" id="dTot">
							<div style="padding:3px; padding-left:7px; margin-bottom:3px" class="fgreen bgcol_4 dlin_3"><strong>Kardex de divisas</strong></div>
							<div id="dTot_Tab" class="dlin_1 bgcol_1" style="overflow:auto">
								<iframe name="frTot_Tab" id="frTot_Tab" align="left" frameborder="0" style="width:100%; height:100%; overflow:visible"></iframe>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
</body>
</html>
