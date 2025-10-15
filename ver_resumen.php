<?php
// Establecer la zona horaria a Panamá
date_default_timezone_set('America/Panama');

// Resto de tu código ...
require_once 'templates/header.php';
?>
<?php require_once 'modal.php'; ?>
<div class="content">
    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Crea una conexión a la base de datos (reemplaza con tus propios detalles)
        $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

        // Verifica la conexión
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        // Obtener la fecha del formulario
        $fecha_resumen = $_POST['fecha_resumen'];

        // Formatear la fecha para que coincida con el formato almacenado en la base de datos
        $fecha_resumen_formateada = date('Y-m-d', strtotime($fecha_resumen));

        // Mostrar la fecha formateada (puedes comentar o eliminar esta línea después de verificar)
        echo "<h2 class='mb-4'>Resumen de Compras para el " . htmlspecialchars($fecha_resumen) . "</h2>";

        // Consulta SQL para obtener todos los productos de los pedidos de la fecha seleccionada
$sql_productos = "SELECT lista_productos FROM pedidos WHERE resumen_fecha LIKE '%$fecha_resumen_formateada%'";


        $resultado_productos = $conexion->query($sql_productos);

        // Array para almacenar los productos y sus cantidades
        $productos_cantidades = array();

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

                if (isset($productos_cantidades[$clave_producto])) {
                    $productos_cantidades[$clave_producto]['cantidad'] += $cantidad_producto;
                } else {
                    $productos_cantidades[$clave_producto] = array(
                        'nombre' => $nombre_producto,
                        'cantidad' => $cantidad_producto,
                        'unidad' => $unidad_producto,
                    );
                }
            }
        }

        // Cierra la conexión
        $conexion->close();
    }
    ?>

    <!-- Agrega aquí el formulario para seleccionar la fecha y los botones -->
    <div class="mb-4">
        <form method="post" class="d-flex align-items-center">
            <div class="form-group me-3">
                <label for="fecha_resumen">Selecciona una fecha:</label>
                <input type="date" name="fecha_resumen" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary me-3">Mostrar Resumen</button>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($productos_cantidades)) { ?>
                <a href="generar_reporte.php?fecha=<?= urlencode($fecha_resumen_formateada) ?>" class="btn btn-success me-3" target="_blank">Generar Reporte</a>
            <?php } ?>
        </form>
    </div>

    <!-- Muestra el resumen consolidado de productos -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($productos_cantidades)) { ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad Total</th>
                        <th>Unidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos_cantidades as $producto) { ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= $producto['cantidad'] ?></td>
                            <td><?= $producto['unidad'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <script>
        // Función para abrir el modal
        function openModal(reportContent) {
            document.getElementById('reportContent').innerHTML = reportContent;
            document.getElementById('myModal').style.display = 'block';
        }

        // Función para cerrar el modal
        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }
    </script>

    <?php require_once 'templates/sidebar.php'; ?>

</div>
</div> <!-- /container -->
<?php require_once 'templates/footer.php'; ?>
