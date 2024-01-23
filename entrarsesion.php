<?php
session_start();

// Crear conexión
$conn = new mysqli('localhost', 'conexion_php', '', 'datos_usuario');

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Consultar la base de datos
    $sql = "SELECT id, email, contraseña FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($contraseña, $row["contraseña"])) {
            // Iniciar sesión
            $_SESSION["user_id"] = $row["id"];

            // Generar un token de sesión (puedes usar una librería como random_bytes para generar un token seguro)
            $token = bin2hex(random_bytes(32));

            // Establecer una cookie con el token
            setcookie("session_token", $token, time() + (86400 * 30), "/"); // Caducidad en 30 días (ajústalo según tus necesidades)

            // Almacenar el token en la base de datos (puedes tener una columna "session_token" en tu tabla de usuarios)
            $updateTokenSql = "UPDATE usuarios SET session_token='$token' WHERE id=" . $row["id"];
            $conn->query($updateTokenSql);

            // Redirigir al contenido privado
            header("Location: contenido_privado.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
}

// Cerrar conexión
$conn->close();
?>
