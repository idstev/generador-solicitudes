<?php
session_start();

// Mostrar alerta de sesión expirada
echo "<script>alert('La sesión ha expirado. Por favor, inicie sesión nuevamente.');</script>";

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir al usuario a la página de inicio de sesión
header("Location: IniciarSesion.php");
exit;
?>
    