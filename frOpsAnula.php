<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura de variables
	$var[1]=$_GET['var1'];
	$var[2]=$_GET['var2'];
	$var[3]=$_GET['var3'];
	$var[4]=$_GET['var4'];	//--> Origen
	$var[5]=$_GET['var5'];	//--> Si muestra opcion para anular cierre de ventanilla
	//---------------------------------------------------
	//Carga combo tipo de operacion
	$strSQL = "SELECT DISTINCT Documento FROM XConf_Consecutivos WHERE Area = '". $var[4] ."'";
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
<title>Anular Operación Ventanilla</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsAnula.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:302px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 101)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 298, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%; overflow:hidden; height:300px" id="frAnula">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Anular operación</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div>
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:115px; text-align:left" class="fcont">Tipo de operación:</td>
								<td align="right">
									<select name="cbAOType" id="tx1" style="width:170px; margin-left:10px" class="txboxo" onchange="cbAOType_Change()">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSQL)?>
										<? 
											if($var[5] == 'SI'){
										?>
										<option value="CIERRE <?=$var[4]?>">CIERRE <?=$var[4]?></option>
										<? } ?>
									</select>
								</td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Sucursal:</td>
								<td align="right">
									<?php
										if($var[4] == 'VENTANILLA' && $var[5] == 'NO'){
									?>
										<input name="txAOSucur" id="tx2" maxlength="15" class="txboxdis" disabled="disabled" style="width:164px; text-align:center; font-weight:bold" value="<?=$var[1]?>" />									
									<?php } else { ?>
										<select name="txAOSucur" id="tx2" style="width:170px; margin-left:10px" class="txboxo" onchange="">
											<option value=""></option>
											<?=$cbLoadS=LoadConfTab($link, 'SELECT Codigo_Sucursal FROM Sucursales')?>
										</select>								
									<?php } ?>
								</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Estación:</td>
								<td align="right">
									<?php
										$dises = '';
										$discl = 'txboxo';
										if($var[4] == 'VENTANILLA' && $var[5] == 'NO'){
											$dises = 'disabled="disabled"';
											$discl = 'txboxdis"';
										}
									?>
										<input name="txAOCaja" id="tx3" maxlength="15" class="<?=$discl?>" <?=$dises?> style="width:164px; text-align:center; font-weight:bold" value="<?=$var[2]?>" />									
								</td>
							</tr>				
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Fecha operación:</td>
								<td align="right">
									<select name="cbYear" id="cbYear" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays()" >
										<option value="">Año</option>
										<?
											//Captura año actual
											$nyear = date('Y');
											for($z = $nyear - 5; $z <= $nyear; $z++)
											{
										?>
											<option value="<?=$z?>"><?=$z?></option>

										<? } ?>
									</select>
									<select name="cbMonth" id="cbMonth" style="width:55px; margin-right:2px" class="txboxo" onchange="getmdays()" >
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
									<select name="cbDay" id="cbDay" style="width:50px;" class="txboxo" onchange="" >
										<option value="">Día</option>
									</select>
								</td>			
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Consecutivo:</td>
								<td align="right"><input name="txAOSerie" id="tx4" maxlength="20" class="txboxo" style="width:164px; text-align:center; font-weight:bold" value="" onkeydown="return Onlynum(this, event)"/></td>
							</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Motivo anulación:</td>
									<td align="right"><textarea name="txAOMotiv" cols="0" class="txboxo" id="tx5" style="width:164px; overflow:auto; height:74px; resize:none;"></textarea></td>					
							</tr>
						</table>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Anular" class="btcont" style="width:88px;" onclick="cmAnular('<?=$var[4]?>')" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="txAOTable" id="txAOTable" value=""  />
						<input type="hidden" name="txAOEmp" id="txAOEmp" value="<?=$var[3]?>"  />
						<input type="hidden" name="txAOCur" id="txAOCur" value=""  />
						<input type="hidden" name="txAOCant" id="txAOCant" value=""  />
						<input type="hidden" name="txAOCOP" id="txAOCOP" value="COP"  />
						<input type="hidden" name="txAOValu" id="txAOValu" value=""  />
					</div>
					<div style="clear:both"></div>
					<div class="bgcol dlin_3 drod_1 fgreen" style="padding:6px; margin-top:10px"><b>Apertura de días exitosa. Las siguientes fechas deben cerrarse nuevamente:</b></div>
					<div id="lstAOList" style="height:190px; overflow:auto; margin-top:4px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="284px">
							<tr class="bgcol_6 fwhite">
								<td style="text-align:left" class="celrow">Listado fechas abiertas</td>
							</tr>
						</table>				
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcont" style="width:88px;" onclick="cmExport_Click('<?=$sGenSet[0]?>')" /></div>
					</div>					
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
