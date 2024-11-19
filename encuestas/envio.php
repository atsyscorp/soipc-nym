<?php
// Cabezeras
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
error_reporting(E_ALL ^ E_NOTICE);
// Include General
include('../General.php');
// Inicia Conexion bd
$link=Conectarse();
//Iniicia PHPMailer
require 'PHPMailer/PHPMailerAutoload.php';
//-----------------------------------------
// Variables de envío de mensaje
$mailC = new PHPMailer;
$mailC->isSMTP();
$mailC->CharSet = "utf-8";
$mailC->SMTPDebug = 0;
$mailC->SMTPAuth = true;	
$mailC->Host = 'smtp.gmail.com';
$mailC->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
$mailC->SMTPSecure = 'ssl';
$mailC->Port = 465;
$mailC->Username = 'findme@newyorkmoney.com.co';
$mailC->Password = '79NewYM79';
$mailC->From = 'findme@newyorkmoney.com.co'; 
$mailC->FromName = "Servicio al Cliente New York Money";
$mailC->addReplyTo('atencionalcliente@newyorkmoney.com.co');
$mailC->isHTML(true);	
$mailC->Subject = "Cuéntanos tu experiencia en nuestra sucursal";
// Consulta a tabla de correos donde el estado de correo sea por enviar
$strSQL1 = "SELECT * FROM Correos WHERE Estado_Correo = 'Por Enviar'";
$p1=mysqli_query($link, $strSQL1) or die(mysqli_error($link));
// While para recorrer cada respuesta
while($q1=mysqli_fetch_array($p1)){
	// Consulta si el Id_Cliente esta dentro de tabla desuscritos
	$strSQL2 = "SELECT * FROM Desuscritos WHERE Id_Cliente = '". $q1['Id_Cliente'] ."'";
	$p2=mysqli_query($link, $strSQL2) or die(mysqli_error($link));
	$count=mysqli_num_rows($p2);
	//si no esta en la tabla desuscritos Valida correo electronico
	if($count == 0){
		// Se valida correo electronico
		$strSQL3 = "SELECT * FROM Clientes WHERE Identificacion = '". $q1['Id_Cliente'] ."'";
		$p3=mysqli_query($link, $strSQL3) or die(mysqli_error($link));
		$q3=mysqli_fetch_array($p3);
		// Si es correcta la validacion de correo electronico se procede a enviar correo electronico
		if (!filter_var($q3['EMail'], FILTER_VALIDATE_EMAIL) === false){
			//Email del cliente
			$mailC->addAddress($q3['EMail']);
			//--------------------------------------------
			//Cuerpo de mensaje
			$bodytoCustomer = "";
    		$bodytoCustomer .= '<html><body style="background-color:rgb(235,235,235);padding:50px 0px;">';
    		$bodytoCustomer .='
				<table width="600px" border="0" cellspacing="1" cellpadding="5" align="center" style="background-color:rgb(255,255,255); font-family:Arial, Helvetica, sans-serif;border-collapse: collapse;border-top:5px solid rgb(135,179,84);box-sizing: border-box;">
				  <tr>
				    <td style="background-color:rgb(255,255,255); padding:15px 20px; text-align:left;box-sizing: border-box;">
				      <a href="http://www.newyorkmoney.com.co" target="_blank">
				        <img src="http://www.soipcnym.com/encuestas/images/logo-nym.png" style=" width:40%; height:auto; border:none" />
				       </a>
				    </td>
				  </tr>
				  <tr>
				    <td style="color:rgb(135,179,84);text-align:left;font-size:21px;padding:15px 20px;box-sizing: border-box;">
				      <strong>HOLA '. $q3['Nombre_Completo'] .'</strong>
				    </td>
				  </tr>
				  <tr>
				    <td style="color:rgb(111,111,111);text-align:left;padding:15px 20px;font-size:17px;box-sizing: border-box;">
				      Gracias por elegir a New York Money para cambiar tus divisas. Te agradeceríamos que nos indiques cómo fue la atención que recibiste en ventanilla y cómo podemos mejorar para la próxima vez que cambies tus divisas. Por favor haz clic en el siguiente botón <b>Entra a Encuesta</b>.
				    </td>
				  </tr>
				  <tr>
				    <td style="text-align:center;">
				      <a href="encuesta.soipcnym.com/encuesta1.php?cliente='.$q1['Id_Cliente'].'&factura='.$q1['Id_Factura'].'" target="_blank">
				        <button style="width:35%;padding:10px;border:none;color:rgb(255,255,255);background-color:rgb(135,179,84);font-size:17px;box-sizing: border-box;cursor:pointer;"><strong>Entra a la Encuesta</strong></button>
				      </a>
				    </td>
				  </tr>
				   <tr>
				    <td style="color:rgb(111,111,111);text-align:left;padding:15px 20px;font-size:17px;box-sizing: border-box;">
				      Si quieres verificar la autenticidad de este correo por favor contáctanos al <a href="tel: +0314322480">4322480</a>.<p></p>
				      Estos datos no serán utilizados para fines comerciales y sólo tienen por objetivo mejorar la prestación del servicio. Si no deseas recibir correos de New York Money, por favor haz clic en el suguiente botón para darte de baja.
				    </td>
				  </tr>
				  <tr>
				    <td style="text-align:center;">
				      <a href="encuesta.soipcnym.com/desuscribir1.php?cliente='.$q1['Id_Cliente'].'" target="_blank">
				        <button style="width:35%;padding:10px;border:none;color:rgb(255,255,255);background-color:rgb(206,206,206);font-size:17px;box-sizing: border-box;cursor:pointer;"><strong>Desuscribirme</strong></button>
				      </a>
				    </td>
				  </tr>
				  <tr>
				    <td style="color:rgb(80,80,80);text-align:left;padding:15px 20px 50px 20px;font-size:11px;box-sizing: border-box;"></td>
				  </tr>
				</table>
    		';
    		 $bodytoCustomer .= '</body></html>';
    		 $mailC->Body = $bodytoCustomer;	
    		 //---------------------------------------------
			//Envio de mensaje	
			$Valmail = $mailC->send();
			// Si el envio fue correcto se actualiza estado del registro a enviado
			if($Valmail){
				$strSQL4 = "UPDATE Correos SET Estado_Correo='Enviado' WHERE Identificacion='". $q1['Identificacion'] ."'";
				$p4=mysqli_query($link, $strSQL4) or die(mysqli_error($link));
			// si el envio no fue correcto se actualiza estado del registro a error
			} else {
				$strSQL4 = "UPDATE Correos SET Estado_Correo='Error' WHERE Identificacion='". $q1['Identificacion'] ."'";
				$p4=mysqli_query($link, $strSQL4) or die(mysqli_error($link));
			}
			// Limpia variable de correo 
			$mailC->ClearAddresses(); 
		// Si no es correcta la validacion de correo electronico se actualiza estado del registro a error
		} else {
			$strSQL4 = "UPDATE Correos SET Estado_Correo='Error' WHERE Identificacion='". $q1['Identificacion'] ."'";
			$p4 = mysqli_query($link, $strSQL4) or die(mysqli_error($link));
		}
	// Si esta en la tabla desuscritos se actualiza el estado del registro a desuscrito
	} else {
		$strSQL4 = "UPDATE Correos SET Estado_Correo='Desuscrito' WHERE Identificacion='". $q1['Identificacion'] ."'";
		$p4 = mysqli_query($link, $strSQL4) or die(mysqli_error($link));
	}
}
?>