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
	//--------------------------------------------
	$stSuc=$_GET['stSuc'];
	$sSucN=$_GET['sSucN'];
	$sType=$_GET['sType'];
	$sIni=$_GET['sIni'];
	$sFin=$_GET['sFin'];
	$stCur=$_GET['sCur'];
	$sCurN=$_GET['sCurN'];
	$sExp=$_GET['sExp'];
	//--------------------------------------
	$link=Conectarse();
	//--------------------------------------
	//Genera encabezado
	$cabex = '';
	if($sExp != ''){
		$cabex = '<span class="fcont" style="font-size:18px">Validación de traslados<br />
				<span class="fcont" style="font-size:16px">Sucursal: '. $sSucN .'<br />
				<span class="fcont" style="font-size:16px">Moneda: '. $sCurN .'<br />
				<span class="fcont" style="font-size:16px">Fecha inicial: '. $sIni .'<br />
				<span class="fcont" style="font-size:16px">Fecha final: '. $sFin .'<br />
				<span class="fcont" style="font-size:16px">Tipo de traslado: '. $sType .'<p></p>';
	}
	//----------------------------------------
	//Encabesado de tabla
	$funval = '<tr class="bgcol_6 fwhite">							
					<td class="celrow" style="width:80px; text-align:left; max-width:80px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:80px">Identificación</td>							
					<td class="celrow" style="width:60px; text-align:left; max-width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:60px">Código movimiento</td>							
					<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Movimiento</td>							
					<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Sucursal</td>							
					<td class="celrow" style="width:60px; text-align:left; max-width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:60px">Estación</td>							
					<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Cajero</td>							
					<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Fecha</td>							
					<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Consecutivo</td>							
					<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Estado</td>							
					<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Origen / Destino</td>							
					<td class="celrow" style="width:100px; text-align:left; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:100px">Doc. Par</td>							
					<td class="celrow" style="width:60px; text-align:left; max-width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:60px">Moneda</td>							
					<td class="celrow" style="width:80px; text-align:left; max-width:80px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:80px">Precio</td>							
					<td class="celrow" style="width:100px; text-align:left; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:100px">Cantidad</td>							
					<td class="celrow" style="width:100px; text-align:left; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:100px">Valor</td>							
					<td class="celrow" style="width:80px; text-align:left; max-width:80px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:80px">Medio pago</td>							
					<td class="celrow" style="width:50px; text-align:left; max-width:50px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:50px">Banco</td>							
					<td class="celrow" style="width:50px; text-align:left; max-width:50px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:50px">Cuenta</td>							
					<td class="celrow" style="width:150px; text-align:left; max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:150px">Observaciones</td>							
					<td class="celrow" style="width:20px; text-align:left; max-width:20px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:20px"></td>							
					<td class="celrow" style="width:80px; text-align:left; max-width:80px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:80px">Identificación</td>							
					<td class="celrow" style="width:60px; text-align:left; max-width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:60px">Código movimiento</td>							
					<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Movimiento</td>							
					<td class="celrow" style="width:55px; text-align:left; max-width:55px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:55px">Sucursal</td>							
					<td class="celrow" style="width:60px; text-align:left; max-width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:60px">Estación</td>							
					<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Cajero</td>							
					<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Fecha</td>							
					<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Consecutivo</td>							
					<td class="celrow" style="width:70px; text-align:left; max-width:70px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:70px">Estado</td>							
					<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">Origen / Destino</td>							
					<td class="celrow" style="width:100px; text-align:left; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:100px">Doc. Par</td>							
					<td class="celrow" style="width:60px; text-align:left; max-width:60px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:60px">Moneda</td>							
					<td class="celrow" style="width:80px; text-align:left; max-width:80px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:80px">Precio</td>							
					<td class="celrow" style="width:100px; text-align:left; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:100px">Cantidad</td>							
					<td class="celrow" style="width:100px; text-align:left; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:100px">Valor</td>							
					<td class="celrow" style="width:80px; text-align:left; max-width:80px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:80px">Medio pago</td>							
					<td class="celrow" style="width:50px; text-align:left; max-width:50px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:50px">Banco</td>							
					<td class="celrow" style="width:50px; text-align:left; max-width:50px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:50px">Cuenta</td>							
					<td class="celrow" style="width:150px; text-align:left; max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:150px">Observaciones</td>							
				</tr>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Validación de traslados</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
