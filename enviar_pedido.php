<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del POST
    $resumenFecha = $_POST['resumenFecha'];
    $listaProductos = $_POST['listaProductos'];
    $nombreCliente = $_SESSION['name'];
    $nombreUsuario = $_SESSION['name'];  // Ajusta esto seg��n tu estructura de sesi��n

    // Destinatario del correo
    $destinatario = 'pedidos@suministrosselectos.online'; // Cambia esto con tu direcci��n de correo

    // Asunto del correo
    $asunto = 'Pedido de ' . $nombreCliente;

    // Cuerpo del correo
    $mensaje = "Resumen del Pedido: $nombreCliente\n\n";
    $mensaje .= "Fecha de Entrega: $resumenFecha\n\n";
    $mensaje .= "Productos:\n$listaProductos";

    // Cabeceras del correo
    $cabeceras = 'From: pedidos@suministrosselectos.online' . "\r\n" .
                 'Reply-To: pedidos@suministrosselectos.online' . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();

    // Crear conexi��n a la base de datos (reemplaza con tus propios detalles)
    $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

    // Verificar la conexi��n
    if ($conexion->connect_error) {
        die("Conexi��n fallida: " . $conexion->connect_error);
    }

    // Preparar la consulta SQL para insertar en la base de datos
    $sql = "INSERT INTO pedidos (nombre_cliente, nombre_usuario, resumen_fecha, lista_productos) VALUES ('$nombreCliente', '$nombreUsuario', '$resumenFecha', '$listaProductos')";

    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        // Enviar el correo
        $enviado = mail($destinatario, $asunto, $mensaje, $cabeceras);

        // Responder al cliente
        if ($enviado) {
            http_response_code(200);
            echo 'Correo enviado y pedido almacenado en la base de datos correctamente.';
        } else {
            http_response_code(500);
            echo 'Error al enviar el correo.';
        }
    } else {
        http_response_code(500);
        echo 'Error al almacenar el pedido en la base de datos: ' . $conexion->error;
    }

    // Cerrar la conexi��n
    $conexion->close();
} else {
    http_response_code(400);
    echo 'Solicitud incorrecta.';
}
?>
