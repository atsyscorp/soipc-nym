<!-- Ventana consulta Satisfaccion de cliente -->
<div align="center" style="width:100%; margin-top:25px;">
	<?=$msbloc=dBloc()?>
	<?php
	//---------------------------------------------------
	//Carga combo sucursales
	$strSQS = "SELECT Codigo_Sucursal FROM Sucursales";
	//-------------------------------------------------
	//Consulta de campos de tabla
	$strSQC = "SELECT * FROM Encuestas LIMIT 0, 1";
	$pc = mysqli_query($link, $strSQC) or die(mysqli_error($link));
	$ic = mysqli_num_fields($pc);
	?>
	<div align="center" style="width:440px; position:relative">
		<div id="dpup2" style="position:relative; top:0px; left:0px">
			<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 436, 2, '', '', 1, '', 1, 'hidden')?>
		</div>
		<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
			<div class="bgcol_6" style="overflow:hidden">
				<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Reporte satisfacción clientes</div>
				<div style="float:right; margin:3px" class="dmMain bgcol_1" onclick="hidcap('dConsCri')"><img src="images/close.png" style="height:20px; width:auto" /></div>
			</div>
			<div style="margin:0px">
				<div style="width:100%">
					<div id="dCriConsulta" class="bttabsel_1 dsition_4" style="width:35%" onClick="Sel_TabC(this)">Criterios de consulta</div>
					<div id="dAvanzadas" class="bttab_1 dsition_4" style="width:35%" onClick="Sel_TabC(this)">Opciones avanzadas</div>
					<div style="width:30%; border-bottom:#BEBEBE solid 1px; float:left; height:35px"></div>
					<div style="clear:both"></div>
				</div>
				<div style="margin:15px 8px">
					<!-- Criterios de consulta -->
					<div id="dCriConsultaT">
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:40%; text-align:left" class="fcont">Tipo de operación:</td>
								<td align="right" style="width:60%">
									<select name="Tipo_Operacion" id="tx1" style="width:100%;" class="txboxo" onchange="">
										<option value=""></option>
										<option value="COMPRAS Y VENTAS">COMPRAS Y VENTAS</option>
										<option value="COMPRA DE DIVISAS">COMPRA DE DIVISAS</option>
										<option value="VENTA DE DIVISAS">VENTA DE DIVISAS</option>
									</select>
								</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Seleccione sucursal:</td>
								<td align="right">
									<select name="Sucursal" id="tx2" style="width:100%;" class="txboxo">
										<option value=""></option>
										<option value="TODAS">TODAS</option>
										<?=$cbLoad=LoadConfTab($link, $strSQS)?>
									</select>
								</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Fecha inicial:</td>
								<td align="right"><input type="date" name="Fecha" id="tx3" maxlength="30" class="txboxo" style="width:98%;" value="" /></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Fecha final:</td>
								<td align="right"><input type="date" name="Fecha" id="tx4" maxlength="30" class="txboxo" style="width:98%;" value="" /></td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Identificación Cliente:</td>
								<td align="right"><input name="Id_Cliente" id="tx5" maxlength="40" class="txbox" style="width:98%; text-align:left;" value="" /></td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Respuesta Satisfacción:</td>
								<td align="right" style="width:60%">
									<select name="Respuesta" id="tx6" style="width:100%;" class="txbox" onchange="">
										<option value=""></option>
										<option value="Excelente">EXCELENTE</option>
										<option value="Bueno">BUENO</option>
										<option value="Regular">REGULAR</option>
										<option value="Malo">MALO</option>
									</select>
								</td>
							</tr>
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Ordenar por:</td>
								<td align="right">
									<select name="cbOrder" id="tx7" style="width:100%;" class="txbox" onChange="Sel_OrderC()">
										<option value=""></option>
										<?php
										for ($j = 0; $j <= $ic - 1; $j++) {
										?>
										<option value="<?php echo mysqli_fetch_field_direct($pc, $j)->name; ?>"><?php echo mysqli_fetch_field_direct($pc, $j)->name; ?></option>
										<? } ?>
									</select>
								</td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Tipo ordenación:</td>
								<td align="right">
									<select name="cbOrderType" id="tx8" style="width:100%;" class="txbox">
										<option value=""></option>
										<option value="asc">Ascendente</option>
										<option value="desc">Descendente</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<!-- Opciones avanzadas -->
					<div id="dAvanzadasT" style="display:none">
						<div style="width:98%; margin:auto">
							<div>
								<div class="fgreen">Seleccione los campos en reporte</div>
								<div style="margin-top:7px; padding:8px 0; max-height:250px; overflow:auto" class="dlin_1 bgcol_1">							
									<div id="dchfields" style="width:96%; margin:auto">
									<?php
									for ($j = 0; $j <= $ic - 1; $j++) {
									?>
										<div style="padding:5px 0">
											<input type="checkbox" id="<?php echo mysqli_fetch_field_direct($pc, $j)->name; ?>" name="<?php echo mysqli_fetch_field_direct($pc, $j)->name; ?>" class="chselfild" /><label for="<?php echo mysqli_fetch_field_direct($pc, $j)->name; ?>" class="fcont"><?php echo mysqli_fetch_field_direct($pc, $j)->name; ?></label>
										</div>						
									<? } ?>
									</div>
								</div>
							</div>						
							<div style="margin-top:15px">
								<div class="fgreen">Seleccione tabla para relacionar</div>
								<div style="margin-top:5px">
									<select name="Relaciona" id="Relaciona" style="width:100%;" class="txbox">
										<option value=""></option>
										<option value="Sucursales|Codigo_Sucursal|Sucursal">Sucursales - Código Sucursal</option>
										<option value="Operacion_Ventanilla|Identificacion|Id_Factura">Operación - Id Factura</option>
										<option value="Clientes|Identificacion|Id_Cliente">Clientes - Documento Declarante</option>
									</select>
								</div>
							</div>						
						</div>		
					</div>
				</div>
				<div style="margin:10px 8px">
					<div style="float:left; margin-right:10px"><input name="btacceptgen" id="btacceptgen" type="button" value="Generar reporte" class="btcont" style="width:120px;" onclick="RepSatisfaccion()" /></div>
					<div style="float:left;"><input name="btexitgen" id="btexitgen" type="button" value="Salir" class="btcont" style="width:88px;" onclick="hidcap('dConsCri')" /></div>
					<div style="clear:both"></div>
				</div>  
			</div>
		</div>
	</div>
</div>