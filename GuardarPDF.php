<?php
// Verificar si se ha enviado un archivo
if ($_FILES['adjunto']['error'] === UPLOAD_ERR_OK) {
    // Obtener información del archivo
    $rutaTemporal = $_FILES['adjunto']['tmp_name'];
    $nombreArchivo = $_FILES['adjunto']['name'];

    // Leer el contenido del archivo
    $contenidoPdf = file_get_contents($rutaTemporal);

    // Conectarse a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Solicitudes";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Escapar los datos para evitar inyección de SQL
    $nombreArchivo = $conn->real_escape_string($nombreArchivo);
    $contenidoPdf = $conn->real_escape_string($contenidoPdf);

    // Insertar el archivo en la base de datos
    $sql = "INSERT INTO archivos (nombre, contenido) VALUES ('$nombreArchivo', '$contenidoPdf')";

    if ($conn->query($sql) === false) {
        echo "Error al guardar el archivo en la base de datos: " . $conn->error;
    }

    $conn->close();
} else {
    echo "No se ha adjuntado ningún archivo";
}
?>