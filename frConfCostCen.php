<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//Carga tabla de centros de costos 
	$strSQL = "SELECT * FROM XConf_CostCenter";
	//---------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuración centro de costos</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConfCostCen.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:304px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 102)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 300, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración centro de costos</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="overflow:visible;">
						<div class="fgreen"><strong>Centro de costos:</strong></div>
						<div><input name="Centro_Costos" id="tx1" maxlength="50" class="txboxo" style="width:283px; text-align:left" value="" onkeyup="txId_Change()" /></div>
					</div>
					<div style="margin-top:10px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="Cost_Accept()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btdelete" id="btdelete" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Cost()" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div id="tabCosts" style="height:110px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="265px">
							<tr class="bgcol_6 fwhite" id="trTasTit">
								<td style="width:265px; text-align:left; vertical-align:middle;" class="celrow">Centros de costos</td>
								<? $listLoad = LoadTable($link, $strSQL, 'true', 0); ?>
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
