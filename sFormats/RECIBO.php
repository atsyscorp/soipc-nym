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
	//Consulta informaci?n de la operaci?n
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
	//Consulta informaci?n beneficiario
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
		$d=mysqli_query($link, $strSQLD) or die(mysqli_error($link));
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
	//Consulta informaci?n de sucursal
	$getSucSet = SucSet($link, $varp[3]);
	//Consulta configuraci?n contable
	$getTaxSet = TaxSet($link, $varp[3]);
	//Consulta identificaci?n de cajero
	$strSQTl = "SELECT Identificacion FROM Usuarios WHERE Nombre='".$varp[5]."'";
	$ptl=mysqli_query($link, $strSQTl) or die(mysqli_error($link));
	$qtl=mysqli_fetch_array($ptl);
	//----------------------------------------------------------
	//Ceros del consucutivo
	$cerocon = '';
	if($varp[10] < 100)
	{
		$cerocon = '000';
	} else if ($varp[10] < 1000) {
		$cerocon = '00';
	} else if ($varp[10] < 10000) {
		$cerocon = '0';
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title>RECIBO</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
<style type="text/css">
@page{
    margin-left: 10px;
    margin-right: 0px;
    margin-top: 0px;
    margin-bottom: 0px;
}
</style>
</head>
<body class="bodygen" style="font-size:9px" onload="">
	<div style="width:280px; margin-top:1px; margin-left:1px ; margin-right:1px">
		<div style="text-align:center"><img src="../images/Logo_Factura.png" style="width:250px; height:auto" /></div>
		
		<?php
				if($varp[48] != 'EFECTIVO' && $varp[33] != 'TUSD'){	//Persona juridica
			?>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px">
			<tr style="vertical-align:bottom">
				<td style="text-align:center; font-weight:bold" colspan="2">RECIBO DE COMPRA, DEBIDA DILIGENCIA  POR COMPRA Y VENTA DE MANERA PROFESIONAL DE DIVISAS Y CHEQUES DE VIAJERO</td>
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
				<td style="text-align:left; font-weight:bold; margin-top:10px" colspan="2">1. IDENTIFICACION DEL PROFESIONAL DEL CAMBIO</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:center;" colspan="2">
					<div style="text-align:center;"><?=$sSucSet[1]?></div>
					<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"><?=$sSucSet[3]?></div>
				</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACION</td>
				<td style="text-align:left; font-weight:bold">NI</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NUMERO</td>
				<td style="text-align:left; font-weight:bold">800.132.527-8</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE</td>
				<td style="text-align:left; font-weight:bold">CAMBIOS NEW YORK <br>MONEY SAS.</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Oficina</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[3]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">Cajero</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[5]?></td>
			</tr>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">DEBIDA DILIGENCIA</td>
			</tr>
			<tr style="height:28px; vertical-align:middle; font-size:14px">
				<td style="text-align:left; border-bottom:#000000 solid 1px"><b>RECIBO DE COMPRA</b></td>
				<td style="text-align:left; font-size:20px; font-weight:bold; border-bottom:#000000 solid 1px"><?=$varp[11]. "-". $cerocon. $varp[10]?></td>
			</tr>
			
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE / RAZON SOCIAL</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[19]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACION</td>
				<td style="text-align:left; font-weight:bold"><?php Gen_Find_Field_1("SELECT Impresion FROM XConf_TiposDoc WHERE Tipo_Documento='".$varp[14]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NUMERO</td>
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
				<td style="text-align:left;">FECHA CONSTITUCION</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">LUGAR CONSTITUCION</td>
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
				<td style="text-align:left;">CORREO ELECTRONICO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[17]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">ACTIVIDAD ECONIMICA</td>
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
				<td style="text-align:left;">NOMBRE, IDENTIFICACION, %PARTICIPACION</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[21]?></td>
			</tr>
			<? } ?>
			
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">DESCRIPCION DE LA OPERACION</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CONCEPTO</td>
				<td style="text-align:left; font-weight:bold">COMPRA DE DIVISAS</td>
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
				<td style="text-align:left; font-weight:bold; padding-top:5px; text-align:center" colspan="2">Valor en Pesos de la Operacion</td>
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
		<div style="text-align:center; margin-top:5px; font-size:7px">Para los fines previstos en el articulo 83 de la constitucion politica de Colombia, declaro bajo la gravedad de juramento, que los conceptos, cantidades y demas datos consignados en el presente formulario, son correctos y la fiel expresion de la verdad .</div>
		<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"></div>
		<div style="text-align:center; margin-top:2px; font-size:7px;margin-bottom:10px">Esta es una nota de entrega previa a la generacion y radicacion de factura electronica con validacion de la DIAN.<br>Al dorso del presente documento, como en nuestra pagina web http://www.newyorkmoney.com.co , encontrara lo relativo a nuestra POLITICAS Y PROCEDIMIENTOS PARA EL TRATAMIENTO DE DATOS PERSONALES (Ley 1581 2012 y normas complementarias), por tanto y con la suscripcion del presente documento ud. nos autoriza para lo alli establecido. Asi mismo Autoriza a CAMBIOS NEW YORK MONEY SAS para que a traves de mi direccion electronica registrada, me envie informacion relacionada con los productos, encuestas, servicios y/o calificaciones del servicio prestado., en caso de no aceptar marque __No acepto</div></div>
		<div style="margin-top:10px">
			<div style="float:left; width:170px">
				<div style="height:90px; border-bottom:#000000 solid 1px;margin-bottom:5px;"></div>			
				<div style="margin-top:4px; text-align:left">FIRMA</div>
				<div style="text-align:left">SIGNATURE</div>
							</div>
			<div style="clear:both"></div>		
		</div>
	
			<? }else { ?>
			
		
		
		
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:5px">
			<tr style="vertical-align:bottom">
				<td style="text-align:center; font-weight:bold" colspan="2">RECIBO DE COMPRA, DEBIDA DILIGENCIA Y DECLARACION DE CAMBIO POR COMPRA Y VENTA DE MANERA PROFESIONAL DE DIVISAS Y CHEQUES DE VIAJERO</td>
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
				<td style="text-align:left; font-weight:bold; margin-top:10px" colspan="2">1. IDENTIFICACION DEL PROFESIONAL DEL CAMBIO</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:center;" colspan="2">
					<div style="text-align:center">Principal: AV CARRERA 15 124 30 LC 1 118 CC UNICENTRO</div>
					<div style="text-align:center">REGIMEN COMUN</div>
					<div style="text-align:center; margin-top:10px">ACTIVIDAD ECONOMICA ICA 6615 TARIFA 11.04X1000</div>
					<div style="text-align:center;"><?=$sSucSet[1]?></div>
					<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"><?=$sSucSet[3]?></div>
				</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACION</td>
				<td style="text-align:left; font-weight:bold">NI</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NUMERO</td>
				<td style="text-align:left; font-weight:bold">800.132.527-8</td>
			</tr>
			<tr style="height:20px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE</td>
				<td style="text-align:left; font-weight:bold">CAMBIOS NEW YORK MONEY SAS.</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">MATRICULA MERCANTIL</td>
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
				<td style="text-align:left; border-bottom:#000000 solid 1px"><b>RECIBO DE COMPRA</b></td>
				<td style="text-align:left;font-size:20px; font-weight:bold; border-bottom:#000000 solid 1px"><?=$varp[11]. "-". $cerocon. $varp[10]?></td>
			</tr>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">2. IDENTIFICACION DEL CLIENTE</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold" colspan="2">(Datos del residente o no residente que compra o vende divisas o cheques de viajero)</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE / RAZON SOCIAL</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[19]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACION</td>
				<td style="text-align:left; font-weight:bold"><?php Gen_Find_Field_1("SELECT Impresion FROM XConf_TiposDoc WHERE Tipo_Documento='".$varp[14]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NUMERO</td>
				<td style="text-align:center; font-size:15px; font-weight:bold"><?=$varp[13]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CIUDAD</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[24]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">DIRECCION</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TELEFONO</td>
				<td style="text-align:left; font-weight:bold"><?=$varp[21]?></td>
			</tr>
			<?php
				if($varp[14] == 'NIT'){	//Persona juridica
			?>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">FECHA CONSTITUCION</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[22]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">LUGAR CONSTITUCION</td>
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
				<td style="text-align:left;">CORREO ELECTRONICO</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[17]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">ACTIVIDAD ECONOMICA</td>
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
				<td style="text-align:left;">NOMBRE, IDENTIFICACION, %PARTICIPACION</td>
				<td style="text-align:left; font-weight:bold"><?=$vben[21]?></td>
			</tr>
			<? } ?>
			<?php
			if($varp[13] != $varp[30]){	//Si declarante y beneficiario son diferentes imprime esta parte de la declaraci?n
			?>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">3. IDENTIFICACION DEL DECLARANTE</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left; font-weight:bold" colspan="2">(Datos de la persona natural que suscribe la declaracion, en nombre propio o representacion del cliente)</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NOMBRE</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[8]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">TIPO IDENTIFICACION</td>
				<td style="text-align:left; font-weight:bold"><?php Gen_Find_Field_1("SELECT Impresion FROM XConf_TiposDoc WHERE Tipo_Documento='".$vdec[3]."'"); ?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">NUMERO</td>
				<td style="text-align:center; font-size:15px; font-weight:bold"><?=$varp[30]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CIUDAD</td>
				<td style="text-align:left; font-weight:bold"><?=$vdec[15]?></td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">DIRECCION</td>
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
				<td style="text-align:left;">TEL?FONO</td>
				<td style="text-align:left; font-weight:bold"><?=$teldec?></td>
			</tr>
			<? } ?>
			<tr style="height:20px; vertical-align:bottom">
				<td style="text-align:left; font-weight:bold" colspan="2">4. DESCRIPCION DE LA OPERACION</td>
			</tr>
			<tr style="height:15px; vertical-align:middle">
				<td style="text-align:left;">CONCEPTO</td>
				<td style="text-align:left; font-weight:bold">COMPRA DE DIVISAS</td>
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
				<td style="text-align:left; font-weight:bold; padding-top:5px; text-align:center" colspan="2">Valor en Pesos de la Operacion</td>
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
		<div style="text-align:center; margin-top:5px; font-size:7px">Para los fines previstos en el articulo 83 de la constitucion politica de Colombia, declaro bajo la gravedad de juramento, que los conceptos, cantidades y demas datos consignados en el presente formulario, son correctos y la fiel expresion de la verdad .</div>
		<div style="text-align:center; border-bottom:#000000 solid 1px; padding-bottom:5px"></div>
		<div style="text-align:center; margin-top:2px; font-size:7px;margin-bottom:10px">Al dorso del presente documento, como en nuestra pagina web http://www.newyorkmoney.com.co , encontrara lo relativo a nuestra POLITICAS Y PROCEDIMIENTOS PARA EL TRATAMIENTO DE DATOS PERSONALES (Ley 1581 2012 y normas complementarias), por tanto y con la suscripcion del presente documento ud. nos autoriza para lo alli establecido. Asi mismo Autoriza a CAMBIOS NEW YORK MONEY SAS para que a traves de mi direccion electronica registrada, me envie informacion relacionada con los productos, encuestas, servicios y/o calificaciones del servicio prestado, en caso de no aceptar marque __No acepto</div></div>
		<div style="margin-top:10px">
			<div style="float:left; width:170px">
					<div style="height:90px; border-bottom:#000000 solid 1px;margin-bottom:5px;"></div>				
				<div style="margin-top:4px; text-align:left">FIRMA DECLARANTE</div>
				<div style="text-align:left">SIGNATURE</div>
							</div>
			
			<div style="clear:both"></div>	
			    
			<div style="text-align:center; margin-top:5px; font-size:15px; width: 280px;">
                 <span style="font-weight: bold; font-size:25px">ATENCION CLIENTES:</span><br>
                <span style="font-weight: bold; text-decoration: underline;">Todos</span> los billetes tienen nuestro <strong>sello de garantia</strong>. <strong>Reviselo</strong> antes de salir. <strong>NO</strong> se aceptan reclamos posteriores. Esto garantiza la autenticidad de su dinero.
            </div>
        <div style="text-align:center; margin-top:5px; font-size:13px; border-top: #000000 solid 1px; padding-top: 5px; width:280px;">
            <span style="font-weight: bold; font-size:20px">ATTENTION CUSTOMERS:</span><br>
            <span style="font-weight: bold; text-decoration: underline;">All</span> banknotes have our <strong>guarantee stamp</strong>. <strong>Please check it</strong> before leaving. <strong>NO</strong> claims will be accepted afterwards. This ensures the authenticity of your money.
        </div>

			
			
		</div>
	</div>
		<? } ?>
</body>
<script>
window.onload = function() {
	GenPrint1('<?=$var[2]?>');
};
</script>
</html>