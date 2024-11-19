<!-- Ventana consulta calificaciones de alerta -->
<div align="center" style="width:100%; margin-top:25px;">
	<?=$msbloc=dBloc()?>
	<?php
	//-------------------------------------------------
	//Consulta de campos de tabla
	$strSQC = "SELECT * FROM Lista_Clinton_1 LIMIT 0, 1";
	$pc = mysqli_query($link, $strSQC) or die(mysqli_error($link));
	$ic = mysqli_num_fields($pc);
	?>
	<div align="center" style="width:440px; position:relative">
		<div id="dpup2" style="position:relative; top:0px; left:0px">
			<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 436, 2, '', '', 1, '', 1, 'hidden')?>
		</div>
		<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
			<div class="bgcol_6" style="overflow:hidden">
				<div style="float:left; font-weight:bold; margin-left:5px; margin-top:6px" class="fwhite">Reporte lista Clinton</div>
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
								<td style="text-align:left" class="fcont">Número identificación:</td>
								<td align="right"><input name="Informacion" id="tx1" maxlength="40" class="txbox" style="width:98%; text-align:left;" value="" /></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Nombres y/o apellidos:</td>
								<td align="right"><input name="Nombre" id="tx2" maxlength="50" class="txbox" style="width:98%; text-align:left;" value="" /></td>
							</tr>						
							<tr class="trtxco">
								<td style="text-align:left" class="fcont">Ordenar por:</td>
								<td align="right">
									<select name="cbOrder" id="tx3" style="width:100%;" class="txbox" onChange="Sel_OrderC()">
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
									<select name="cbOrderType" id="tx4" style="width:100%;" class="txbox">
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
									</select>
								</div>
							</div>						
						</div>		
					</div>
				</div>
				<div style="margin:10px 8px">
					<div style="float:left; margin-right:10px"><input name="btacceptgen" id="btacceptgen" type="button" value="Generar reporte" class="btcont" style="width:120px;" onclick="RepClinton()" /></div>
					<div style="float:left;"><input name="btexitgen" id="btexitgen" type="button" value="Salir" class="btcont" style="width:88px;" onclick="hidcap('dConsCri')" /></div>
					<div style="clear:both"></div>
				</div> 
			</div>
		</div>
	</div>
</div>