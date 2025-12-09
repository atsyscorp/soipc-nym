<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura de variables
	$var[1]=$_GET['var1'];	//Id de ajuste de egreso
	$var[2]=$_GET['var2'];	//Sucursal
	$var[3]=$_GET['var3'];	//Caja
	$var[4]=$_GET['var4'];	//Usuario
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//---------------------------------------------------
	//Carga datos de egreso de origen de tabla temporal
    $strSQLS = "SELECT * FROM Traslados_Ventanilla WHERE Identificacion = '".  $var[1]. "'";
	$p=mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for($i = 0; $i <= 18; $i++){
			$varp[$i] = $n[$i];
		}
	}
	//---------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ajuste de recursos</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsAjustes_1.js" type="text/javascript"></script>
</head>
<body>
<body class="bodygen bgcol" onload="frOpsAjustes_Load()">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:614px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 257)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 610, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px;" class="fwhite">Ajuste de recursos ventanilla</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div class="bgcol dlin_3 drod_1" style="padding:6px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr style="height:16px">
								<td style="width:60px; text-align:left; vertical-align:middle" class="fcont">Sucursal:</td>
								<td style="width:50px; text-align:left; vertical-align:middle"><input name="Sucursal" id="tx4" maxlength="15" class="txlabel fgreen" style="width:50px; text-align:left; font-weight:bold" type="text" value="<?=$var[2]?>" disabled="disabled"  /></td>								
								<td style="width:60px; text-align:left; vertical-align:middle" class="fcont">Fecha:</td>
								<td align="left" width=""><input name="Fecha" id="tx7" maxlength="15" class="txlabel fgreen" style="width:300px; text-align:left; font-weight:bold" type="text" value="" disabled="disabled"  /></td>								
							</tr>
							<tr style="height:16px">
								<td style="width:60px; text-align:left; vertical-align:middle" class="fcont">Estación:</td>
								<td style="width:50px; text-align:left; vertical-align:middle"><input name="Estacion" id="tx5" maxlength="15" class="txlabel fgreen" style="width:50px; text-align:left; font-weight:bold" type="text" value="<?=$var[3]?>" disabled="disabled"  /></td>								
								<td style="width:60px; text-align:left; vertical-align:middle" class="fcont">Cajero:</td>
								<td align="left" width=""><input name="Cajero" id="tx6" maxlength="50" class="txlabel fgreen" style="width:300px; text-align:left; font-weight:bold" type="text" value="<?=$var[4]?>" disabled="disabled"  /></td>								
							</tr>
						</table>
					</div>
					<div style="margin-top:7px; overflow:hidden">
						<div style="float:left; width:292px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Identificación del traslado</strong></div>
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Tipo movimiento:</td>
										<td align="right"><input name="Tipo_Movimiento" id="tx3" maxlength="45" class="txboxdis" style="width:166px; text-align:left" value="INGRESO" type="text" disabled="disabled" />
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Consecutivo:</td>
										<td align="right"><input name="Consecutivo" id="tx8" maxlength="45" class="txboxdis" style="width:166px; text-align:center; font-weight:bold" value="<?=$sSerie=ReadSerie($link, "Select Consecutivo From XConf_Consecutivos Where Codigo ='142' AND Sucursal = '". $var[2]. "'")?>" type="text" disabled="disabled" />
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Origen:</td>
										<td align="right">
											<?php
											//Valida si es la misma sucursal, el origen destino es caja
											if($var[2] != $varp[3]){	//Sucursales diferentes
											?>
											<input name="Origen_Destino" id="tx10" maxlength="45" class="txboxdis" style="width:166px; text-align:left" value="<?=$varp[3]?>" type="text" disabled="disabled" />
											<? } else { ?>										
											<input name="Origen_Destino" id="tx10" maxlength="45" class="txboxdis" style="width:166px; text-align:left" value="CAJA <?=$varp[4]?>" type="text" disabled="disabled" />
											<? } ?>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Documento par:</td>
										<td align="right"><input name="Documento_Par" id="tx11" maxlength="20" class="txboxdis" style="width:166px; text-align:left;" value="<?=$varp[7]?>" type="text" disabled="disabled"/>
										</td>
									</tr>
								</table>
							</div>
						</div>					
						<div style="float:right; width:292px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Valores del traslado</strong></div>
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Moneda (Divisa):</td>
										<td align="right"><input name="Moneda" id="tx12" maxlength="20" class="txboxdis" style="width:166px; text-align:left;" value="<?=$varp[11]?>" type="text" disabled="disabled"/>
										</td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Precio promedio:</td>
										<td align="right"><input name="Precio_Promedio" id="tx13" maxlength="15" class="txboxdis" style="width:166px; text-align:right; font-weight:bold" value="<?=number_format($varp[12], 4, $GLdecsepa, $GLmilsepa)?>" disabled="disabled"/></td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Cantidad ajuste:</td>
										<td align="right"><input name="Cantidad" id="tx14" maxlength="15" class="txboxdis" style="width:166px; text-align:right; font-weight:bold" value="<?=number_format($varp[13], 2, $GLdecsepa, $GLmilsepa)?>" disabled="disabled"/></td>
									</tr>
									<tr class="trtxco">
										<td style="width:95px; text-align:left" class="fcont">Valor del ajuste:</td>
										<td align="right"><input name="Valor" id="tx15" maxlength="15" class="txboxdis" style="width:166px; text-align:right; font-weight:bold" value="<?=number_format($varp[14], 2, $GLdecsepa, $GLmilsepa)?>" oninput="" disabled="disabled" /></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden" class="dlin_1 drod_1">
						<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
							<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Medio de pago</strong></div>
							<div style="margin-bottom:7px">
								<table style="width:100%" cellpadding="0" cellspacing="0">
									<tr class="trtxco">
										<td style="width:90px; text-align:left" class="fcont">Medio de pago:</td>
										<td style="width:95px" align="right"><input name="Medio_Pago" id="tx16" maxlength="50" class="txboxdis" style="width:84px; text-align:left;" value="<?=$varp[15]?>" type="text" disabled="disabled"/></td>
										<td style="width:95px; text-align:right" class="fcont">Nombre banco:</td>
										<td align="right" style="width:145px"><input name="Banco" id="tx17" maxlength="50" class="txboxdis" style="width:136px; text-align:left;" value="<?=$varp[16]?>" type="text" disabled="disabled"/>
										</td>
										<td style="width:50px; text-align:right" class="fcont">Cuenta:</td>
										<td align="right"><input name="Cuenta" id="tx18" maxlength="50" class="txboxdis" style="width:91px; text-align:left;" value="<?=$varp[17]?>" type="text" disabled="disabled"/>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:4px; overflow:hidden; padding-bottom:3px">
						<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Observaciones</strong></div>
						<div><textarea name="Observaciones" cols="0" class="txboxo" id="tx19" style="width:592px; overflow:auto; height:35px; resize:none;"><?=$varp[18]?></textarea></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:4px; overflow:hidden; padding-bottom:3px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcont" style="width:88px;" onclick="Accept_Ajuste('<?=$var[1]?>')" /></div>
						<div style="float:left; margin-right:10px"><input name="btcancel" id="btcancel" type="button" value="Cancelar" class="btcont" style="width:88px;" onclick="Cancel_Ajuste('<?=$var[1]?>')" /></div>
						<div style="float:left; margin-right:10px"><input name="btprint" id="btprint" type="button" value="Imprimir" class="btcontdis" style="width:88px;" onclick="cmPrint_Clic()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value=""  />
						<input type="hidden" name="Codigo_Movimiento" id="tx2" value="142" />
						<input type="hidden" name="Estado" id="tx9" value="ACTIVO" />
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div>
	    <iframe name="frPrint1" id="frPrint1" align="left" frameborder="0" style="width:0px; height:0px;" src=""></iframe>
	    <iframe name="frPrint2" id="frPrint2" align="left" frameborder="0" style="width:0px; height:0px;" src=""></iframe>
	</div>
</div>
</body>
</html>
