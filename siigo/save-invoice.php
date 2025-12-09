<?php
    ini_set('max_execution_time', 300); // Configura el tiempo máximo de ejecución a 300 segundos (5 minutos)
    date_default_timezone_set('America/Bogota');

    $includeBilling = 0; // Incluir generación de factura de venta? 1/0
    $sendDIANBilling = 0; // Enviar factura de venta a la DIAN? (1/0) Funciona si $includeBilling está true

    // Incluye los archivos necesarios
    require_once 'siigoAPIClient.php';
    require_once 'traitDatabaseCon.php';
    require_once 'traitParametros.php';
    require_once 'logger.php';

    if($includeBilling == 1) {

        cleanLogFile();
        writeLog("[save-invoice] Inicio de ronda");

        // optiene coneccion a bd, token siigo y los ids a generar factura
        $link = connectToDatabase();
        $authToken = getSiigoAuthToken();
        $invoiceIds = getPendingInvoiceIds($link);

        // Organizar el nombre
        function determineName($nombre) {
            $partes = explode(" ", $nombre);
            $apellido = array_pop($partes);
            if (count($partes) > 0) {
                $posibleSegundoApellido = end($partes);
                if (strlen($posibleSegundoApellido) <= 3) {
                    $apellido = $posibleSegundoApellido . " " . $apellido;
                    array_pop($partes);
                }
            }
            $nombres = implode(" ", $partes);
            
            return [
                'nombres' => $nombres,
                'apellidos' => $apellido
            ];
        }

        $grpEvent = [];
        foreach ($invoiceIds as $invoiceId) {

            // Optener datos de la factura por invoiceId y beneficiario por documento
            $datosFactura = getOpVentanillaData($link, $invoiceId); // entra a consultar los datos de la factura
            writeLog('[save-invoice] $datosFactura: ' . json_encode($datosFactura));

            // Obtener datos de cliente
            $docClient = ($datosFactura['Tipo_Documento'] == 'NIT') ? substr($datosFactura['Documento_Beneficiario'], 0, -1) : $datosFactura['Documento_Beneficiario'];
            $dataPerson = getClienteData($link, $docClient);
            writeLog('[save-invoice] $dataPerson: ' . json_encode($dataPerson));

            // Obtener codigos para hacer match son siigo api
            $codigoCiudad = getCodigoCiudad($link, $dataPerson['Ciudad'], $dataPerson['Departamento']);
            writeLog('[save-invoice] $codigoCiudad: ' . json_encode($codigoCiudad));

            // Obtener codigo DIAN
            $codigoDocDian = getCodigoDocDian($link, $dataPerson['Tipo_Documento']);
            writeLog('[save-invoice] $codigoDocDian: ' . json_encode($codigoDocDian));

            // Verificar si alguna de las consultas falló o no encontró datos
            if (!$datosFactura || !$dataPerson || !$codigoCiudad || !$codigoDocDian) {
                $errorMessage = "Error obteniendo los datos necesarios de la base de datos para la factura ID: " . $invoiceId;
                writeLog($errorMessage, "error");
                continue;
            }
            
            // Dar salida a la consola de Javascript
            echo "<script>console.log('datos de factura: " . json_encode($datosFactura) . "');</script>";
            echo "<script>console.log('datos del beneficiario: " . json_encode($dataPerson) . "');</script>";
            
            // Establecer número de reintentos máximos e inicial.
            $max_retries = 6;
            $retries = 0;

            $totalResults = NULL;

            do {

                // Comprobar cliente
                $clienteResponse = checkCliente($datosFactura['Documento_Beneficiario'], $authToken);
                writeLog('[save-invoice] $clienteResponse: '. json_encode($clienteResponse));

                // Validar respuesta de SIIGO
                $codigoValidacion = validarRespuestaSiigo($clienteResponse);
                writeLog('[save-invoice] $codigoValidacion: '. $codigoValidacion);

                // Imprimir salida en el navegador
                echo "<script>console.log('checkCliente respuesta: " . ((isset($clienteResponse)) ? addslashes(json_encode($clienteResponse)) : '') . " ');</script>";

                // Ejecutar acción según el caso.
                switch ($codigoValidacion) {
                    case 0: // Sin problemas
                        writeLog('[save-invoice] Exitoso el registro de cliente');
                        echo "<script>console.log(' exitosa ');</script>";
                    break 2; // Sale de la operación y de la estructura anidada 2
                    case 1: // Reintentar

                        if ($retries < $max_retries) {

                            // Si hay reintentos por debajo del limite permitido
                            echo "<script>console.log(' reintento ');</script>";
                            $message = $clienteResponse['Errors'][0]['Message'];
                            $start = strpos($message, "Try again in") + 12;
                            $end = strpos($message, " seconds.");
                            $seconds = substr($message, $start, $end - $start);
                            sleep($seconds);
                            $retries++;
                            break;

                        } else {

                            // No hay mas reintentos
                            echo "<script>console.log('sin reintentos');</script>";
                            $lastUpdate = date("Y-m-d H:i:s");
                            $errorMessage = "[save-invoice] buscar de cliente error en factura : " .$invoiceId ."  -  " . addslashes($clienteResponse['Errors'][0]);
                            writeLog($errorMessage);
                            $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                            break 2; // Salir del bucle actual y continuar con la siguiente factura

                        }

                    break;
                    case 2: // Surgió un error

                        echo "<script>console.log('error general 4xx');</script>";
                        $lastUpdate = date("Y-m-d H:i:s");
                        $errorMessage = "[save-invoice] buscar de cliente error en factura : " .$invoiceId ."  -  " . addslashes($clienteResponse['Errors'][0]);
                        writeLog($errorMessage);
                        $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                    
                    break 2; // Salir del bucle actual y continuar con la siguiente factura
                    case 3: // Error en la ejecución por parte de SIIGO o generado por este archivo

                        echo "<script>console.log('error ejecucion 5xx');</script>";
                        writeLog("Error 5xx " . json_encode($clienteResponse['Errors'][0]), 'error');
                        exit(); // Finaliza la ejecución
                    
                    break 2;
                    case 4: // Si ya existe la factura

                        writeLog("[save-invoice] Error 4: Cliente ya existe", 'error');
                        echo "<script>console.log('already exist');</script>";

                    break 2;
                }

            } while ($retries <= $max_retries);

            writeLog('[save-invoice] $retries <= $max_retries: '. $retries . ' <= ' . $max_retries);        

            if ($codigoValidacion==2) {
                writeLog('[save-invoice] Error general, pasando a la siguiente factura.');
                echo "<script>console.log('error general pasando siguiente factura');</script>";
                continue;
            }

            $totalResults = (isset($clienteResponse) && is_array($clienteResponse)) ? $clienteResponse['pagination']['total_results'] : 0;

            // Crear el payload del cliente según el tipo de persona
            $ClientPayload = [];

            // Obtener person type y nombre correctos para NI si esel caso
            $person_type = ($dataPerson['Tipo_Documento'] == 'NIT') ? 'Company' : 'Person';

            //Consulta codigo de documento del beneficiario -> Codigo DIAN
            $ClientPayload['type'] = 'Customer';
            $ClientPayload['person_type'] = $person_type;
            $ClientPayload['id_type'] = $codigoDocDian['Codigo_DIAN'];
            $ClientPayload['identification'] = $dataPerson["Documento"];

            $customerEmail = 'noemail@newyorkmoney.com.co';
            if($dataPerson["EMail"] !== '') {
                $customerEmail = ($dataPerson["EMail"] == 'NS') ? 'noemail@newyorkmoney.com.co' : $dataPerson["EMail"];
            }

            if ($person_type == 'Company') {
                $grpDecl = determineName($datosFactura["Nombre_Declarante"]);

                $ClientPayload['name'] = [$dataPerson['Nombre_Completo']];
                $ClientPayload['check_digit'] = $dataPerson['DV'];
                $ClientPayload['commercial_name'] = $dataPerson['Nombre_Completo'];
                $ClientPayload['contacts'] = [
                    [
                        "first_name" => $grpDecl['nombres'],
                        "last_name" => $grpDecl['apellidos'],
                        "phone" => array("number" => $datosFactura["Telefono"]),
                        "email" => $customerEmail
                    ]
                ];
            } else {
                $ClientPayload['name'] = [
                    $dataPerson['Nombre_1'] . ((!empty($dataPerson['Nombre_2'])) ? ' '.$dataPerson['Nombre_2'] : ''), 
                    $dataPerson['Apellido_1'] . ((!empty($dataPerson['Apellido_2'])) ? ' '.$dataPerson['Apellido_2'] : '')
                ];
                $ClientPayload['commercial_name'] = '';
                $ClientPayload['contacts'] = [
                    [
                        "first_name" => $dataPerson['Nombre_1'],
                        "last_name" => $dataPerson['Apellido_1'],
                        "email" => $customerEmail
                    ]
                ];
            }

            $ClientPayload['address']['address'] = $dataPerson["Direccion"];
            $ClientPayload['address']['city']['country_code'] = 'CO';
            $ClientPayload['address']['city']['state_code'] = substr($codigoCiudad['Codigo'], 0, 2);
            $ClientPayload['address']['city']['city_code'] = $codigoCiudad['Codigo'];
            $ClientPayload['address']['city']['postal_code'] = '';
            $ClientPayload['phones'][0]['indicative'] = '57';
            if($datosFactura["Telefono"] !== '') {
                $ClientPayload['phones'][0]['number'] = $datosFactura["Telefono"];
            } else {
                $ClientPayload['phones'][0]['number'] = $datosFactura["Celular"];
            }

            $clientJsonPayload = json_encode($ClientPayload);
            writeLog('[save-invoice] $clientJsonPayload: ' . $clientJsonPayload);
            echo "<script>console.log('Payload de cliente: " . addslashes(json_encode($clientJsonPayload)) . " ');</script>";

            writeLog('[save-invoice] $totalResults: ' . $totalResults);
            if ($totalResults == 0) {
                $max_retries = 3;
                $retries = 0;
                do {
                    // Comprobar si el cliente ya existe en SIIGO
                    $clientExists = 0;
                    $clientCheck = checkCliente($dataPerson["Documento"], $authToken);
                    if(is_array($clientCheck) && count($clientCheck['results']) == 0) {
                        $createResponse = createCliente($clientJsonPayload, $authToken);
                        writeLog('[save-invoice] Cliente ' . $dataPerson['Nombre_Completo'].' no existe en SIIGO.');
                    } else {
                        $createResponse = $clientCheck['results'][0];
                        writeLog('[save-invoice] Cliente ' . $dataPerson['Nombre_Completo'].' ya existe en SIIGO.');
                        writeLog('[save-invoice] Resultado de datos ' . json_encode($createResponse));
                        $clientExists = 1;
                    }

                    writeLog('[save-invoice] $createResponse: ' . json_encode($createResponse));
                    echo "<script>console.log(' respuesta creacion cliente: " . addslashes(json_encode($createResponse)) . " ');</script>";

                    $codigoValidacion = validarRespuestaSiigo($createResponse);
                    writeLog('[save-invoice] $codigoValidacion: ' . json_encode($codigoValidacion));
                    switch ($codigoValidacion) {
                        case 0:
                            $siigoClient = $createResponse;
                        break 2;
                        case 1:
                            if ($retries < $max_retries) {
                                writeLog('[save-invoice] Reintento '.$retries.' de crear cliente');
                                echo "<script>console.log(' haciendo reintento ');</script>";
                                $message = $createResponse['Errors'][0]['Message'];
                                $start = strpos($message, "Try again in") + 12;
                                $end = strpos($message, " seconds.");
                                $seconds = substr($message, $start, $end - $start);
                                sleep($seconds);
                                $retries++;
                                break;
                            } else {
                                writeLog('[save-invoice] Reintentos agotados para crear cliente');
                                echo "<script>console.log('agoto reintentos');</script>";
                                $lastUpdate = date("Y-m-d H:i:s");
                                $errorMessage = "[save-invoice] crear cliente error en factura : " .$invoiceId ."  -  " . addslashes($createResponse) . " - Cód: " . $codigoValidacion;
                                writeLog($errorMessage);
                                $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                                break 2; // Salir del bucle actual y continuar con la siguiente factura
                            }
                        case 2:
                            writeLog('[save-invoice] Error 4xx al crear cliente: '. json_encode($createResponse) );
                            echo "<script>console.log(' error general 4xx ');</script>";
                            $lastUpdate = date("Y-m-d H:i:s");
                            $errorMessage = "crear cliente error en factura : " .$invoiceId ."  -  Msg: " . json_encode($createResponse);
                            $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                            break 3; // Salir del bucle actual y continuar con la siguiente factura
                        case 3:
                            writeLog('Error 5xx al crear cliente.');
                            echo "<script>console.log(' error ejecucion 5xx ');</script>";
                            exit();
                    }
                } while ($retries < $max_retries);
                if ($codigoValidacion==2) {
                    continue;
                }
            } else {
                $max_retries = 3;
                $retries = 0;
                do {
                    $updateResponse = updateCliente($clienteResponse['results'][0]['id'], $clientJsonPayload, $authToken);
                    echo "<script>console.log(' respuesta actualizacion cliente: " . addslashes(json_encode($updateResponse)) . " ');</script>";
                    writeLog('[save-invoice] $updateResponse: '. json_encode($updateResponse));

                    $codigoValidacion = validarRespuestaSiigo($updateResponse);
                    writeLog('[save-invoice] $codigoValidacion update: '. json_encode($codigoValidacion));
            
                    switch ($codigoValidacion) {
                        case 0:
                            echo "<script>console.log('actualizacion cliente exitosa ');</script>";
                            $siigoClient = $updateResponse;
                            break 2;
                        case 1:
                            // Reintentar la petición
                            if ($retries < $max_retries) { // 
                                writeLog('[save-invoice] Reintentando actualizar cliente');
                                echo "<script>console.log(' haciendo reintento ');</script>";
                                $message = $updateResponse['Errors'][0]['Message'];
                                $start = strpos($message, "Try again in") + 12;
                                $end = strpos($message, " seconds.");
                                $seconds = substr($message, $start, $end - $start);
                                sleep($seconds);
                                $retries++;
                                break;
                            } else {
                                writeLog('[save-invoice] Agotado el reintento de actualizar cliente');
                                echo "<script>console.log(' agoto los reintentos ');</script>";
                                $lastUpdate = date("Y-m-d H:i:s");
                                $errorMessage = "[save-invoice] Reintentos agotados - actualizar cliente error en factura : " .$invoiceId ."  -  " .addslashes(json_encode($updateResponse));
                                writeLog($errorMessage);
                                $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                                break 2; // Salir del bucle actual y continuar con la siguiente factura
                            }
                            break;
                        case 2:
                            writeLog('[save-invoice] Error 4xx al actualizar cliente: '. json_encode($updateResponse) );
                            echo "<script>console.log(' error general 4xx ');</script>";
                            $lastUpdate = date("Y-m-d H:i:s");
                            $errorMessage = "[save-invoice] Error 4xx - actualizar cliente error en factura : " .$invoiceId ."  -  " . addslashes(json_encode($updateResponse));
                            writeLog($errorMessage);
                            $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                            break 3; // Salir del bucle actual y continuar con la siguiente factura
                        case 3:
                            writeLog('[save-invoice] Error 5xx al actualizar cliente.');
                            echo "<script>console.log(' error ejecucion 5xx ');</script>";
                            exit();
                        case 4:
                            writeLog('[save-invoice] Cliente ya existe: '. json_encode($clientJsonPayload));
                            echo "<script>console.log(' already exist ');</script>";
                            break 2;
                    }
                } while ($retries < $max_retries);
                if ($codigoValidacion==2) {
                    continue;
                }
            }

            // Creacion del payload de la factura para siigo
            $phones = [];
            if($datosFactura["Telefono"] !== '') {
                $phones[0]['number'] = $datosFactura["Telefono"];
            } else {
                $phones[0]['number'] = $datosFactura["Celular"];
            }
            $paymentCodeVar = 0;
            if($datosFactura['Medio_Pago'] == 'EFECTIVO'){
                $paymentCodeVar = $paymentMeansCode['EFECTIVO'][$datosFactura['Sucursal']];
            } else {
                $paymentCodeVar = $paymentMeansCode['BANCOS'];
            }
            $invoiceData = array(
                "document" => array(
                    "id" => $docCodes[$datosFactura['Sucursal']]
                ),
                "number" => $datosFactura['Consecutivo'],
                "date" => $datosFactura['Fecha'],
                "customer" => array(
                    "branch_office" => 0,
                    "person_type"  => $person_type,
                    "id_type"  => $siigoClient['id_type']['code'],
                    "name"  =>  $siigoClient['name'],
                    "identification" => $siigoClient['identification'],
                    "address" => array (
                        "address" => $siigoClient['address']['address'],
                        "city" => array(
                            "country_code" => $siigoClient['address']['city']['country_code'],
                            "state_code" => $siigoClient['address']['city']['state_code'],
                            "city_code" => $siigoClient['address']['city']['city_code'],
                        ),
                        ),
                    //"phones" => $siigoClient['phones'],
                    "phones" => $phones,
                    "contacts" => $siigoClient['contacts'] // posible error
                ),
                "stamp" => array(
                    "send" => ($sendDIANBilling == 1) ? true : false,
                ),
                "mail" => array(
                    "send" => true
                ),
                "observations" => "",
                "seller" => 980,
                "items" => array(
                    array(
                        //"code" => $currencyProducts[$datosFactura['Moneda']]['Code'],
                        "code" => $datosFactura['Sucursal'],
                        "description" => $currencyProducts[$datosFactura['Moneda']]['Name'],
                        "quantity" => $datosFactura['Cantidad'],
                        "price" =>  $datosFactura['Precio_Sin_Iva'],
                    )
                ),
                "payments" => array(
                    array(
                        "id" => $paymentCodeVar,
                        "value" => $datosFactura['Valor'],
                        "due_date" => $datosFactura['Fecha']
                    )
                ),
                "comments" => '',
                "related_users" => array(
                    'seller_id' => 980,
                    'collector_id' => 980
                ),
                "active" => true,
                "vat_responsible" => false,
                "fiscal_responsibilities" => array(
                    array(
                        'code' => 'R-99-PN',
                    )
                )
            );

            $invoiceJsonPayload = json_encode($invoiceData);
            echo "<script>console.log('Payload de invoice: " . $invoiceJsonPayload . " ');</script>";
            writeLog('[save-invoice] invoiceJsonPayload: '. json_encode($invoiceJsonPayload));

            $max_retries = 3; // Número máximo de reintentos permitidos
            $retries = 0; // Contador de reintentos

            writeLog('[save-invoice] Inicio creación factura');
            writeLog('[save-invoice] Intento ' . $retries . ' de ' . $max_retries);
            writeLog('[save-invoice] '.$retries < $max_retries);

            do {
                writeLog('[save-invoice] Listo para enviar factura...');
                $responseInvoice = createInvoice($invoiceJsonPayload,$authToken);
                writeLog('[save-invoice] $responseInvoice: ' . addslashes(json_encode($responseInvoice)));
                echo "<script>console.log(' respuesta creacion factura: " . addslashes(json_encode($responseInvoice)) . " ');</script>";
                $codigoValidacion = validarRespuestaSiigo($responseInvoice);
                echo "<script>console.log('codigoValidacion factura: " . $codigoValidacion. "');</script>";
                writeLog('[save-invoice] $codigoValidacion: '.$codigoValidacion);
                switch ($codigoValidacion) {
                    case 0:
                        // Respuesta exitosa
                        // Realizar acciones correspondientes
                        echo "<script>console.log('Respuesta factura: " . addslashes(json_encode($responseInvoice)) . "');</script>";
                        writeLog('[save-invoice] '.addslashes(json_encode($responseInvoice)));
                        $lastUpdate = date("Y-m-d H:i:s");
                        $errorMessage = json_encode($responseInvoice);
                        $updateResult = setInvoiceStatus($link, 'Enviada', $lastUpdate, $errorMessage, $invoiceId);
                        break 2;
                    case 1:
                        // Reintentar la petición
                        echo "<script>console.log('Reintento');</script>";
                        if ($retries < $max_retries) {
                            $message = $responseInvoice['Errors'][0]['Message'];
                            $start = strpos($message, "Try again in") + 12;
                            $end = strpos($message, " seconds.");
                            $seconds = substr($message, $start, $end - $start);
                            sleep($seconds);
                            $retries++;
                            continue 2;
                        } else {
                            $lastUpdate = date("Y-m-d H:i:s");
                            $errorMessage = "[save-invoice] Error al crear la factura: " .$invoiceId ."  -  " .  addslashes(json_encode($responseInvoice));
                            writeLog($errorMessage);
                            $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                            break 2; // Salir del bucle actual y continuar con la siguiente factura
                        }
                        break;
                    case 2:
                        // Error 400 sin reintento
                        echo "<script>console.log('Error general 4xx');</script>";
                        $lastUpdate = date("Y-m-d H:i:s");
                        $errorMessage = "[save-invoice] Error al crear la factura: " .$invoiceId ."  -  " . addslashes(json_encode($responseInvoice));
                        writeLog($errorMessage);
                        $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                        break 2; // Salir del bucle actual y continuar con la siguiente factura
                    case 3:
                        // Error 500 para ejecución
                        echo "<script>console.log('Error de ejecución 5xx');</script>";
                        exit();
                        break;
                    case 4:
                        // Respuesta ya existe
                        // Realizar acciones correspondientes
                        echo "<script>console.log('Ya existe');</script>";
                        $lastUpdate = date("Y-m-d H:i:s");
                        $errorMessage = "";
                        $updateResult = setInvoiceStatus($link, 'Enviada', $lastUpdate, $errorMessage, $invoiceId);
                        echo "<script>console.log('Respuesta siigo creacion factura: " . ((isset($logMessageSiigoClient)) ? $logMessageSiigoClient : '') . " For: " . $datosClienteFactura[1] . " In: " . $invoiceId . " Fila Afectada " . $updateResult . "');</script>";
                        break;
                }
            } while ($retries < $max_retries);

            // Elimina datos antiguos de variables si fueron declarados.
            unset($datosFactura, $dataPerson, $codigoCiudad, $codigoDocDian, $errorMessage, $clientJsonPayload, $siigoClient, $invoiceData);

        }
        writeLog("[save-invoice] Fin de ronda");

    }

?>