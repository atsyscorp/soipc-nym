<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura de variables
	$var[1]=$_GET['var1'];
	$var[2]=$_GET['var2'];
	$var[3]=$_GET['var3'];	//-->Origen
	//---------------------------------------------------
	$link=Conectarse();
	//---------------------------------------------------
	$strSQLS = "SELECT Codigo_Sucursal FROM Sucursales";
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Validación cierres ventanilla</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frRepOpsClose.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="frRep_Load('<?=$var[3]?>', '<?=$var[1]?>', '<?=$var[2]?>')">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:430px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 165)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 426, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%;">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Validación cierres ventanilla</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div>
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:50px; text-align:left" class="fcont">Sucursal:</td>
								<td align="left">
									<select name="tx1" id="tx1" style="width:70px; margin-left:10px" class="txboxo" onchange="cbSuc_Change()">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSQLS)?>
									</select>
								</td>
								<td style="width:80px; text-align:right" class="fcont">Fecha inicial:</td>
								<td align="right">
									<select name="cbYear" id="cbYear" style="width:60px; margin-right:2px" class="txboxo" onchange="getmdays()" >
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
									<select name="cbMonth" id="cbMonth" style="width:60px; margin-right:2px" class="txboxo" onchange="getmdays()" >
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
									<select name="cbDay" id="cbDay" style="width:60px;" class="txboxo" onchange="" >
										<option value="">Día</option>
									</select>
								</td>			
							</tr>
							<tr class="trtxco">
								<td style="width:50px; text-align:left" class="fcont">Estación:</td>
								<td align="left">
									<select name="tx2" id="tx2" style="width:70px; margin-left:10px" class="txboxo" onchange="RepTit()">
									</select>
								</td>
								<td style="width:80px; text-align:right" class="fcont">Fecha final:</td>
								<td align="right">
									<select name="cbYear1" id="cbYear1" style="width:60px; margin-right:2px" class="txboxo" onchange="getmdays_1()" >
										<option value="">Año</option>
										<?php
											// Captura año actual
											$nyear = date('Y');
											for($z = $nyear - 5; $z <= $nyear; $z++)
											{
										?>
											<option value="<?=$z?>"><?=$z?></option>

										<? } ?>
									</select>
									<select name="cbMonth1" id="cbMonth1" style="width:60px; margin-right:2px" class="txboxo" onchange="getmdays_1()" >
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
									<select name="cbDay1" id="cbDay1" style="width:60px;" class="txboxo" onchange="" >
										<option value="">Día</option>
									</select>
								</td>			
							</tr>
						</table>
					</div>
					<div style="margin-top:7px;">
						<div style="float:left; margin-right:5px"><input name="btaccept" id="btaccept" type="button" value="Validar cierres" class="btcont" style="width:120px;" onclick="cmAccept_Clic()" /></div>
						<div style="float:left; margin-right:5px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcontdis" disabled="disabled" style="width:88px;" onclick="cmExport_Click('<?=$sGenSet[0]?>')" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px" id="dRepExpo">
						<div style="padding:3px; padding-left:7px; margin-bottom:2px;" class="fgreen bgcol_4 dlin_3"><strong>Cantidad de operaciones y cierres por fecha: <span id="dSuc"></span></strong></div>
						<div id="lstTCList" style="height:310px; overflow:auto; margin-top:4px" class="dlin_1 bgcol_1">
							<table cellpadding="0" cellspacing="0" width="580px" id="tbTCList">
								<tr class="bgcol_6 fwhite" id="trTCList">
									<td style="text-align:left; width:70px" class="celrow">Fecha</td>
									<td style="text-align:center; width:55px" class="celrow">Compras</td>
									<td style="text-align:center; width:50px" class="celrow">Ventas</td>
									<td style="text-align:center; width:60px" class="celrow">Traslados</td>
									<td style="text-align:center; width:52px" class="celrow">Egresos</td>
									<td style="text-align:center; width:70px" class="celrow">Estado cierre</td>
									<td style="text-align:left;" class="celrow">Cerrado por</td>
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
