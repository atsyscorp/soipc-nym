<?php
    // Función para escribir logs en un archivo
    function writeLog($message)
    {
        $logFile = 'logs.txt'; // Nombre del archivo de logs
        $timestamp = date('[Y-m-d H:i:s]'); // Fecha y hora actual

        // Formato del mensaje de log: [Fecha y hora] Mensaje
        $logMessage = $timestamp . ' ' . $message . PHP_EOL;

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
