<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	$link=Conectarse();
	//Carga Listado de Usuarios
	$strSQL = "SELECT * FROM Usuarios";
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Configuración de Usuarios</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frUsers.js" type="text/javascript"></script>
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
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Configuración de Usuarios</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div style="margin-bottom:7px">
						<div style="float:left" class="fgreen"><strong>Información del usuario</strong></div>
						<div style="float:left; width:358px; margin-top:9px; margin-left:10px" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible; margin-top:7px">
						<div style="float:left; width:245px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:90px; text-align:left" class="fcont">Identificación:</td>
									<td align="right"><input name="Identificacion" id="tx1" maxlength="50" class="txboxo" style="width:150px; text-align:left" value="" onkeyup="txId_Change()" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Nombre:</td>
									<td align="right"><input name="Nombre" id="tx2" maxlength="100" class="txboxo" style="width:150px; text-align:left" value="" onkeyup="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Clave acceso:</td>
									<td align="right"><input name="ClaveAcceso" id="tx3" maxlength="20" class="txboxo" style="width:150px; text-align:left" value="" onkeyup="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Cargo:</td>
									<td align="right">
										<select name="Cargo" id="tx4" style="width:156px; margin-left:10px" class="txboxo" onchange="">
											<option value=""></option>
											<option value="ADMINISTRADOR">ADMINISTRADOR</option>
											<option value="CAJERO">CAJERO</option>
											<option value="COORDINADOR">COORDINADOR</option>
											<option value="CONTADOR">CONTADOR</option>
											<option value="CUMPLIMIENTO">CUMPLIMIENTO</option>
											<option value="SUPERUSUARIO">SUPERUSUARIO</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div style="float:right; width:245px; overflow:visible">
							<table style="width:100%" cellpadding="0" cellspacing="0">
								<tr class="trtxco">
									<td style="width:90px; text-align:left" class="fcont">Teléfono:</td>
									<td align="right"><input name="Telefono" id="tx6" maxlength="50" class="txbox" style="width:150px; text-align:left" value="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">E-mail:</td>
									<td align="right"><input name="EMail" id="tx7" maxlength="70" class="txbox" style="width:150px; text-align:left" value="" /></td>
								</tr>
								<tr class="trtxco">
									<td style="text-align:left" class="fcont">Observaciones:</td>
									<td align="right"><textarea name="Observaciones" cols="0" class="txbox" id="tx8" style="width:150px; overflow:auto; height:46px; resize:none;"></textarea></td>
								</tr>
							</table>
						</div>
						<div style="clear:both"></div>
					</div>
					<div style="margin-bottom:7px; margin-top:7px">
						<div style="float:left" class="fgreen"><strong>Seleccione módulos a los que tiene acceso</strong></div>
						<div style="float:left; width:245px; margin-top:9px; margin-left:10px" class="dlin_4"></div>
						<div style="clear:both"></div>
					</div>
					<div style="overflow:visible;">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr>
								<td style="width:96px; text-align:left" class="fcont"><input name="ch1" id="ch1" type="checkbox" value="" onchange="" />Configuración</td>
								<td style="width:88px; text-align:left" class="fcont"><input name="ch2" id="ch2" type="checkbox" value="" onchange="" />Ventanilla</td>
								<td style="width:96px; text-align:left" class="fcont"><input name="ch3" id="ch3" type="checkbox" value="" onchange="" />Coordinación</td>
								<td style="width:92px; text-align:left" class="fcont"><input name="ch4" id="ch4" type="checkbox" value="" onchange="" />Contabilidad</td>
								<td style="width:96px; text-align:left" class="fcont"><input name="ch5" id="ch5" type="checkbox" value="" onchange="" />Cumplimiento</td>
							</tr>
						</table>
					</div>
					<div style="margin-bottom:10px; margin-top:10px; width:100%" class="dlin_4"></div>
					<div>
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcontdis" style="width:88px;" onclick="User_Accept()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btmodif" id="btmodif" type="button" value="Modificar" class="btcontdis" style="width:88px;" onclick="Modi_User()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btdelete" id="btdelete" type="button" value="Eliminar" class="btcontdis" style="width:88px;" onclick="Del_User()" disabled="disabled" /></div>
						<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcont" style="width:88px;" onclick="Export_User('<?=$sGenSet[0]?>')" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="NivelAcceso" id="tx5" value="0|0|0|0|0|0|0"  />
						<input type="hidden" name="Otro1" id="tx9" value=""  />
					</div>
					<div style="clear:both"></div>
					<div id="tabUsers" style="height:164px; overflow:auto; margin-top:7px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="970px">
							<tr class="bgcol_6 fwhite" id="trTasTit">
								<td style="width:86px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol('td1')" onmouseout="menusol('td1')">Identificación
									<div style="position:relative; visibility:hidden" id="td1">
										<div style=" position:absolute; left:70px; top:-15px; line-height:0.9em">
											<div onclick="User_Order('Identificacion', 'asc')">&#9650</div>
											<div onclick="User_Order('Identificacion', 'desc')">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:125px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol('td2')" onmouseout="menusol('td2')">Nombre
									<div style="position:relative; visibility:hidden" id="td2">
										<div style=" position:absolute; left:90px; top:-15px; line-height:0.9em">
											<div onclick="User_Order('Nombre', 'asc')">&#9650</div>
											<div onclick="User_Order('Nombre', 'desc')">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:100px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol('td3')" onmouseout="menusol('td3')">Clave acceso
									<div style="position:relative; visibility:hidden" id="td3">
										<div style=" position:absolute; left:75px; top:-15px; line-height:0.9em">
											<div onclick="User_Order('ClaveAcceso', 'asc')">&#9650</div>
											<div onclick="User_Order('ClaveAcceso', 'desc')">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:95px; text-align:left; vertical-align:middle; cursor:pointer" class="celrow" onmouseover="menusol('td4')" onmouseout="menusol('td4')">Cargo
									<div style="position:relative; visibility:hidden" id="td4">
										<div style=" position:absolute; left:80px; top:-15px; line-height:0.9em">
											<div onclick="User_Order('Cargo', 'asc')">&#9650</div>
											<div onclick="User_Order('Cargo', 'desc')">&#9660</div>
										</div>
									</div>
								</td>
								<td style="width:85px; text-align:left; vertical-align:middle" class="celrow">Nivel acceso</td>
								<td style="width:67px; text-align:left; vertical-align:middle" class="celrow">Teléfono</td>
								<td style="width:90px; text-align:left; vertical-align:middle" class="celrow">E-mail</td>
								<td style="width:120px; text-align:left; vertical-align:middle" class="celrow">Dirección</td>
								<td style="width:200px; text-align:left; vertical-align:middle" class="celrow">Observaciones</td>
							</tr>
							<?
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
