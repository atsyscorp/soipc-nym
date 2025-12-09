<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Carga configuracion general para exportar
	$link=Conectarse();
	$getGenSet = GenSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Parámetros segmentos de mercado</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frCumpParam.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:320px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 110)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 316, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Parámetros segmentos</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-bottom:7px">
						<div style="float:left" class="fgreen"><strong>Identificación segmento de mercado</strong></div>
						<div style="float:right; width:86px; margin-top:9px;" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:125px; text-align:left" class="fcont">Tipo de operación:</td>
								<td align="right">
									<select name="Operacion" id="tx1" style="width:180px;" class="txboxo" onchange="Type_Change()">
										<option value=""></option>
										<option value="COMPRA DE DIVISAS">COMPRA DE DIVISAS</option>
										<option value="VENTA DE DIVISAS">VENTA DE DIVISAS</option>
									</select>
								</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Segmento mercado:</td>
								<td align="right">
									<select name="Segmento" id="tx3" style="width:180px;" class="txboxo" onchange="Segmento_OnChange()">
										<option value=""></option>
									</select>
								</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Grupo segmento:</td>
								<td align="right"><input name="Grupo_Segmento" id="tx2" maxlength="50" class="txboxdis" style="width:174px;" value="" disabled="disabled"/></td>
							</tr>
						</table>
					</div>
					<div style="margin-top:10px">
						<div style="float:left" class="fgreen"><strong>Parámetros de comportamiento</strong></div>
						<div style="float:right; width:113px; margin-top:9px;" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="text-align:left; width:230px" class="fcont">Frecuencia mensual de operaciones:</td>
								<td align="right"><input name="Frecuencia_Operaciones" id="tx4" maxlength="10" class="txboxo" style="width:70px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx4')" /></td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left;" class="fcont">Monto acumulado diario en dólares:</td>
								<td align="right"><input name="Acumulado_Diario" id="tx5" maxlength="10" class="txboxo" style="width:70px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx5')" /></td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left;" class="fcont">Monto acumulado mensual en dólares:</td>
								<td align="right"><input name="Acumulado_Mensual" id="tx6" maxlength="10" class="txboxo" style="width:70px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx6')" /></td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left;" class="fcont">Tope máximo acumulado diario dólares:</td>
								<td align="right"><input name="Tope_Diario" id="tx7" maxlength="10" class="txboxo" style="width:70px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx7')" /></td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left;" class="fcont">Tope máximo acumulado mes dólares:</td>
								<td align="right"><input name="Tope_Mensual" id="tx8" maxlength="10" class="txboxo" style="width:70px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" oninput="txChange_Num('tx8')" /></td>
							</tr>
						</table>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="Accept_Param()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="Modi_Param()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btdelete" id="btdelete" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Param()" disabled="disabled" /></div>
						<div style="clear:both"></div>
					</div>
					<div id="tabParam" style="height:140px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="750px">
							<tr class="bgcol_6 fwhite" id="trParTit">
								<td style="width:100px; text-align:left" class="celrow">Operación</td>
								<td style="width:95px; text-align:left" class="celrow">Grupo segmento</td>
								<td style="width:95px; text-align:left" class="celrow">Segmento</td>
								<td style="width:80px; text-align:left" class="celrow">Frecuencia</td>
								<td style="width:80px; text-align:left" class="celrow">Acumulado día</td>
								<td style="width:80px; text-align:left" class="celrow">Acumulado mes</td>
								<td style="width:80px; text-align:left" class="celrow">Tope diario</td>
								<td style="text-align:left" class="celrow">Tope mensual</td>
							</tr>
						</table>				
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcont" style="width:88px;" onclick="Export_Param('<?=$sGenSet[0]?>')" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<div style="clear:both"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
