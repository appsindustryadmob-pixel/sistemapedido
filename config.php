<?php
require_once 'messages.php';

define('BASE_PATH', 'localhost'); // Ruta base donde se encuentra
define('DB_HOST', 'localhost'); // Servidor de la base de datos
define('DB_USERNAME', 'd279606_adminsumi'); // Usuario de la base de datos
define('DB_PASSWORD', 'vv7820396$$'); // Contraseña de la base de datos
define('DB_NAME', 'd279606_dbsumi'); // Nombre de la base de datos

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Utilizar spl_autoload_register en lugar de __autoload
spl_autoload_register(function ($class) {
    $parts = explode('_', $class);
    $path = implode(DIRECTORY_SEPARATOR, $parts);
    // Asegúrate de agregar la extensión .php al nombre del archivo
    require_once $path . '.php';
});
