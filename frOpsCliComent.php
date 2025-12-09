<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura variable de ID de operacion
	$var[1]=(isset($_GET['var1'])) ? $_GET['var1'] : ''; 
	//---------------------------------------------------
	//variables de main
	$var[2]=(isset($_GET['var2'])) ? $_GET['var2'] : ''; //--> Sucursal
	$var[3]=(isset($_GET['var3'])) ? $_GET['var3'] : ''; //--> Caja
	$var[4]=(isset($_GET['var4'])) ? $_GET['var4'] : ''; //--> Usuario
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Si var1 es no vacio consulta información de operacion
	if($var[1] != '')
	{
		$strSQLO = "SELECT * FROM Operacion_Ventanilla WHERE Identificacion = '". $var[1]. "'";
		$p=mysqli_query($link, $strSQLO) or die(mysqli_error($link));
		while($n=mysqli_fetch_array($p)){
			$tops = $n['Tipo_Operacion'];
			$cops = $n['Codigo_Operacion'];
			$cons = $n['Consecutivo'];
			$idbn = $n['Documento_Beneficiario'];
			$nobn = $n['Nombre_Completo'];
			$idde = $n['Documento_Declarante'];
			$node = $n['Nombre_Declarante'];
			$var[2] = $n['Sucursal']; //--> Sucursal
			$var[3] = $n['Estacion']; //--> Caja
			$var[4] = $n['Cajero']; //--> Usuario
		}
	}	
	//---------------------------------------------------
	//Carga combo tipo de operacion
	$strSQL = "SELECT DISTINCT Documento FROM XConf_Consecutivos WHERE Area = 'VENTANILLA'";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Comentarios Operaciones</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsCliComent.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="frOpsCliComent_Load('<?=(isset($tops)) ? $tops : NULL?>', '<?=(isset($cops)) ? $cops : NULL?>')">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen"> 
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:422px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 161)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 418, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div class="bgcol_6" style="overflow:hidden">
					<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Comentarios a operaciones y clientes</div>
					<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="closewin()"><img src="images/close.png" style="height:20px; width:auto" /></div>
				</div>
				<div style="margin:8px">
					<div class="bgcol dlin_3 drod_1" style="padding:6px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr style="height:16px">
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Sucursal:</td>
								<td align="left" width="56px"><input name="Sucursal" id="tx4" maxlength="15" class="txlabel fgreen" style="width:46px; text-align:left; font-weight:bold" type="text" value="<?=$var[2]?>" disabled="disabled"  /></td>								
								<td style="width:58px; text-align:left; vertical-align:middle" class="fcont">Fecha: </td>
								<td align="left"><input name="Fecha" id="tx7" maxlength="15" class="txlabel fgreen" style="width:215px; text-align:left; font-weight:bold" type="text" value="" disabled="disabled"  /></td>								
							</tr>
							<tr style="height:16px">
								<td style="width:62px; text-align:left; vertical-align:middle" class="fcont">Estación:</td>
								<td align="left" width="56px"><input name="Estacion" id="tx5" maxlength="15" class="txlabel fgreen" style="width:46px; text-align:left; font-weight:bold" type="text" value="<?=$var[3]?>" disabled="disabled"  /></td>								
								<td style="width:58px; text-align:left; vertical-align:middle" class="fcont">Cajero:</td>
								<td align="left"><input name="Empleado" id="tx6" maxlength="50" class="txlabel fgreen" style="width:215px; text-align:left; font-weight:bold" type="text" value="<?=$var[4]?>" disabled="disabled"  /></td>								
							</tr>
						</table>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:8px">
						<div style="float:left" class="fgreen"><strong>Identificación de operación</strong></div>
						<div style="float:right; width:245px; margin-top:9px" class="dlin_4"></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:0px">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:205px; text-align:left" class="fcont">Tipo de operación:</td>
								<td style="text-align:left" class="fcont">Consecutivo:</td>
							</tr>
							<tr class="trtxco">
								<td align="left">
									<select name="Tipo_Operacion" id="tx3" style="width:195px;" class="txboxo" onchange="TipoOps_Change()">
										<option value=""></option>
										<?=$cbLoad=LoadConfTab($link, $strSQL)?>
									</select>
								</td>
								<td align="left"><input name="Consecutivo" id="tx8" maxlength="20" class="txboxo" style="width:192px; text-align:left" value="<?=(isset($cons)) ? $cons : NULL?>" onkeydown="return Onlynum(this, event)" /></td>
							</tr>
						</table>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:8px">
						<div style="float:left" class="fgreen"><strong>Identificación del cliente</strong></div>
						<div style="float:right; width:255px; margin-top:9px" class="dlin_4"></div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:7px; overflow:hidden">
						<div style="float:left; width:196px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Beneficiario</strong></div>
									<div class="fcont">Documento: <input name="Documento_Beneficiario" id="tx9" maxlength="20" class="txboxo" style="width:103px; text-align:left" value="<?=(isset($idbn)) ? $idbn : NULL?>" onkeyup="FindCli('tx9', 'tx10')" /></div>
									<div class="fcont" style="margin-bottom:4px; margin-top:7px">Nombre beneficiario:</div>
									<div><textarea name="Nombre_Beneficiario" cols="0" class="txboxo" id="tx10" style="width:173px; overflow:auto; height:50px; resize:none; margin-bottom:3px"><?=(isset($nobn)) ? $nobn : NULL?></textarea></div>
							</div>
						</div>
						<div style="float:right; width:196px; overflow:hidden" class="dlin_1 drod_1">
							<div style="margin-left:8px; margin-right:8px; margin-bottom:4px">
								<div style="margin-top:6px; margin-bottom:4px" class="fgreen"><strong>Declarante</strong></div>
									<div class="fcont">Documento: <input name="Documento_Declarante" id="tx11" maxlength="20" class="txbox" style="width:103px; text-align:left" value="<?=(isset($idde)) ? $idde : NULL?>" onkeyup="FindCli('tx11', 'tx12')" /></div>
									<div class="fcont" style="margin-bottom:4px; margin-top:7px">Nombre declarante:</div>
									<div><textarea name="Nombre_Declarante" cols="0" class="txbox" id="tx12" style="width:173px; overflow:auto; height:50px; resize:none; margin-bottom:3px"><?=(isset($nobn)) ? $nobn : NULL?></textarea></div>
							</div>
						</div>						
					</div>					
					<div style="margin-top:4px">
						<div style="padding:4px; border-bottom:none" class="fcont">Escriba el comentario al cliente o la operación</div>
						<div><textarea name="Observaciones" cols="0" class="txboxo" id="tx13" style="width:400px; overflow:auto; height:72px; resize:none; margin-bottom:3px"></textarea></div>	
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:4px; overflow:hidden; padding-bottom:3px">
						<div style="float:left; margin-right:10px"><input name="btaccept" id="btaccept" type="button" value="Aceptar" class="btcont" style="width:88px;" onclick="cmAccept_Clic()" /></div>
						<div style="float:left;"><input name="btexit" id="btexit" type="button" value="Salir" class="btcont" style="width:88px;" onclick="closewin()" /></div>
						<input type="hidden" name="Identificacion" id="tx1" value=""  />
						<input type="hidden" name="Codigo_Operacion" id="tx2" value=""  />
						<input type="hidden" name="Contador" id="tx14" value="1"  />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
