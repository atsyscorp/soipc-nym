<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Carga combo tipo de operacion
	$link=Conectarse();
	//---------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Estado de clientes</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frCumpEstClie.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:328px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 114)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 324, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%;">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Estado de clientes</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div>
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:140px; text-align:left" class="fcont">Identificación de cliente:</td>
								<td align="right"><input name="txECClie" id="txECClie" maxlength="30" class="txboxo" style="width:160px; text-align:center; font-weight:bold" value="" onkeypress="disbtn('btsave'); return txID_Press(this, event)" /></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Estado de cliente:</td>
								<td align="right">
									<select name="cbECEstado" id="cbECEstado" style="width:166px;" class="txboxo" onchange="">
										<option value=""></option>
										<option value="NORMAL">NORMAL</option>
										<option value="OBSERVACION">OBSERVACION</option>
										<option value="BLOQUEADO">BLOQUEADO</option>
									</select>
								</td>
							</tr>						
						</table>
						<div style="margin-top:7px">
							<div class="fcont">Observaciones a cliente:</div>
							<div style="margin-top:4px"><textarea name="txECObs" cols="0" class="txbox" id="txECObs" style="width:306px; overflow:auto; height:48px; resize:none;"></textarea></div>
						</div>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Buscar cliente" class="btcont" style="width:112px;" onclick="Find_Clic()" /></div>
						<div style="float:left; margin-right:10px"><input name="btsave" id="btsave" type="button" value="Guardar" class="btcontdis" disabled="disabled" style="width:88px;" onclick="cmModif_Clic()" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div id="lstECList" style="height:224px; overflow:auto; margin-top:10px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="100%" id="tbECList">
							<tr class="bgcol_6 fwhite" id="trECTit">
								<td style="text-align:left; width:104px" class="celrow">Campo</td>
								<td style="text-align:left" class="celrow">Valor</td>
							</tr>
						</table>				
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
