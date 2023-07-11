<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";
// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Obtener el último archivo adjunto de la base de datos
$sql = "SELECT * FROM archivos ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $filename = $row['Nombre'];
    $filecontent = $row['Contenido'];

    // Descargar el archivo adjunto
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $filecontent;
} else {
    echo "No se encontró ningún archivo adjunto en la base de datos.";
}

$conn->close();
?>
