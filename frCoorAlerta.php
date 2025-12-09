<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	date_default_timezone_set('America/Bogota');
	//Captura variables
	$var[1]=$_GET['var1']; //--> Id de usuario que crea la alerta
	$link=Conectarse();
	//Carga Listado de sucursales
	$strSUC = "SELECT Codigo_Sucursal FROM Sucursales";
	//---------------------------------------------------
	//Carga informacion de alertas ultimas 30
	$strSQL = "SELECT * FROM Alertas_Usuarios ORDER BY Fecha desc LIMIT 0, 30";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Alertas usuarios y sucursales</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frCoorAlerta.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:570px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 235)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 566, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Alertas cajeros y sucursales</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; width:270px; overflow:visible">
							<div>
								<div style="float:left" class="fgreen"><strong>Criterios de alerta</strong></div>
								<div style="float:right; width:155px; margin-top:9px;" class="dlin_4"></div>
								<div style="clear:both"></div>
							</div>
							<table style="width:100%; margin-top:7px" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:110px; text-align:left" class="fcont">Sucursal:</td>
									<td align="right">
										<select name="Sucursal" id="tx6" style="width:150px;" class="txboxo">
											<option value=""></option>
											<?=$cbLoad=LoadConfTab($link, $strSUC)?>
										</select>
									</td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Cajero:</td>
									<td align="right">
										<select name="Usuario" id="tx7" style="width:150px;" class="txboxo">
											<option value=""></option>
											<?php
												$strSQUr = "SELECT Identificacion, Nombre FROM Usuarios";
												$pur=mysqli_query($link, $strSQUr) or die(mysqli_error($link));
												while($qur=mysqli_fetch_array($pur)){
												
											?>
											<option value="<?=$qur['Identificacion']?>"><?=$qur['Nombre']?></option>
											<? } ?>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div style="float:right; width:270px; overflow:visible">
							<div>
								<div style="float:left" class="fgreen"><strong>Información de alerta</strong></div>
								<div style="float:right; width:140px; margin-top:9px;" class="dlin_4"></div>
								<div style="clear:both"></div>
							</div>
							<table style="width:100%; margin-top:7px" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:110px; text-align:left" class="fcont">Fecha alerta:</td>
									<td align="right"><input name="Fecha" id="tx3" class="txboxdis" style="width:150px; text-align:left" value="<?php echo date("Y-m-d"); ?>" disabled="disabled" /></td>
								</tr>
								<tr class="trtxco">
									<td style="width:110px; text-align:left" class="fcont">Estado leído:</td>
									<td align="right"><input name="Leido" id="tx8" class="txboxdis" style="width:150px; text-align:left" value="NO" disabled="disabled" /></td>
								</tr>
							</table>		
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin-top:10px">
						<div>
							<div style="float:left" class="fgreen"><strong>Contenido de alerta</strong></div>
							<div style="float:right; width:430px; margin-top:9px;" class="dlin_4"></div>
							<div style="clear:both"></div>
						</div>
						<table style="width:100%; margin-top:7px" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:100%; text-align:left" class="fcont">Título:</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont"><textarea name="Titulo" cols="0" class="txboxo" id="tx4" maxlength="190" style="width:548px; overflow:auto; height:35px; resize:none; text-transform:none"></textarea></td>
							</tr>
							<tr class="trtxco">
								<td style="width:100%; text-align:left" class="fcont">Contenido / mensaje:</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont"><textarea name="Contenido" cols="0" class="txboxo" id="tx5" style="width:548px; overflow:auto; height:80px; resize:none; text-transform:none"></textarea></td>
							</tr>
						</table>
					</div>
					<div style="margin-top:10px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcont" style="width:88px;" onclick="Alert_Accept()" /></div>
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="Modi_Alert()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btdelete" id="btdelete" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_Alert()" disabled="disabled" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value=""  />
						<input type="hidden" name="Identificacion_General" id="tx2" value=""  />
					</div>					
					<div style="clear:both"></div>
					<div id="tabAlerts" style="height:190px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="1170px">
							<tr class="bgcol_6 fwhite" id="trSucTit">
								<td style="width:100px; max-width:100px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Identificación</td>
								<td style="width:110px; max-width:100px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Creado por</td>
								<td style="width:80px; max-width:80px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Fecha</td>
								<td style="width:200px; max-width:200px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Título</td>
								<td style="width:370px; max-width:370px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Contenido</td>
								<td style="width:80px; max-width:80px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Sucursal</td>
								<td style="width:170px; max-width:170px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Usuario</td>
								<td style="width:60px; max-width:60px; text-align:left; vertical-align:middle; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="celrow">Leído</td>
							</tr>
							<? $listLoad = LoadTable_3($link, $strSQL, 'true', 0); ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>