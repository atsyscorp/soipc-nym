<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("../General.php");
	//---------------------------------------------------
	//Captura id de operacion y origen de formato
	$var[1]=$_GET['var1'];
	$var[2]=$_GET['var2'];	//Cuando es por buscar operacion la variable es no vacia y carga seccion de conocimiebnto del cliente
	//Valida Acceso a archivo
	if($var[1] == ''){
		header("location:../index.php");
	}
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//---------------------------------------------------
	//Consulta información de la operación
    $strSQLS = "SELECT * FROM Operacion_Ventanilla WHERE Identificacion = '".  $var[1]. "'";
	$p=mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for($i = 1; $i <= 55; $i++){
			if($n[$i] == '')
			{
				$varp[$i] = '<span style="color:#FFFFFF">.</span>';
			} else {
				$varp[$i] = $n[$i];
			}
		}
	}

	//Consulta información beneficiario
    $strSQLB = "SELECT * FROM Clientes WHERE Identificacion = '".  $varp[13]. "'";
	$b=mysqli_query($link, $strSQLB) or die(mysqli_error($link));
	while($nb=mysqli_fetch_array($b)){
		for($i = 1; $i <= 29; $i++){
			$vben[$i] = $nb[$i];
		}
	}
	//Consulta info declarante si son diferentes
	if($varp[13] != $varp[30])
	{
		$strSQLD = "SELECT * FROM Clientes WHERE Identificacion = '".  $varp[30]. "'";
		$d = mysqli_query($link, $strSQLD) or die(mysqli_error($link));
		while($nd=mysqli_fetch_array($d)){
			for($i = 1; $i <= 29; $i++){
				$vdec[$i] = $nd[$i];
			}
		}
	} else {
		for($i = 1; $i <= 29; $i++){
			$vdec[$i] = $vben[$i];
		}
	}


	// Consulta del acumulado de los últimos 7 días
	// Consulta acumulados de cliente
	$dADia = 0;
	$dAMes = 0;
	$dAAno = 0;
	$dA7 = 0;

	// Fechas
	$today = date('Y-m-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$year = date('Y'); // Obtener el año actual
	$month = date('m'); // Obtener el mes actual
	// Fecha actual
	$hoyfec = date('Y-m-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$hoymes = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01'; // Agregar un cero al mes si es necesario
	$hoyano = $year . '-01-01'; // Obtener el primer día del año actual

	// Fecha últimos 7 días para la DDCI insertada juan camilo 08/03/2023
	$fechaInicio = date('Y-m-d', strtotime('-6 days')); // Restar 6 días a la fecha actual
	//$fechaInicio = $today.getFullYear() . "-" . antcero($today.getMonth() + 1) . "-" . antcero($today.getDate());
	$fechaFin = $hoyfec;

	$strSQL7 = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" . $varp[13] . "' AND Fecha >= '" . $fechaInicio . "' AND Fecha <= '" . $fechaFin . "' AND Codigo_Operacion = '141' AND Estado_Operacion = 'ACTIVO'";

	$acum = mysqli_query($link, $strSQL7) or die(mysqli_error($link));
	$nAcu = mysqli_fetch_array($acum);
	$acumulado = $nAcu[0]; // Obtener el valor del acumulado

	
	//Consulta información de sucursal
	$getSucSet = SucSet($link, $varp[3]);
	//Consulta configuración contable
	$getTaxSet = TaxSet($link, $varp[3]);
	//Consulta identificación de cajero
	$strSQTl = "SELECT Identificacion FROM Usuarios WHERE Nombre='".$varp[5]."'";
	$ptl = mysqli_query($link, $strSQTl) or die(mysqli_error($link));
	$qtl=mysqli_fetch_array($ptl);
	//----------------------------------------------------------
	//Ceros del consucutivo
	$cerocon = '';
	/*	if($varp[10] < 100)
	{
		$cerocon = '000';
	} else if ($varp[10] < 1000) {
		$cerocon = '00';
	} else if ($varp[10] < 10000) {
		$cerocon = '0';
	} */	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title>FACTURA</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
<style type="text/css">
@page{
    margin-left: 10px;
    margin-right: 0px;
    margin-top: 0px;
    margin-bottom: 0px;

@media print {
  .page-break { page-break-before: always; }
}


}
</style>
</head>


<body class="bodygen" style="font-size:9px" onload="">
	<div style="width:250px; margin-top:10px; margin-left:0px">
		<div style="text-align:center"><img src="../images/Logo_Factura.png" style="width:230px; height:auto"></div>
			<?php
				if($varp[48] != 'EFECTIVO' && $varp[33] != 'TUSD'){	//Persona juridica
			?>

			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:0px">
			<tr style="vertical-align:bottom">
				<td style="text-align:center; font-weight:bold"; colspan="2">NOTA DE ENTREGA, DEBIDA DILIGENCIA  POR COMPRA Y VENTA DE MANERA PROFESIONAL DE DIVISAS Y CHEQUES DE VIAJERO</td>
			</tr>
			<tr style="height:25px; vertical-align:top">
				<td style="text-align:center; border-bottom:#000000 solid 1px; border-top:#000000 solid 1px" colspan="2">Formulario DDC  Par 2 Art 17 numeral 3.1 Res 061 de 2017 </td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; width:135px; padding-top:10px">Ciudad</td>
				<td style="text-align:left; width:115px; font-weight:bold; padding-top:10px"><?=$sSucSet[5]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Fecha</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[6]?></td>
			</tr>
			
			
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold; margin-top:10px" colspan="2">1. IDENTIFICACIÓN DEL PROFESIONAL DEL CAMBIO</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:center;" colspan="2">
					<div style="text-align:center">Principal: AV CARRERA 15 124 30 LC 1 118 CC UNICENTRO</div>
					<div style="text-align:center">RÉGIMEN COMÚN</div>
					<div style="text-align:center; margin-top:10px">ACTIVIDAD ECONÓMICA ICA 6615 TARIFA 11.04X1000</div>
					<div style="text-align:center;">Autorización Numeración de Facturación Electrónica <?=$sSucSet[8]?> de <?=$sSucSet[10]?>. Vigencia 12 meses.</div>
					<div style="text-align:center;">Numeración Autorizada <?=$sSucSet[9]?> <?=$sSucSet[11]?> al <?=$sSucSet[12]?></div>
					<div style="text-align:center;"><?=$sSucSet[1]?></div>
					<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"><?=$sSucSet[3]?></div>
				</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:center;" colspan="2">
					<div style="text-align:center;"><?=$sSucSet[1]?></div>
					<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"><?=$sSucSet[3]?></div>
				</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACIÓN</td>
				<td style="text-align:left; font-weight:bold">NI</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NÚMERO</td>
				<td style="text-align:left; font-weight:bold">800.132.527-8</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE</td>
				<td style="text-align:left; font-weight:bold">CAMBIOS NEW YORK MONEY SAS.</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Oficina</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[3]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Cajero</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[5]?></td>
			</tr>
			
			<tr style="height:28px; vertical-align:middle; font-size:14px">
				<td style="text-align:left; border-bottom:#000000 solid 1px"><b>FACTURA DE VENTA</b></td>
				<td style="text-align:left; font-weight:bold; border-bottom:#000000 solid 1px"><?=$sSucSet[9]. "-". $cerocon. $varp[10]?></td>
			</tr>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">DEBIDA DILIGENCIA DEL BENEFICIARIO</td>
			</tr>
			
			
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE / RAZÓN SOCIAL</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[19]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACIÓN</td>
				<td style="text-align:left; font-weight:bold"><?php Gen_Find_Field_1("SELECT Impresion FROM XConf_TiposDoc WHERE Tipo_Documento='".$varp[14]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NÚMERO</td>
				<td style="text-align:center; font-size:15px; font-weight:bold"><?=$varp[13]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CIUDAD</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[24]?></td>
			</tr>
			<?php
				if($varp[14] == 'NIT'){	//Persona juridica
			?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">FECHA CONSTITUCIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">LUGAR CONSTITUCIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[9]?></td>
			</tr>
			<? } else { ?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">FECHA DE NACIMIENTO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">LUGAR DE NACIMIENTO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[9]?></td>
			</tr>
			<? } ?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CORREO ELECTRÓNICO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[17]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">ACTIVIDAD ECONÓMICA</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[25]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">PERSONA PEP</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[26]?></td>
			</tr>
			<?php
			$separar = explode("-",$vben[20]);	//separa la variable de origen de fondos por el -
			
			?>
			<tr style="height:15px; vertical-align:middle">
   				<td style="text-align:left;">ORIGEN DE FONDOS</td>
   				<!--<td style="text-align:left; font-weight:bold"><?=$vben[20]?></td>-->
   				<td style="text-align:left; font-weight:bold">
        			<?= !empty(trim($separar[0])) ? $separar[0] : "AHORROS"; ?>
  				 </td>
			</tr>

			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">DESTINO DE FONDOS</td>
				<!--<td style="text-align:left; font-weight:bold; border-bottom:#000000 solid 1px">Xp</td>-->
				<td style="text-align:left; font-weight:bold">
				<?= !empty(trim($separar[1])) ? $separar[1] :"AHORROS"; ?>
				</td>
			</tr>	
			<?php
				if($varp[14] == 'NIT'){	//Persona juridica
			?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE, IDENTIFICACIÓN, %PARTICIPACIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[21]?></td>
			</tr>
			<? } ?>
			
			
			<?php
			if($varp[13] != $varp[30]){	//Si declarante y beneficiario son diferentes imprime esta parte de la declaración
			?>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">DEBIDA DILIGENCIA DEL DECLARANTE</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold" colspan="2">(Datos de la persona natural que suscribe la declaración, en nombre propio o representación del cliente)</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[8]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACIÓN</td>
				<td style="text-align:left; font-weight:bold"><?php Gen_Find_Field_1("SELECT Impresion FROM XConf_TiposDoc WHERE Tipo_Documento='".$vdec[3]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NÚMERO</td>
				<td style="text-align:center; font-size:15px; font-weight:bold"><?=$varp[30]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CIUDAD</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[15]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">DIRECCIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[13]?></td>
			</tr>
			<?
				//Validacion telefono			
				if($vdec[11] == '')
				{
					$teldec = $vdec[12];				
				} else {
					$teldec = $vdec[11];				
				}			
			?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TELÉFONO</td>
				<td style="text-align:left; font-weight:bold"><?=$teldec?></td>
			</tr>
			<? } ?>
			
			
			
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">DESCRIPCIÓN DE LA OPERACIÓN</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CONCEPTO</td>
				<td style="text-align:left; font-weight:bold">VENTA DE DIVISAS</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">MONEDA</td>
				<td style="text-align:center; font-weight:bold; font-size:15px"><?php Gen_Find_Field_1("SELECT Nombre FROM XConf_Monedas WHERE Moneda='".$varp[33]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">MONTO</td>
				<td style="text-align:center; font-weight:bold; font-size:15px"><?=number_format($varp[36], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TASA DE CAMBIO</td>
				<td style="text-align:center; font-weight:bold; font-size:15px">$<?=number_format($varp[35], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold; padding-top:5px; text-align:center" colspan="2">Valor en Pesos de la Operación</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">EFECTIVO</td>
				<td style="text-align:center; font-weight:bold; font-size:13px"><?php if($varp[48] == 'EFECTIVO' && $varp[33] != 'TUSD'){ echo 'COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa);} else { echo 'COP$ 0.00';} ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CHEQUE DE VIAJERO</td>
				<td style="text-align:center; font-weight:bold; font-size:13px"><?php if($varp[33] == 'TUSD'){ echo 'COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa);} else { echo 'COP$ 0.00';} ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">PAGO DIFERENTE A EFECTIVO</td>
				<td style="text-align:center; font-weight:bold; font-size:13px"><?php if($varp[48] != 'EFECTIVO' && $varp[33] != 'TUSD'){ echo 'COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa);} else { echo 'COP$ 0.00';} ?></td>
			</tr>
			
			
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold; font-size:15px">TOTAL</td>
				<td style="text-align:center; font-weight:bold; font-size:15px; border-top:#000000 solid 1px"><?='COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
		</table>
		<div style="text-align:center; margin-top:5px; font-size:7px">Para los fines previstos en el artículo 83 de la Constitución Política de Colombia, declaro bajo la gravedad de juramento, que los conceptos, cantidades y demás datos consignados en el presente formulario, son correctos y la fiel expresión de la verdad .</div>
		<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"></div>
		<div style="text-align:center; margin-top:2px; font-size:7px">Esta es una nota de entrega previa a la generación y radicación de la factura electrónica con validación de la DIAN, esta sera enviada al correo suministrado, en caso de requerir la impresion de la representacion grafica, solicitelo en ventanilla.
		<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"></div>Al dorso del presente documento, como en nuestra pagina web http://www.newyorkmoney.com.co , encontrara lo relativo a nuestra POLÍTICAS Y PROCEDIMIENTOS PARA EL TRATAMIENTO DE DATOS PERSONALES (Ley 1581 2012 y normas complementarias), por tanto y con la suscripción del presente documento ud. nos autoriza para lo allí establecido. Así mismo AUTORIZO a CAMBIOS NEW YORK MONEY SAS para que a través de mi dirección electrónica registrada, me envíe información relacionada con los productos, encuestas, servicios y/o calificaciones del servicio prestado, en caso de no aceptar marque __No acepto</div>
		<div style="margin-top:10px">
			<div style="float:left; width:170px">
				<div style="height:90px; border-bottom:#000000 solid 1px"></div>			
				<div style="margin-top:4px; text-align:left">FIRMA</div>
				<div style="text-align:left">SIGNATURE</div>
				<div style="text-align:center; margin-top:2px; font-size:10px"> La factura fue enviada a su correo, si no le llego escribanos a contabilidad@newyorkmoney.com.co  </div>
										
							</div>
		

			<div style="clear:both"></div>	
			
		
		<?php
			if($varp[36] >= 1){	// para imprimir DDCI
			?>
			<tr style="height:15px; vertical-align:middle">
    				<td style="text-align:left;"><?= $acumArray[1] ?></td>
    				<td style="text-align:left; font-weight:bold"><?= $acumArray[1] ?></td>
			</tr>
			<? } ?>	
		</div>
	
			<? }else { ?>
			
		
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px">
			<tr style="vertical-align:bottom">
				<td style="text-align:center; font-weight:bold" colspan="2">NOTA DE ENTREGA, DEBIDA DILIGENCIA Y DECLARACIÓN DE CAMBIO POR COMPRA Y VENTA DE MANERA PROFESIONAL DE DIVISAS Y CHEQUES DE VIAJERO</td>
			</tr>
			<tr style="height:25px; vertical-align:top">
				<td style="text-align:center; border-bottom:#000000 solid 1px; border-top:#000000 solid 1px" colspan="2">Formulario No. 18 Circular Externa DCIN-83 de 03 Octubre 2017, Formato fusionado Par. 1 Art. 17 numeral 3.1 Res 061 de 2017</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; width:135px; padding-top:10px">Ciudad</td>
				<td style="text-align:left; width:115px; font-weight:bold; padding-top:10px"><?=$sSucSet[5]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Fecha</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[6]?></td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold; margin-top:10px" colspan="2">1. IDENTIFICACIÓN DEL PROFESIONAL DEL CAMBIO</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:center;" colspan="2">
					<div style="text-align:center">Principal: AV CARRERA 15 124 30 LC 1 118 CC UNICENTRO</div>
					<div style="text-align:center">RÉGIMEN COMÚN</div>
					<div style="text-align:center; margin-top:10px">ACTIVIDAD ECONÓMICA ICA 6615 TARIFA 11.04X1000</div>
					<div style="text-align:center;">Autorización Numeración de Facturación Electrónica <?=$sSucSet[8]?> de <?=$sSucSet[10]?>. Vigencia 12 meses.</div>
					<div style="text-align:center;">Numeración Autorizada <?=$sSucSet[9]?> <?=$sSucSet[11]?> al <?=$sSucSet[12]?></div>
					<div style="text-align:center;"><?=$sSucSet[1]?></div>
					<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"><?=$sSucSet[3]?></div>
				</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACIÓN</td>
				<td style="text-align:left; font-weight:bold">NI</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NÚMERO</td>
				<td style="text-align:left; font-weight:bold">800.132.527-8</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE</td>
				<td style="text-align:left; font-weight:bold">CAMBIOS NEW YORK MONEY SAS.</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">MATRÍCULA MERCANTIL</td>
				<td style="text-align:left; font-weight:bold"><?=$sSucSet[2]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Oficina</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[3]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Cajero</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[5]?></td>
			</tr>
			<tr style="height:28px; vertical-align:middle; font-size:14px">
				<td style="text-align:left; border-bottom:#000000 solid 1px"><b>FACTURA DE VENTA</b></td>
				<td style="text-align:left; font-weight:bold; border-bottom:#000000 solid 1px"><?=$sSucSet[9]. "-". $cerocon. $varp[10]?></td>
			</tr>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">2. IDENTIFICACIÓN DEL CLIENTE</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold" colspan="2">(Datos del residente o no residente que compra o vende divisas o cheques de viajero)</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE / RAZÓN SOCIAL</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[19]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACIÓN</td>
				<td style="text-align:left; font-weight:bold"><?php Gen_Find_Field_1("SELECT Impresion FROM XConf_TiposDoc WHERE Tipo_Documento='".$varp[14]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NÚMERO</td>
				<td style="text-align:center; font-size:15px; font-weight:bold"><?=$varp[13]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CIUDAD</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[24]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">DIRECCIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TELÉFONO</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[21]?></td>
			</tr>
			<?php
				if($varp[14] == 'NIT'){	//Persona juridica
			?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">FECHA CONSTITUCIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">LUGAR CONSTITUCIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[9]?></td>
			</tr>
			<? } else { ?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">FECHA DE NACIMIENTO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">LUGAR DE NACIMIENTO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[9]?></td>
			</tr>
			<? } ?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CORREO ELECTRÓNICO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[17]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">ACTIVIDAD ECONÓMICA</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[25]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">PERSONA PEP</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[26]?></td>
			</tr>
			<?php
			$separar = explode("-",$vben[20]);	//separa la variable de origen de fondos por el -
			
			?>
			<tr style="height:15px; vertical-align:middle">
   				<td style="text-align:left;">ORIGEN DE FONDOS</td>
   				<!--<td style="text-align:left; font-weight:bold"><?=$vben[20]?></td>-->
   				<td style="text-align:left; font-weight:bold">
        			<?= !empty(trim($separar[0])) ? $separar[0] : "AHORROS"; ?>
  				 </td>
			</tr>

			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">DESTINO DE FONDOS</td>
				<!--<td style="text-align:left; font-weight:bold; border-bottom:#000000 solid 1px">Xp</td>-->
				<td style="text-align:left; font-weight:bold">
				<?= !empty(trim($separar[1])) ? $separar[1] :"AHORROS"; ?>
				</td>
			</tr>	
			<?php
				if($varp[14] == 'NIT'){	//Persona juridica
			?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE, IDENTIFICACIÓN, %PARTICIPACIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[21]?></td>
			</tr>
			<?php } ?>
			<?php
			if($varp[13] != $varp[30]){	//Si declarante y beneficiario son diferentes imprime esta parte de la declaración
			?>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">3. IDENTIFICACIÓN DEL DECLARANTE</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold" colspan="2">(Datos de la persona natural que suscribe la declaración, en nombre propio o representación del cliente)</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[8]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACIÓN</td>
				<td style="text-align:left; font-weight:bold"><?php Gen_Find_Field_1("SELECT Impresion FROM XConf_TiposDoc WHERE Tipo_Documento='".$vdec[3]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NÚMERO</td>
				<td style="text-align:center; font-size:15px; font-weight:bold"><?=$varp[30]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CIUDAD</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[15]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">DIRECCIÓN</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[13]?></td>
			</tr>

			<?
				//Validacion telefono			
				if($vdec[11] == '')
				{
					$teldec = $vdec[12];				
				} else {
					$teldec = $vdec[11];				
				}			
			?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TELÉFONO</td>
				<td style="text-align:left; font-weight:bold"><?=$teldec?></td>
			</tr>
			<? } ?>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">4. DESCRIPCIÓN DE LA OPERACIÓN</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CONCEPTO</td>
				<td style="text-align:left; font-weight:bold">VENTA DE DIVISAS</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">MONEDA</td>
				<td style="text-align:center; font-weight:bold; font-size:15px"><?php Gen_Find_Field_1("SELECT Nombre FROM XConf_Monedas WHERE Moneda='".$varp[33]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">MONTO</td>
				<td style="text-align:center; font-weight:bold; font-size:15px"><?=number_format($varp[36], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TASA DE CAMBIO</td>
				<td style="text-align:center; font-weight:bold; font-size:15px">$<?=number_format($varp[35], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold; padding-top:5px; text-align:center" colspan="2">Valor en Pesos de la Operación</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">EFECTIVO</td>
				<td style="text-align:center; font-weight:bold; font-size:13px"><?php if($varp[48] == 'EFECTIVO' && $varp[33] != 'TUSD'){ echo 'COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa);} else { echo 'COP$ 0.00';} ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CHEQUE DE VIAJERO</td>
				<td style="text-align:center; font-weight:bold; font-size:13px"><?php if($varp[33] == 'TUSD'){ echo 'COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa);} else { echo 'COP$ 0.00';} ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">PAGO DIFERENTE A EFECTIVO</td>
				<td style="text-align:center; font-weight:bold; font-size:13px"><?php if($varp[48] != 'EFECTIVO' && $varp[33] != 'TUSD'){ echo 'COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa);} else { echo 'COP$ 0.00';} ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold; font-size:15px">TOTAL</td>
				<td style="text-align:center; font-weight:bold; font-size:15px; border-top:#000000 solid 1px"><?='COP$ '.number_format($varp[46], 2, $GLdecsepa, $GLmilsepa)?></td>
			</tr>
		</table>
		<div style="text-align:center; margin-top:5px; font-size:7px">Para los fines previstos en el artículo 83 de la Constitución Política de Colombia, declaro bajo la gravedad de juramento, que los conceptos, cantidades y demás datos consignados en el presente formulario, son correctos y la fiel expresión de la verdad .</div>
		<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"></div>
		<div style="text-align:center; margin-top:2px; font-size:7px">Esta es una nota de entrega previa a la generación y radicación de la factura electrónica con validación de la DIAN, esta sera enviada al correo suministrado, en caso de requerir la impresion de la representacion grafica, solicitelo en ventanilla.
		<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"></div>Al dorso del presente documento, como en nuestra pagina web http://www.newyorkmoney.com.co , encontrara lo relativo a nuestra POLÍTICAS Y PROCEDIMIENTOS PARA EL TRATAMIENTO DE DATOS PERSONALES (Ley 1581 2012 y normas complementarias), por tanto y con la suscripción del presente documento ud. nos autoriza para lo allí establecido. Así mismo AUTORIZO a CAMBIOS NEW YORK MONEY SAS para que a través de mi dirección electrónica registrada, me envíe información relacionada con los productos, encuestas, servicios y/o calificaciones del servicio prestado, en caso de no aceptar marque __No acepto</div></div>
		<div style="margin-top:1px">
			<div style="float:left; width:170px">
				<div style="height:70px; border-bottom:#000000 solid 1px"></div>			
				<div style="margin-top:4px; text-align:left">FIRMA DECLARANTE</div>
				<div style="text-align:left">SIGNATURE
					
				</div>
				
						</div>
		
		</div>	
	
				</div>
	
		<? } ?>
<?php
			if($acumulado >= 10000){	//validacion para imprimir la DDCI
			?>
		

		<div style="width: 250px; margin-top: 20px; margin-left: 0px;"><br />
			<table style="margin-top:100px;" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr style="vertical-align: bottom;">
					<td style="text-align: center; font-weight: bold;" colspan="3">FORMULARIO DE DEBIDA DILIGENCIA CLIENTE INTENSIFICADA</td>
				</tr>
				<tr style="height: 25px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">Como complemento de la informacion suministrada, completar la siguiente informacion</td>
				</tr>
				<tr style="height: 20px; vertical-align: middle;border:none;">
					<td style="text-align: left; font-weight: bold; margin-top: 10px;border: none;" colspan="3">1. La fuente de su riqueza proviene de:</td>
				</tr>
				<tr style="height: 5px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 0px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 5px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 10px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 20px; vertical-align: middle;">
					<td style="text-align: left; font-weight: bold; margin-top: 10px;  border-top: #000000 solid 1px;" colspan="3">2. Los recursos obtenidos son destinados para: (explicar al detalle)</td>
</tr>

				<tr style="height: 5px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 0px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 5px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 10px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 20px; vertical-align: middle;">
					<td style="text-align: left; font-weight: bold; margin-top: 10px; border-top: #000000 solid 1px;" colspan="3">3. Describa su ocupacion u oficio al detalle:</td>
				</tr>
				<tr style="height: 5px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 0px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 5px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="height: 10px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">&nbsp;</td>
				</tr>
				
				<tr style="height: 20px; vertical-align: middle; border-top: #000000 solid 1px;">
				<td style="text-align: left; font-weight: bold; margin-top: 10px; border-top: #000000 solid 1px;" colspan="3">4. Seleccione en que rango se encuentra el Volumen de activos a su nombre:</td>
				</tr>
				<tr style="height: 15px; vertical-align: middle;">
					<td style="text-align: left; font-weight: bold;">1millon -</td>
					<td style="text-align: left; font-weight: bold;">50 millones</td>
					<td>
						<div style="width: 20px; height: 100%; background-color: white; border: 1px solid black;">&nbsp;</div>
					</td>
				</tr>
				<tr style="height: 15px; vertical-align: middle;">
					<td style="text-align: left; font-weight: bold;">50millones -</td>
					<td style="text-align: left; font-weight: bold;">100 millones</td>
					<td>
						<div style="width: 20px; height: 100%; background-color: white; border: 1px solid black;">&nbsp;</div>
					</td>
				</tr>
				<tr style="height: 15px; vertical-align: middle;">
					<td style="text-align: left; font-weight: bold;">100 millones -</td>
					<td style="text-align: left; font-weight: bold;">400 millones</td>
					<td>
						<div style="width: 20px; height: 100%; background-color: white; border: 1px solid black;">&nbsp;</div>
					</td>
				</tr>
				<tr style="height: 15px; vertical-align: middle;">
					<td style="text-align: left; font-weight: bold;" colspan="2">Mas de 400 millones</td>
					<td>
						<div style="width: 20px; height: 100%; background-color: white; border: 1px solid black;">&nbsp;</div>
					</td>
				</tr>
				<tr style="height: 20px; vertical-align: middle;">
					<td style="text-align: left; margin-top: 10px; border-top: 2px solid black; border-right: none; border-bottom: none; border-left: none;" colspan="3">

					<span style="font-weight: bold;">5. Tipo soporte fuente de riqueza o fuente de ingreso que se anexa:</span>
					</td>
				</tr>

  			</table>
 			 <table style="margin-top: 5px;" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="text-align: left; font-weight: bold; border-right: 1px solid black; margin-top: 0px; border-top: 0 px ;"colspan="1">Declaración de renta</td>

					<td style="padding: 0; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
   					 SI
					</td>
					<td style="padding: 0; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
    					NO
					</td>
				</tr>
				<tr>
					<td style="text-align: left; font-weight: bold; border-right: 1px solid black; margin-top: 0px; border-top: 0 px ;"colspan="1">Cert.Laboral o comp.Nomina</td>

					<td style="padding: 0; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
   					 SI
					</td>
					<td style="padding: 0; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
    					NO
					</td>
				</tr>
				<tr>
					<td style="text-align: left; font-weight: bold; border-right: 1px solid black; margin-top: 0px; border-top: 0 px ;"colspan="1">Promesa compra venta</td>

					<td style="padding: 0; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
   					 SI
					</td>
					<td style="padding: 0; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;">
    					NO
					</td>
				</tr>
				<tr>
					<td style="text-align: left; font-weight: bold; margin-top: 0px; border-bottom: 1px solid black;" colspan="3">Otros:</td>

				</tr>

				
				<tr style="height: 5px; vertical-align: top;">
					<td style="text-align: center; border-bottom: #000000 solid 1px; border-top: #000000 solid 1px;" colspan="3">&nbsp;</td>
				</tr>
				<tr style="margin-top: 80px;">
					<td style="text-align: center; font-weight: bold; margin-top: 80px; font-size: 7px;" colspan="3">Declaro bajo la gravedad de juramento, y para cumplir con los fines establecidos en el Artículo 83 de la Constitución Política de Colombia, que las respuestas, activos y demás datos consignados en el presente formulario son verídicos y representan la verdad de manera precisa. En caso de que falte algún documento de soporte, me comprometo a enviarlo al correo electrónico direccionoperativa@newyorkmoney.com.co.</td>
				</tr>
				
			</table>
			<div style="margin-top:1px">

				<div style="float:left; width:170px">
					<div style="height:70px; border-bottom:#000000 solid 1px"></div>			
					<div style="margin-top:4px; text-align:left">FIRMA</div>
					<div style="text-align:left">SIGNATURE</div>
					
				</div>
			</div>		
<? } ?>


				<div style="clear:both"></div>

		
		</div>

	</div>
	
				
</body>
<script>
window.onload = function() {
	GenPrint1('<?=$var[2]?>');
};
</script>
</html>
