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

$sql = "SELECT D.id, P.idFuncionario AS pdf_id, D.SaldoV2022, D.VacacionesG, D.Total22y23, D.PermisoT, D.DescuentoC, D.TotalT, D.VacacionesD, D.Suma, SUBSTRING_INDEX(P.Nombre, ' ', 1) AS Nombre, SUBSTRING_INDEX(P.Apellido, ' ', 1) AS Apellido
FROM funcionarios P 
INNER JOIN DescuentosV D ON P.idFuncionario = D.id";
$result = $conn->query($sql);

?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="table-responsive">
    <div style="text-align: center;">
<img src="https://static.wixstatic.com/media/265b70_a2f460791b4346b3baf4ade406eb0754~mv2_d_1722_1768_s_2.jpg"
                    width="170px" height="">
                </div>
    <table class="table">
        <thead class="table-dark">
        <h2 style="text-align: center;">LISTA DE SOLICITUDES</h2>
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
                <th style="text-align: center;">id</th>
                <th style="text-align: center;">Funcionario</th>
                <th style="text-align: center;">SALDO VACACIONES 2022</th>
                <th style="text-align: center;">VACACIONES GENERADAS 2023</th>
                <th style="text-align: center;">TOTAL VACACIONES SALDO 2022 Y VACACIONES GENERADAS 2023</th>
                <th style="text-align: center;">PERMISOS CON CARGO A VACACIONES TOMADOS AÑO 2023</th>
                <th style="text-align: center;">DESCUENTO DE CONFORMIDAD AL ART. 29 DE LA LOSEP</th>
                <th style="text-align: center;">TOTAL PERMISOS TOMADOS, VACACIONES TOMADAS Y DESCUENTO SABADO Y DOMINGO DEL AÑO 2023</th>
                <th style="text-align: center;">TOTAL DISPONIBLE DE VACACIONES UNA VEZ DESCONTADOS PERMISOS Y VACACIONES TOMADAS EN EL AÑO 2023</th>
                <th style="text-align: center;">Conteo de solicitudes</th>
                <form action="CerrarSesion.php" method="post">
                </form>      
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $fileId = $row["pdf_id"];
                    $fileName = $row["Nombre"];
                    $fileApellido = $row["Apellido"];
                    $Saldo2022 = $row["SaldoV2022"];
                    $VacacionesG = $row["VacacionesG"];
                    $Total20y23 = $row["Total22y23"];
                    $PermisoT = $row["PermisoT"];
                    $DescuentoC = $row["DescuentoC"];
                    $TotalT = $row["TotalT"];
                    $VacacionesD = $row["VacacionesD"];
                    $Suma = $row["Suma"];


            ?>
                    <tr class="table-warning">
                        <td  style="text-align: center;"><?php echo $fileId; ?></td>
                        <td style="text-align: center;"><?php echo $fileName, " ", $fileApellido; ?></td>
                        <td style="text-align: center;"><?php echo $Saldo2022 ?></td>
                        <td style="text-align: center;"><?php  echo $VacacionesG ?></td>
                        <td style="text-align: center;"><?php  echo $Total20y23?></td>
                        <td style="text-align: center;"><?php  echo $PermisoT?></td>
                        <td style="text-align: center;"><?php  echo $DescuentoC?></td>
                        <td style="text-align: center;"><?php  echo $TotalT?></td>
                        <td style="text-align: center;"><?php  echo $VacacionesD?></td>
                        <td style="text-align: center;"><?php  echo $Suma?></td>
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
<?php
$conn->close();
?>