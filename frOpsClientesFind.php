<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//Carga configuracion general
	$getGenSet = GenSet($link);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Consulta de Clientes</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frOpsClientesFind.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol_2">
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:0px;">
		<div align="center" style="width:654px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 274)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 648, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="bgcol_2" align="left" style="width:100%">
				<div style="clear:both"></div>
				<div style="margin:8px">
					<div style="margin-top:8px">
						<div style="float:left; overflow:hidden; margin-right:8px">
							<div class="fcont" style="padding-left:2px">Seleccione variable de consulta</div>
							<div>
								<select name="cbCOCVar" id="cbCOCVar" class="txbox" style="width:184px; margin:2px; text-transform:none" onchange="" >
									<option value=""></option>
									<option value="Identificacion">Identificacion</option>
									<option value="Documento">Documento</option>
									<option value="DV">DV</option>
									<option value="Tipo_Documento">Tipo_Documento</option>
									<option value="Nombre_1">Nombre_1</option>
									<option value="Nombre_2">Nombre_2</option>
									<option value="Apellido_1">Apellido_1</option>
									<option value="Apellido_2">Apellido_2</option>
									<option value="Nombre_Completo">Nombre_Completo</option>
									<option value="Nacionalidad">Nacionalidad</option>
									<option value="Indicativo">Indicativo</option>
									<option value="Telefono">Telefono</option>
									<option value="Celular">Celular</option>
									<option value="Direccion">Direccion</option>
									<option value="Departamento">Departamento</option>
									<option value="Ciudad">Ciudad</option>
									<option value="Ocupacion">Ocupacion</option>
									<option value="EMail">EMail</option>
									<option value="Grupo_Segmento">Grupo_Segmento</option>
									<option value="Segmento">Segmento</option>
									<option value="Origen_Fondos">Origen_Fondos</option>
									<option value="Observaciones">Observaciones</option>
									<option value="Fecha_Ingreso">Fecha_Ingreso</option>
									<option value="Fecha_Operacion">Fecha_Operacion</option>
									<option value="Estado">Estado</option>
								</select>
							</div>
						</div>
						<div style="float:left; overflow:hidden; margin-right:8px">
							<div class="fcont" style="padding-left:2px">Digite la palabra clave por la cual desea buscar clientes:</div>
							<div><input name="txCOCFind" id="txCOCFind" maxlength="100" class="txbox" style="width:302px; text-align:left; margin:2px; margin-top:3px" value="" onkeydown="return txFind_Enter(this, event)" /></div>
						</div>
						<div style="float:left; overflow:hidden; margin-right:0px">
							<input name="cmCOCFind" id="cmCOCFind" type="button" value="Buscar clientes" class="btcont" style="width:117px; margin:2px; margin-top:14px" onclick="cmCOCFind_Click()" />										
						</div>
					</div>
					<div style="clear:both"></div>
					<div style="margin-top:6px">
						<div style="float:left; overflow:hidden; margin-left:2px; margin-right:8px; margin-top:1px">
							<span class="fcont">Registro:</span><input name="txCOCReg" id="txCOCReg" maxlength="100" class="txboxdis" style="width:58px; text-align:center; margin:2px; margin-left:4px; margin-right:6px" value="0" readonly="true" onkeydown="" /><span class="fcont">De:</span> 	<input name="txCOCTotal" id="txCOCTotal" maxlength="100" class="txboxdis" style="width:58px; text-align:center; margin:2px; margin-left:4px; margin-right:4px" value="0" readonly="true" onkeydown="" />
						</div>
						<div style="float:left; overflow:hidden; margin-right:8px;">
							<input name="cmCOCSendD" id="cmCOCSendD" type="button" value="Enviar a declarante" class="btcont" style="width:144px; margin:2px; margin-top:0px" onclick="cmCOCSendD_Click()" />										
						</div>
						<div style="float:left; overflow:hidden; margin-right:8px;">
							<input name="cmCOCSendB" id="cmCOCSendB" type="button" value="Enviar a beneficiario" class="btcont" style="width:144px; margin:2px; margin-top:0px" onclick="cmCOCSendB_Click()" />										
						</div>
						<div style="float:left; overflow:hidden; margin-right:0px;">
							<input name="cmCOCExport" id="cmCOCExport" type="button" value="Exportar" class="btcont" style="width:92px; margin:2px; margin-top:0px" onclick="cmCOCExport_Click('<?=$sGenSet[0]?>')" />										
						</div>
						<input type="hidden" name="IdCli" id="IdCli" value="" />
					</div>					
					<div style="clear:both"></div>
					<div style="margin-top:6px">
						<div style="padding:4px; border-bottom:none" class="fcont bgcol drod_4 dlin_3">Listado de clientes encontrados (Clic para seleccionar)</div>
						<div id="dCOCList" style="height:488px; overflow:auto" class="dlin_1 bgcol_1">
							<table id="lstCOCList" cellpadding="0" cellspacing="0" width="2311px">
								<tr id="trCoCabe" class="bgcol_6 fwhite">
									<td style="width:76px; text-align:left" class="celrow">Identificación</td>
									<td style="width:67px; text-align:left" class="celrow">Documento</td>
									<td style="width:29px; text-align:left" class="celrow">DV</td>
									<td style="width:44px; text-align:left" class="celrow">Tipo documento</td>
									<td style="width:80px; text-align:left" class="celrow">Nombre 1</td>
									<td style="width:80px; text-align:left" class="celrow">Nombre 2</td>
									<td style="width:80px; text-align:left" class="celrow">Apellido 1</td>
									<td style="width:80px; text-align:left" class="celrow">Apellido 2</td>
									<td style="width:120px; text-align:left" class="celrow">Nombre completo</td>
									<td style="width:80px; text-align:left" class="celrow">Nacionalidad</td>
									<td style="width:40px; text-align:left" class="celrow">Indicativo</td>
									<td style="width:70px; text-align:left" class="celrow">Teléfono</td>
									<td style="width:70px; text-align:left" class="celrow">Celular</td>
									<td style="width:120px; text-align:left" class="celrow">Dirección</td>
									<td style="width:80px; text-align:left" class="celrow">Departamento</td>
									<td style="width:80px; text-align:left" class="celrow">Ciudad</td>
									<td style="width:100px; text-align:left" class="celrow">Ocupación</td>
									<td style="width:80px; text-align:left" class="celrow">E-Mail</td>
									<td style="width:80px; text-align:left" class="celrow">Grupo segmento</td>
									<td style="width:80px; text-align:left" class="celrow">Segmento</td>
									<td style="width:70px; text-align:left" class="celrow">Origen fondos</td>
									<td style="width:120px; text-align:left" class="celrow">Observaciones</td>
									<td style="width:70px; text-align:left" class="celrow">Fecha nacimiento</td>
									<td style="width:70px; text-align:left" class="celrow">Última operación</td>
									<td style="width:60px; text-align:left" class="celrow">Estado</td>
									<td style="width:45px; text-align:left" class="celrow">Contador</td>
									<td style="width:70px; text-align:left" class="celrow">Persona política</td>
									<td style="width:120px; text-align:left" class="celrow">Contacto</td>
									<td style="width:80px; text-align:left" class="celrow">Parentesco</td>
									<td style="width:70px; text-align:left" class="celrow">Teléfono contacto</td>
								</tr>
							</table>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
