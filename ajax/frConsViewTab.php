<?php
//ARCHIVO FUNCIONES FRREPVALTRAS.PHP
//============================================================
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	include("../General.php");
	//---------------------------------------------------
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	//-----------------------------------------------
	//Captura variables
	$sRepName = $_GET['RepName'];	//Nombre de reporte
	$sFieldsC = $_GET['FieldsC'];	//Campos que va a traer
	$sTableC = $_GET['TableC'];		//Nombre de tabla
	$sWhereC = $_GET['WhereC'];		//String de consulta
	$sOrderfC = $_GET['OrderfC'];	//Variable de ordenación
	$sOrderdC = $_GET['OrderdC'];	//Dirección de ordenación
	$sRelC = $_GET['RelC'];		//Campos relacionados
	$sExp=$_GET['sExp'];	//Exportar
	//Variables de reporte acumulado
	$sRType = $_GET['RType'];
	$sRVal = $_GET['RVal']; 
	//--------------------------------------
	$link=Conectarse();
	//--------------------------------------
	//Configuracion general
	$getGenSet = GenSet($link);
	//--------------------------------------
	//Genera encabezado
	$cabex = '';
	if($sExp != ''){
		$cabex = '<div style="padding:10px">
				<span class="fcont" style="font-size:18px">SOIPC - '.$sGenSet[0].'</span><br />
				<span class="fcont" style="font-size:18px">'.$sRepName.'</span><br />
				<span class="fcont" style="font-size:14px">Criterio Consulta: '. $sWhereC .'</span><p></p>';
	}
	//-----------------------------------
	//Consutrye string de Consulta Campos, Tabla y Where
	if($sFieldsC == ''){
		$sFieldsC = '*';
	} else {
		$sFieldsC = substr($sFieldsC, 0, strlen($sFieldsC) - 1);
	}
	//----------------------------------------------
	//Valida el string de order si es reporte de operaciones significativas por acumulado
	if($sOrderfC != ''){
		$sOrderG = ' ORDER BY '.$sOrderfC.' '.$sOrderdC;
	} else {
		$sOrderG = '';
	} 
	$strSQL = "SELECT ".$sFieldsC." FROM ".$sTableC." WHERE ".$sWhereC.$sOrderG;
	$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$ic = mysqli_num_fields($p);
	$ir=mysqli_num_rows($p);
	//------------------------------------
	//Consulta de totales
	$sTotSum = '';
	$sTotAvg = '';
	for ($j = 0; $j <= $ic - 1; $j++) {
		if(mysqli_fetch_field_direct($p, $j)->type == 'real' || mysqli_fetch_field_direct($p, $j)->type == 'int'){
			$sTotSum = $sTotSum."SUM(".mysqli_fetch_field_direct($p, $j)->name."),";
			$sTotAvg = $sTotAvg."AVG(".mysqli_fetch_field_direct($p, $j)->name."),";
		}
	}
	$ic1 = 0;
	if($sTotSum != ''){
		$strSQ1 = "SELECT ".substr($sTotSum, 0, strlen($sTotSum) - 1)." FROM ".$sTableC." WHERE ".$sWhereC;
		$strSQ2 = "SELECT ".substr($sTotAvg, 0, strlen($sTotAvg) - 1)." FROM ".$sTableC." WHERE ".$sWhereC;
		$p1 = mysqli_query($link, $strSQ1) or die(mysqli_error($link));
		$ic1 = mysqli_num_fields($p1);
		$q1=mysqli_fetch_array($p1);
		$p2=mysqli_query($link, $strSQ2) or die(mysqli_error($link));
		$q2=mysqli_fetch_array($p2);
	}
	//---------------------------------
	//Vector de relación en caso que exista	
	if($sRelC != ''){
		//Abre vector
		$srelv = explode("|", $sRelC);
		$strSQ3 = "SELECT * FROM ".$srelv[0]." LIMIT 0, 1";	//Consulta solo para columnas de tabla
		$p3 = mysqli_query($link, $strSQ3) or die(mysqli_error($link));
		$ic3 = mysqli_num_fields($p3);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Visor de Consultas</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
<script src="../scripts/frConsView.js" type="text/javascript"></script>
</head>
<body class="bodygen" style="margin:0px 0px 0px 0px">
	<?=$cabex?>
	<!-- Totales de Consulta -->
	<div style="padding:7px; margin-top:0px; box-sizing:border-box" class="fgreen bgcol_4 dlin_3"><strong>Totales de Consulta</strong></div>
	<div style="overflow:auto">	
		<table id="lstTot_Tab" cellpadding="0" cellspacing="0">
			<tr class="bgcol_6 fwhite">
				<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px">Tipo</td>			
				<?php
				for ($j = 0; $j <= $ic1 - 1; $j++) {
				?>					
				<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px"><?php echo str_replace(")","",str_replace("SUM(","",mysqli_fetch_field_direct($p1, $j)->name)); ?></td>			
				<? } ?>
			</tr>
			<tr class="trwhite">
				<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px">SUMA</td>			
				<?php
				for ($j = 0; $j <= $ic1 - 1; $j++) {
				?>					
				<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px"><?=number_format($q1[$j], 2, $GLdecsepa, $GLmilsepa)?></td>			
				<? } ?>
			</tr>	
			<tr class="trgray">
				<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px">PROM</td>			
				<?php
				for ($j = 0; $j <= $ic1 - 1; $j++) {
				?>					
				<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px"><?=number_format($q2[$j], 2, $GLdecsepa, $GLmilsepa)?></td>			
				<? } ?>
			</tr>	
		</table>
	</div>
	<!-- Detalle de Consulta -->
	<div style="padding:7px; margin-top:15px; box-sizing:border-box" class="fgreen bgcol_4 dlin_3"><strong>Registros Encontrados</strong></div>
	<div id="dRegi_Table" style="overflow:auto">
		<table id="lstRegs_Tab" cellpadding="0" cellspacing="0">
			<thead>
				<tr id="thHead" class="bgcol_6 fwhite">
					<?php
					for ($j = 0; $j <= $ic - 1; $j++) {
					?>					
					<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px"><?php echo mysqli_fetch_field_direct($p, $j)->name; ?></td>			
					<? } ?>
					<?php
					if($sRType == 'ACUMULADO'){
					?>
					<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px">Acumulado cliente</td>			
					<? } ?>
					<?php
					if($sRelC != ''){
					?>
						<?php
						for ($j = 0; $j <= $ic3 - 1; $j++) {
						?>					
						<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:85px; width:85px"><?php echo mysqli_fetch_field_direct($p3, $j)->name; ?></td>			
						<? } ?>
					<? } ?>				
				</tr>
			</thead>
			<tbody id="thRegs">
				<?php
				$m = 0;
				while($q=mysqli_fetch_array($p)){
					//Valida si es reporte acumulado
					if($sRType == 'ACUMULADO'){
						$strSQV1 = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE ".$sWhereC." AND Documento_Beneficiario='".$q['Documento_Beneficiario']."'";
						$pv1 = mysqli_query($link, $strSQV1) or die(mysqli_error($link));
						$qv1=mysqli_fetch_array($pv1);
						if($qv1[0] < $sRVal){
							continue;
						}
					}
					//------------------------------------------------------					
					$m++;
					if($k==0){
						$sclas = "trwhite";
						$scfont = "fcont";					
						$k=1;
					} else {
						$sclas = "trgray";
						$scfont = "fcont";					
						$k=0;
					}
				?>	
				<tr id="tr-<?=$m?>" onclick="Sel_RowC(this)"  valign="middle" class="<?=$scfont?> <?=$sclas?>">
					<?php
						for ($j = 0; $j <= $ic - 1; $j++) {
							if(mysqli_fetch_field_direct($p, $j)->type == 'real'){
					?>
					<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:80px"><?=number_format($q[$j], 2, $GLdecsepa, $GLmilsepa)?></td>
						<? } else { ?>
					<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:80px" title="<?=$q[$j]?>"> <?=$q[$j]?></td>
						<? } ?>
					<? } ?>
					<?php
					if($sRType == 'ACUMULADO'){
					?>
					<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:80px"><?=number_format($qv1[0], 2, $GLdecsepa, $GLmilsepa)?></td>
					<? } ?>
					<?php
					if($sRelC != ''){
						$strSQ4 = "SELECT * FROM ".$srelv[0]." WHERE ".$srelv[1]."='".$q[$srelv[2]]."'";
						$p4 = mysqli_query($link, $strSQ4) or die(mysqli_error($link));
						$q4=mysqli_fetch_array($p4);
					?>
						<?php
						for ($j = 0; $j <= $ic3 - 1; $j++) {
							if(mysqli_fetch_field_direct($p4, $j)->type == 'real'){
						?>					
						<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:80px"><?=number_format($q4[$j], 2, $GLdecsepa, $GLmilsepa)?></td>
						<? } else { ?>
						<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:80px" title="<?=$q4[$j]?>"><?=$q4[$j]?></td>
						<? } ?>
						<? } ?>
					<? } ?>
				</tr>
				<? } ?>
			</tbody>
		</table>
		<!-- Script para poner la cantidad de registros en contenedor -->
		<script>
			var txtotr = window.parent.document.getElementById('txRegTot')
			if(txtotr != null){
				txtotr.value = <?=$m?>;
			}
		</script>
		<!-- Sript para alto de contenedor -->
		<?php
		if($sExp == ''){	//Si es reporte sin exportar
		?>
		<script>
			TabSize_1();
		</script>
		<? } ?>
	</div>
</body>
</html>