</head>
<body class="bodygen" style="margin:0px 0px 0px 0px">
	<?=$cabex?>
	<table id="lstTot_Tab" cellpadding="0" cellspacing="0">
		<?=$funval?>
		<?php
			$strSQL = "SELECT * FROM Traslados_Ventanilla WHERE Tipo_Movimiento='".$sType."'".$stSuc." AND Fecha>='".$sIni."' AND Fecha<='".$sFin."' AND Estado='ACTIVO'".$stCur." ORDER BY Identificacion asc";
			$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
			$i = mysqli_num_fields($p);
			$k = 0;
			while($q=mysqli_fetch_array($p)){
				if($k==0){
					$sclas = "trwhite";
					$scfont = "fcont";					
					$k=1;
				} else {
					$sclas = "trgray";
					$scfont = "fcont";					
					$k=0;
				}
				//----------------------------------------------
				//Validar traslados entre sucursales y entre cajas
				$sDoVal = 'NO';
				$strSQ1 = '';
				if($q['Origen_Destino'] != 'TESORERIA' && $q['Origen_Destino'] != 'COORDINACION' && $q['Origen_Destino'] != 'OTROS'){
					$sDoVal = 'SI';
					if($sType == 'INGRESO'){
						$sCorType = 'EGRESO';
						if($q['Documento_Par'] == ''){
							$sDoVal = 'NO';
							$sclas = "bgcol_5";
							$scfont = "fwhite";					
						} else {
							//Define si es traslado para caja o para sucursal
							if(strrpos($q['Origen_Destino'], 'CAJA') === false){
								$strSQ1 = "SELECT * FROM Traslados_Ventanilla WHERE Tipo_Movimiento='EGRESO' AND Sucursal='".$q['Origen_Destino']."' AND Consecutivo='".$q['Documento_Par']."' AND Estado='Activo'"; //Traslado sucursal
							} else {
								$strSQ1 = "SELECT * FROM Traslados_Ventanilla WHERE Tipo_Movimiento='EGRESO' AND Sucursal='".$q['Sucursal']."' AND Estacion='".str_replace('CAJA ', '', $q['Origen_Destino'])."' AND Consecutivo='".$q['Documento_Par']."' AND Estado='Activo'"; //Traslado Cajas
							}
						}
					} else {
						$sCorType = 'INGRESO';
						//Define si es traslado para caja o para sucursal
						if(strrpos($q['Origen_Destino'], 'CAJA') === false){
							$strSQ1 = "SELECT * FROM Traslados_Ventanilla WHERE Tipo_Movimiento='INGRESO' AND Sucursal='".$q['Origen_Destino']."' AND Documento_Par='".$q['Consecutivo']."' AND Estado='Activo'"; //Traslado sucursal
						} else {
							$strSQ1 = "SELECT * FROM Traslados_Ventanilla WHERE Tipo_Movimiento='INGRESO' AND Sucursal='".$q['Sucursal']."' AND Estacion='".str_replace('CAJA ', '', $q['Origen_Destino'])."' AND Documento_Par='".$q['Consecutivo']."' AND Estado='Activo'";		//Traslado caja
						}
					}
					//--------------------------
					if($sDoVal == 'SI'){
						$i1 = 0;
						$p1 = mysqli_query($link, $strSQ1) or die(mysqli_error($link));
						$i1 = mysqli_num_rows($p1);
						$q1=mysqli_fetch_array($p1);						
						if($i1 != 1){ //No encontró registros
							$sDoVal = 'NO';
							$sclas = "bgcol_5";
							$scfont = "fwhite";					
						} else {
							//Valida valores de traslado correspondiente ==> Moneda y cantidad
							if($q['Moneda'] != $q1['Moneda'] || $q['Cantidad'] != $q1['Cantidad']){
								$sclas = "bgcol_5";
								$scfont = "fwhite";					
							}
						}
					}				
				}					
		?>
		<tr valign="middle" class="<?=$scfont?> <?=$sclas?>">
			<?php
				for ($j = 0; $j <= $i - 1; $j++) {
					if(mysqli_fetch_field_direct($p, $j)->type == 'real'){
			?>
			<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px"><?=number_format($q[$j], 2, $GLdecsepa, $GLmilsepa)?></td>
				<? } else { ?>
			<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px" title="<?=$q[$j]?>"> <?=$q[$j]?></td>
				<? } ?>
			<? } ?>
			<td class="bgcol_6"></td>
			<?php
				if($sDoVal == 'SI'){
			?>	
				<?php
					for ($j = 0; $j <= $i - 1; $j++) {
						if(mysqli_fetch_field_direct($p1, $j)->type == 'real'){
				?>
				<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px"><?=number_format($q1[$j], 2, $GLdecsepa, $GLmilsepa)?></td>
					<? } else { ?>
				<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px" title="<?=$q1[$j]?>"> <?=$q1[$j]?></td>
					<? } ?>
				<? } ?>
			<? } else { ?>
				<?php
					for ($j = 0; $j <= $i - 1; $j++) {	//Campos vacios si no es necesaria la validacion
				?>
				<td class="celrow"></td>
				<? } ?>		
			<? } ?>
		</tr>	
		<? } ?>
	</table>
</body>
</html>
