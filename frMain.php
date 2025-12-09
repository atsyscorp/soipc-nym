<?php
	error_reporting(E_ALL ^ E_NOTICE);
   	include("General.php");
	//Captura variables de inicio
	$access = (isset($_POST['tx1'])) ? $_POST['tx1'] : '';
	$login= (isset($_POST['tx2'])) ? $_POST['tx2'] : '';
	$caja= (isset($_POST['tx3'])) ? $_POST['tx3'] : '';
	//Valida si no entra desde inicio y sale
	if($access == '' || $login == ''){
		header("location:index.php");
	}
	//------------------------------------------------------------------
	$link = Conectarse();
	//Carga configuracion general
	$getGenSet = GenSet($link);
	//Carga configuracion de usuario
	$us = mysqli_query($link, "SELECT Identificacion FROM Usuarios WHERE ClaveAcceso = '". $login ."'") or die(mysqli_error($link));
	while($ur=mysqli_fetch_array($us)){$iduse = $ur['Identificacion'];}
	$getUser = (isset($iduse)) ? GetUser($link, $iduse) : NULL;
	/*
	//Carga Sucursal
	if($sUserCargo != 'SUPERUSUARIO')
	{
		$ipadrs = $_SERVER['REMOTE_ADDR'];
		$s = mysqli_query($link, "SELECT Codigo_Sucursal FROM Sucursales WHERE IP_Adress = '". $ipadrs ."'") or die(mysqli_error($link));
		while($su=mysqli_fetch_array($s)){$idsuc = $su[Codigo_Sucursal];}
		$getSucSet = SucSet($link, $idsuc);	
	} else {
		$getSucSet = SucSet($link, 'GEN');	
	}
	*/
	
	//Carga Sucursal
	if(isset($sUserCargo) && $sUserCargo != 'SUPERUSUARIO') {
		$ipadrs = $_SERVER['REMOTE_ADDR'];
		$s = mysqli_query($link, "SELECT Codigo_Sucursal FROM Sucursales WHERE IP_Adress LIKE '%". $ipadrs ."%'") or die(mysqli_error($link));
		while($su = mysqli_fetch_array($s)){$idsuc = $su['Codigo_Sucursal'];}
		$getSucSet = SucSet($link, $idsuc);	
	} else {
		$getSucSet = SucSet($link, 'GEN');	
	}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SOIPC</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frMain.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol" onload="IniSesion('<?=(isset($iduse)) ? $iduse : NULL?>', '<?=(isset($sUserName)) ? $sUserName : NULL?>', '<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>', '<?=$caja?>'); ConsAlert('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>', '<?=(isset($iduse)) ? $iduse : NULL ?>'); ConsClose('<?=(isset($sSucSet)) ? $sSucSet[0] : NULL ?>', '<?=$caja?>'); LoadOpsInf('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>', '<?=$caja?>'); ConsSals('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>'); ConsTasasCV('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>'); ConsTras('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>', '<?=$caja?>'); ConsTasasAlert('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>')">
