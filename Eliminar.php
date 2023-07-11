<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$fileId = $_GET['id']; // ID del archivo PDF a eliminar

$sql = "SELECT id, SUBSTRING_INDEX(nombre, ' ', 1) AS Nombre, SUBSTRING_INDEX(apellido, ' ', 1) AS Apellido, fecha FROM pdf WHERE id = $fileId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row["id"];
    $fileName = $row["Nombre"];
    $fileApellido = $row["Apellido"];
    $Nombre = $fileName . ' ' . $fileApellido;
    $fecha = $row['fecha'];
    $Estado = "Rechazado";

    //Enviar datos de registro diario
    $sqlInsert = "INSERT INTO registroDiario (Solicitante, fecha, Estado) VALUES('$Nombre', '$fecha', '$Estado')";
    if (mysqli_query($conn, $sqlInsert)) {
        // La inserción fue exitosa, ahora procedemos con la eliminación
        $sqlDelete = "DELETE FROM pdf WHERE id = $fileId";
        if (mysqli_query($conn, $sqlDelete)) {
            header("Location: Solicitudes.php");
        } else {
            echo "Error al eliminar el dato: " . mysqli_error($conn);
        }
    } else {
        echo "Error al insertar el registro diario: " . mysqli_error($conn);
    }
} else {
    echo "No se encontró el registro con el ID proporcionado.";
}

?>