<?php
    ini_set('max_execution_time', 300); // Configura el tiempo máximo de ejecución a 300 segundos (5 minutos)
    date_default_timezone_set('America/Bogota');
    // Incluye los archivos necesarios
    require_once 'siigoAPIClient.php';
    require_once 'traitDatabaseCon.php';
    require_once 'traitParametros.php';
    require_once 'logger.php';
    cleanLogFile();
    writeLog("Inicio de ronda");

    // optiene coneccion a bd, token siigo y los ids a generar factura
    $link = connectToDatabase();
    $authToken = getSiigoAuthToken();
    $invoiceIds = getPendingInvoiceIds($link);
    
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

    foreach ($invoiceIds as $invoiceId) {

        // Elimina datos antiguos de variables si fueron declarados.
        unset($datosFactura, $dataPerson, $codigoCiudad, $codigoDocDian, $errorMessage, $clientJsonPayload, $siigoClient, $invoiceData);

        // Optener datos de la factura por invoiceId y beneficiario por documento
        $datosFactura = getOpVentanillaData($link, $invoiceId); // entra a consultar los datos de la factura

        // Obtener datos de cliente
		$dataPerson = getClienteData($link, $datosFactura['Documento_Beneficiario']);

        // Obtener codigos para hacer match son siigo api
        $codigoCiudad = getCodigoCiudad($link, $dataPerson['Ciudad'], $dataPerson['Departamento']);

        // Obtener codigo DIAN
        $codigoDocDian = getCodigoDocDian($link, $dataPerson['Tipo_Documento']);

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

		do {

            // Comprobar cliente
            $clienteResponse = checkCliente($datosFactura['Documento_Beneficiario'], $authToken);

            // Validar respuesta de SIIGO
            $codigoValidacion = validarRespuestaSiigo($clienteResponse);

            // Imprimir salida en el navegador
            echo "<script>console.log(' checkCliente respuesta: " . ((isset($clienteResponse)) ? addslashes($clienteResponse) : '') . " ');</script>";

            // Ejecutar acción según el caso.
            switch ($codigoValidacion) {
                case 0: // Sin problemas
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
                        $errorMessage = "buscar de cliente error en factura : " .$invoiceId ."  -  " . addslashes($clienteResponse['Errors'][0]);
                        writeLog($errorMessage);
                        $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                        break 2; // Salir del bucle actual y continuar con la siguiente factura

                    }

                break;
                case 2: // Surgió un error

                    echo "<script>console.log('error general 4xx');</script>";
                    $lastUpdate = date("Y-m-d H:i:s");
                    $errorMessage = "buscar de cliente error en factura : " .$invoiceId ."  -  " . addslashes($clienteResponse['Errors'][0]);
                    writeLog($errorMessage);
                    $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                
                break 2; // Salir del bucle actual y continuar con la siguiente factura
                case 3: // Error en la ejecución por parte de SIIGO o generado por este archivo

                    echo "<script>console.log('error ejecucion 5xx');</script>";
                    writeLog("Error 5xx " . json_encode($clienteResponse['Errors'][0]), 'error');
                    exit(); // Finaliza la ejecución
                
                break 2;
                case 4: // Si ya existe la factura

                    writeLog("Error 4: Factura ya existe", 'error');
                    echo "<script>console.log('already exist');</script>";

                break 2;
            }

        } while ($retries <= $max_retries);

        if ($codigoValidacion==2) {
            echo "<script>console.log('error general pasando siguiente factura');</script>";
            continue;
        }

        $totalResults = $clienteResponse['pagination']['total_results'];

        // Obtener person type y nombre correctos para NI si esel caso
        $person_type = ($dataPerson['Tipo_Documento'] == 'NI') ? 'Company' : 'Person';
        if ($person_type === 'Company') {
            $name = array($dataPerson['Nombre_Completo']);
            $grpDecl = determineName($datosFactura["Nombre_Declarante"]);

            $contacts =  array(
                array(
                    "first_name" => $grpDecl['nombres'],
                    "last_name" => $grpDecl['apellidos'],
                    "phone" => array("number" => $datosFactura["Telefono"]),
                    "email" => $dataPerson["EMail"]
                ));
        } else {
            $name = array($dataPerson['Nombre_1'], $dataPerson['Apellido_1']);
            $contacts =  array(
                array(
                    "first_name" => $dataPerson['Nombre_1'],
                    "last_name" => $dataPerson['Apellido_1'],
                    "email" => $dataPerson["EMail"]
                ));
        }
        
        // Crear el payload del cliente según el tipo de persona
        $ClientPayload = [
            "person_type"       => $person_type,
            "id_type"           => $codigoDocDian['Codigo_DIAN'],
            "identification"    => $dataPerson["Identificacion"],
            "name"              => $name,
            "address"           => [
                "address"   => $dataPerson["Direccion"],
                "city"      => [
                    "country_code"  => "CO",
                    "state_code"    => substr($codigoCiudad['Codigo'], 0, 2),
                    "city_code"     => $codigoCiudad['Codigo']
                ]
            ],
            "phones"            => [
                [
                    "number"    => $datosFactura["Telefono"]
                ]
            ],
            "contacts"          => $contacts
        ];

        $clientJsonPayload = json_encode($ClientPayload);
        mail('soporte@atsys.co','ClientPayload',var_export($ClientPayload, true));
        echo "<script>console.log('Payload de cliente: " . addslashes(json_encode($clientJsonPayload)) . " ');</script>";

        mail('soporte@atsys.co', '$totalResult', $totalResult);
        if ($totalResults == 0) {
            $max_retries = 3;
            $retries = 0;
            do {

                $createResponse = createCliente($clientJsonPayload, $authToken);
                mail('soporte@atsys.co','$createResponse',var_export($createResponse,true));
                echo "<script>console.log(' respuesta creacion cliente: " . addslashes(json_encode($createResponse)) . " ');</script>";

                $codigoValidacion = validarRespuestaSiigo($createResponse);
                switch ($codigoValidacion) {
                    case 0:
                        echo "<script>console.log('creación cliente exitosa ');</script>";
                        $siigoClient = $createResponse;
                    break 2;
                    case 1:
                        if ($retries < $max_retries) {
                            echo "<script>console.log(' haciendo reintento ');</script>";
                            $message = $createResponse['Errors'][0]['Message'];
                            $start = strpos($message, "Try again in") + 12;
                            $end = strpos($message, " seconds.");
                            $seconds = substr($message, $start, $end - $start);
                            sleep($seconds);
                            $retries++;
                            break;
                        } else {
                            echo "<script>console.log('agoto reintentos');</script>";
                            $lastUpdate = date("Y-m-d H:i:s");
                            $errorMessage = "crear cliente error en factura : " .$invoiceId ."  -  " . addslashes($createResponse) . " - Cód: " . $codigoValidacion;
                            writeLog($errorMessage);
                            $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                            break 2; // Salir del bucle actual y continuar con la siguiente factura
                        }
                    case 2:
                        echo "<script>console.log(' error general 4xx ');</script>";
                        $lastUpdate = date("Y-m-d H:i:s");
                        $errorMessage = "crear cliente error en factura : " .$invoiceId ."  -  " . addslashes($createResponse) . " - Msg: " . json_encode($createResponse);
                        $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                        break 3; // Salir del bucle actual y continuar con la siguiente factura
                    case 3:
                        echo "<script>console.log(' error ejecucion 5xx ');</script>";
                        exit();
                    case 4:
                        echo "<script>console.log(' already exist ');</script>";
                        break 2;
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
                mail('');
        
                $codigoValidacion = validarRespuestaSiigo($updateResponse);
        
                switch ($codigoValidacion) {
                    case 0:
                        echo "<script>console.log('actualizacion cliente exitosa ');</script>";
                        $siigoClient = $updateResponse;
                        break 2;
                    case 1:
                        // Reintentar la petición
                        if ($retries < $max_retries) { // 
                            echo "<script>console.log(' haciendo reintento ');</script>";
                            $message = $updateResponse['Errors'][0]['Message'];
                            $start = strpos($message, "Try again in") + 12;
                            $end = strpos($message, " seconds.");
                            $seconds = substr($message, $start, $end - $start);
                            sleep($seconds);
                            $retries++;
                            break;
                        } else {
                            echo "<script>console.log(' agoto los reintentos ');</script>";
                            $lastUpdate = date("Y-m-d H:i:s");
                            $errorMessage = "Reintentos agotados - actualizar cliente error en factura : " .$invoiceId ."  -  " .addslashes(json_encode($updateResponse));
                            writeLog($errorMessage);
                            $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                            break 2; // Salir del bucle actual y continuar con la siguiente factura
                        }
                        break;
                    case 2:
                        echo "<script>console.log(' error general 4xx ');</script>";
                        $lastUpdate = date("Y-m-d H:i:s");
                        $errorMessage = "Error 4xx - actualizar cliente error en factura : " .$invoiceId ."  -  " . addslashes(json_encode($updateResponse));
                        writeLog($errorMessage);
                        $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                        break 3; // Salir del bucle actual y continuar con la siguiente factura
                    case 3:
                        echo "<script>console.log(' error ejecucion 5xx ');</script>";
                        exit();
                    case 4:
                        echo "<script>console.log(' already exist ');</script>";
                        break 2;
                }
            } while ($retries < $max_retries);
            if ($codigoValidacion==2) {
                continue;
            }
        }

        // Creacion del payload de la factura para siigo
        $invoiceData = array(
            "document" => array(
                "id" => $docCodes[$datosFactura['Sucursal']] 
            ),
            "number" => $datosFactura['Consecutivo'],
            "date" => $datosFactura['Fecha'],
            "customer" => array(
                "person_type"  => $person_type,
                "id_type"  =>  $siigoClient['id_type']['code'],
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
                "phones" => $siigoClient['phones'],
                "contacts" => $siigoClient['contacts'] // posible error
            ),
            "stamp" => array(
                "send" => true
            ),
            "mail" => array(
                "send" => true
            ),
            "observations" => "",
            "seller" => 887,
            "items" => array(
                array(
                    "code" => $currencyProducts[$datosFactura['Moneda']]['Code'],
                    "description" => $currencyProducts[$datosFactura['Moneda']]['Name'],
                    "quantity" => $datosFactura['Cantidad'],
                    "price" =>  $datosFactura['Precio_Sin_Iva'],
                )
            ),
            "payments" => array(
                array(
                    "id" => $paymentMeansCodes[$datosFactura['Instrumento']],
                    "value" => $datosFactura['Valor'],
                    "due_date" => $datosFactura['Fecha']
                )
            ),
        );
        var_dump($invoiceData);
        mail('soporte@atsys.co','invoiceData',var_export($invoiceData,true));
        $invoiceJsonPayload = json_encode($invoiceData);
        echo $invoiceJsonPayload;
        echo "<script>console.log('Payload de invoice: " . $invoiceJsonPayload . " ');</script>";  
        mail('soporte@atsys.co','invoiceJsonPayload',var_export($invoiceJsonPayload,true));
      //  exit();
        $max_retries = 3; // Número máximo de reintentos permitidos
        $retries = 0; // Contador de reintentos
        do {
            $responseInvoice = createInvoice($invoiceJsonPayload,$authToken);
            mail('soporte@atsys.co','$responseInvoice',var_export($responseInvoice,true));
            echo "<script>console.log(' respuesta creacion factura: " . addslashes(json_encode($responseInvoice)) . " ');</script>";
            $codigoValidacion = validarRespuestaSiigo($responseInvoice);
            echo "<script>console.log('codigoValidacion factura: " . $codigoValidacion. "');</script>";
            switch ($codigoValidacion) {
                case 0:
                    // Respuesta exitosa
                    // Realizar acciones correspondientes
                    echo "<script>console.log('Respuesta factura: " . addslashes(json_encode($responseInvoice)) . "');</script>";
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
                        $errorMessage = "Error al crear la factura: " .$invoiceId ."  -  " .  addslashes(json_encode($responseInvoice));
                        writeLog($errorMessage);
                        $updateResult = setInvoiceStatus($link, 'Error', $lastUpdate, $errorMessage, $invoiceId);
                        break 2; // Salir del bucle actual y continuar con la siguiente factura
                    }
                    break;
                case 2:
                    // Error 400 sin reintento
                    echo "<script>console.log('Error general 4xx');</script>";
                    $lastUpdate = date("Y-m-d H:i:s");
                    $errorMessage = "Error al crear la factura: " .$invoiceId ."  -  " . addslashes(json_encode($responseInvoice));
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
                    echo "<script>console.log('Respuesta siigo creacion factura: " . $logMessageSiigoClient . " For: " . $datosClienteFactura[1] . " In: " . $invoiceId . " Fila Afectada " . $updateResult . "');</script>";
                    break;
            }
        } while ($retries < $max_retries);  
    }
    writeLog("Fin de ronda");


?>