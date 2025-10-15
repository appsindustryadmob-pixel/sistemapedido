<?php
// Verifica si se ha proporcionado la fecha en la URL
if (isset($_GET['fecha'])) {
    // Obtener la fecha de la URL y formatearla
    $fecha_resumen_formateada = date('Y-m-d', strtotime($_GET['fecha']));

    // Crea una conexión a la base de datos (reemplaza con tus propios detalles)
    $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta SQL para obtener todos los productos de los pedidos de la fecha seleccionada
    $sql_productos = "SELECT lista_productos FROM pedidos WHERE resumen_fecha LIKE '%$fecha_resumen_formateada%'";

    $resultado_productos = $conexion->query($sql_productos);

    // Array para almacenar los productos agrupados
    $productos_agrupados = array();

    // Procesa los resultados para obtener cantidades totales por producto
    while ($fila_producto = $resultado_productos->fetch_assoc()) {
        $productos_pedido = explode("\n", $fila_producto['lista_productos']);

        foreach ($productos_pedido as $producto) {
            // Utilizar expresión regular para manejar el formato (nombre - cantidad - unidad)
            $matches = [];
            preg_match('/^(.*?) - (\d+) ([^\d]+)$/', $producto, $matches);
            
            $nombre_producto = trim($matches[1]);
            $cantidad_producto = intval($matches[2]);
            $unidad_producto = trim($matches[3]);

            // Crear una clave única para cada producto considerando el nombre y la unidad
            $clave_producto = $nombre_producto . '-' . $unidad_producto;

            if (isset($productos_agrupados[$clave_producto])) {
                $productos_agrupados[$clave_producto] += $cantidad_producto;
            } else {
                $productos_agrupados[$clave_producto] = $cantidad_producto;
            }
        }
    }

    // Cierra la conexión
    $conexion->close();

    // Muestra el contenido agrupado del reporte en la ventana modal
    echo "<h2>Reporte de Compras para el $fecha_resumen_formateada</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Producto</th><th>Total</th><th>Unidad</th><th>Pedido Merca</th><th>Pedido Foodie</th></tr>";

    // Itera sobre los productos agrupados y muestra la información en la tabla
    foreach ($productos_agrupados as $clave_producto => $cantidad) {
        // Separar la clave en nombre y unidad
        list($nombre_producto, $unidad_producto) = explode('-', $clave_producto, 2);

        echo "<tr><td>$nombre_producto</td><td>$cantidad</td><td>$unidad_producto</td><td></td><td></td></tr>";
    }

    echo "</table>";

    // Agrega el script JavaScript para imprimir y redirigir después de un breve retraso
    echo "<script>
            // Función para cerrar la ventana modal
            function closeModal() {
                document.getElementById('myModal').style.display = 'none';
            }

            // Función para imprimir y redirigir después de un breve retraso
            function printAndRedirect() {
                setTimeout(function () {
                    window.print();
                    closeModal(); // Cierra la ventana modal después de imprimir
                    window.location.href = 'index.php'; // Redirige a la página principal
                }, 1000); // Retraso de 1 segundo antes de imprimir y redirigir
            }

            // Llama a la función para imprimir y redirigir
            printAndRedirect();
          </script>";
} else {
    // Redirige a la página principal si no se proporciona la fecha
    header('Location: index.php');
    exit;
}
?>
