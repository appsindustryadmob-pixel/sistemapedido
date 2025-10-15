<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del POST
    $pedidoId = $_POST['pedidoId'];
    $fechaEntregaConfirmada = $_POST['fechaEntregaConfirmada'];
    $comentario = $_POST['comentario'];

    // Crea una conexión a la base de datos (reemplaza con tus propios detalles)
    $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Escapa las variables para evitar inyección de SQL
    $pedidoId = $conexion->real_escape_string($pedidoId);
    $fechaEntregaConfirmada = $conexion->real_escape_string($fechaEntregaConfirmada);
    $comentario = $conexion->real_escape_string($comentario);

    // Actualiza la base de datos con la fecha de entrega confirmada y el comentario
    $sql = "UPDATE pedidos SET fecha_entrega_confirmada = '$fechaEntregaConfirmada', comentario = '$comentario', enviado = 1 WHERE id = $pedidoId";

    if ($conexion->query($sql) === TRUE) {
        // Éxito al actualizar la base de datos
        http_response_code(200);
        echo 'Fecha de entrega confirmada, comentario agregado y pedido marcado como entregado.';
    } else {
        // Error al actualizar la base de datos
        http_response_code(500);
        echo 'Error al actualizar la fecha de entrega, comentario y estado del pedido.';
    }

    // Cierra la conexión
    $conexion->close();
} else {
    // Solicitud incorrecta
    http_response_code(400);
    echo 'Solicitud incorrecta.';
}
?>
