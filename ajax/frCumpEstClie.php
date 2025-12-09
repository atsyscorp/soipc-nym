<?php
//ARCHIVO FUNCIONES FRCUMPESTCLIE.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "Gen_Find_CLI":
		$calFun = Gen_Find_CLI();
		break;

	default:
}
//-------------------------------------------------
//Funcion para buscar cliente y regresar valores
function Gen_Find_CLI()
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
				if($sfield == 'Estado' && $n[$i] == 'BLOQUEADO'){
					echo '<td class="celrow bgcol_5 fwhite" style="width:104px; max-width:104px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:104px">'. $sfield. '</td>';				
				} else {				
					echo '<td class="celrow" style="width:104px; max-width:104px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:104px">'. $sfield. '</td>';				
				}
				echo '<td class="celrow"><input name="'. $sfield .'" id="tx'. $z .'" class="txlabel" style="width:157px;" value="'. $n[$i] .'" type="text" disabled="disabled"/></td>';				
				echo '</tr>';
			}
		}
	} else {
		echo $mensaje;
	}
}





?>