<?php require_once 'templates/header.php'; ?>

<div class="content">
    <?php
    // Función para obtener un id único para cada producto
    function obtenerIdUnico()
    {
        return uniqid('producto_', true);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
        $nombre = $_POST['nombre'];

        $productos = json_decode(file_get_contents('productos.json'), true);

        $nuevoProducto = [
            'id' => obtenerIdUnico(),
            'nombre' => $nombre,
        ];

        $productos[] = $nuevoProducto;

        file_put_contents('productos.json', json_encode($productos, JSON_PRETTY_PRINT));

        // Redirigir después de agregar producto
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_producto'])) {
        $productoNombre = $_POST['producto_nombre'];

        $productos = json_decode(file_get_contents('productos.json'), true);

        // Filtrar productos, excluyendo el producto a eliminar
        $productos = array_filter($productos, function ($producto) use ($productoNombre) {
            return $producto['nombre'] !== $productoNombre;
        });

        file_put_contents('productos.json', json_encode($productos, JSON_PRETTY_PRINT));

        // Redirigir después de eliminar producto
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar_producto'])) {
        $productoNombre = $_POST['producto_nombre_modificar'];
        $nuevoNombre = $_POST['nuevo_nombre'];

        $productos = json_decode(file_get_contents('productos.json'), true);

        // Modificar el nombre del producto
        foreach ($productos as &$producto) {
            if ($producto['nombre'] === $productoNombre) {
                $producto['nombre'] = $nuevoNombre;
            }
        }

        file_put_contents('productos.json', json_encode($productos, JSON_PRETTY_PRINT));

        // Redirigir después de modificar producto
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
    ?>

    <div style="max-width: 800px; margin: 20px auto;">
        <h2>Agregar Producto</h2>

        <!-- Formulario para agregar productos -->
        <form method="post" action="">
            <div style="margin-bottom: 10px;">
                <label for="nombre" style="display: block; margin-bottom: 5px;">Nombre del Producto:</label>
                <input type="text" name="nombre" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>
            <button type="submit" name="agregar_producto" style="background-color: #4caf50; color: #fff; border: none; padding: 8px; border-radius: 4px; cursor: pointer;">Agregar Producto</button>
        </form>
    </div>

    <div style="max-width: 800px; margin: 20px auto;">
        <h2>Lista de Productos</h2>

        <!-- Barra de búsqueda -->
        <div style="margin-bottom: 10px;">
            <label for="busqueda" style="display: block; margin-bottom: 5px;">Buscar Producto:</label>
            <input type="text" id="busqueda" oninput="filtrarProductos()" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <!-- Tabla para mostrar la lista de productos -->
        <table id="tabla-productos" style="width: 100%; border-collapse: collapse; box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="padding: 15px;">Nombre del Producto</th>
                    <th style="padding: 15px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $productos = json_decode(file_get_contents('productos.json'), true);
                foreach ($productos as $producto) {
                    echo "<tr class='fila-producto' data-producto-nombre='{$producto['nombre']}'>";
                    echo "<td style='padding: 12px;'>{$producto['nombre']}</td>";
                    echo "<td style='padding: 12px;'>";
                    echo "<div class='btn-group' role='group'>";
                    echo "<form method='post' action='' style='display: inline-block; margin-right: 5px;'>";
                    echo "<input type='hidden' name='producto_nombre' value='{$producto['nombre']}'>";
                    echo "<button type='submit' name='eliminar_producto' class='btn btn-danger btn-sm'>Eliminar</button>";
                    echo "</form>";

                    echo "<button class='btn btn-primary btn-sm' onclick='modificarProducto(\"{$producto['nombre']}\")'>Modificar</button>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Formulario para modificar productos -->
    <div style="max-width: 800px; margin: 20px auto; display: none;" id="modificar-campos">
        <h2>Modificar Producto</h2>
        <form method="post" action="">
            <input type="hidden" name="producto_nombre_modificar" id="producto-nombre-modificar">
            <div style="margin-bottom: 10px;">
                <label for="nuevo_nombre" style="display: block; margin-bottom: 5px;">Nuevo Nombre:</label>
                <input type="text" name="nuevo_nombre" id="nuevo_nombre" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>
            <button type="submit" name="modificar_producto" class="btn btn-primary btn-sm">Guardar Cambios</button>
        </form>
    </div>

    <!-- Script para activar el formulario de modificación al hacer clic en una fila -->
    <script>
        function modificarProducto(nombre) {
            var modificarCampos = document.getElementById("modificar-campos");
            var nuevoNombreInput = document.getElementById("nuevo_nombre");
            var productoNombreModificarInput = document.getElementById("producto-nombre-modificar");

            modificarCampos.style.display = "block";
            nuevoNombreInput.value = nombre;
            productoNombreModificarInput.value = nombre;

            // Desactivar botones y resaltado de otras filas mientras se modifica
            var filasProductos = document.getElementsByClassName("fila-producto");
            for (var j = 0; j < filasProductos.length; j++) {
                filasProductos[j].style.pointerEvents = "none";
                filasProductos[j].style.backgroundColor = "#ddd";
            }
        }

        function filtrarProductos() {
            var input = document.getElementById('busqueda');
            var filter = input.value.toUpperCase();
            var table = document.getElementById('tabla-productos');
            var rows = table.getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var td = rows[i].getElementsByTagName('td')[0];
                if (td) {
                    var txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }
    </script>
</div>

<?php require_once 'templates/footer.php'; ?>
