<?php
//ARCHIVO FUNCIONES FRCONSVIEW.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "Sel_MovOpsCh":
		$calFun = Sel_MovOpsCh();
		break;
	case "Sel_MovOpsCb":
		$calFun = Sel_MovOpsCb();
		break;


	default:
}
//------------------------------------------
//Funcion para obtener los campos de tabla seleccionada
function Sel_MovOpsCh()
{
	$stable =$_GET['strSQL'];		//Captura nombre de tabla
	//--------------------------------------------------
	$link=Conectarse();
	//-------------------------------------------------------
	//Consulta de campos de tabla
	$strSQC = "SELECT * FROM ".$stable." LIMIT 0, 1";
	$pc = mysqli_query($link, $strSQC) or die(mysqli_error($link));
	$ic = mysqli_num_fields($pc);
	//--------------------------------------------------
	for ($j = 0; $j <= $ic - 1; $j++) {
		echo '<div style="padding:5px 0">
				<input type="checkbox" id="'.mysqli_fetch_field_direct($pc, $j)->name.'" name="'.mysqli_fetch_field_direct($pc, $j)->name.'" class="chselfild" /><label for="'.mysqli_fetch_field_direct($pc, $j)->name.'" class="fcont">'.mysqli_fetch_field_direct($pc, $j)->name.'</label>
				</div>';
	} 
}
//------------------------------------------
//Funcion para obtener los campos de tabla seleccionada
function Sel_MovOpsCb()
{
	$stable =$_GET['strSQL'];		//Captura nombre de tabla
	//--------------------------------------------------
	$link=Conectarse();
	//-------------------------------------------------------
	//Consulta de campos de tabla
	$strSQC = "SELECT * FROM ".$stable." LIMIT 0, 1";
	$pc = mysqli_query($link, $strSQC) or die(mysqli_error($link));
	$ic = mysqli_num_fields($pc);
	//--------------------------------------------------
	for ($j = 0; $j <= $ic - 1; $j++) {
		echo '<option value="'.mysqli_fetch_field_direct($pc, $j)->name.'">'.mysqli_fetch_field_direct($pc, $j)->name.'</option>
';
	} 
}
?>