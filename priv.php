<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

// Contenido privado
echo "Bienvenido al contenido privado. Usuario ID: " . $_SESSION["user_id"];
?>
