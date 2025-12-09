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
		$apiMode = 'test'; //test | production
		$suscriptionKey = 'e1b0aed6afd5456c9c176cb1b1ba8add';
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
		$salesManIdentification = '963852741';
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
		$invoiceQueryLimit = '1'; //Change to 10 in production
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
		$strSQ0 = "SELECT InvoiceId FROM Factura_Electronica WHERE SendProcess='Crear' AND SendStatus<>'Enviada' ORDER BY LastUpdate ASC LIMIT ".$invoiceQueryLimit; //Por Enviar, Enviada, Error 
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
			$clientData['person_type'] = 'Person';
			$clientData['id_type'] = $q4['Codigo_DIAN'];
			$clientData['identification'] = $q2['Documento'];
			$clientData['check_digit'] = $q2['DV'];
			if($q2['Tipo_Documento'] == 'NIT'){
				$clientData['name'] = ''; 
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
				$clientData['contacts'][0]['email'] = utf8_encode(trim($q3[17]));
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
			$clientJson = json_encode($clientData);
			echo $clientJson;













			//Arma Json
			/*$invoiceData = [];
			$invoiceData['Header']['Id'] = 0;
			$invoiceData['Header']['DocCode'] = $docCode[$q1['Sucursal']];
			$invoiceData['Header']['Number'] = $q1['Consecutivo'];	
			$invoiceData['Header']['EmailToSend'] = null; 
			$invoiceData['Header']['DocDate'] = date('Ymd', strtotime($q1['Fecha']));
			$invoiceData['Header']['MoneyCode'] = 'COP';
			$invoiceData['Header']['ExchangeValue'] = 0;
			$invoiceData['Header']['DiscountValue'] = 0;
			$invoiceData['Header']['VATTotalValue'] = 0;	//Valor total IVA -> Variable
			$invoiceData['Header']['ConsumptionTaxTotalValue'] = 0;
			$invoiceData['Header']['TaxDiscTotalValue'] = 0; //Sumatoria de retenciones -> Variable
			$invoiceData['Header']['RetVATTotalID'] = -1;
			$invoiceData['Header']['RetVATTotalPercentage'] = -1; //Porcentaje reteIVA -> Variable
			$invoiceData['Header']['RetVATTotalValue'] = 0; //Valor reteIVA -> Variable
			$invoiceData['Header']['RetICATotalID'] = -1;
			$invoiceData['Header']['RetICATotalValue'] = 0; //Total reteICA -> Variable
			$invoiceData['Header']['RetICATotaPercentage'] = -1; //Porcentaje reteICA
			$invoiceData['Header']['SelfWithholdingTaxID'] = 0;
			$invoiceData['Header']['SelfWithholdingTaxTotalValue'] = 0;
			$invoiceData['Header']['SelfWithholdingTaxTotalPercentage'] = 0;
			$invoiceData['Header']['TotalValue'] = $q1['Valor']; //Valor neto de factura -> Variable
			$invoiceData['Header']['TotalBase'] = $q1['Valor'];	//Valor antes de impuestpos -> Variable
			$invoiceData['Header']['SalesmanIdentification'] = $salesManIdentification;
			$invoiceData['Header']['Observations'] = '';
			//-------------------------------------------------------------
			if($q2['Tipo_Documento'] == 'NIT'){
				$invoiceData['Header']['Account']['IsSocialReason'] = true;
				$invoiceData['Header']['Account']['FullName'] = utf8_encode($q2['Nombre_Completo']); 
				$invoiceData['Header']['Account']['FirstName'] = '';
				$invoiceData['Header']['Account']['LastName'] = '';
			} else {
				$invoiceData['Header']['Account']['IsSocialReason'] = false;
				$invoiceData['Header']['Account']['FullName'] = ''; 
				$invoiceData['Header']['Account']['FirstName'] = utf8_encode($q2['Nombre_1'].' '.$q2['Nombre_2']);
				$invoiceData['Header']['Account']['LastName'] = utf8_encode($q2['Apellido_1'].' '.$q2['Apellido_2']);
			}	
			$invoiceData['Header']['Account']['IdTypeCode'] = $q4['Codigo_DIAN'];
			$invoiceData['Header']['Account']['Identification'] = $q2['Documento'];
			$invoiceData['Header']['Account']['CheckDigit'] = $q2['DV'];
			$invoiceData['Header']['Account']['BranchOffice'] = 0;
			if($q2['Tipo_Documento'] == 'NIT'){
				$invoiceData['Header']['Account']['IsVATCompanyType'] = true;
			} else {
				$invoiceData['Header']['Account']['IsVATCompanyType'] = false;
			} 
			$invoiceData['Header']['Account']['Address'] = $q2['Direccion'];
			$invoiceData['Header']['Account']['Phone']['Indicative'] = 0;
			if($q2['Telefono'] != ''){
				$invoiceData['Header']['Account']['Phone']['Number'] = $q2['Telefono'];
			} else {
				$invoiceData['Header']['Account']['Phone']['Number'] = $q2['Celular'];
			}
			$invoiceData['Header']['Account']['Phone']['Extention'] = 0;
			$invoiceData['Header']['Account']['City']['CountryCode'] = 'CO';
			$invoiceData['Header']['Account']['City']['StateCode'] = substr($q5['Codigo'],0,2);	
			$invoiceData['Header']['Account']['City']['CityCode'] = $q5['Codigo'];
			//-----------------------------------------------------------
			$invoiceData['Header']['Contact']['Code'] = 0;
			$invoiceData['Header']['Contact']['Phone1']['Indicative'] = 0;
			if($q3[11] != ''){
				$invoiceData['Header']['Contact']['Phone1']['Number'] = $q3[11];
			} else {
				$invoiceData['Header']['Contact']['Phone1']['Number'] = 0;
			}
			$invoiceData['Header']['Contact']['Phone1']['Extention'] = 0;
			$invoiceData['Header']['Contact']['Mobile']['Indicative'] = 0;
			if($q3[12] != ''){
				$invoiceData['Header']['Contact']['Mobile']['Number'] = $q3[12];
			} else {
				$invoiceData['Header']['Contact']['Mobile']['Number'] = 0;
			}
			$invoiceData['Header']['Contact']['Mobile']['Extention'] = 0;
			if(!filter_var(trim($q3[17]), FILTER_VALIDATE_EMAIL)){
				$invoiceData['Header']['Contact']['EMail'] = 'noemail@newyorkmoney.com.co';
			} else {
				$invoiceData['Header']['Contact']['EMail'] = utf8_encode(trim($q3[17]));
			}
			$invoiceData['Header']['Contact']['FirstName'] = utf8_encode($q3[4].' '.$q3[5]);	
			$invoiceData['Header']['Contact']['LastName'] = utf8_encode($q3[6].' '.$q3[7]);
			$invoiceData['Header']['Contact']['IsPrincipal'] = true;
			$invoiceData['Header']['Contact']['Gender'] = 0;
			$invoiceData['Header']['Contact']['BirthDate'] = date('Ymd', strtotime($q3[22]));
			//----------------------------------------------------------
			$invoiceData['Header']['CostCenterCode'] = $costCenterCode;
			$invoiceData['Header']['SubCostCenterCode'] = '';
			//----------------------------------------------------------
			$invoiceData['Items'][0]['ProductCode'] = $q1['Sucursal'];
			$invoiceData['Items'][0]['Description'] = $productDescription.$q1['Sucursal'];
			$invoiceData['Items'][0]['GrossValue'] = $q1['Valor'];
			$invoiceData['Items'][0]['BaseValue'] = $q1['Valor'];	//Igual a gross value
			$invoiceData['Items'][0]['Quantity'] = '1'; //Siempre 1 indicaciÃ³n de cliente
			$invoiceData['Items'][0]['UnitValue'] = $q1['Valor'];
			$invoiceData['Items'][0]['DiscountValue'] = 0;
			$invoiceData['Items'][0]['DiscountPercentage'] = 0;
			$invoiceData['Items'][0]['TaxAddName'] = '';
			$invoiceData['Items'][0]['TaxAddId'] = -1;
			$invoiceData['Items'][0]['TaxAddValue'] = 0;
			$invoiceData['Items'][0]['TaxAddPercentage'] = 0;
			$invoiceData['Items'][0]['TaxDiscountName'] = '';
			$invoiceData['Items'][0]['TaxDiscountId'] = -1;
			$invoiceData['Items'][0]['TaxDiscountValue'] = 0;
			$invoiceData['Items'][0]['TaxDiscountPercentage'] = 0;
			$invoiceData['Items'][0]['TotalValue'] = $q1['Valor'];
			$invoiceData['Items'][0]['ProductSubType'] = 0;
			$invoiceData['Items'][0]['TaxAdd2Name'] = '';
			$invoiceData['Items'][0]['TaxAdd2Id'] = -1;
			$invoiceData['Items'][0]['TaxAdd2Value'] = 0;
			$invoiceData['Items'][0]['TaxAdd2Percentage'] = 0;
			$invoiceData['Items'][0]['WareHouseCode'] = '';
			$invoiceData['Items'][0]['SalesmanIdentification'] = '';
			//----------------------------------------------------------
			if($q1['Medio_Pago'] == 'EFECTIVO'){
				$invoiceData['Payments'][0]['PaymentMeansCode'] = $paymentMeansCode['EFECTIVO'][$q1['Sucursal']];
			} else {
				$invoiceData['Payments'][0]['PaymentMeansCode'] = $paymentMeansCode['BANCOS'];
			}
			$invoiceData['Payments'][0]['Value'] = $q1['Valor'];	//Mismo valor neto de factura
			$invoiceData['Payments'][0]['DueDate'] = 0;
			$invoiceData['Payments'][0]['DueQuote'] = 0;
			//----------------------------------------------------------
			$invoiceJson = json_encode($invoiceData);
			//echo $invoiceJson.'<br>';
			//exit();
			//-----------------------------------------------------------
			//Inicio y setup CURL
			$apich = curl_init($apiurl);
			curl_setopt($apich, CURLOPT_POST, true);
			$apiheaders = array(
				"Content-type: application/json",
				"Ocp-Apim-Subscription-Key: ".$suscriptionKey,
				"Authorization: ". $authToken
			);
			curl_setopt($apich, CURLOPT_HTTPHEADER, $apiheaders);
			curl_setopt($apich, CURLOPT_POSTFIELDS, $invoiceJson);
			curl_setopt($apich, CURLOPT_RETURNTRANSFER, true);
			$apiresult = curl_exec($apich);
			curl_close($apich);
			$resultJson = json_decode($apiresult, true); 
			//echo $apiresult;
			//-------------------------------------
			//Update estado de facturas subidas --> Debe validar la respuesta de SIIGO
			$lastUpdate = date("Y-m-d H:i:s");
			if(isset($resultJson['Header']['Id']) && $resultJson['Header']['Id'] != ''){	//EnvÃ­o OK
				$strSQ6 = "UPDATE Factura_Electronica SET SendStatus='Enviada', LastUpdate='".$lastUpdate."' WHERE InvoiceId='".$q0['InvoiceId']."'";

			} else {
				$strSQ6 = "UPDATE Factura_Electronica SET SendStatus='Error', LastUpdate='".$lastUpdate."', ApiResponse='".$apiresult."' WHERE InvoiceId='".$q0['InvoiceId']."'";
			}
			$p6=mysqli_query($link, $strSQ6) or die(mysqli_error($link));
			//-------------------------------------
			sleep(3);*/
		}
	}
?>