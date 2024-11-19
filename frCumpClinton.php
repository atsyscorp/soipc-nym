<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$var[1]=$_GET['var1']; //--> Usuario
	//---------------------------------------------------
	$link=Conectarse();
	//Carga Historia actualizaciones
	$strSQL = "SELECT * FROM Actualizaciones_Lista_Clinton ORDER BY Fecha desc";
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Actualizar lista Clinton</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frCumpClinton.js" type="text/javascript"></script>
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
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Actualización lista Clinton</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div>
						<div style="float:left; margin-right:10px">
							<input name="btaccept" id="btaccept" type="button" value="Actualizar lista" class="btcont" style="width:120px;" onclick="actualizarYListar('<?=$var[1]?>')" />
						</div>
						<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar historia" class="btcont" style="width:120px;" onclick="Export_Clinton('<?=$sGenSet[0]?>')" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div id="tabClinton" style="height:400px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="480px">
							<tr class="bgcol_6 fwhite" id="trSucTit">
								<td style="width:90px; text-align:left; vertical-align:middle;" class="celrow">Identificacion</td>
								<td style="width:170px; text-align:left; vertical-align:middle;" class="celrow">Actualizado por</td>
								<td style="width:110px; text-align:left; vertical-align:middle;" class="celrow">Fecha actualizacion</td>
								<td style="width:110px; text-align:left; vertical-align:middle;" class="celrow">Cantidad registros</td>
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