<?=$dwt=MsWait(45, 215)?>
<div class="dcabe_1 bgcol_2 dlin_2" style="position:fixed; top:0px; left:0px; z-index:5; overflow:visible">
	<div style="float:left; margin-left:7px"><img src="images/icononym.png" style="height:24px; width:auto" /></div>
	<div style="float:left; margin-left:7px; margin-top:4px" class="fgreen"><?=(isset($sGenSet) && is_array($sGenSet)) ? $sGenSet[0] : NULL?> - <?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?></div>
	<?php if(isset($sSucSet) && $sSucSet[0] != "GEN") { ?>
		<div style="float:left; margin-top:4px; margin-left:7px" class="fgreen">(Estación <?=$caja?>)</div>
	<?php } ?>
	<div style="float:right; margin-right:50px" class="dmMain" onclick="exitapp()"><img src="images/close.png" style="height:24px; width:auto" /></div>
	<div style="float:right; margin-right:3px; position:relative; outline:0" class="dmMain" tabindex="0" onblur=""><img src="images/alert.png" style="height:24px; width:auto" onclick="waitim('dMAlerts1'); menusol('dMAlerts'); ShowAlerts('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>', '<?=(isset($iduse)) ? $iduse : NULL ?>')"/>
		<div style="position:absolute; left:13px; top:0px; z-index:70">
			<div id="dalcant" style="font-size:10px; text-align:center; width:14px; height:14px" class="fwhite bgcol_5 drod_3"></div>
		</div>
		<div id="dMAlerts" style="position:absolute; left:-185px; top:28px; cursor:default; visibility:hidden">
			<div style="overflow:hidden; width:210px; text-align:left; outline:0" class="bgcol_2 dlin_3 drod_1 dsom_3">
				<div id="dMAlerts1" style="margin:4px;overflow:hidden;"></div>
			</div>
		</div>
	</div>
	<div style="float:right; margin-right:3px; position:relative; outline:0" class="dmMain" tabindex="0" onblur=""><img src="images/traslado.png" style="height:24px; width:auto" onclick="waitim('dTrasa1'); menusol('dTrasa'); ShowTrasa('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>', '<?=$caja?>', '<?=(isset($sUserName)) ? $sUserName : NULL?>')"/>
		<div style="position:absolute; left:13px; top:0px; z-index:70">
			<div id="dtracan" style="font-size:10px; text-align:center; width:14px; height:14px" class="fwhite bgcol_5 drod_3"></div>
		</div>
		<div id="dTrasa" style="position:absolute; left:-185px; top:28px; cursor:default; visibility:hidden">
			<div style="overflow:hidden; width:210px; text-align:left; outline:0" class="bgcol_2 dlin_3 drod_1 dsom_3">
				<div id="dTrasa1" style="margin:4px;overflow:hidden;"></div>
			</div>
		</div>	
	</div>
	<div style="float:right; margin-right:20px; margin-top:4px" class="fgreen"><?=(isset($sUserName)) ? $sUserName : ''?></div>
	<div style="float:right; margin-right:0px"><img src="images/profile.png" style="height:24px; width:auto" /></div>
