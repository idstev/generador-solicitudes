<?php

require('fpdf185/fpdf.php');

// Obtener los datos del formulario HTML
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$puesto = $_POST['puesto'];
$UAD = $_POST['UAD'];
$lugar = $_POST['lugar'];
$motivo = $_POST['motivo'];
$asunto = $_POST['asunto'];
$dias = $_POST['dias'];
$tarj= $_POST['tarj'];
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];

// Crear un nuevo objeto FPDF
$pdf = new FPDF();

// Agregar una página al PDF
$pdf->AddPage();

// Obtener las dimensiones de la página
$pageWidth = $pdf->GetPageWidth();
$pageHeight = $pdf->GetPageHeight();

// Insertar la imagen en el PDF ocupando toda la página
$pdf->Image('Solicitud.jpg', 0, 0, $pageWidth, $pageHeight);

// Conectarse a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT firma FROM funcionarios WHERE idFuncionario = '$tarj'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Obtener los datos de la imagen
    $row = $result->fetch_assoc();
    $imageData = $row['firma'];

    // Crear un archivo temporal para la imagen
    $tempFileName = 'temp_image_' . uniqid() . '.jpg';
file_put_contents($tempFileName, $imageData);

$desplazamientoIzquierda = 65;

    // Calcular las dimensiones y posición de la imagen
    $imageWidth = 35; // Ancho de la imagen en puntos
    $imageHeight = 35; // Altura de la imagen en puntos
    $imageX = ($pageWidth - $imageWidth) / 2; // Posición X centrada
    $newImageX = $imageX - $desplazamientoIzquierda;
    $imageY = $pageHeight - $imageHeight - 38; // Posición Y en la parte inferior con un margen de 20 puntos


    // Insertar la imagen en el PDF
    $pdf->Image($tempFileName, $newImageX, $imageY, $imageWidth, $imageHeight);

    // Eliminar el archivo temporal
    unlink($tempFileName);
}

// Establecer la fuente y el tamaño del texto
$pdf->SetFont('Arial', 'B', 12);

// Insertar los datos en el PDF
date_default_timezone_set('America/Guayaquil');
$fecha = date('Y-m-d');
$pdf->MultiCell(180, 23, "$fecha", 0, 'R');
$pdf->SetX(160);
$pdf->SetY(58);
$pdf->Cell(50, 0, "$nombre", 200, 200, 'R');
$pdf->SetX(160);
$pdf->SetY(58);
$pdf->Cell(140, 0, "$apellido", 200, 200, 'R');
$pdf->SetX(160);
$pdf->SetY(70, 'R');
$pdf->cell(140, 0, "$puesto", 200, 200, 'R');
$pdf->SetX(160);
$pdf->SetY(80);
$pdf->cell(128, 0, "$UAD", 200, 200, 'R');
$pdf->SetX(160);
$pdf->SetY(90);
$pdf->cell(140, 0, "$lugar", 200, 200, 'R');
$pdf->SetX(160);
$pdf->SetY(105);
$pdf->cell(140, 0, "$tarj", 200, 200, 'R');
$pdf->SetFontSize(10);
$pdf->SetX(60);
$pdf->SetY(160);
$pdf->cell(0, 0, "$motivo", 0, 60);
$pdf->SetFontSize(18);
$pdf->SetX(160);
$pdf->SetY(130);
$pdf->cell(120, 0, "$asunto", 200, 200, 'R');
$pdf->SetFontSize(12);
$pdf->SetX(160);
$pdf->SetY(181);
$pdf->cell(130, 10, "$dias", 0, 1, 'R');
$pdf->SetFontSize(10);
$pdf->SetX(160);
$pdf->SetY(185);
$pdf->cell(130, 10, "$desde", 0, 1, 'R');
$pdf->SetX(160);
$pdf->SetY(195);
$pdf->cell(130, 10, "$hasta", 0, 1, 'R');
$pdf->SetX(160);
$pdf->SetY(185.5);
$horaActual = date('h:i:s');
$pdf->Cell(22, 10, "$horaActual", 0, 1, 'R');

// Guardar el PDF en una variable
$pdfData = $pdf->Output('', 'S');

// Escapar los datos para evitar inyección de SQL
$tarj= $conn->real_escape_string($tarj);
$nombre = $conn->real_escape_string($nombre);
$asunto = $conn->real_escape_string($asunto);
$apellido = $conn->real_escape_string($apellido);
$fechaInicio=$conn ->real_escape_string($desde);
$fechaFinal= $conn ->real_escape_string($hasta);
$horaInicio = $conn ->real_escape_string($horaActual);
$pdfData = $conn->real_escape_string($pdfData);


// Insertar el PDF y el archivo adjunto en la base de datos
$sql = "INSERT INTO pdf (id, Nombre, Asunto, Apellido, fechaInicio, fechaFinal, horaInicio, Dias, contenido) VALUES ('$tarj', '$nombre', '$asunto', '$apellido', '$fechaInicio', '$fechaFinal', '$horaInicio', '$dias', '$pdfData')";

if ($conn->query($sql) === true) {
    echo "El PDF se ha guardado correctamente en la base de datos.";
} else {
    echo "Error al guardar el PDF en la base de datos: " . $conn->error;
}

$conn->close();
?>
