<?php 
//ARCHIVO SUBIDA IMAGENES FROPSCLIENTES
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
	$imClient=$_GET['Client']; //Cliente
	$mensaje = ''; 
if ((($_FILES['webcam']['type'] == 'image/gif')
|| ($_FILES['webcam']['type'] == 'image/png')
|| ($_FILES['webcam']['type'] == 'image/x-png')
|| ($_FILES['webcam']['type'] == 'image/jpeg')
|| ($_FILES['webcam']['type'] == 'image/pjpeg'))
&& ($_FILES['webcam']['size'] < 5242880))
  {
	  if ($_FILES["webcam"]["error"] > 0)
    	{
			$mensaje = 'Error subiendo la imagen';
			echo $mensaje;
	    } else {
			//Crea nuevo nombre de imagen
		   	$imname = date('YmdHis');
			$newflname = $imClient."-".$imname.".jpg";
    	  	move_uploaded_file($_FILES["webcam"]["tmp_name"], "../Fotos/".$newflname);
			//Crea registro en base se datos
			$Sql = "INSERT INTO Fotos VALUES ('". $imClient."-".$imname. "', '". $imClient. "')";
			$link=Conectarse();
			mysqli_query($link, $Sql) or die(mysqli_error($link)); 
			//--------------------------------------------
			$mensaje = '1.|.'.$imClient."-".$imname;
			echo $mensaje;
    	}
  } else {
		$mensaje = 'Error en formato de imagen';
		echo $mensaje;
  }
?>
