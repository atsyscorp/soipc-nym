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
	//---------------------------------------------------
	//Carga combo tipo de operacion
	$strSQL = "SELECT DISTINCT Documento FROM XConf_Consecutivos WHERE Area = '". $var[4] ."'";
	//Carga combo de sucurasales
	$strSQLS = "SELECT Codigo_Sucursal FROM Sucursales";
	//---------------------------------------------------
	$link=Conectarse();
	//---------------------------------------------------
	//Carga configuracion general
	$getGenSet = GenSet($link);
	// captura del nombre de ususario
	$nombreUsuario = $_GET['var3'];
	//USUARIOS PERMITIDOS PARA EXPORTAR
	$usuariosPermitidos = array('ALVARO PEÑA', 'OSCAR RODRIGUEZ', 'ANDRES NAVARRO','JUAN CAMILO NAVARRO','SAMUEL');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Buscar operación ventanilla</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frFindOps.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:302px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 101)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 298, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%;">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Buscar operación</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div>
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:115px; text-align:left" class="fcont">Tipo de operación:</td>
								<td align="right">
									<select name="cbBOType" id="tx1" style="width:170px; margin-left:10px" class="txboxo" onchange="cbBOType_Change()">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSQL)?>
									</select>
								</td>
							</tr>						
							<tr class="trtxco">
								<td style="width:115px; text-align:left" class="fcont">Sucursal:</td>
								<td align="right">
									<select name="cbBOSucur" id="tx2" style="width:170px; margin-left:10px" class="txboxo" onchange="cbBOSucur_Change()">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSQLS)?>
									</select>
								</td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Consecutivo:</td>
								<td align="right"><input name="txBOSerie" id="tx3" maxlength="20" class="txboxo" style="width:164px; text-align:center; font-weight:bold" value="" onkeydown="return Onlynum(this, event)"/></td>
							</tr>
						</table>
					</div>
					<div style="margin-top:7px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Buscar" class="btcont" style="width:88px;" onclick="Find_Clic()" /></div>
						<?php
							// Verificar si el usuario actual tiene permisos para exportar
							if(in_array($nombreUsuario, $usuariosPermitidos)) { // USUARIOS PERMITIDOS PARA EXPORTAR
						?>
                        	<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcontdis" disabled="disabled" style="width:88px;" onclick="Export_Clic()" /></div>
                        <?php } else { ?>
                       		<div style="float:left; margin-right:10px"><input name="btexport" id="btexport" type="button" value="Exportar" class="btcontdis" disabled="disabled" style="width:88px;" onclick="alert('No tienes permiso para exportar.')" /></div>
                        <?php } ?>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="txBOForm" id="txBOForm" value=""  />
						<input type="hidden" name="txBOTable" id="txBOTable" value=""  />
						<input type="hidden" name="txBOTable" id="txBOPrefix" value=""  />
						<input type="hidden" name="txBOId" id="txBOId" value=""  />
						<input type="hidden" name="txBOOrigen" id="txBOOrigen" value="<?=$var[4]?>"  />
 
					</div>
					<div style="clear:both"></div>
					<div id="lstBOList" style="height:308px; overflow:auto; margin-top:4px" class="dlin_1 bgcol_1">
						<table cellpadding="0" cellspacing="0" width="284px" id="tbBOList">
							<tr class="bgcol_6 fwhite" id="trOpsTit">
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
