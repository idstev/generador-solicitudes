<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require_once 'fpdf185/fpdf.php';
require_once 'FPDI-master/src/autoload.php';

$mail = new PHPMailer();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$fileId = $_GET['id'];
$sql = "SELECT D.id, P.id AS pdf_id, D.SaldoV2022, D.VacacionesG, D.Total22y23, D.PermisoT, D.DescuentoC, D.TotalT, D.VacacionesD, D.Suma, SUBSTRING_INDEX(P.Nombre, ' ', 1) AS Nombre, SUBSTRING_INDEX(P.Apellido, ' ', 1) AS Apellido, P.Dias, P.fecha, P.contenido
FROM pdf P 
INNER JOIN DescuentosV D ON P.id = D.id
WHERE P.id = $fileId";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fileName = $row["Nombre"];
    $fileApellido = $row["Apellido"];
    $pdfData = $row['contenido']; // Corregido
    $Dias = $row['Dias'];
    $SaldoV2022 = $row['SaldoV2022'];
    $VacacionesG = $row["VacacionesG"];
    $Total = $row["Total22y23"];
    $PermisoT = $row["PermisoT"];
    $Descuento = $row["DescuentoC"];
    $VacacionesD = $row["VacacionesD"];
    $Suma = $row["Suma"];
    $horaInicioStr = $row["fecha"]; // Corregido

    // Verifica si la fecha está definida
    if (isset($row["fecha"])) { // Corregido
        date_default_timezone_set('America/Guayaquil');
        $fechaActual = date("Y-m-d"); // Obtiene la fecha actual en formato "YYYY-MM-DD"
        $fechaHoraInicio = $fechaActual . ' ' . $horaInicioStr;

        // Convertir la fecha/hora completa a un timestamp
        $horaInicioTimestamp = strtotime($fechaHoraInicio);

        // Obtener la hora actual en timestamp
        $horaActual = time();

        // Convertir la fecha/hora completa a un timestamp
        $horaInicioTimestamp = strtotime($horaInicioStr);

        // Calcular la diferencia en segundos (positivos)
        $diferenciaSegundos = $horaActual - $horaInicioTimestamp;

        // Calcular la diferencia en horas y minutos
        $diferenciaHoras = floor($diferenciaSegundos / 3600);
        $diferenciaMinutos = floor(($diferenciaSegundos % 3600) / 60);

        // Muestra la diferencia en horas y minutos (positivos)
        echo "La diferencia en horas y minutos es: " . $diferenciaHoras . " horas " . $diferenciaMinutos . " minutos";

        try {
            // Establecer la zona horaria de Ecuador
            date_default_timezone_set('America/Guayaquil');

            // Realiza cálculos basados en el valor de $Dias
            switch ($Dias) {
                case 1:
                    $resultado = (((8 * 1) / 8) + ((0 * 1) / 480));
                    $resultado = round($resultado, 2);
                    break;
                case 2:
                    $resultado = (((16 * 1) / 8) + ((0 * 1) / 480));
                    $resultado = round($resultado, 2);
                    break;
                case 3:
                    $resultado = (((24 * 1) / 8) + ((0 * 1) / 480));
                    $resultado = round($resultado, 2);
                    break;
                case 4:
                    $resultado = (((32 * 1) / 8) + ((0 * 1) / 480));
                    $resultado = round($resultado, 2);
                    break;
                case 0:
                    $resultado = ((($diferenciaHoras * 1) / 8) + (($diferenciaMinutos * 1) / 480));
                    $resultado = round($resultado, 2);
                    break;
                default:
                    $resultado = 0;
                    break;
            }

            if ($resultado) {
                $Suma = $Suma + $resultado; // Suma $resultado al valor actual de $Suma

                // Actualiza valores basados en el nuevo $Suma
                $SaldoV2022 = 4;
                $VacacionesG = 2.5 * 6;
                $Total = $VacacionesG + $SaldoV2022;
                $Descuento = 6;
                $PermisoT = $Suma + $Descuento;
                $VacacionesD = $Total + $PermisoT;
                $TotalT=$Descuento+$PermisoT;

                // Sentencia SQL para actualizar los datos
                $sql = "UPDATE DescuentosV SET
                SaldoV2022 = $SaldoV2022,
                VacacionesG = $VacacionesG,
                Total22y23 = $Total,
                PermisoT = $PermisoT,
                DescuentoC = $Descuento,
                TotalT = $TotalT,
                VacacionesD = $VacacionesD,
                Suma = $Suma
                WHERE id = $fileId";

                // Ejecutar la sentencia SQL
                $conn->query($sql);

                // Obtén la hora actual
                $horaActual = date("H:i");

                // Guardar el PDF en un archivo temporal
                $tempFile = 'temp.pdf';
                file_put_contents($tempFile, $pdfData);

                // Importar el PDF existente usando FPDI
                $pdf = new \setasign\Fpdi\Fpdi();
                $pdf->AddPage();
                $pdf->setSourceFile($tempFile);

                // Importar todas las páginas del PDF original
                for ($pageNumber = 1; $pageNumber <= $pdf->setSourceFile($tempFile); $pageNumber++) {
                    $templateId = $pdf->importPage($pageNumber);
                    $pdf->useTemplate($templateId);
                }

                // Establecer la fuente y tamaño del texto
                $pdf->SetFont('Arial', 'B', 10);

                // Agregar la hora actual al contenido del PDF
                $pdf->SetXY(20, 195); // Coordenadas X e Ydel texto
                $pdf->Cell(22, 10, $horaActual, 0, 1);

                // Obtener el contenido del PDF actualizado
                ob_start();
                $pdf->Output('F', 'temp_updated.pdf');
                $contenidoPDF = ob_get_clean();

                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'mail.inamhi.gob.ec';
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->Username = 'dgonzalez@inamhi.gob.ec';
                $mail->Password = 'Diego2021*';

                // Configuración del correo electrónico
                $mail->setFrom('dgonzalez@inamhi.gob.ec', 'Solicitud');
                $mail->addAddress('ritopoto29@gmail.com', 'Destinatario');
                $mail->Subject = 'Solicitud de permiso';
                $mail->Body = "El empleado de nombre: " . $fileName . ' ' . $fileApellido . " ha enviado la siguiente solicitud";

                // Adjuntar el PDF actualizado
                $mail->addAttachment('temp_updated.pdf', 'solicitud.pdf');

                // Enviar correo electrónico
                $enviado = $mail->send();

                // Realizar la inserción en la tabla 'registroDiario'
                $Aceptado = "Aceptado";
                $sqlInsert = "INSERT INTO registroDiario (Solicitante, fecha, Estado) VALUES ('$fileName', '$horaInicioStr', '$Aceptado')";
                if ($conn->query($sqlInsert) === TRUE) {
                    echo 'Inserción en la tabla registroDiario realizada correctamente.';
                } else {
                    echo 'Error al realizar la inserción en la tabla registroDiario: ' . $conn->error;
                }

                // Eliminar el registro de la tabla 'pdf'
                $sqlDelete = "DELETE FROM pdf WHERE id = $fileId";
                if ($conn->query($sqlDelete) === TRUE) {
                    echo 'Eliminación del registro en la tabla pdf realizada correctamente.';
                } else {
                    echo 'Error al eliminar el registro de la tabla pdf: ' . $conn->error;
                }
            }
        } catch (Exception $e) {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'La fecha no está definida.';
    }
} else {
    echo 'No se encontró un PDF en la base de datos para adjuntar con el ID proporcionado.';
}

$conn->close();
?>
