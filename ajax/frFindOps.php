<?php
//ARCHIVO FUNCIONES FRFINDOPS.PHP
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
	if($count == 1){
		$ifi=mysqli_num_fields($p);
		while($n=mysqli_fetch_array($p)){
			for($i = 1; $i <= $ifi - 1; $i++)
			{
				$sfield = mysqli_fetch_field_direct($p, $i)->name;
				if(strrpos($sfield, "Estado") === false)
				{
					echo '<tr valign="middle" class="fcont">';				
				} else {
					if($n[$i] != "ACTIVO")
					{
						echo '<tr valign="middle" class="fcont bgcol_5">';				
					} else {
						echo '<tr valign="middle" class="fcont">';				
					} 
				}
				echo '<td class="celrow">'. $sfield. '</td>';				
				echo '<td class="celrow">'. $n[$i]. '</td>';				
				echo '</tr>';
			}
		}
	} else {
		echo $mensaje;
	}
}
?>
