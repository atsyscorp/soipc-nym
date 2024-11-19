<?php
//ARCHIVO FUNCIONES FRCUMPMODOPS.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "Gen_Find_OPS":
		$calFun = Gen_Find_OPS();
		break;

	default:
}
//-------------------------------------------------
//Funcion para buscar operación y regresar valores
function Gen_Find_OPS()
{
	$strSQL =$_GET['strSQL'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	$mensaje = '';
	$z = 0;
	if($count == 1){
		$ifi=mysqli_num_fields($p);
		while($n=mysqli_fetch_array($p)){
			for($i = 0; $i <= $ifi - 1; $i++)
			{
				$z++;
				$sfield = mysqli_fetch_field_direct($p, $i)->name;
				echo '<tr valign="middle" class="fcont trnone">';				
				echo '<td class="celrow" style="width:104px; max-width:104px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:104px">'. $sfield. '</td>';				
				if($z == 1){
					echo '<td class="celrow"><input name="'. $sfield .'" id="tx'. $z .'" class="txlabel" style="width:140px;" value="'. $n[$i] .'" type="text" disabled="disabled"/></td>';				
				} else if(mysqli_fetch_field_direct($p, $i)->type == 'real') {				
					echo '<td class="celrow"><input name="'. $sfield .'" id="tx'. $z .'" class="txlabel" style="width:140px;" value="'. $n[$i] .'" type="text" onkeydown="return OnlynumDec(this, event)"/></td>';				
				} else {
					echo '<td class="celrow"><input name="'. $sfield .'" id="tx'. $z .'" class="txlabel" style="width:140px;" value="'. $n[$i] .'" type="text" /></td>';				
				}
				echo '</tr>';
			}
		}
	} else {
		echo $mensaje;
	}
}





?>