</div>
<div style="clear:both"></div>
<div class="dlin_7 bgcol_2" style="position:fixed; top:41px; left:0px; z-index:5; height:96%; width:210px;"> 
	<div class="bgcol_1 dlin_3 drod_1 fcont" style="margin:5px; padding:4px; height:97%; overflow:auto">
		<?php
			if(isset($sUserAcces) && $sUserAcces[0] == 1){
		?>
		<!--Configuracion -->
		<div>
			<div class="dMenOpt" onclick="MenuAccess('Configuracion')"><img src="images/settings.png" class="imMenu" /><div class="fgreen"><b>Configuración</b></div></div>
			<div id="Configuracion" style="margin-left:7px; height:auto; overflow:hidden">
				<div class="dMenOpt" onclick="MenuAccess('Empresa')"><img src="images/file.png" class="imMenu" /><div>Empresa</div></div>
					<div id="Empresa" style="margin-left:7px; height:0px; overflow:hidden">
						<div class="dMenOpt" onclick="clicmen('frUsers', '')"><img src="images/window.png" class="imMenu" /><div>Usuarios</div></div>

						<div class="dMenOpt" onclick="clicmen('frConfSucur', '')"><img src="images/window.png" class="imMenu" /><div>Sucursales</div></div>
					</div>
				<div class="dMenOpt" onclick="MenuAccess('Contabilidad2')"><img src="images/file.png" class="imMenu" /><div>Contabilidad</div></div>
					<div id="Contabilidad2" style="margin-left:7px; height:0px; overflow:hidden">
						<div class="dMenOpt" onclick="clicmen('frConfContable', '')"><img src="images/window.png" class="imMenu" /><div>Configuración contable</div></div>
						<div class="dMenOpt" onclick="clicmen('frConfBanks', '')"><img src="images/window.png" class="imMenu" /><div>Cuentas bancarias</div></div>
						<div class="dMenOpt" onclick="clicmen('frConfCostCen', '')"><img src="images/window.png" class="imMenu" /><div>Centros de costos</div></div>
						<div class="dMenOpt" onclick="clicmen('frConfSerie', '')"><img src="images/window.png" class="imMenu" /><div>Consecutivos</div></div>
					</div>
				<div class="dMenOpt" onclick="clicmen('frConfCambios', '')"><img src="images/window.png" class="imMenu" /><div>Configuración cambiaria</div></div>
				<div class="dMenOpt" onclick="clicmen('frConfSegmentos', '')"><img src="images/window.png" class="imMenu" /><div>Segmentos de mercado</div></div>
			</div>		
		</div>
		<?php } ?>
		<?php
			if(isset($sUserAcces) && $sUserAcces[1] == 1){
		?>
		<!--Ventanilla -->
		<div>
			<div class="dMenOpt" onclick="MenuAccess('Ventanilla')"><img src="images/ventanilla.png" class="imMenu" /><div class="fgreen"><b>Ventanilla</b></div></div>
			<div id="Ventanilla" style="margin-left:7px; height:auto; overflow:hidden">
				<?php /*
				<div class="dMenOpt" onclick="clicmen('frOpsTasas', 'var1=<?=$sSucSet[0]?>&var2=<?=$caja?>')"><img src="images/window.png" class="imMenu" /><div>Precios compra y venta</div></div> 
				*/
				?>
				<div class="dMenOpt" onclick="clicmen('frOpsClientes', 'var1=Dec&var2=<?=$sUserAcces[1]?>&var3=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var4=<?=$caja?>&var5=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Conocimiento clientes</div></div>
				<div class="dMenOpt" onclick="clicmen('frOpsCompra', 'var4=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var5=<?=$caja?>&var6=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Compra de divisas</div></div>
				<div class="dMenOpt" onclick="clicmen('frOpsVenta', 'var4=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var5=<?=$caja?>&var6=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Venta de divisas</div></div>
				<div class="dMenOpt" onclick="clicmen('frOpsAjustes', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Ajuste recursos</div></div>
				<div class="dMenOpt" onclick="clicmen('frOpsEgresos', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Egresos ventanilla</div></div>
				<div class="dMenOpt" onclick="clicmen('frOpsAnula', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=VENTANILLA&var5=NO')"><img src="images/window.png" class="imMenu" /><div>Anular operación</div></div>
				<div class="dMenOpt" onclick="clicmen('frFindOps', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=VENTANILLA')"><img src="images/look.png" class="imMenu" /><div>Buscar operación</div></div>
				<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepConsClie')"><img src="images/reports.png" class="imMenu" /><div>Consolidado cliente</div></div>
					
					
				<?php
					if($caja == 01){ //Caja principal puede hacer reportes de las otras cajas
				?>
				<div class="dMenOpt" onclick="clicmen('frRepConsOps', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=PRI')"><img src="images/reports.png" class="imMenu" /><div>Reporte movimiento</div></div>
				<?php } else { ?>
				<div class="dMenOpt" onclick="clicmen('frRepConsOps', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=CAJA')"><img src="images/reports.png" class="imMenu" /><div>Reporte movimiento</div></div>
				<?php } ?>
				<div class="dMenOpt" onclick="clicmen('frRepConsOps', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=CIERRE')"><img src="images/closev.png" class="imMenu" /><div>Cierres ventanilla</div></div>
			</div>
			<div class="dMenOpt" onclick="MenuAccess('Otras herramientas')"><img src="images/file.png" class="imMenu" /><div>Otras herramientas</div></div>
			<div id="Otras herramientas" style="margin-left:7px; height:0px; overflow:hidden">
				<div class="dMenOpt" onclick="clicmen('frOpsCliComent', 'var2=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var3=<?=$caja?>&var4=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Comentario operación</div></div>
				<?php if($sUserName=='MIREYA MORA' ){ //usuario habilitado para actualizar lista clinton 06/03/2023 inserta juan camilo ?>
				<div class="dMenOpt" onclick="clicmen('frCumpClinton', 'var1=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Actualizar lista Clinton</div></div>
				<?php } ?>
				<?php
					if($caja == 01){ //Caja principal puede hacer reportes de las otras cajas
				?>
				<div class="dMenOpt" onclick="clicmen('frRepTotComVen', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=PRI')"><img src="images/reports.png" class="imMenu" /><div>Total compra y venta</div></div>
				<div class="dMenOpt" onclick="clicmen('frRepOpsClose', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=PRI')"><img src="images/reports.png" class="imMenu" /><div>Validar cierres</div></div>
				<?php } else { ?>
				<div class="dMenOpt" onclick="clicmen('frRepOpsClose', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=CAJA')"><img src="images/reports.png" class="imMenu" /><div>Total compra y venta</div></div>
				<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepConsClie')"><img src="images/reports.png" class="imMenu" /><div>Consolidado cliente</div></div>	
				<?php } ?>
			</div>
		</div>
		<?php } ?>	
		<?php
			if(isset($sUserAcces) && $sUserAcces[2] == 1){
		?>
		<!--Coordinación -->
		<div>
			<div class="dMenOpt" onclick="MenuAccess('Coordinación')"><img src="images/coordinacion.png" class="imMenu" /><div class="fgreen"><b>Coordinación</b></div></div>
			<div id="Coordinación" style="margin-left:7px; height:auto; overflow:hidden">
				<div class="dMenOpt" onclick="clicmen('frOpsTasas_1', '')"><img src="images/window.png" class="imMenu" /><div>Precios compra y venta</div></div>
				<div class="dMenOpt" onclick="clicmen('frOpsAnula', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=VENTANILLA&var5=SI')"><img src="images/window.png" class="imMenu" /><div>Anular ops. ventanilla</div></div>
				<div class="dMenOpt" onclick="clicmen('frCoorAlerta', 'var1=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Alertas cajeros</div></div>
				<div class="dMenOpt" onclick="clicmen('frRepConsOps', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=COORDINA')"><img src="images/reports.png" class="imMenu" /><div>Consolidado ventanilla</div></div>
				<div class="dMenOpt" onclick="clicmen('frRepTotComVen', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=COORDINA')"><img src="images/reports.png" class="imMenu" /><div>Total compra y venta</div></div>
				<div class="dMenOpt" onclick="clicmen('frRepOpsClose', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=COORDINA')"><img src="images/reports.png" class="imMenu" /><div>Validar cierres</div></div>
				<div class="dMenOpt" onclick="clicmen('frRepKardex', '')"><img src="images/reports.png" class="imMenu" /><div>Kardex de divisas</div></div>
				<div class="dMenOpt" onclick="clicmen('frRepValTras', '')"><img src="images/reports.png" class="imMenu" /><div>Validar traslados</div></div>
			</div>
		</div>
		<?php } ?>	
		<?php
			if(isset($sUserAcces) && $sUserAcces[4] == 1){
		?>
		<!-- Contabilidad -->
		<div>
			<div class="dMenOpt" onclick="MenuAccess('Contabilidad1')"><img src="images/account.png" class="imMenu" /><div class="fgreen"><b>Contabilidad</b></div></div>
			<div id="Contabilidad1" style="margin-left:7px; height:auto; overflow:hidden">
				<div class="dMenOpt" onclick="MenuAccess('Reportes de ventanilla')"><img src="images/file.png" class="imMenu" /><div>Reportes de ventanilla</div></div>
					<div id="Reportes de ventanilla" style="margin-left:7px; height:0px; overflow:hidden">
						<div class="dMenOpt" onclick="clicmen('frRepConsOps', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=COORDINA')"><img src="images/reports.png" class="imMenu" /><div>Consolidado ventanilla</div></div>
						<div class="dMenOpt" onclick="clicmen('frFindOps', 'var1=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var2=<?=$caja?>&var3=<?=$sUserName?>&var4=VENTANILLA')"><img src="images/look.png" class="imMenu" /><div>Buscar operación</div></div>
						<div class="dMenOpt" onclick="clicmen('frRepKardex', '')"><img src="images/reports.png" class="imMenu" /><div>Kardex de divisas</div></div>
						<div class="dMenOpt" onclick="clicmen('frRepValTras', '')"><img src="images/reports.png" class="imMenu" /><div>Validar traslados</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepConsClie')"><img src="images/reports.png" class="imMenu" /><div>Consolidado cliente</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepMovVenta')"><img src="images/reports.png" class="imMenu" /><div>Operaciones de ventanilla</div></div>
					</div>
			</div>
		</div>
		<?php } ?>
		<?php
			if(isset($sUserAcces) && $sUserAcces[5] == 1){
		?>
		<!-- Cumplimiento -->
		<div>
			<div class="dMenOpt" onclick="MenuAccess('Cumplimiento')"><img src="images/cumplimiento.png" class="imMenu" /><div class="fgreen"><b>Cumplimiento</b></div></div>
			<div id="Cumplimiento" style="margin-left:7px; height:auto; overflow:hidden">
				<div class="dMenOpt" onclick="MenuAccess('Configuración SIPLA')"><img src="images/file.png" class="imMenu" /><div>Configuración SIPLA</div></div>
					<div id="Configuración SIPLA" style="margin-left:7px; height:0px; overflow:hidden">
						<div class="dMenOpt" onclick="clicmen('frCumpCalifica', '')"><img src="images/window.png" class="imMenu" /><div>Calificaciones alertas</div></div>
						<div class="dMenOpt" onclick="clicmen('frCumpParam', '')"><img src="images/window.png" class="imMenu" /><div>Parámetros segmentos</div></div>
					</div>
				<div class="dMenOpt" onclick="MenuAccess('Reportes')"><img src="images/file.png" class="imMenu" /><div>Reportes</div></div>
					<div id="Reportes" style="margin-left:7px; height:0px; overflow:hidden">
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepConsClie')"><img src="images/reports.png" class="imMenu" /><div>Consolidado cliente</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepAlertas')"><img src="images/reports.png" class="imMenu" /><div>Señales de alerta</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepComents')"><img src="images/reports.png" class="imMenu" /><div>Comentarios operaciones</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepSegmentos')"><img src="images/reports.png" class="imMenu" /><div>Segmentación mercado</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepClinton')"><img src="images/reports.png" class="imMenu" /><div>Reporte lista Clinton</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepSignifica')"><img src="images/reports.png" class="imMenu" /><div>Operaciones significativas</div></div>
						<div class="dMenOpt" onclick="clicmen('frConsView', 'var1=frRepSatisfaccion')"><img src="images/reports.png" class="imMenu" /><div>Satisfacción Clientes</div></div>
					</div>
				<div class="dMenOpt" onclick="MenuAccess('Actualizaciones y registros')"><img src="images/file.png" class="imMenu" /><div>Actualizaciones y registros</div></div>
					<div id="Actualizaciones y registros" style="margin-left:7px; height:0px; overflow:hidden">
						<div class="dMenOpt" onclick="clicmen('frCumpEstClie', '')"><img src="images/block.png" class="imMenu" /><div>Estado de cliente</div></div>
						<div class="dMenOpt" onclick="clicmen('frCumpModOps', '')"><img src="images/window.png" class="imMenu" /><div>Modificar operación</div></div>
						<div class="dMenOpt" onclick="clicmen('frOpsClientes', 'var1=Dec&var2=<?=(isset($sUserAcces) && is_array($sUserAcces)) ? $sUserAcces[1] : NULL?>&var3=<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>&var4=<?=$caja?>&var5=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Modificar clientes</div></div>
						<div class="dMenOpt" onclick="clicmen('frCumpClinton', 'var1=<?=$sUserName?>')"><img src="images/window.png" class="imMenu" /><div>Actualizar lista Clinton</div></div>
					</div>
				<div class="dMenOpt" onclick="MenuAccess('Reportes de ley')"><img src="images/file.png" class="imMenu" /><div>Reportes de ley</div></div>
					<div id="Reportes de ley" style="margin-left:7px; height:0px; overflow:hidden">
						<div class="dMenOpt" onclick="clicmen('frRepDIAN', '')"><img src="images/repley.png" class="imMenu" /><div>Reporte DIAN</div></div>
						<div class="dMenOpt" onclick="clicmen('frRepUIAF', '')"><img src="images/repley.png" class="imMenu" /><div>Reporte UIAF</div></div>
					</div>
			</div>
		</div>
		<?php } ?>
	</div> 
