<?php
    // Asegúrate de incluir las mismas dependencias que el archivo original
    include("General.php");

    // Conectarse a la base de datos
    $link = Conectarse();

    // Simula la llamada a descargar_y_procesar.php para descargar y procesar la lista
    // Puedes usar la función file_get_contents o similar
    $resultadoDescarga = file_get_contents('https://www.soipcnym.com/descargar_y_procesar.php');

    // Verifica si la descarga fue exitosa
    if ($resultadoDescarga === FALSE) {
        echo "Error al descargar y procesar la lista";
        exit;
    } else {
        echo "Lista descargada y procesada correctamente";
    }

    // Llama a la lógica de Update_List (simula la carga de frCumpClinton_1.php)
    $usuario = 'CRON';  // Ajusta esto según lo que necesites
    $updateUrl = 'https://www.soipcnym.com/frCumpClinton_1.php?var1=' . $usuario;
    $resultadoUpdate = file_get_contents($updateUrl);

    // Verifica si la actualización fue exitosa
    if ($resultadoUpdate === FALSE) {
        echo "Error al actualizar la lista";
    } else {
        echo "Lista actualizada correctamente";
    }

    // Cierra la conexión
    mysqli_close($link);
?>
