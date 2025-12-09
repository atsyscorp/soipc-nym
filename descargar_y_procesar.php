<?php
// URL desde donde se descargará el archivo original de la lista clinton
$url = "https://www.treasury.gov/ofac/downloads/sdn.csv";

// Ruta donde se guardará el archivo en tu servidor
$localPath = __DIR__ . "/sFormats/SDNv4.csv";

// Inicializar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3");

$fileContent = curl_exec($ch);

if ($fileContent === false) {
    die("Error al descargar el archivo desde la URL usando cURL: " . curl_error($ch));
}

curl_close($ch);

// Separar el contenido en líneas
$fileContent = explode("\n", $fileContent);

// Eliminar la última fila (con el símbolo raro)
array_pop($fileContent);

// Guardar el contenido restante en el nuevo archivo en el servidor
$fileHandle = fopen($localPath, "w");

if ($fileHandle === false) {
    die("Error al crear el archivo local en el servidor.");
}

foreach ($fileContent as $line) {
    fwrite($fileHandle, $line . PHP_EOL);
}

fclose($fileHandle);

echo "Archivo creado exitosamente en: $localPath";
?>
