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

$sql = "SELECT P.id, A.idCert , SUBSTRING_INDEX(P.Nombre, ' ', 1) AS NombreP, SUBSTRING_INDEX(P.Apellido, ' ', 1) AS ApellidoP, A.Certificado AS CertificadoA
        FROM archivos A
        INNER JOIN pdf P
        WHERE P.id=$fileId
        AND P.id=A.idCert";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fileName = $row["NombreP"];
    $fileApellido= $row["ApellidoP"];
    $fileContent = $row["CertificadoA"];
 

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Certificado'. $fileName. ' ' .$fileApellido.  '.pdf"');
    echo $fileContent;
    exit();
} else {
    echo "Archivo no encontrado.";
    exit();
}

$conn->close();
?>
