<?php
    require_once 'logger.php';
    require_once '../inc/params.php';

    function connectToDatabase(){
        global $db;
        $apiMode = 'production'; // Modo de la API: test | production

        // Configuración de la base de datos para los modos de producción y prueba
        $databaseConfig = array(
            'production' => array(
                'host' => $db['host'],
                'username' => $db['user'],
                'password' => $db['pass'],
                'database' => $db['db']
            ),
            'test' => array(
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'nym_soipc'
            )
        );

        $link = null; // Variable para almacenar la conexión

        if ($apiMode == 'production') {
            // Configuración de la base de datos en modo producción
            $config = $databaseConfig['production'];
        } else {
            // Configuración de la base de datos en modo prueba
            $config = $databaseConfig['test'];
        }

        // Establecer la conexión a la base de datos
        $link = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

        if (!$link) {
            // Si hay un error en la conexión, se muestra un mensaje, se establece $link en null y se detiene la ejecución del programa
            $errorMessage = "Error conectando a la base de datos: " . mysqli_connect_error();
            writeLog($errorMessage);
            exit(); // Detener la ejecución del programa
        }

        // Establecer la codificación de caracteres en UTF-8
        mysqli_set_charset($link, "utf8");

        return $link; // Se devuelve la conexión (puede ser null en caso de error)
    }

    function getPendingInvoiceIds($link){
        $invoiceQueryLimit = '20';
        $getInvoiceIdQuery = "SELECT InvoiceId FROM Factura_Electronica WHERE SendProcess = 'Crear' AND SendStatus = 'Por Enviar' ORDER BY RowId ASC LIMIT " . $invoiceQueryLimit;
        $getInvoiceIdStatement = mysqli_query($link, $getInvoiceIdQuery);

        if (!$getInvoiceIdStatement) {
            $errorMessage = "[getPendingInvoiceIds]: Error  en la ejecución de la consulta: " . mysqli_error($link);
            writeLog($errorMessage);
            mysqli_close($link);
            exit();
        }

        $invoiceIds = array();

        while ($invoice = mysqli_fetch_assoc($getInvoiceIdStatement)) {
            $invoiceIds[] = $invoice['InvoiceId'];
        }

        if (empty($invoiceIds)) {
            $errorMessage = "[getPendingInvoiceIds]: Lo siento, ha ocurrido un error obteniendo datos.";
            writeLog($errorMessage);
            mysqli_close($link);
            exit();
        }

        mysqli_free_result($getInvoiceIdStatement);

        return $invoiceIds;
    }

    function getOpVentanillaData($link, $invoiceId){
        $opVentanillaQuery = "SELECT Identificacion, Sucursal, Fecha, Consecutivo, Documento_Beneficiario, Documento_Declarante, Moneda, Precio_Sin_Iva, Precio_Con_Iva, Cantidad, Valor, IVA, Rete_Fuente, Rete_ICA, Rete_IVA, Caja_Nacional, Medio_Pago, Instrumento, Telefono, Nombre_Declarante, Nombre_Completo FROM Operacion_Ventanilla WHERE Identificacion = ?";
        $statement = mysqli_prepare($link, $opVentanillaQuery);

        if (!$statement) {
            $errorMessage = "[getOpVentanillaData]: Error en la preparación de la consulta: " . mysqli_error($link);
            writeLog($errorMessage);
            mysqli_close($link);
            exit();
        }

        mysqli_stmt_bind_param($statement, 's', $invoiceId);
        mysqli_stmt_execute($statement);

        if (mysqli_stmt_errno($statement)) {
            $errorMessage = "[getOpVentanillaData]: Error en la ejecución de la consulta: " . mysqli_stmt_error($statement);
            writeLog($errorMessage);
            mysqli_close($link);
            exit();
        }

        $opVentanillaData = mysqli_stmt_get_result($statement);
        $datosFactura = mysqli_fetch_array($opVentanillaData);

        mysqli_stmt_close($statement);

        return $datosFactura;
    }

    function getClienteData($link, $documentoCliente){
        $clientesDataQuery = "SELECT * FROM Clientes WHERE Identificacion = ?";
        $statement = mysqli_prepare($link, $clientesDataQuery);

        if (!$statement) {
            $errorMessage = "[getClienteData]: Error en la preparación de la consulta: " . mysqli_error($link);
            writeLog($errorMessage);
            mysqli_close($link);
            exit();
        }

        mysqli_stmt_bind_param($statement, 's', $documentoCliente);
        mysqli_stmt_execute($statement);

        if (mysqli_stmt_errno($statement)) {
            $errorMessage = "[getClienteData]: Error en la ejecución de la consulta: " . mysqli_stmt_error($statement);
            writeLog($errorMessage);
            mysqli_stmt_close($statement);
            mysqli_close($link);
            exit();
        }

        $clienteData = mysqli_stmt_get_result($statement);
        $dataPerson = mysqli_fetch_array($clienteData);

        mysqli_stmt_close($statement);

        return $dataPerson;
    }

    function getCodigoCiudad($link, $ciudad, $departamento){
        $queryCodigoCiudad = "SELECT Codigo FROM XConf_Ciudades WHERE Ciudad=? AND Departamento=?";
        $statement = mysqli_prepare($link, $queryCodigoCiudad);

        if (!$statement) {
            $errorMessage = "[getCodigoCiudad]: Error en la preparación de la consulta: " . mysqli_error($link);
            writeLog($errorMessage);
            mysqli_close($link);
            exit();
        }

        mysqli_stmt_bind_param($statement, 'ss', $ciudad, $departamento);
        mysqli_stmt_execute($statement);

        if (mysqli_stmt_errno($statement)) {
            $errorMessage = "[getCodigoCiudad]: Error en la ejecución de la consulta: " . mysqli_stmt_error($statement);
            writeLog($errorMessage);
            mysqli_stmt_close($statement);
            mysqli_close($link);
            exit();
        }

        $codigoCiudadData = mysqli_stmt_get_result($statement);
        $codigoCiudad = mysqli_fetch_array($codigoCiudadData);

        mysqli_stmt_close($statement);

        return $codigoCiudad;
    }

    function getCodigoDocDian($link, $tipoDocumento){
        $queryGetCodigoDocumento = "SELECT Codigo_DIAN FROM XConf_TiposDoc WHERE Tipo_Documento=?";
        $statement = mysqli_prepare($link, $queryGetCodigoDocumento);

        if (!$statement) {
            $errorMessage = "[getCodigoDocDian]: Error en la preparación de la consulta: " . mysqli_error($link);
            writeLog($errorMessage);
            mysqli_close($link);
            exit();
        }

        mysqli_stmt_bind_param($statement, 's', $tipoDocumento);
        mysqli_stmt_execute($statement);

        if (mysqli_stmt_errno($statement)) {
            $errorMessage = "[getCodigoDocDian]: Error en la ejecución de la consulta: " . mysqli_stmt_error($statement);
            writeLog($errorMessage);
            mysqli_stmt_close($statement);
            mysqli_close($link);
            exit();
        }

        $codigoDocumentoData = mysqli_stmt_get_result($statement);
        $codigoDocDian = mysqli_fetch_array($codigoDocumentoData);

        mysqli_stmt_close($statement);

        return $codigoDocDian;
    }

    function setInvoiceStatus($link, $status, $updateDate, $apiresult, $invoiceId){
        $escapedApiResult = mysqli_real_escape_string($link, $apiresult);

        $query = "UPDATE Factura_Electronica SET SendStatus=?, LastUpdate=?, ApiResponse=? WHERE InvoiceId=?";
        $statement = mysqli_prepare($link, $query);

        if (!$statement) {
            $errorMessage = "Error en la preparación de la consulta: " . mysqli_error($link);
            writeLog($errorMessage);
            return null;
        }

        mysqli_stmt_bind_param($statement, 'ssss', $status, $updateDate, $escapedApiResult, $invoiceId);
        mysqli_stmt_execute($statement);

        if (mysqli_stmt_errno($statement)) {
            $errorMessage = "Error en la ejecución de la consulta: " . mysqli_stmt_error($statement);
            writeLog($errorMessage);
            mysqli_stmt_close($statement);
            mysqli_close($link);
            exit();
        }

        $numRowsAffected = mysqli_stmt_affected_rows($statement);

        mysqli_stmt_close($statement);

        return $numRowsAffected;
    }
?>
