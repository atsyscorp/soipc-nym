<?php 
// ARCHIVO SUBIDA IMAGENES FROPSCLIENTES
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
// header('Content-Type: text/html; charset=utf-8'); // No es necesario si solo retornamos texto/código.
include("../General.php");

// 1. CAPTURA DE VARIABLES
$imClient = (isset($_GET['Client'])) ? $_GET['Client'] : ''; // Identificacion del Cliente (del querystring)
$mensaje = ''; 
$link = Conectarse(); // Asume que esta función retorna la conexión DB.
$upload_dir = "../Fotos/"; // Ruta de la carpeta de fotos.

// 2. OBTENER DATA RAW DE WEBCAM.JS
// Webcam.js envía la imagen Base64, la buscamos en el data POST.
$raw_data = file_get_contents("php://input"); // Método moderno y recomendado para data raw
$base64_img = '';

if ($raw_data) {
    // Si la data es recibida en el cuerpo (típico cuando se usa el método 'POST')
    // Asumimos que la data Base64 viene sin el prefijo 'data:image/...' si usamos Webcam.upload() correctamente.
    $base64_img = $raw_data;
} elseif (isset($_POST['webcam'])) {
    // Intenta capturar si viene en una variable 'webcam' (menos común con Webcam.upload)
    $base64_img = $_POST['webcam'];
}

// 3. PROCESAR LA IMAGEN BASE64
// La data recibida de Webcam.js SÍ incluye el prefijo: "data:image/jpeg;base64,..."
if (!empty($base64_img)) {
    // Limpia el prefijo Base64 (ej. 'data:image/jpeg;base64,')
    $base64_parts = explode(',', $base64_img);
    if (count($base64_parts) > 1) {
        $base64_data = $base64_parts[1];
    } else {
        $base64_data = $base64_img;
    }

    $decoded_image = base64_decode($base64_data);
    
    if ($decoded_image === false) {
        // Falló la decodificación Base64
        $mensaje = '0.|.Error de decodificación Base64.';
    } else {
        // Genera el nombre del archivo según el requisito: [ID_CLIENTE]-[YYYYMMDDhhmm]
        $imname = date('YmdHis');
        $newflname = $imClient . "-" . $imname . ".jpg";
        $full_path = $upload_dir . $newflname;

        // 4. GUARDAR LA IMAGEN Y REGISTRAR EN DB (Requisito 1.3)
        if (file_put_contents($full_path, $decoded_image) !== false) {
            // Éxito al guardar el archivo en el servidor.
            
            // Crea registro en base de datos (se usa el nombre del archivo SIN .jpg para Identificacion en la tabla Fotos)
            $Sql = "INSERT INTO Fotos (Identificacion, Documento) VALUES ('" . $imClient . "-" . $imname . "', '" . $imClient . "')";
            mysqli_query($link, $Sql) or die(mysqli_error($link)); 
            
            // Retorna éxito: '1.|.nombre_de_archivo_sin_extension'
            $mensaje = '1.|.' . $imClient . "-" . $imname;
        } else {
            // Falló la escritura en disco
            $mensaje = '0.|.Error al guardar el archivo en el servidor. Revise permisos de carpeta.';
        }
    }
} else {
    // No se recibió la data de la imagen
    $mensaje = '0.|.No se recibió la data de la imagen.';
}

// 5. RESPUESTA FINAL
echo $mensaje;
?>