<?php
  // Configurar el envío de correo

    use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
use PhpMyAdmin\UrlRedirector;

  require 'PHPMailer-master/src/Exception.php';
  require 'PHPMailer-master/src/PHPMailer.php';
  require 'PHPMailer-master/src/SMTP.php';
  
  $mail = new PHPMailer();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  
  if (isset($_FILES['adjunto']) && $_FILES['adjunto']['error'] === UPLOAD_ERR_OK) {
    $nombreAdjunto = $_FILES['adjunto']['name'];
    $rutaTemporal = $_FILES['adjunto']['tmp_name'];
  
    try {
      // Configuración del servidor SMTP
      $mail->isSMTP();
      $mail->Host = 'mail.inamhi.gob.ec';
      $mail->Port = 587;
      $mail->SMTPAuth = true;
      $mail->Username = 'dgonzalez@inamhi.gob.ec';
      $mail->Password = 'Diego2021*';
      
      // Configuración del correo electrónico
      $mail->setFrom('dgonzalez@inamhi.gob.ec', 'Remitente');
      $mail->addAddress('ritopoto29@gmail.com', 'Destinatario');
      $mail->Subject ='Solicitud de permiso';
      $mail->Body = "El empleadod de nombre: ".$nombre . " ha enviado la siguiente solicitud";
  
      // Adjuntar archivo
      $mail->addAttachment($rutaTemporal, $nombreAdjunto);
      
      // Enviar correo electrónico
      $enviado = $mail->send();
  
      if ($enviado) {
        echo 'Correo enviado exitosamente con archivo adjunto.';
      } else {
        echo 'Error al enviar el correo con archivo adjunto.';
      }
    } catch (Exception $e) {
      echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
    }
  } else {
    echo 'No se seleccionó ningún archivo adjunto o se produjo un error.';
  }
}
?>
