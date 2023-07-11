<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$fileId = $_GET['id']; // ID del archivo PDF a descargar

$sql = "SELECT id, SUBSTRING_INDEX(nombre, ' ', 1) AS Nombre, SUBSTRING_INDEX(apellido, ' ', 1) AS Apellido, contenido FROM pdf WHERE id = $fileId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fileName = $row["Nombre"];
    $fileApellido= $row["Apellido"];
    $fileContent = $row["contenido"];
 

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Solicitud ' . $fileName. ' ' .$fileApellido. '.pdf"');
    echo $fileContent;
    exit();
} else {
    echo "Archivo no encontrado.";
    exit();
}

$conn->close();
?>
