<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("../General.php");
	$access=$_REQUEST['var0'];
	$login=$_REQUEST['var1'];
	$caja=$_REQUEST['var2'];
	$mensaje = '';
	//-------------------------------------------------------
	//Busca clave de acceso en configuracion
	$link=Conectarse();
	$p=mysqli_query($link, "SELECT * FROM Configuracion_General where Clave_Acceso = '$access'") or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	if($count==0){
		$mensaje = MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 350, 45, 'La clave de acceso es incorrecta y no se puede ingresar a la aplicación.', "hidcap('dMsj')", 0, '', '', 'visible');
		echo $mensaje;
		return;
	}
	//Valida usuario
	$u=mysqli_query($link, "SELECT * FROM Usuarios WHERE ClaveAcceso = '$login'") or die(mysqli_error($link));
	$countu=mysqli_num_rows($u);
	if($countu==0){
		$mensaje = MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 350, 45, 'El Permiso de Usuario es incorrecto y no se puede ingresar a la aplicación.', "hidcap('dMsj')", 0, '', '', 'visible');
		echo $mensaje;
		return;
	}

	//Valida IP de conexion si no es superusuario
   	$us=mysqli_fetch_array($u);
	if($us['Cargo'] != 'SUPERUSUARIO')
	{
		//Captura IP
		$ipadrs = $_SERVER['REMOTE_ADDR'];
		$s=mysqli_query($link, "SELECT * FROM Sucursales where IP_Adress LIKE '%$ipadrs%'") or die(mysqli_error($link));
		$counts=mysqli_num_rows($s);
		if($counts==0)
		{
			$mensaje = MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 350, 45, 'El usuario no tiene permiso para conectarse desde la ubicación actual.', "hidcap('dMsj')", 0, '', '', 'visible');
			echo $mensaje;
			return;
		} else {
			if($caja != '')
			{
				$mensaje = '100';
				echo $mensaje;
			} else {
				$su=mysqli_fetch_array($s);
				$mensaje = $su['Cajas'];
				echo $mensaje;
			}		
		}
	} else {	//Superusuario envia mensaje de submit
		$mensaje = '100';
		echo $mensaje;
	}	

	

	
 ?>
