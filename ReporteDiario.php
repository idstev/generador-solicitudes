<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: IniciarSesion.php");
    exit;
}
// Verificar el tiempo de inactividad
$inactivityTimeout = 600; // 10 minutos en segundos
if (isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity']) > $inactivityTimeout) {
    session_unset();    // Limpiar todas las variables de sesión
    session_destroy();  // Destruir la sesión
    header("Location: IniciarSesion.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el formulario de selección de fecha
if (isset($_GET['fechaSeleccionada'])) {
    // Obtener la fecha seleccionada por el usuario
    $fechaSeleccionada = $_GET['fechaSeleccionada'];

    // Modificar la consulta SQL para obtener los datos de la fecha seleccionada
    $sql = "SELECT id, Solicitante, fecha, Estado FROM registroDiario
            WHERE DATE(fecha) = '$fechaSeleccionada'";
    $result = $conn->query($sql);
} else {
    // Si no se ha proporcionado una fecha seleccionada, mostrar los datos de hoy
    $sql = "SELECT id, Solicitante, fecha, Estado FROM registroDiario
            WHERE DATE(fecha) >= DATE(NOW()) AND DAY(fecha) = DAY(NOW())";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reporte Diario</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .imprimir-btn {
                display: none;
            }

            .reporte-btn {
                display: none;
            }

            .Solicitudes {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="table-responsive">
        <br><br>
        <h2 style="text-align: center;">REPORTE DIARIO DE SOLICITUDES</h2>
        <br><br>
        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #e3f2fd;">
            <div>
                <button class="btn btn-primary Solicitudes" type="button" onclick="history.back()" name="volver atrás"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z" />
                        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                    </svg> Volver</button>
            </div>
            <div style="text-align: right;">
                <form>

                    <button style="margin-left: 1600px;" class="btn btn-success imprimir-btn" onclick="imprimirTabla()">Imprimir reporte</button>

                </form>
            </div>
            <br>

            <div style="text-align: right;">

            </div>
        </nav>
        <form method="get">
            <br>
            <div class=" reporte-btn">
                <br>
                <label for="fechaSeleccionada">Seleccionar fecha:</label>
                <input type="date" id="fechaSeleccionada" name="fechaSeleccionada">
                <button type="submit" class="btn btn-primary">Mostrar reporte</button>
            </div>
        </form>
        <br>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>id</th>
                    <th>Solicitante</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $fileId = $row["id"];
                        $fileName = $row["Solicitante"];
                        $fileFecha = $row["fecha"];
                        $fileEstado = $row["Estado"];
                ?>
                        <tr>
                            <td><?php echo $fileId ?></td>
                            <td><?php echo $fileName ?></td>
                            <td><?php echo $fileFecha ?></td>
                            <td><?php echo $fileEstado ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='3'>No se encontraron archivos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function imprimirTabla() {
            window.print();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Función para actualizar la actividad de la sesión mediante una petición AJAX
            function actualizarActividad() {
                $.ajax({
                    url: 'ActualizarActividad.php', // Ruta al archivo PHP que actualizará la actividad
                    type: 'POST',
                    data: {
                        actualizacion: true
                    },
                    success: function(response) {
                        console.log('Actividad de sesión actualizada');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al actualizar la actividad de sesión:', error);
                    }
                });
            }

            // Ejecutar la función de actualización de actividad cada 5 minutos (ajusta este tiempo según tus necesidades)
            setInterval(actualizarActividad, 5 * 60 * 1000); // 5 minutos en milisegundos
        });
    </script>
</body>

</html>