<?php

    // Función para insertar factura para integración SIIGO
    function SaveInErrorLog($Tipo, $Mensaje, $Iniciador) {
        $link = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['db']);
        $sql = "INSERT INTO Error_Log SET `Tipo`=?, `Mensaje`=?, Iniciador=?, Fecha=NOW()";
        $stmt = mysqli_prepare($link, $link);

        mysqli_stmt_bind_param($stmt, "sss", $Tipo, $Mensaje, $Iniciador);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Get the ID
        $lastId = mysqli_insert_id($link);

        return [
            'completed' => true,
            'errorId' => $lastId
        ];
    }

    // Función para escribir logs en un archivo
    function writeLog($message)
    {
        $logFile = 'logs.txt'; // Nombre del archivo de logs
        $timestamp = date('[Y-m-d H:i:s]'); // Fecha y hora actual

        // Formato del mensaje de log: [Fecha y hora] Mensaje
        $logMessage = $timestamp . ' ' . $message . PHP_EOL;

        // Establecer conexión con la base de datos para registrar el error
        $eLog = SaveInErrorLog('info', $message, $_SERVER['PHP_SELF']);

        // Abre el archivo en modo append (agregar contenido al final)
        $file = fopen($logFile, 'a');

        if ($file) {
            // Escribe el mensaje en el archivo
            fwrite($file, $logMessage);
            fclose($file);
        }
        // Imprime el mensaje en la consola
        echo "<script>console.log('".$message."');</script>";
    }

    // Función para limpiar el archivo de logs si es el primer día del mes
    function cleanLogFile()
    {
        $logFile = 'logs.txt'; // Nombre del archivo de logs
        $currentDay = date('j'); // Día actual

        if ($currentDay === '1') {
            // Si es el primer día del mes, borra el archivo de logs
            file_put_contents($logFile, '');
        }
    }
?>
