<?php
// Inicia o reanuda la sesión
session_start();

// Verifica si el usuario está autenticado y es un administrador
if (!isset($_SESSION['name']) || $_SESSION['role'] != '1') {
    // Redirige a la página de inicio de sesión si no está autenticado o no es administrador
    header('Location: login.php');
    exit();
}

// Verifica si se reciben datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crea una conexión a la base de datos (reemplaza con tus propios detalles)
    $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Obtiene los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    // Inserta el nuevo usuario en la base de datos
    $sqlInsert = "INSERT INTO users (name, email, password, role) VALUES ('$nombre', '$email', '$password', '$rol')";
    $resultadoInsert = $conexion->query($sqlInsert);

    // Cierra la conexión después de realizar la inserción
    $conexion->close();

    if ($resultadoInsert) {
        // Redirige de nuevo a la página de creación de usuarios con un mensaje de éxito
        header('Location: admin.php?mensaje=Usuario creado exitosamente.');
        exit();
    } else {
        // Redirige de nuevo a la página de creación de usuarios con un mensaje de error
        header('Location: admin.php?mensaje=Error al crear el usuario: ' . $conexion->error);
        exit();
    }
} else {
    // Si no se reciben datos del formulario, redirige a la página de creación de usuarios
    header('Location: admin.php');
    exit();
}
?>
