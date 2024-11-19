<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("../General.php");
	//---------------------------------------------------
	//Captura id de operacion
	$var[1]=$_GET['var1'];
	$var[2]=$_GET['var2'];
	//Valida Acceso a archivo
	if($var[1] == ''){
		header("location:../index.php");
	}
	//---------------------------------------------------
	//Captura variables publicas
	$link=Conectarse();
	//---------------------------------------------------
	//Consulta información de operacion y crea variables
    $strSQLS = "SELECT * FROM Egresos_Ventanilla WHERE Identificacion = '".  $var[1]. "'";
	$p = mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for($i = 1; $i <= 27; $i++){
			if($n[$i] == '')
			{
				$varp[$i] = '<span style="color:#FFFFFF">.</span>';
			} else {
				$varp[$i] = $n[$i];
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EGRESO</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
</head>
<body class="bodygen" onload="GenPrint1('<?=$var[2]?>');">
	<div style="width:720px; margin-top:20px; margin-left:10px">
		<div style="border:#000000 solid 1px; overflow:hidden">
			<div style="margin:4px">
				<div style="margin-top:30px"><?=$varp[6]?></div>
				<div style="margin-top:2px">
					<div style="float:left; width:500px; overflow:hidden"><?=$varp[9]?></div>
					<div style="float:right; width:90px; text-align:center">$<?=number_format($varp[19], 2, $GLdecsepa, $GLmilsepa)?></div>
				</div>
				<div style="clear:both"></div>	
				<div style="margin-top:2px">
					<div style="float:left"><?=$varp[8]?></div>
				</div>	
				<div style="clear:both"></div>	
				<div style="margin-bottom:30px"></div>		
			</div>
		</div>	
		<div style="border:#000000 solid 1px; border-top:none; overflow:hidden">
			<div style="margin:4px; margin-top:0px; margin-right:0px">
				<div style="float:left; width:450px">
					<div style="margin-top:2px">NEW YORK MONEY LTDA.</div>
					<div style="margin-top:2px">UNICENTRO LC 1-118</div>					
					<div style="margin-top:2px">TELÉFONO: 522 19 33</div>
					<div></div>					
				</div>
				<div style="float:right; width:220px; border-left:#000000 solid 1px">
					<div>Comprobante de Egreso</div>
					<div style="margin-top:2px; margin-bottom:2px">NO.</div>					
					<div style="height:30px; border-top:#000000 solid 1px; vertical-align:middle; text-align:center"><b><?=$varp[7]?></b></div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>	
		<div style="border:#000000 solid 1px; overflow:hidden; margin-top:4px">
			<div style="border-bottom:#000000 solid 1px; overflow:hidden">
				<div style="float:left; width:100px; text-align:center; border-right:#000000 solid 1px">Código PUC</div>
				<div style="float:left; width:440px; text-align:center; border-right:#000000 solid 1px">Concepto</div>
				<div style="float:left; width:170px; text-align:center;">Valor</div>
			</div>
			<div style="clear:both"></div>	
			<div style="overflow:hidden">
				<div style="float:left; width:100px; height:40px; text-align:center; border-right:#000000 solid 1px"><?=$varp[10]?></div>
				<div style="float:left; width:440px; height:40px; text-align:left; border-right:#000000 solid 1px"><?=$varp[11]?></div>
				<div style="float:left; width:170px; height:40px; text-align:center;"><?=number_format($varp[14], 2, $GLdecsepa, $GLmilsepa)?></div>
			</div>
			<div style="clear:both"></div>	
			<div style="overflow:hidden;">
				<div style="float:left; width:100px; text-align:center; border-right:#000000 solid 1px; color:#FFFFFF">.</div>
				<div style="float:left; width:440px; text-align:left; border-right:#000000 solid 1px">Valor IVA</div>
				<div style="float:left; width:170px; text-align:center;"><?=number_format($varp[15], 2, $GLdecsepa, $GLmilsepa)?></div>
			</div>
			<div style="overflow:hidden;">
				<div style="float:left; width:100px; text-align:center; border-right:#000000 solid 1px; color:#FFFFFF">.</div>
				<div style="float:left; width:440px; text-align:left; border-right:#000000 solid 1px">Retención en la Fuente</div>
				<div style="float:left; width:170px; text-align:center;"><?=number_format($varp[16], 2, $GLdecsepa, $GLmilsepa)?></div>
			</div>
			<div style="overflow:hidden;">
				<div style="float:left; width:100px; text-align:center; border-right:#000000 solid 1px; color:#FFFFFF">.</div>
				<div style="float:left; width:440px; text-align:left; border-right:#000000 solid 1px">Retención de ICA</div>
				<div style="float:left; width:170px; text-align:center;"><?=number_format($varp[17], 2, $GLdecsepa, $GLmilsepa)?></div>
			</div>
			<div style="overflow:hidden; border-bottom:#000000 solid 1px">
				<div style="float:left; width:100px; text-align:center; border-right:#000000 solid 1px; color:#FFFFFF">.</div>
				<div style="float:left; width:440px; text-align:left; border-right:#000000 solid 1px">Retención de IVA</div>
				<div style="float:left; width:170px; text-align:center;"><?=number_format($varp[18], 2, $GLdecsepa, $GLmilsepa)?></div>
			</div>
			<div style="overflow:hidden; border-bottom:#000000 solid 1px">
				<div style="float:left; width:130px; text-align:center; border-right:#000000 solid 1px">Forma de Pago:</div>
				<div style="float:left; width:320px; text-align:left; border-right:#000000 solid 1px"><?=$varp[20]?></div>
				<div style="float:left; width:89px; text-align:center; border-right:#000000 solid 1px">Valor Neto $</div>
				<div style="float:left; width:170px; text-align:center;"><?=number_format($varp[19], 2, $GLdecsepa, $GLmilsepa)?></div>
			</div>
			<div style="overflow:hidden;">
				<div style="float:left; width:451px; border-right:#000000 solid 1px">
					<div style="overflow:hidden; border-bottom:#000000 solid 1px">
						<div style="float:left; width:70px; text-align:left; border-right:#000000 solid 1px">Banco:</div>
						<div style="float:left; width:140px; text-align:left; border-right:#000000 solid 1px"><?=$varp[22]?></div>
						<div style="float:left; width:80px; text-align:left; border-right:#000000 solid 1px">Cheque:</div>
						<div style="float:left; width:150px; text-align:left;"><?=$varp[24]?></div>
					</div>
					<div style="overflow:hidden; border-bottom:#000000 solid 1px">
						<div style="float:left; width:130px; text-align:left; border-right:#000000 solid 1px">Cuenta Bancaria:</div>
						<div style="float:left; width:320px; text-align:left;"><?=$varp[23]?></div>
					</div>
					<div style="overflow:hidden; border-bottom:#000000 solid 1px">
						<div style="float:left; width:130px; text-align:left; border-right:#000000 solid 1px">Debitese a:</div>
						<div style="float:left; width:320px; text-align:left; color:#FFFFFF">.</div>
					</div>
					<div style="overflow:hidden; border-bottom:#000000 solid 1px">
						<div style="float:left; width:130px; text-align:left; border-right:#000000 solid 1px">Elaborado:</div>
						<div style="float:left; width:95px; text-align:left; border-right:#000000 solid 1px">Revisado:</div>
						<div style="float:left; width:95px; text-align:left; border-right:#000000 solid 1px">Aprobado:</div>
						<div style="float:left; width:125px; text-align:left;">Contabilizado:</div>
					</div>
					<div style="overflow:hidden">
						<div style="float:left; width:130px; height:60px; text-align:left; border-right:#000000 solid 1px"><?=$varp[5]?></div>
						<div style="float:left; width:95px; height:60px; text-align:left; border-right:#000000 solid 1px; color:#FFFFFF">.</div>
						<div style="float:left; width:95px; height:60px; text-align:left; border-right:#000000 solid 1px; color:#FFFFFF">.</div>
						<div style="float:left; width:125px; height:60px; text-align:left; ; color:#FFFFFF">.</div>
					</div>
				</div>			
				<div style="float:right; width:266px">
					<div style="border-bottom:#000000 solid 1px">Firma del Beneficiario</div>
					<div style="height:75px; color:#FFFFFF">.</div>
					<div style="border-bottom:#000000 solid 1px">Documento:</div>
					<div>Fecha de recibido:</div>
				</div>			
			</div>	
		</div>	
	</div>
</body>
</html>