</div>
<div id="drealtime" style="position:fixed; right:0px; top:60px; width:0px; z-index:4; height:auto" class="dsition_3">
	<div style="position:absolute; left:-37px; top:-4px;">
		<div class="dmMain" style="margin:3px"><img src="images/realtime.png" style="height:26px; width:auto" onclick="DRealTime()" /></div>	
	</div>
	<div style="height:97%; width:250px" class="bgcol_2 dlin_1 drod_1">
		<div style="overflow:hidden;">
			<div class="bgcol_2 fgreen dlin_2 drod_4" style="padding:7px; font-size:15px;">Saldos ventanillas</div>
			<div id="dSaldos" class="dlin_2 bgcol_1" style="height:245px; overflow:auto"></div>
		</div>
		<div style="overflow:hidden">
			<div class="bgcol_2 fgreen dlin_2" style="padding:7px; font-size:15px;">Tasas compra y venta</div>
			<div id="dTasasCV" class="bgcol_1 drod_5" style="height:245px; overflow:auto"></div>
		</div>
	</div>
</div>
<div id="dquick" style="position:fixed; left:210px; top:100%; height:250px; width:100%; z-index:5;" class="dsition_2">
	<div style="position:absolute; left:10px; top:-35px;">
		<div class="dmMain" style="margin:3px"><img src="images/quick.png" style="height:26px; width:auto" onclick="DQuickInfo()" /></div>	
	</div>
	<div style="height:250px; width:100%" class="bgcol_2 dlin_8">
		<div style="width:99%; margin-top:4px; padding-bottom:5px; padding-top:5px; overflow:hidden;" class="bgcol_1 dlin_3 drod_1">
			<div style="margin-bottom:10px">
				<div style="float:left; margin-left:9px">
					<div class="fcont" style="margin-bottom:3px">Último Cierre</div>
					<div>
						<input name="txMCCierre" id="txMCCierre" maxlength="50" class="txboxdis" style="width:78px; font-weight:bold; height:18px; text-align:center; padding-left:0px" value="0000-00-00" disabled="disabled" />
					</div>
				</div>
				<div style="float:left; margin-left:9px">
					<div class="fcont" style="margin-bottom:3px">Moneda</div>
					<div>
						<select name="cbMCCurr" id="cbMCCurr" style="width:58px; font-weight:bold" class="txboxo" onchange="cbMCCurr_Change('<?=(isset($sSucSet) && is_array($sSucSet)) ? $sSucSet[0] : NULL?>', '<?=$caja?>')">
							<option value=""></option>
							<?=$cbCurLoad=LoadConfTab($link, "SELECT Moneda FROM Arqueo_Ventanilla WHERE Sucursal = '". (isset($sSucSet) && is_array($sSucSet) ? $sSucSet[0] : NULL). "' AND Estacion = '". $caja. "'")?>
						</select>				
					</div>
				</div>
				<div style="float:left; margin-left:9px; width:84%; overflow:hidden">
					<div class="dlin_1 bgcol_2 drod_1" style="width:99%; height:43px">
						<div style="font-weight:bold; margin-top:4px" class="fgreen">
							<div style="float:left; text-align:center; width:80px; margin-right:7px">Saldo Inicial</div>
							<div style="float:left; text-align:center; width:80px; margin-right:7px">Compras</div>
							<div style="float:left; text-align:center; width:80px; margin-right:7px">Entradas</div>
							<div style="float:left; text-align:center; width:80px; margin-right:7px">Ventas</div>
							<div style="float:left; text-align:center; width:80px; margin-right:7px">Salidas</div>
							<div style="float:left; text-align:center; width:80px; margin-right:7px">Saldo Final</div>
						</div>
						<div style="clear:both"></div>
						<div style="font-weight:bold; margin-top:3px" class="fgreen">
							<div id="dC1" title="Saldo_Inicial" style="float:left; text-align:center; width:80px; margin-right:7px">0</div>
							<div id="dC2" title="Compras" style="float:left; text-align:center; width:80px; margin-right:7px">0</div>
							<div id="dC3" title="Entradas" style="float:left; text-align:center; width:80px; margin-right:7px">0</div>
							<div id="dC4" title="Ventas" style="float:left; text-align:center; width:80px; margin-right:7px">0</div>
							<div id="dC5" title="Salidas" style="float:left; text-align:center; width:80px; margin-right:7px">0</div>
							<div id="dC6" title="Saldo_Final" style="float:left; text-align:center; width:80px; margin-right:7px">0</div>
						</div>
					</div>					
				</div>
			</div>
			<div style="clear:both"></div>
			<div style="margin-left:9px; margin-top:4px; margin-right:9px">
				<div class="fgreen dlin_3 drod_4" style="padding:4px; border-bottom:none"><b>Detalle de Operaciones</b></div>
				<div id="dMCList" class="dlin_1" style="height:147px; overflow:auto">
					<table cellpadding="0" cellspacing="0" id="lstMCList" width="530px">
						<tr class="bgcol_6 fwhite">
							<td style="width:90px; text-align:left" class="celrow">Tipo</td>
							<td style="width:70px; text-align:left" class="celrow">Consecutivo</td>
							<td style="width:100px; text-align:left" class="celrow">Detalle</td>
							<td style="width:50px; text-align:left" class="celrow">Moneda</td>
							<td style="width:70px; text-align:left" class="celrow">Precio</td>
							<td style="width:70px; text-align:left" class="celrow">Cantidad</td>
							<td style="width:80px; text-align:left" class="celrow">Medio pago</td>
						</tr>
					</table>
				</div>			
			</div>
		</div>
	</div>
