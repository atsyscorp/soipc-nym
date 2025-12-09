<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Calificaciones de alerta</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frCumpCalifica.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:350px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 125)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 346, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Calificaciones de alerta</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div>
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:210px; text-align:left" class="fgreen"><b>Calificaciones para:</b></td>
								<td align="right">
									<select name="Operacion" id="tx1" style="width:111px;" class="txboxo" onchange="cbOps_Change()">
										<option value=""></option>
										<option value="COMPRA DE DIVISAS">COMPRA DE DIVISAS</option>
										<option value="VENTA DE DIVISAS">VENTA DE DIVISAS</option>
									</select>
								</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Frecuencia segmento de mercado:</td>
								<td align="right"><input name="Frecuencia" id="tx2" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()" /></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Acumulado día segmento mercado:</td>
								<td align="right"><input name="Acumulado_Diario_Segmento" id="tx3" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Acumulado mes segmento mercado:</td>
								<td align="right"><input name="Acumulado_Mes_Segmento" id="tx4" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Histórico acumulado diario cliente:</td>
								<td align="right"><input name="Acumulado_Diario_Cliente" id="tx5" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Histórico acumulado mes cliente:</td>
								<td align="right"><input name="Acumulado_Mes_Cliente" id="tx6" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Tope cantidad acumulada diaria:</td>
								<td align="right"><input name="Tope_Acumulado_Diario" id="tx7" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Tope cantidad acumulada mes:</td>
								<td align="right"><input name="Tope_Acumulado_Mes" id="tx8" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Calificación alerta ventanilla:</td>
								<td align="right"><input name="Calificacion_Ventanilla" id="tx9" maxlength="5" class="txboxo" style="width:105px; text-align:right" value="0" onkeydown="return OnlynumDec(this, event)" onkeyup="Cal_Tot()"/></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fgreen"><b>Calificación Total (=10):</b></td>
								<td align="right"><input name="txTot" id="txTot" maxlength="5" class="txboxdis" style="width:105px; text-align:right; font-weight:bold" value="0" disabled="disabled" /></td>
							</tr>						
						</table>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Guardar" class="btcont" style="width:88px;" onclick="Accept_Cal()" /></div>
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
