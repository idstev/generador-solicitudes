<?php
if(isset($_POST["submit"])){
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false){
        $image = $_FILES['image']['tmp_name'];
        $imgContent = addslashes(file_get_contents($image));
        $Tarj = $_POST['id'];
        $Nombre = $_POST['Nombre'];
        $Apellido = $_POST['Apellido'];

        /*
         * Insertar datos de la imagen en la base de datos
         */

        // Detalles de la base de datos
        $dbHost     = 'localhost';
        $dbUsername = 'root';
        $dbPassword = '';
        $dbName     = 'Solicitudes';

        // Crear conexión y seleccionar la base de datos
        $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

        // Verificar la conexión
        if($db->connect_error){
            die("Connection failed: " . $db->connect_error);
        }


        // Insertar contenido de la imagen en la base de datos
        $insert = $db->query("INSERT into funcionarios (idFuncionario, Nombre, Apellido, firma) VALUES ('$Tarj', '$Nombre', '$Apellido', '$imgContent')");
        if($insert){
            echo "Archivo subido exitosamente.";
        }else{
            echo "Error al subir el archivo, por favor intenta de nuevo.";
        }
    }else{
        echo "Por favor selecciona un archivo de imagen para subir.";
    }
}

?>