</div>
<div align="center" style="width:100%; height:100%; margin-top:0px; overflow:visible; position:fixed; top:41px; left:211px; z-index:2" id="dfrMain">
	  <iframe name="frMain" id="frMain" align="left" frameborder="0" style="width:100%; height:100%; overflow:visible"></iframe>
</div>
<!-- Modal alerta -->
<div id="dTasaAlM" style="position:fixed; top:0px; left:0px; width:100%; height:100%; z-index:1000; visibility:hidden" class="bgcol_8 dsition_4 dsition_5">
	<div id="dTasaAlC" style="width:40%; margin-left:auto; margin-right:auto; margin-top:-700px; padding:15px 0" class="drod_1 dsom_3 dsition_3 bgcol_5 dlin_10">
		<div style="width:90%; margin:auto; font-size:14px" class="fwhite">
			<div><b>¡ALERTA DE CAMBIO DE PRECIOS!</b></div>
			<div style="margin-top:10px;">Las siguientes monedas cambiaron de precios:</div>
			<div style="margin-top:15px">
				<table style="width:70%" border="0" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td style="width:30%; padding:5px 0" class="dlin_9_1">MONEDA</td>
							<td style="width:45%; padding:5px 0" class="dlin_9_1">COMPRA</td>
							<td style="width:45%; padding:5px 0" class="dlin_9_1">VENTA</td>
						</tr>
					</thead>
					<tbody id="tbTasasCH">
					
					</tbody>
				</table>
			</div>
			<div style="margin-top:30px">
				<input name="btaccept_ts" id="btaccept_ts" type="button" value="Aceptar" class="btcont dsition_3" style="width:88px;" onclick="HideAlTasa()" />
			</div>
		</div>
	</div>
</div>
<!-- script nuevo -->
<script>
	var beamer_config = {
		product_id : 'tFjcgVKo55650' //DO NOT CHANGE: This is your product code on Beamer
	};
</script>
<script type="text/javascript" src="https://app.getbeamer.com/js/beamer-embed.js" defer="defer"></script>
<!-- hasta aca -->
</body>
</html>
