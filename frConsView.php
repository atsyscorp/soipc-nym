<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	include("General.php");
	//---------------------------------------------------
	//Recibe variable de qué reporte se va a hacer
	$var[1]=$_GET['var1'];	//Tipo de reporte que va a cargar
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
<title>Visor de Consultas</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frConsView.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="TabSize()">
<script>
	ValWinFrame();
</script>
	<div align="center" style="width:100%; margin-top:0px; height:100%">
		<div align="center" style="width:100%; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 5)?>
			</div>
			<div align="left" style="width:100%; overflow:hidden">
				<div style="margin:0px">
					<div class="bgcol_2 dlin_1">
						<div class="fgreen" style="padding:10px; box-sizing:border-box; float:left">Detalle de Registros</div>
						<div style="clear:both"></div>					
					</div>
					<div style="padding:10px; margin-top:5px; box-sizing:border-box" class="bgcol_1 dlin_3">
						<div style="float:left; margin-top:7px" class="fcont">Registro:</div>
						<div style="float:left; margin-left:10px">
							<input name="txReg" id="txReg" class="txboxdis fgreen" style="width:70px; text-align:center; font-size:19px; font-weight:bold" value="0" disabled="disabled"  />
						</div>						
						<div style="float:left; margin-top:7px; margin-left:10px" class="fcont">De:</div>
						<div style="float:left; margin-left:10px">
							<input name="txRegTot" id="txRegTot" class="txboxdis fgreen" style="width:70px; text-align:center; font-size:19px; font-weight:bold" value="0" disabled="disabled" />
						</div>						
						<div style="float:left; margin-left:10px"><input name="btregview" id="btregview" type="button" value="Ampliar registro" class="btcont" style="width:120px; padding:7px 0; height:auto" onclick="AmpRegist()" /></div>
						<div style="float:left; margin-left:10px"><input name="btrefresh" id="btrefresh" type="button" value="Refrescar consulta" class="btcont" style="width:130px; padding:7px 0; height:auto" onclick="viscap('dConsCri')" /></div>
						<div style="float:left; margin-left:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcont" style="width:100px; padding:7px 0; height:auto" onclick="cmExport_Clic()" /></div>
						<div style="float:left; margin-left:10px"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:100px; padding:7px 0; height:auto" onclick="closewin()" /></div>
						<input type="hidden" id="txCelRow" name="txCelRow" />
						<div style="clear:both"></div>
					</div>				
					<div style="margin:0px; margin-top:7px; position:relative; overflow:visible;" id="dTabs">
						<div style="position:relative; top:0px; left:0px; width:100%;" id="dTot">
							<div id="dTot_Tab" class="dlin_1 bgcol_1" style="overflow:auto">
								<iframe name="frTot_Tab" id="frTot_Tab" align="left" frameborder="0" style="width:100%; height:100%; overflow:visible"></iframe>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<!-- Ventanas modales -->
	<!-- Generador de consulta -->
	<div id="dConsCri" style="position:fixed; height:100%; width:100%; top:0px; left:0px" class="bgcol_7">
		<?php
			include($var[1].'.php');
		?>
	</div>
	<!-- Visor de registro -->
	<div id="dAmpReg" style="position:fixed; height:100%; width:100%; top:0px; left:0px; visibility:hidden" class="bgcol_7">
		<div align="center" style="width:100%; margin-top:25px;">
		<div align="center" style="width:516px; position:relative">
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Visor de registros</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="hidcap('dAmpReg')"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:0px">
					<div style="width:97%; padding-top:10px; margin:auto">
						<div style="float:left; font-size:27px; cursor:pointer" title="Anterior" class="fgreen" onClick="AmpRegist_Ant()">&#9668</div>
						<div style="float:left; font-size:27px; cursor:pointer" title="Siguiente" class="fgreen" onClick="AmpRegist_Sig()">&#9658</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin:10px 8px">
						<div class="txbox" style="width:99%; height:450px; overflow:auto; padding:10px 0">
							<div id="dRegAmpV" style="width:96%; margin:auto; line-height:1.4em"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<body>
</body>
</html>
