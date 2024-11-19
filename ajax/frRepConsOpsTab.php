<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	include("../General.php");
	//---------------------------------------------------
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	//--------------------------------------------------
	$strSQL = isset($_GET['swhere']) ? $_GET['swhere'] : '';
	$iField = isset($_GET['stable']) ? $_GET['stable'] : '';
	$sRep = isset($_GET['srep']) ? $_GET['srep'] : '';
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta configuracion general
	$getGenSet = GenSet($link);
	//-------------------------------------------------
	//Texto re encabezado si es exportar reporte
	$cabe = '';
	if($sRep != ''){
		$cabe = '<span class="fcont" style="font-size:23px">SOIPC - '. $sGenSet[0] .'<br />';
		$cabe = $cabe .'<span class="fcont" style="font-size:18px">'. $sRep .'<p></p>';
	}	
	//--------------------------------------------------
	$mensaje = '';
	//-------------------------------------------------
	//Consulta
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = mysqli_num_fields($p);
	//Encabezado de tabla
	$mensaje = '<table cellpadding="0" cellspacing="0">
					<tr class="bgcol_6 fwhite">';
	for ($j = 0; $j <= $i - 1; $j++) {
		if(mysqli_fetch_field_direct($p, $j)->type == 'real')
		{
			$mensaje = $mensaje. '<td class="celrow" style="width:90px; text-align:right; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">'. mysqli_fetch_field_direct($p, $j)->name . '</td>';
		} else {
			$mensaje = $mensaje. '<td class="celrow" style="width:90px; text-align:left; max-width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:90px">'. mysqli_fetch_field_direct($p, $j)->name . '</td>';
		}
	}
	$mensaje = $mensaje. '</tr>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$sRep?></title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script src="../scripts/General.js" type="text/javascript"></script>
<script src="../scripts/frRepConsOps.js" type="text/javascript"></script>
</head>
<body class="bodygen" style="margin:0px 0px 0px 0px">
<?=$cabe?>
<?=$mensaje?>
	<?
		while($n=mysqli_fetch_array($p)){
		if($n[intval($iField)] == 'ANULADO')
		{
	?>
	<tr valign="middle" class="fwhite bgcol_5">
	<? 
		} else {
	?>
	<tr valign="middle" class="fcont trnone">
	<? } ?>
	<?
		for ($j = 0; $j <= $i - 1; $j++) {
			if(mysqli_fetch_field_direct($p, $j)->type == 'real')
			{
	?>
	<td class="celrow" style="text-align:right; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px"><?=number_format($n[$j], 2, $GLdecsepa, $GLmilsepa)?></td>
	<?
	 	} else {
	?>
	<td class="celrow" style="text-align:left; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:90px" title="<?=$n[$j]?>"> <?=$n[$j]?></td>
	<? }} ?>
	</tr>
	<? } ?>
	</table>
</body>
</html>
