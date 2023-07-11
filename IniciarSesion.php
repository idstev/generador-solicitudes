<?php
session_start();

// Verificar si el usuario ya ha iniciado sesión
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: Solicitudes.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "JefeInmediato";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if (!$conn) {
        die("No hay conexión: " . mysqli_connect_error());
    }

    $user = $_POST["nombre"];
    $pswd = $_POST["contra"];

    $query = mysqli_query($conn, "SELECT * FROM Administrador WHERE Usuario= '".$user."' and Pass = '".$pswd."'");
    $nr = mysqli_num_rows($query);

    if ($nr == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user;
        mysqli_close($conn);
        header('Location: Solicitudes.php');
        exit;
    } else {
        $error_message = "Credenciales inválidas";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Iniciar Sesión</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="text-align: center;">

<div class="container mt-3 col-3">
    <div> 
        <img src="https://static.wixstatic.com/media/265b70_a2f460791b4346b3baf4ade406eb0754~mv2_d_1722_1768_s_2.jpg" width="170px" height=""> 
    </div>
    <h3>Ingreso</h3>

    <?php if (isset($error_message)) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form action="IniciarSesion.php" class="was-validated" method="post">
        <div class="mb-3 mt-3">
            <label for="nombre" class="form-label">Usuario:</label>
            <input type="text" class="form-control" id="nombre" placeholder="Ingrese Usuario" name="nombre" required>
            <div class="valid-feedback"></div>
            <div class="invalid-feedback">Campo Obligatorio.</div>
        </div>
        <div class="mb-3">
            <label for="contra" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="contra" placeholder="Ingrese Contraseña" name="contra" required>
            <div class="valid-feedback"></div>
            <div class="invalid-feedback">Campo Obligatorio.</div>
        </div>

        <button type="submit" class="btn btn-primary">Ingresar</button>
    </form>
</div>

</body>
</html>
