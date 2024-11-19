<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
	//Configuracion cambiaria
	$getExcSet = ExcSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte UIAF</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frRepUIAF.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="Rep_Load()">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:534px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 217)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 530, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div id="dpup3" style="position:relative; top:0px; left:0px; z-index:65">
				<div style="width:100%; position:absolute; top:130px; visibility:hidden" align="center" id="dProg">
					<div style="margin:auto; width:300px" class="drod_1 dlin_1 dsom_3 bgcol_2">
						<div style="margin:10px; font-weight:bold" class="fgreen" id="dPrMsg">Iniciando creación de archivo...</div>					
					</div>
				</div>
			</div>
			<div id="dpup4" style="position:relative; top:0px; left:0px; z-index:65">
				<div style="width:100%; position:absolute; top:130px; visibility:hidden" align="center" id="dFinal">
					<div style="margin:auto; width:300px" class="drod_1 dlin_1 dsom_3 bgcol_2">
						<div style="margin:10px;">
							<a id="aDown" href="#" download><div class="btcont" style="margin-bottom:7px; width:120px; padding-top:7px" onclick="hidcap('dFinal'); hidcap('dbloc')">Descargar archivo</div></a> 
							<a id="aShow" href="#" target="_blank"><div class="btcont" style="margin-bottom:7px; width:120px; padding-top:7px" onclick="">Ver archivo</div></a> 
						</div>					
					</div>
				</div>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Reporte UIAF</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-bottom:7px">
						<div style="float:left" class="fgreen"><strong>Identificación de archivo plano</strong></div>
						<div style="float:left; width:332px; margin-top:9px; margin-left:10px" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; width:250px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="text-align:left; width:140px" class="fcont">Mes y año de envío:</td>
									<td align="right"><input name="Mes_Ano" id="tx1" maxlength="10" class="txboxdis" style="width:98px; text-align:center;" value="" disabled="disabled"/></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left;" class="fcont">Sector y tipo de entidad:</td>
									<td align="right"><input name="Sector" id="tx2" maxlength="30" class="txboxdis" style="width:98px; text-align:center;" value="02003" disabled="disabled"/></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Código UIAF empresa:</td>
									<td align="right"><input name="Codigo" id="tx3" maxlength="15" class="txboxo" style="width:98px; text-align:center;" value="<?=$sExcSet[1]?>" onkeydown="return Onlynum(this, event)"/></td>
								</tr>
							</table>
						</div>
						<div style="float:right; width:250px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="text-align:left; width:85px" class="fcont">Trimestre:</td>
									<td align="right">
										<select name="Trimestre" id="tx4" style="width:165px; margin-left:10px" class="txboxo" onchange="cbTrim_Change()">
											<option value=""></option>
											<option value="Enero - Marzo">Enero - Marzo</option>
											<option value="Abril - Junio">Abril - Junio</option>
											<option value="Julio - Septiembre">Julio - Septiembre</option>
											<option value="Octubre - Diciembre">Octubre - Diciembre</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left;" class="fcont">Fecha inicial:</td>
									<td align="right">
										<select name="cbYear" id="cbYear" class="txboxo" style="width:54px; font-size:11px" onchange="getmdays()" >
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
										<select name="cbMonth" id="cbMonth" style="width:52px; font-size:11px" class="txboxo" onchange="getmdays()" >
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
										<select name="cbDay" id="cbDay" style="width:52px; font-size:11px" class="txboxo" onchange="" >
											<option value="">Día</option>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left;" class="fcont">Fecha final:</td>
									<td align="right">
										<select name="cbYear1" id="cbYear1" style="width:54px; font-size:11px" class="txboxo" onchange="getmdays_1()" >
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
										<select name="cbMonth1" id="cbMonth1" style="width:52px; font-size:11px" class="txboxo" onchange="getmdays_1()" >
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
										<select name="cbDay1" id="cbDay1" style="width:52px; font-size:11px" class="txboxo" onchange="" >
											<option value="">Día</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Crear archivo plano" class="btcont" style="width:136px;" onclick="Crea_Clic()" /></div>
						<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar errores" class="btcont" style="width:136px;" onclick="Export_Err('<?=$sGenSet[0]?>')" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:136px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div style="padding:3px; padding-left:7px; margin-bottom:2px; margin-top:7px" class="fgreen bgcol_4 dlin_3"><strong>Listado de errores en creación de archivo</strong></div>
					<div id="dError" style="height:160px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="100%" id="tabError">
							<tr class="bgcol_6 fwhite" id="trError">
								<td style="text-align:left; width:70px" class="celrow">Registro</td>
								<td style="text-align:left; width:96px" class="celrow">Consecutivo</td>
								<td style="text-align:left;" class="celrow">Error</td>
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
