<?php
$apiMode = 'production'; //test | production
$createClientPayloadJson = "";
$link = null;

require '../inc/params.php';

if ($apiMode == 'production') {
    // Conexión a la base de datos de producción
    $link = mysqli_connect($db['host'],$db['user'],$db['pass'],$db['db']);
} else {
    // Conexión a la base de datos de prueba
    $link = mysqli_connect("localhost", "root", "", "nym_soipc");
}

// Verificar si hubo un error en la conexión
if (!$link) {
    die("Error conectando a la base de datos.");
}

// Realizar la consulta SQL
$query = "SELECT c.Documento, c.Tipo_Documento, ov.Telefono, c.Direccion, c.EMail, fe.InvoiceId, fe.SendStatus, fe.ApiResponse, fe.LastUpdate 
FROM Clientes AS c 
INNER JOIN Operacion_Ventanilla AS ov ON c.Documento = ov.Documento_Beneficiario 
INNER JOIN Factura_Electronica AS fe ON fe.InvoiceId = ov.Identificacion 
WHERE fe.SendStatus <> 'Enviada' AND c.EMail NOT REGEXP '^.+@.+\..{2,}$' ORDER BY fe.RowId ASC LIMIT 250";

$result = mysqli_query($link, $query);

// Verificar si se obtuvieron resultados
if (mysqli_num_rows($result) > 0) {
    // Crear la tabla y mostrar los encabezados
    echo "<table style='border-collapse: collapse;'>";
    echo "<tr><th style='border: 1px solid black;'>Documento</th><th style='border: 1px solid black;'>Tipo de Documento</th><th style='border: 1px solid black;'>Teléfono</th><th style='border: 1px solid black;'>Dirección</th><th style='border: 1px solid black;'>Email</th><th style='border: 1px solid black;'>InvoiceId</th><th style='border: 1px solid black;'>ApiResponse</th><th style='border: 1px solid black;'>SendStatus</th><th style='border: 1px solid black;'>LastUpdate</th></tr>";

    // Recorrer los resultados y mostrar los datos en la tabla
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td style='border: 1px solid black;'>" . $row['Documento'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['Tipo_Documento'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['Telefono'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['Direccion'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['EMail'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['InvoiceId'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['ApiResponse'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['SendStatus'] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row['LastUpdate'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No se encontraron resultados.";
}

// Cerrar la conexión a la base de datos
mysqli_close($link);
?>
