<?php
	//CreaciÃ³n de factura
	//-----------------------------------------------------
	require_once("siigo-auth.php");
	/*echo $authToken;
	exit();*/
	//-----------------------------------------------------
	//Valida que exista el token de autenticaciÃ³n
	if(isset($authToken) && $authToken != ''){
		//Variables de configuraciÃ³n de factura
		$apiMode = 'production'; //test | production
		$suscriptionKey = 'e1b0aed6afd5456c9c176cb1b1ba8add';
		$invoiceHeaders = array(
			"Content-type: application/json",
			//la siguiente linea fue implementado por juan camilo el 21/11/23 por actualziacion de siigo
		"Partner-ID: SOIPCNYM",
			"Authorization: ".$authToken
		);
		//-------------------------------------
		//Vector de cÃ³digos de facturas
		$docCode['A01'] = '29515';		
		$docCode['S82'] = '29516';		
		$docCode['CA2'] = '29517';		
		$docCode['U18'] = '29514';		
		$docCode['U47'] = '29512';		
		$docCode['G20'] = '29513';		
		$docCode['A48'] = '29363';		
		//-------------------------------------
		$productDescription = 'Venta de Activo en Moneda Extranjera - ';
		//-------------------------------------
		$salesManIdentification = '980';
		//-------------------------------------
		//Medio de pago
		$paymentMeansCode['EFECTIVO']['A01'] = '8436';
		$paymentMeansCode['EFECTIVO']['S82'] = '8437';
		$paymentMeansCode['EFECTIVO']['CA2'] = '8438';
		$paymentMeansCode['EFECTIVO']['U18'] = '8435';
		$paymentMeansCode['EFECTIVO']['U47'] = '2314';
		$paymentMeansCode['EFECTIVO']['G20'] = '8433';
		$paymentMeansCode['EFECTIVO']['A48'] = '8434';
		$paymentMeansCode['BANCOS'] = '8470';
		//-----------------------------------
		$costCenterCode = '';
		$invoiceQueryLimit = '10'; //Change to 10 in production
		//-------------------------------------------------------------------
		//ConexiÃ³n a base de datos
		if($apiMode == 'test'){
			if (!($link=mysqli_connect("162.219.251.8","soipcnym_remotefindme","79FindMe79", "soipcnym_dbase"))){	//Local
		      echo "Error conectando a la base de datos.";
		      exit();
		   	}
		} else {
			if (!($link=mysqli_connect("localhost","soipcnym_dbase","$79Nym79$", "soipcnym_dbase"))){
		      echo "Error conectando a la base de datos.";
		      exit();
		   	}
		}
		//-------------------------------------------------------------------
		//Consulta facturas por subir
		$strSQ0 = "SELECT InvoiceId FROM Factura_Electronica WHERE SendProcess='Crear' AND SendStatus<>'Enviada' ORDER BY LastUpdate ASC LIMIT 0, ".$invoiceQueryLimit; //Por Enviar, Enviada, Error 
		$p0=mysqli_query($link, $strSQ0) or die(mysqli_error($link));
		$count0=mysqli_num_rows($p0);
		if($count0 == 0){exit();}
		//------------------------------------------------------------------
		while($q0=mysqli_fetch_array($p0)){
			date_default_timezone_set('America/Bogota');
			//Consulta datos de la factura
			$strSQ1 = "SELECT Identificacion, Sucursal, Fecha, Consecutivo, Documento_Beneficiario, Documento_Declarante, Precio_Sin_Iva, Precio_Con_Iva, Cantidad, Valor, IVA, Rete_Fuente, Rete_ICA, Rete_IVA, Caja_Nacional, Medio_Pago FROM Operacion_Ventanilla WHERE Identificacion='".$q0['InvoiceId']."'";
			$p1=mysqli_query($link, $strSQ1) or die(mysqli_error($link));
			$q1=mysqli_fetch_array($p1);
			//-------------------------------------------------------------
			//Consulta datos del beneficiario y declarante en caso que sea diferente
			//Beneficiario
			$strSQ2 = "SELECT * FROM Clientes WHERE Identificacion='".$q1['Documento_Beneficiario']."'";
			$p2=mysqli_query($link, $strSQ2) or die(mysqli_error($link));
			$q2=mysqli_fetch_array($p2);
			//Declarante
			if($q1['Documento_Beneficiario'] == $q1['Documento_Declarante']){
				for($i = 0; $i < mysqli_num_fields($p2); $i++){
					$q3[$i] = $q2[$i];
				}
			} else {
				$strSQ3 = "SELECT * FROM Clientes WHERE Identificacion='".$q1['Documento_Declarante']."'";
				$p3=mysqli_query($link, $strSQ3) or die(mysqli_error($link));
				$q3=mysqli_fetch_array($p3);
			}
			//-------------------------------------------------------------
			//Consulta codigo de documento del beneficiario -> Codigo DIAN
			$strSQ4 = "SELECT Codigo_DIAN FROM XConf_TiposDoc WHERE Tipo_Documento='".$q2['Tipo_Documento']."'";
			$p4=mysqli_query($link, $strSQ4) or die(mysqli_error($link));
			$q4=mysqli_fetch_array($p4);
			//-------------------------------------------------------------
			//Consulta cÃ³digo departamento y ciudad
			$strSQ5 = "SELECT Codigo FROM XConf_Ciudades WHERE Ciudad='".$q2['Ciudad']."' AND Departamento='".$q2['Departamento']."'";
			$p5=mysqli_query($link, $strSQ5) or die(mysqli_error($link));
			$q5=mysqli_fetch_array($p5);
			//-------------------------------------------------------------------
			//API CREACIÓN DE CLIENTE 
			$clientData = [];
			$clientData['type'] = 'Customer';
			if($q2['Tipo_Documento'] == 'NIT'){
				$clientData['person_type'] = 'Company';
			} else {
				$clientData['person_type'] = 'Person';
			}
			$clientData['id_type'] = $q4['Codigo_DIAN'];
			$clientData['identification'] = $q2['Documento'];
			if($q2['Tipo_Documento'] == 'NIT'){
				$clientData['check_digit'] = $q2['DV'];
				$clientData['name'][0] = utf8_encode($q2['Nombre_Completo']); 
				$clientData['commercial_name'] = utf8_encode($q2['Nombre_Completo']);
			} else {
				$clientData['name'][0] = utf8_encode($q2['Nombre_1'].' '.$q2['Nombre_2']); 
				$clientData['name'][1] = utf8_encode($q2['Apellido_1'].' '.$q2['Apellido_2']); 
				$clientData['commercial_name'] = '';
			}
			$clientData['branch_office'] = 0;
			$clientData['active'] = true;
			$clientData['vat_responsible'] = false;
			$clientData['fiscal_responsibilities'][0]['code'] = 'R-99-PN';
			$clientData['address']['address'] = utf8_encode($q2['Direccion']);
			$clientData['address']['city']['country_code'] = 'CO';
			$clientData['address']['city']['state_code'] = substr($q5['Codigo'], 0, 2);
			$clientData['address']['city']['city_code'] = $q5['Codigo'];
			$clientData['address']['postal_code'] = '';
			$clientData['phones'][0]['indicative'] = '57';
			if($q2['Telefono'] != ''){
				$clientData['phones'][0]['number'] = utf8_encode($q2['Telefono']);
			} else {
				$clientData['phones'][0]['number'] = utf8_encode($q2['Celular']);
			}
			$clientData['phones'][0]['extension'] = '';
			$clientData['contacts'][0]['first_name'] = utf8_encode($q3[4].' '.$q3[5]);
			$clientData['contacts'][0]['last_name'] = utf8_encode($q3[6].' '.$q3[7]);
			if(!filter_var(trim($q3[17]), FILTER_VALIDATE_EMAIL)){
				$clientData['contacts'][0]['email'] = 'noemail@newyorkmoney.com.co';
			} else {
				$clientData['contacts'][0]['email'] = utf8_encode(trim($q2['EMail']));
			}
			$clientData['contacts'][0]['phone']['indicative'] = '57';
			if($q3[11] != ''){
				$clientData['contacts'][0]['phone']['number'] = $q3[11];
			} else {
				$clientData['contacts'][0]['phone']['number'] = $q3[12];
			}
			$clientData['contacts'][0]['phone']['extension'] = '';
			$clientData['comments'] = '';
			$clientData['related_users']['seller_id'] = $salesManIdentification;
			$clientData['related_users']['collector_id'] = $salesManIdentification;
			//---------------------------------------------
			$clientJson = json_encode($clientData);
			//echo $clientJson;
			$chClient = curl_init();
			$urlClient = 'https://api.siigo.com/v1/customers';
			curl_setopt($chClient, CURLOPT_URL, $urlClient);
			curl_setopt($chClient, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($chClient, CURLOPT_HEADER, FALSE);
			curl_setopt($chClient, CURLOPT_POST, TRUE);
			curl_setopt($chClient, CURLOPT_POSTFIELDS, $clientJson);
			curl_setopt($chClient, CURLOPT_HTTPHEADER, $invoiceHeaders);
			$clientResponse = curl_exec($chClient);
			curl_close($chClient);
			$clientResponseJson = json_decode($clientResponse, true);
			//var_dump($clientResponse);
			//echo '<br>Hola mundo';
			$clientId = $clientResponseJson['identification'];
			$clientError = $clientResponseJson['Errors'][0]['Code'];
			/*echo $clientError;
			exit();*/
			//echo $clientId;
			//----------------------------------------------------------------------
			//Acá va la validación si recibe el clientId o error
			$doInvoice = false;
			$lastUpdate = date("Y-m-d H:i:s");
			if(isset($clientId) || $clientId != ''){
				$doInvoice = true;
			} else if($clientError=='already_exists'){
				$doInvoice = true;
				$clientId = str_replace('The identification already exists: ','',$clientResponseJson['Errors'][0]['Message']);
			} else {
				$strSQ6 = "UPDATE Factura_Electronica SET SendStatus='Error', LastUpdate='".$lastUpdate."', ApiResponse='Error creación de cliente - ".$clientResponse."' WHERE InvoiceId='".$q0['InvoiceId']."'";
				$p6=mysqli_query($link, $strSQ6) or die(mysqli_error($link));
				//var_dump($clientResponse);
				exit();
			}
			//----------------------------------------------------------------------
			if($doInvoice == true){	
				$invoiceData['document']['id'] = $docCode[$q1['Sucursal']];
				$invoiceData['number'] = $q1['Consecutivo'];
				$invoiceData['date'] = $q1['Fecha'];
				$invoiceData['customer']['identification'] = $clientId;
				$invoiceData['customer']['branch_office'] = 0;
				//$invoiceData['cost_center'] = '';
				//$invoiceData['currency']['code'] = 'COP';
				//$invoiceData['currency']['exchange_rate'] = 1;
				$invoiceData['seller'] = $salesManIdentification;
				$invoiceData['observations'] = '';
				$invoiceData['items'][0]['code'] = $q1['Sucursal'];
				$invoiceData['items'][0]['description'] = $productDescription.$q1['Sucursal'];
				$invoiceData['items'][0]['quantity'] = 1;
				$invoiceData['items'][0]['price'] = $q1['Valor'];
				$invoiceData['items'][0]['discount'] = 0;
				if($q1['Medio_Pago'] == 'EFECTIVO'){
					$invoiceData['payments'][0]['id'] = $paymentMeansCode['EFECTIVO'][$q1['Sucursal']];
				} else {
					$invoiceData['payments'][0]['id'] = $paymentMeansCode['BANCOS'];
				}
				$invoiceData['payments'][0]['value'] = $q1['Valor'];
				$invoiceData['payments'][0]['due_date'] = $q1['Fecha'];
				//-----------------------------------
				//var_dump($invoiceData);
				//echo 'hola mundo';
				$invoiceJson = json_encode($invoiceData);
				$chInvoice = curl_init();
				$urlInvoice = 'https://api.siigo.com/v1/invoices';
				curl_setopt($chInvoice, CURLOPT_URL, $urlInvoice);
				curl_setopt($chInvoice, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($chInvoice, CURLOPT_HEADER, FALSE);
				curl_setopt($chInvoice, CURLOPT_POST, TRUE);
				curl_setopt($chInvoice, CURLOPT_POSTFIELDS, $invoiceJson);
				curl_setopt($chInvoice, CURLOPT_HTTPHEADER, $invoiceHeaders);
				$invoiceResponse = curl_exec($chInvoice);
				curl_close($chInvoice);
				$invoiceResponseJson = json_decode($invoiceResponse, true);
				//var_dump($invoiceResponse);
				//---------------------------------------------
				//Update estado de facturas subidas --> Debe validar la respuesta de SIIGO
				if(isset($invoiceResponseJson['id']) && $invoiceResponseJson['id'] != ''){	//Enviado OK
					$strSQ6 = "UPDATE Factura_Electronica SET SendStatus='Enviada', LastUpdate='".$lastUpdate."', ApiResponse='' WHERE InvoiceId='".$q0['InvoiceId']."'";
				} else {	
					$strSQ6 = "UPDATE Factura_Electronica SET SendStatus='Error', LastUpdate='".$lastUpdate."', ApiResponse='Error factura - ".$invoiceResponse."' WHERE InvoiceId='".$q0['InvoiceId']."'";
				}
				$p6=mysqli_query($link, $strSQ6) or die(mysqli_error($link));
				//-------------------------------------
				sleep(3);
			}
		}
	}
?>