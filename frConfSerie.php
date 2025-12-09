<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//Carga Listado de tipos de documento de impresion
	$strSQL = "SELECT DISTINCT Documento FROM XConf_Consecutivos WHERE Area = 'VENTANILLA'";
	//---------------------------------------------------
	//Carga Listado de sucursales
	$strSuc = "SELECT Codigo_Sucursal FROM Sucursales";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuración de consecutivos</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConfSerie.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:600px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 250)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 596, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración de consecutivos</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-top:0px">
						<div style="float:left" class="fgreen"><strong>Configuración de consecutivos</strong></div>
						<div style="float:right; width:395px; margin-top:9px" class="dlin_4"></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:4px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:80px; text-align:left" class="fcont">Sucursal:</td>
								<td style="width:250px; text-align:left" class="fcont">Documento:</td>
								<td style="width:74px; text-align:left" class="fcont">Código:</td>
								<td style="width:82px; text-align:left" class="fcont">Prefijo:</td>
								<td style="text-align:left" class="fcont">Consecutivo:</td>
							</tr>
							<tr class="trtxco">
								<td align="left">
									<select name="Sucursal" id="tx2" style="width:70px;" class="txboxo" onchange="Suc_Change()">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSuc)?>
									</select>
								</td>
								<td align="left">
									<select name="Documento" id="tx3" style="width:240px;" class="txboxo" onchange="Doc_OnChange()">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSQL)?>
									</select>
								</td>
								<td align="left">
									<input name="Codigo" id="tx4" maxlength="10" class="txboxdis" style="width:60px; text-align:center" value="" onkeyup="" disabled="disabled" />
								</td>
								<td align="left">
									<input name="Prefijo" id="tx6" maxlength="5" class="txboxo" style="width:68px; text-align:center" value="" onkeyup="" />
								</td>
								<td align="left">
									<input name="Consecutivo" id="tx7" maxlength="10" class="txboxo" style="width:88px; text-align:right" value="0" onkeydown="return Onlynum(this, event)" />
								</td>
							</tr>
						</table>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Guardar" class="btcont" style="width:88px;" onclick="Modi_Serie()" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value="" />
						<input type="hidden" name="Area" id="tx5" value="" />
						<input type="hidden" name="Formato" id="tx8" value="" />
						<input type="hidden" name="Tabla" id="tx9" value="" />
					</div>
					<div style="clear:both"></div>
					<div id="tabSerie" style="height:150px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="875px">
							<tr class="bgcol_6 fwhite" id="trSerTit">
								<td style="width:45px; text-align:left" class="celrow">Id</td>
								<td style="width:50px; text-align:left" class="celrow">Sucursal</td>
								<td style="width:185px; text-align:left" class="celrow">Documento</td>
								<td style="width:50px; text-align:left" class="celrow">Código</td>
								<td style="width:85px; text-align:left" class="celrow">Area</td>
								<td style="width:55px; text-align:left" class="celrow">Prefijo</td>
								<td style="width:85px; text-align:left" class="celrow">Consecutivo</td>
								<td style="width:120px; text-align:left" class="celrow">Formato</td>
								<td style="text-align:left" class="celrow">Tabla</td>
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
