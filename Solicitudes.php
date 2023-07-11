<title>Solicitudes</title>
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

// Actualizar el tiempo de actividad
$_SESSION['lastActivity'] = time();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Solicitudes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT id, Asunto,  SUBSTRING_INDEX(nombre, ' ', 1) AS Nombre, SUBSTRING_INDEX(apellido, ' ', 1) AS Apellido FROM pdf";
$result = $conn->query($sql);

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="table-responsive">
    <h2 style="text-align: center;">LISTA DE SOLICITUDES</h2>
    <table class="table">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #e3f2fd;">
        <div>
        <button class="btn btn-primary Solicitudes" type="button" onclick="history.back()" name="volver atrás"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
  <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
</svg> Volver</button>
    </div>
        <div style="text-align: right;">
        <form>
                    <a href="ReporteDiario.php" style="margin-left: 1550px;"  type="submit" class="btn btn-success">Registro diario</a>
                </form>
                </div>
                <br>
                |
                <div style="text-align: right;">
                <form action="CerrarSesion.php" method="post">
                    <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                </form>  
                    </div>
</nav>
        <thead class="table-dark">
            <tr>
                <th>T.Control</th>
                <th>Solicitante</th>
                <th>Asunto</th>
                <th>Certificado(PDF)</th>
                <th>DocumentoOficial</th>
                <th>Solicitud(PDF)</th>
                <th>Rechazar</th>
                <th>Aceptar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $fileId = $row["id"];
                    $fileName = $row["Nombre"];
                    $fileApellido = $row["Apellido"];
                    $fileAsunto = $row["Asunto"];


            ?>
                    <tr>
                        <td><?php echo $fileId; ?></td>
                        <td><?php echo $fileName, " ", $fileApellido; ?></td>
                        <td><?php echo $fileAsunto ?></td>
                        <td>
                            <a class="btn btn-success" href="DescargarCert.php?id=<?php echo $fileId; ?>"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                    <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z" />
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                </svg> Descargar</a>
                        </td>
                        <td>
                            <a class="btn btn-success" href="DescargarOficial.php?id=<?php echo $fileId; ?>"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                    <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z" />
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                </svg> Descargar</a>
                        </td>
                        <td>
                            <a class="btn btn-success" href="descargarpdf.php?id=<?php echo $fileId; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-down" viewBox="0 0 16 16">
                                    <path d="M8.5 6.5a.5.5 0 0 0-1 0v3.793L6.354 9.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 10.293V6.5z" />
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                                </svg> Descargar</a>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="Eliminar.php?id=<?php echo $fileId; ?>">Rechazar</a>
                        </td>
                        <td>
                            <button class="btn btn-success" onclick="enviarSolicitud(<?php echo $fileId; ?>)">Aceptar</button>
                        </td>

                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4'>No se encontraron archivos.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    // Función para enviar la solicitud AJAX y mostrar la alerta
    function enviarSolicitud(fileId) {
        // Realizar la solicitud AJAX con jQuery
        $.ajax({
            url: 'Aceptar.php?id=' + fileId,
            type: 'GET',
            success: function(response) {
                // Mostrar la alerta después de enviar la solicitud
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Su solicitud ha sido enviada',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    // Recargar la página después de mostrar la alerta
                    location.reload();
                });
            },
            error: function() {
                // Manejar errores si ocurre algún problema con la solicitud AJAX
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Error al enviar el formulario',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
</script>
<script>
    // Iniciar temporizador de inactividad
var inactivityTimer;

// Reiniciar temporizador de inactividad al realizar una acción
$(document).on('click keypress', function() {
    clearTimeout(inactivityTimer);
    startInactivityTimer();
});

// Función para iniciar el temporizador de inactividad
function startInactivityTimer() {
    inactivityTimer = setTimeout(function() {
        // Redirigir a la página de cierre de sesión después de 10 minutos de inactividad
        window.location.href = "CerrarSesion.php";
    }, 600000); // 10 minutos en milisegundos
}

// Iniciar el temporizador de inactividad cuando se carga la página
startInactivityTimer();
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Función para actualizar la actividad de la sesión mediante una petición AJAX
        function actualizarActividad() {
            $.ajax({
                url: 'ActualizarActividad.php', // Ruta al archivo PHP que actualizará la actividad
                type: 'POST',
                data: { actualizacion: true },
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
<?php
$conn->close();
?>