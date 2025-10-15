<?php
// Establecer la zona horaria a Panamá
date_default_timezone_set('America/Panama');

// Verifica si se proporcionó el ID del pedido
if (isset($_GET['pedidoId'])) {
    $pedidoId = $_GET['pedidoId'];

    // Crea una conexión a la base de datos (reemplaza con tus propios detalles)
    $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta la información del pedido
    $sql = "SELECT * FROM inventario_diario WHERE id = $pedidoId";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();

        // Construye un array con la información del pedido
        $infoPedido = array(
            'nombreUsuario' => $fila['nombre_usuario'],
            'fechaEntrega' => $fila['resumen_fecha'],
            'listaProductos' => $fila['lista_productos']
            
        );

        // Convierte el array a formato JSON y lo imprime
        echo json_encode($infoPedido);
    } else {
        // Si no se encuentra el pedido, devuelve un mensaje de error
        echo json_encode(array('error' => 'Inventario no encontrado'));
    }

    // Cierra la conexión
    $conexion->close();
} else {
    // Si no se proporcionó el ID del pedido, devuelve un mensaje de error
    echo json_encode(array('error' => 'ID de inventario no proporcionado'));
}
?>
