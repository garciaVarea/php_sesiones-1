<?php

// Crear conexión
$conn = new mysqli('localhost', 'conexion_php', '', 'sesiones');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para encriptar la contraseña
function encriptarContraseña($contraseña) {
    return password_hash($contraseña, PASSWORD_BCRYPT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $correo = $_POST["correo"];
    $archivojpg = $_POST["archivo_jpg"];
    $archivopdf = $_POST["archivo_pdf"];
    $contraseña = encriptarContraseña($_POST["contraseña"]);

    // Validar el formulario
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        // Guardar en la base de datos
        $sql = "INSERT INTO usuarios (token, correo, contraseña, imagen, archivo) VALUES (null, '$correo', '$contraseña', '$archivojpg', '$archivopdf')";

        if ($conn->query($sql) === TRUE) {
            echo "Datos guardados correctamente.";
        } else {
            echo "Error al guardar datos: " . $conn->error;
        }
    } else {
        echo "Correo no válido.";
    }

    // Subir archivos
    $directorio = "uploads/";
    $nombreArchivoJPG = $_FILES["archivo_jpg"]["name"];
    $nombreArchivoPDF = $_FILES["archivo_pdf"]["name"];

    move_uploaded_file($_FILES["archivo_jpg"]["tmp_name"], $directorio . $nombreArchivoJPG);
    move_uploaded_file($_FILES["archivo_pdf"]["tmp_name"], $directorio . $nombreArchivoPDF);
}

// Cerrar conexión
$conn->close();