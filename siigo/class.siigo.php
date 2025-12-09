<?php

require_once dirname(__FILE__) . '/../inc/params.php';

class Siigo {

    public $env = 'prod'; // prod or dev
    public $userAgent = 'SOIPC/1.3 Mozilla/5.0 Darwin/24.4.0';
	public $token_url = 'https://integrations.siigo.com/auth/connect/token';
    public $token;
    public $log_table = 'Error_Log';
    public $queryLimit = 20;

    public $mysqli;
    public $auth = [
        'prod' => [
            'username' => 'gerencia@newyorkmoney.com.co',
            'access_key' => 'ZWUxMjBhOTYtOGVjNC00OWM1LTk0NDAtOTE3NGQyOTAzNjU3OipqNmdYc3tCdEY='
        ],
        'dev' => [
            'username' => 'test',
            'access_key' => 'test'
        ]
    ];
    
    public $docCodes = [
        'A01' => '29515',
        'S82' => '29516',
        'CA2' => '29517',
        'U18' => '29514',
        'U47' => '29512',
        'G20' => '29513',
        'A48' => '29363',
    ];

    public $paymentMeansCodes = [
        'EFECTIVO' => '5464',
        'TARJETA CRÉDITO' => '5467',
        'TARJETA DÉBITO' => '5466',
        'TRANSFERENCIA' => '6442'
    ];

    public $paymentMeansCode = [
        'EFECTIVO' => [
            'A01' => '8436',
            'S82' => '8437',
            'CA2' => '8438',
            'U18' => '8435',
            'U47' => '2314',
            'G20' => '8433',
            'A48' => '8434'
        ],
        'BANCOS' => '8470',
        'TARJETA CRÉDITO' => '5467',
        'TARJETA DÉBITO' => '5466',
        'TRANSFERENCIA' => '6442'
    ];

    // Productos - Monedas
    public $currencyProducts = [
        'USD' => [
            'Code' => '01',
            'Name' => 'USD-DOLAR AMERICANO',
            'Id' => '20045'
        ],
        'GBP' => [
            'Code' => '02',
            'Name' => 'GBP-LIBRA ESTERLINA',
            'Id' => '20050'
        ],
        'EUR' => [
            'Code' => '03',
            'Name' => 'EUR-EURO',
            'Id' => '20057'
        ],
        'DOP' => [
            'Code' => '04',
            'Name' => 'DOP-PESO DOMINICANO',
            'Id' => '20058'
        ],
        'BRL' => [
            'Code' => '05',
            'Name' => 'BRL-REAL BRASIL',
            'Id' => '20059'
        ],
        'BOB' => [
            'Code' => '06',
            'Name' => 'BOB-BOLIVIANO',
            'Id' => '20060'
        ],
        'CAD' => [
            'Code' => '07',
            'Name' => 'CAD-DOLAR CANADIENSE',
            'Id' => '20061'
        ],
        'CHF' => [
            'Code' => '08',
            'Name' => 'CHF-FRANCO SUIZO',
            'Id' => '20062'
        ],
        'CLP' => [
            'Code' => '09',
            'Name' => 'CLP-PESO CHILENO',
            'Id' => '20063'
        ],
        'CNY' => [
            'Code' => '010',
            'Name' => 'CNY-YUAN CHINO',
            'Id' => '20064'
        ],
        'COP' => [
            'Code' => '011',
            'Name' => 'COP-PESO COLOMBIANO',
            'Id' => '20065'
        ],
        'UYU' => [
            'Code' => '012',
            'Name' => 'UYU-PESO URUGUAYO',
            'Id' => '20066'
        ],
        'AWG' => [
            'Code' => '013',
            'Name' => 'AWG-FLORIN ARUBA',
            'Id' => '20067'
        ],
        'PYG' => [
            'Code' => '014',
            'Name' => 'PYG-GUARANI PARAGUAYO',
            'Id' => '20068'
        ],
        'PEN' => [
            'Code' => '015',
            'Name' => 'PEN-SOL PERUANO',
            'Id' => '20070'
        ],
        'MXN' => [
            'Code' => '016',
            'Name' => 'MXN-PESO MEXICANO',
            'Id' => '20071'
        ],
        'JPY' => [
            'Code' => '017',
            'Name' => 'JPY-YEN JAPONES',
            'Id' => '20072'
        ],
        'GTQ' => [
            'Code' => '018',
            'Name' => 'GTQ-QUETZAL GUATEMALTECO',
            'Id' => '20073'
        ],
        'ARS' => [
            'Code' => '019',
            'Name' => 'ARS-PESO ARGENTINO',
            'Id' => '20074'
        ],
        'CRC' => [
            'Code' => '020',
            'Name' => 'CRC-COLON COSTARICA',
            'Id' => '22340'
        ],
        'AUD' => [
            'Code' => '021',
            'Name' => 'AUD-DOLAR AUSTRALIANO',
            'Id' => '22341'
        ],
        'ANG' => [
            'Code' => '022',
            'Name' => 'ANG-FLORIN ANTILLANO',
            'Id' => '22342'
        ]
    ];

