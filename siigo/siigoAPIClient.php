<?php
    function siigoRequest($endpoint, $method, $payload, $authToken) {
        //$baseURL = 'private-anon-b1de262d6b-siigoapi.apiary-mock.com/v1/';
        $baseURL ='https://api.siigo.com/v1/';
        $url = $baseURL . $endpoint;
    
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $authToken,
            'Partner-ID: SOIPCNYM'
        );
    
        $ch = curl_init($url);
    
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        $response = curl_exec($ch);
    
        // Verificar si se produjo un error en la solicitud
        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            // Manejar el error de acuerdo a tus necesidades
            echo "<script>console.log('" . $errorMessage . "');</script>";
        }
    
        // Cerrar sesión CURL
        curl_close($ch);
    
        return $response;
    }

    function checkCliente($identification, $authToken) {
        $endpoint = 'customers';
        $queryParams = http_build_query(array('identification' => $identification));
        $url = $endpoint . '?' . $queryParams;
    
        $response = siigoRequest($url, 'GET', null, $authToken);
        $responseArray = json_decode($response, true);
    
        // Verificar si se encontró al cliente
        return $responseArray;
    }
    
    function updateCliente($idSiigo, $updateData, $authToken) {
        $endpoint = "customers/$idSiigo";
    
        $response = siigoRequest($endpoint, 'PUT', $updateData, $authToken);
        $responseArray = json_decode($response, true);
    
        return $responseArray;
    }

    function createCliente($clientJsonPayload, $authToken) {
        $endpoint = "customers";
    
        $response = siigoRequest($endpoint, 'POST', $clientJsonPayload, $authToken);
        $responseArray = json_decode($response, true);
    
        return $responseArray;
    }
    
    function createInvoice($invoiceData, $authToken) {
        $endpoint = 'invoices';
    
        $response = siigoRequest($endpoint, 'POST', $invoiceData, $authToken);
        $responseArray = json_decode($response, true);
    
        return $responseArray;
    }

    function createInvoiceClient($payload,$authToken) {
        // URL de la API de Siigo
        $url = 'https://api.siigo.com/v1/invoices';
        
        // Configuración de la solicitud
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $authToken,
        );
        
        // Iniciar sesión CURL y configurar la solicitud POST
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // Ejecutar la solicitud y obtener la respuesta
        $response = curl_exec($ch);
        
        // Verificar si se produjo un error en la solicitud
        if (curl_errno($ch)) {
            $errorMessage = curl_error($ch);
            // Manejar el error de acuerdo a tus necesidades
            echo "Error en la solicitud: " . $errorMessage;
            return null;
        } else {
            // Convertir la respuesta a un array JSON
            $responseArray = json_decode($response, true);
            
            // Verificar si la solicitud fue exitosa
            if (isset($responseArray['Errors'])) {
                // Manejar el error de acuerdo a tus necesidades
                echo "Error en la creación de la factura: " . $responseArray['Errors'][0]['Message'];
                return $responseArray;
            } else {
                // La factura se creó exitosamente
                return $responseArray;
            }
        }
        
        // Cerrar sesión CURL
        curl_close($ch);
    }
    
    //getSiigoAuthToken optiene el token de atenticacion en siigo
    //para las peticiones del api
    function getSiigoAuthToken() {
        //URL del api de get token
        $url = 'https://api.siigo.com/auth';
        //payload get token
        //adicionar datos de produccion NYM
        $env = "production";
        if ($env == "test"){
            $data = array(
                'username' => 'siigoapi@pruebas.com',
                'access_key' => 'OWE1OGNkY2QtZGY4ZC00Nzg1LThlZGYtNmExMzUzMmE4Yzc1Omt2YS4yJTUyQEU='
            );
        }else{
            $data = array(
                'username' => 'gerencia@newyorkmoney.com.co',
                'access_key' => 'ZWUxMjBhOTYtOGVjNC00OWM1LTk0NDAtOTE3NGQyOTAzNjU3OipqNmdYc3tCdEY='
            );
        }
        //convierte el arrray en JsonPayload
        $data_json = json_encode($data);
        //Preparar preticion CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json))
        );
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        $response = curl_exec($ch);
    
        curl_close($ch);
    
        if (!$response) {
            return null;
        } else {
            $response_arr = json_decode($response, true);
            return $response_arr['access_token'];
        }
    }

    function validarRespuestaSiigo($respuesta) {
        if (isset($respuesta['Status']) && isset($respuesta['Errors'])) {
            $status = $respuesta['Status'];
            if ($status == 429) {
                return 1;  // Reintentar la petición
            } elseif ($status == 400 && ($respuesta['Errors'][0]['Code'] === 'already_exists' || $respuesta['Errors'][0]['Code'] === 'duplicated_document') ) {
                return 4;  // Respuesta "already_exist"
            } elseif ($status >= 400 && $status <= 415) {
                return 2;  // Error entre 400 y 415, hacer una validación adicional
            } elseif ($status >= 500) {
                return 3;  // Error 500 o superior
            }
        } else {
            return 0;  // Respuesta exitosa, no contiene status ni errors
        }

        return 2;  // En caso de que no se cumpla ninguna condición anterior, asumir error entre 400 y 415
    }


?>
