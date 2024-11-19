<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//Carga Listado de Usuarios
	$strSQL = "SELECT * FROM Segmentos_Mercado";
	//---------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuración Segmentos de Mercado</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConfSegmentos.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:488px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 194)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 484, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración segmentos de mercado</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-top:0px">
						<div style="float:left" class="fgreen"><strong>Segmentos de mercado</strong></div>
						<div style="float:right; width:325px; margin-top:9px" class="dlin_4"></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:4px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:254px; text-align:left" class="fcont">Segmento del mercado:</td>
								<td style="text-align:left" class="fcont">Grupo de segmentación:</td>
							</tr>
							<tr class="trtxco">
								<td align="left">
									<input name="Segmento" id="tx1" maxlength="80" class="txboxo" style="width:240px; text-align:left" value="" onkeyup="txId_Change()" />
								</td>
								<td align="left">
									<select name="Grupo" id="tx2" style="width:215px;" class="txboxo" onchange="">
										<option value=""></option>
										<option value="PERSONA NATURAL">PERSONA NATURAL</option>
										<option value="PERSONA JURÍDICA">PERSONA JURÍDICA</option>
										<option value="EXTRANJERO">EXTRANJERO</option>
										<option value="INTERMEDIARIO MERCADO">INTERMEDIARIO MERCADO</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="Segment_Accept()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btdelete" id="btdelete" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Seg()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div id="tabSegs" style="height:162px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr class="bgcol_6 fwhite" id="trSegTit">
								<td style="width:250px; text-align:left" class="celrow">Segmento de mercado</td>
								<td style="text-align:left" class="celrow">Grupo de segmentación</td>
							</tr>
							<?php
							$listLoad = LoadTable($link, $strSQL, 'true', 0);
							?>
						</table>				
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
