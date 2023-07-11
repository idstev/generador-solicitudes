<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";

// Conexión a la base de datos
$tarj = $_POST['tarj'];
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si se ha seleccionado un archivo adjunto
if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
    // Obtener el contenido del archivo adjunto
    $pdfContent = file_get_contents($_FILES['pdf']['tmp_name']);
    $pdfContent = $conn->real_escape_string($pdfContent);

    // Insertar el archivo adjunto en la base de datos
    $sql = "INSERT INTO archivos (idCert, NombreA, Certificado) VALUES ('$tarj', '" . $_FILES['pdf']['name'] . "', '$pdfContent')";
    if ($conn->query($sql) === true) {
        echo $_SERVER['Solicitud.html'];
    } else {
        echo "Error al guardar el archivo PDF en la base de datos: " . $conn->error;
    }
} else {
    echo "Error al subir el archivo PDF.";
}

$conn->close();
?>