    public function __construct() 
    {
        global $db;
        $this->mysqli = new \mysqli($db['host'], $db['user'], $db['pass'], $db['db']);
        if ($this->mysqli->connect_errno) {
            trigger_error("Fallo al conectar a MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error);
        }

        $this->token = $this->getToken();
        return $this->mysqli;
    }

    public function getToken() {

        $url = 'https://api.siigo.com/auth';
        $data = [
            'username' => $this->auth[$this->env]['username'],
            'access_key' => $this->auth[$this->env]['access_key']
        ];
        $data_json = json_encode($data);

        $ch = curl_init();
        $opt = [
            CURLOPT_URL             => $url,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $data_json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_json)
            ],
            CURLOPT_SSL_VERIFYPEER => false
        ];

        curl_setopt_array($ch, $opt);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            return null;
        } else {
            $response_arr = json_decode($response, true);
            return is_array($response_arr) ? $response_arr['access_token'] : null;
        }

    }

    public function request($endpoint, $method, $payload = NULL, $headers=[]) {
        $baseURL ='https://api.siigo.com/v1/';
        $url = $baseURL . $endpoint;

        $_headers = array_merge(
            $headers,
            [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token,
                'Partner-ID: SOIPCNYM'
            ]
        );

        $ch = curl_init($url);
        $payload = json_encode($payload);

        switch ($method) {
            case 'GET':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            break;
            case 'POST':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            break;
            case 'PATCH':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            break;
            default:
                // Método no soportado
                return false;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            return $errorMessage;
        }

        curl_close($ch);
        return $response;
    }

    public function validateAnswer($answer) {
        if (isset($answer['Status']) && isset($answer['Errors'])) {
            $status = $answer['Status'];

            if ($status == 429) {
                return 1;  // Try again
            } elseif ($status == 400 && ($answer['Errors'][0]['Code'] === 'already_exists' || $answer['Errors'][0]['Code'] === 'duplicated_document') ) {
                return 4;  // "already_exist"
            } elseif ($status >= 400 && $status <= 415) {
                return 2;  // Between 400 and 415, make addon validate
            } elseif ($status >= 500) {
                return 3;  // Error 500
            }
        } else {
            return 0;  // Successful answer, no errors and status
        }

        return 2;  // In case if doesn't accomplish any of the above, must error is in 400 and 415
    }

    public function getInvoice($invoice_id, $onlyQr=false) {
        $endPoint = ($onlyQr) ? 'invoices/'.$invoice_id.'/qr': 'invoices/'.$invoice_id;
        $response = $this->request($endPoint, "GET");
        $res = json_decode($response, true);
        return $res;
    }

    public function createInvoice($invoiceData) {

        $initialkey = 'NYM'.$this->token.time().rand(10000,999999);
        $idempotencyKey = substr(md5($initialkey),0, 30);
        $headers = ['Idempotency-Key: ' . $idempotencyKey];
        return $this->request('invoices', 'POST', json_encode($invoiceData), $headers);        

    }

    public function setName($name) {
        $parts = explode(" ", $name);
        $lastname = array_pop($parts);
        if (count($parts) > 0) {
            $possibleSecondSurname = end($parts);
            if (strlen($possibleSecondSurname) <= 3) {
                $lastname = $possibleSecondSurname . " " . $lastname;
                array_pop($parts);
            }
        }
        $names = implode(" ", $parts);
        
        return [
            'name' => $names,
            'lastname' => $lastname
        ];
    }

    public function setLineInLog($message, $type='info', $isClient=false) {
        if($isClient) {
            echo '<script>console.'.$type.'(\''.$message.'\');</script>';
        }
        return $this->mysqli->query("INSERT INTO $this->log_table SET Tipo='$type', Mensaje='$message', Fecha=NOW(), Iniciador='".$_SERVER['PHP_SELF']."'");
    }

    // Get pending invoices
    public function getPendingInvoiceIds($isBuy = false){
        $table = $isBuy ? 'Factura_Electronica_Compras': 'Factura_Electronica';
        $getInvoiceIdQuery = "SELECT InvoiceId FROM $table WHERE SendProcess='Crear' 
        AND SendStatus = 'Por Enviar' ORDER BY RowId ASC LIMIT " . $this->queryLimit;
        $getInvoiceIdStatement = $this->mysqli->query($getInvoiceIdQuery);

        if (!$getInvoiceIdStatement) {
            $errorMessage = "[getPendingInvoiceIds]: Error en la ejecución de la consulta: " . $this->mysqli->error;
            $this->setLineInLog($errorMessage, 'Error');
            $this->mysqli->close();
            return false;
        }

        $invoiceIds = [];

        while ($invoice = mysqli_fetch_assoc($getInvoiceIdStatement)) {
            $invoiceIds[] = $invoice['InvoiceId'];
        }

        if (empty($invoiceIds)) {
            $errorMessage = "[getPendingInvoiceIds]: Lo siento, ha ocurrido un error obteniendo datos.";
            $this->setLineInLog($errorMessage, 'Error');
            $this->mysqli->close();
            exit();
        }

        $getInvoiceIdStatement->free_result();

        return $invoiceIds;
    }

    public function getCounterOperation($invoiceId) {

        $statement = $this->mysqli->prepare("SELECT Identificacion, Sucursal, Fecha, Consecutivo, Documento_Beneficiario, 
        Documento_Declarante, Moneda, Precio_Sin_Iva, Precio_Con_Iva, Cantidad, Valor, IVA, Rete_Fuente, Rete_ICA, Rete_IVA, Caja_Nacional, 
        Medio_Pago, Instrumento, Telefono, Nombre_Declarante, Nombre_Completo 
        FROM Operacion_Ventanilla WHERE Identificacion = ?");

        if (!$statement) {
            $errorMessage = "[getOpVentanillaData]: Error en la preparación de la consulta: " . $this->mysqli->error;
            $this->setLineInLog($errorMessage, 'error');
            $this->mysqli->close();
            return false;
        }

        $statement->bind_param('s', $invoiceId);
        $statement->execute();

        if ($statement->errno) {
            $errorMessage = "[getOpVentanillaData]: Error en la ejecución de la consulta: " . $statement->error;
            $this->setLineInLog($errorMessage, 'error');
            $this->mysqli->close();
            return false;
        }

        $opVentanillaData = $statement->get_result();
        $datosFactura = $opVentanillaData->fetch_array();

        $statement->close();

        return $datosFactura;
    }

    public function getClient($identification) {
        $statement = $this->mysqli->prepare("SELECT * FROM Clientes WHERE Identificacion = ?");

        if (!$statement) {
            $errorMessage = "[getClienteData]: Error en la preparación de la consulta: " . $this->mysqli->error;
            $this->setLineInLog($errorMessage, 'error');
            $this->mysqli->close();
            return false;
        }

        $statement->bind_param('s', $identification);
        $statement->execute();

        if ($statement->errno) {
            $errorMessage = "[getClienteData]: Error en la ejecución de la consulta: " . $statement->error;
            $this->setLineInLog($errorMessage, 'error');
            $statement->close();
            return false;
        }

        $clienteData = $statement->get_result();
        $dataPerson = $clienteData->fetch_array();

        $statement->close();

        return $dataPerson;
    }

    public function apiCreateClient($clientJsonPayload) {
        $endpoint = "customers";

        $response = $this->request($endpoint, 'POST', $clientJsonPayload);
        $responseArray = json_decode($response, true);

        return $responseArray;
    }

    public function getCityCode($city, $dept){
        $statement = $this->mysqli->prepare("SELECT Codigo FROM XConf_Ciudades WHERE Ciudad=? AND Departamento=?");

        if (!$statement) {
            $errorMessage = "[getCodigoCiudad]: Error en la preparación de la consulta: " . $this->mysqli->error;
            $this->setLineInLog($errorMessage, 'error');
            $this->mysqli->close();
            return false;
        }

        $statement->bind_param('ss', $city, $dept);
        $statement->execute();

        if ($statement->errno) {
            $errorMessage = "[getCodigoCiudad]: Error en la ejecución de la consulta: " . $statement->error;
            $this->setLineInLog($errorMessage, 'error');
            $statement->close();
            return false;
        }

        $codigoCiudadData = $statement->get_result();
        $codigoCiudad = $codigoCiudadData->fetch_array();

        $statement->close();

        return $codigoCiudad;
    }

    public function getDianCode($type) {

        $statement = $this->mysqli->prepare("SELECT Codigo_Dian FROM XConf_TiposDoc WHERE Tipo_Documento=?");

        if (!$statement) {
            $errorMessage = "[getCodigoDocDian]: Error en la preparación de la consulta: " . $this->mysqli->error;
            $this->setLineInLog($errorMessage, 'error');
            $this->mysqli->close();
            return false;
        }

        $statement->bind_param('s', $type);
        $statement->execute();

        if ($statement->errno) {
            $errorMessage = "[getCodigoDocDian]: Error en la ejecución de la consulta: " . $statement->error;
            $this->setLineInLog($errorMessage, 'error');
            $statement->close();
            return false;
        }

        $codigoDocumentoData = $statement->get_result();
        $codigoDocDian = $codigoDocumentoData->fetch_array();
        $statement->close();

        return $codigoDocDian;

    }

    public function setInvoiceStatus($status, $updateDate, $apiresult, $invoiceId){
        $escapedApiResult = $this->mysqli->real_escape_string($apiresult);

        $statement = $this->mysqli->prepare("UPDATE Factura_Electronica SET SendStatus=?, LastUpdate=?, ApiResponse=? WHERE InvoiceId=?");

        if (!$statement) {
            $errorMessage = "Error en la preparación de la consulta: " . $this->mysqli->error;
            $this->setLineInLog($errorMessage, 'error');
            return false;
        }

        $statement->bind_param('ssss', $status, $updateDate, $escapedApiResult, $invoiceId);
        $statement->execute();

        if ($statement->errno) {
            $errorMessage = "Error en la ejecución de la consulta: " . $statement->error;
            $this->setLineInLog($errorMessage, 'error');
            $statement->close();
            return false;
        }

        $numRowsAffected = $statement->affected_rows;
        $statement->close();
        return $numRowsAffected;
    }

    public function setPurchaseInvoice($id) {
        // Get operation
        $operation = $this->getCounterOperation($id);

        // Get exchange
        $exchangeQuery = $this->mysqli->query("SELECT Precio_Compra FROM Tasas WHERE Moneda='".$operation['Moneda']."' AND Sucursal='".$operation['Sucursal']."'");
        $exchangeData = $exchangeQuery->fetch_array();

        $payload = [
            'document' => [
                'id' => $operation['Sucursal']
            ],
            'date' => $operation['Fecha'],
            'supplier' => [
                'identification' => $operation['Documento_Declarante'],
                'branch_office' => 0
            ],
            'cost_center' => 235,
            'provider_invoice' => [
                'prefix' => 'NYM-FC1',
                'number' => $operation['Sucursal']
            ],
            'currency' => [
                'code' => $operation['Moneda'],
                'exchange_rate' => $exchangeData['Precio_Compra']
            ],
            'observations' => 'Factura de compra',
            'discount_type' => 'Value',
            'supplier_by_item' => false,
            'tax_included' => false,
            'items' => [

            ],
            'payments' => [

            ]
        ];

        $request = $this->request('purchases', 'POST', json_encode($payload));
        $responseArray = json_decode($request, true);

        return $responseArray;

        /*
        {
            
            "currency": {
                "code": "USD",
                "exchange_rate": 3825.03
            },
            "observations": "Observaciones",
            "discount_type": "Value",
            "supplier_by_item": false,
            "tax_included": false,
            "items": [
                {
                "type": "Product",
                "code": "Item-1",
                "description": "Camiseta de algodón",
                "quantity": 1,
                "price": 1069.77,
                "discount": 0.0,
                "taxes": [
                    {
                    "id": 13156
                    }
                ]
                }
            ],
            "payments": [
                {
                "id": 5636,
                "value": 1273.03,
                "due_date": "2021-03-19"
                }
            ]
            }
        */

    }

    public function apiCheckClient($identification) {
        $endpoint = 'customers';
        $queryParams = http_build_query(['identification' => $identification]);
        $url = $endpoint . '?' . $queryParams;
    
        $response = $this->request($url, 'GET');
        $responseArray = json_decode($response, true);

        return $responseArray;
    }

    public function apiUpdateClient($idSiigo, $updateData) {
        $endpoint = "customers/$idSiigo";
    
        $response = $this->request($endpoint, 'PUT', $updateData);
        $responseArray = json_decode($response, true);

        return $responseArray;
    }

}