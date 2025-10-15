<?php require_once 'templates/header.php'; ?>

<style>
    .content {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .form-container,
    .order-summary,
    .pedido-enviado {
        flex-basis: calc(50% - 20px);
        box-sizing: border-box;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .pedido-item {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #ddd;
        padding: 10px;
    }

    .pedido-item button {
        margin-left: 10px;
        cursor: pointer;
        background-color: #f44336;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
    }

    #search-bar,
    #product-dropdown,
    #unidad-dropdown,
    #cantidad-input,
    #comentario-textarea,
    #fecha-entrega,
    #agregar-producto,
    #guardar-enviar-btn {
        margin-top: 10px;
    }

    #product-dropdown,
    #unidad-dropdown {
        width: 100%;
        padding: 8px;
    }

    #comentario-textarea,
    #fecha-entrega,
    #cantidad-input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        resize: none;
        max-height: 34px;
        min-height: 34px;
    }

    #agregar-producto,
    #nuevo-pedido-btn,
    #guardar-enviar-btn {
        background-color: #4caf50;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    #guardar-enviar-btn {
        background-color: #2196f3;
        display: none;
    }

    #pedido-enviado {
        display: none;
    }

    .pedido-enviado h2 {
        color: #4caf50;
    }

    #pedido-enviado-lista {
        list-style-type: none;
        padding: 0;
    }

    #pedido-enviado-lista li {
        margin-bottom: 5px;
    }

    @media screen and (max-width: 600px) {
        .content {
            flex-direction: column;
        }

        .form-container,
        .order-summary,
        .pedido-enviado {
            flex-basis: 100%;
        }
    }
</style>

<div class="content">
    <div class="form-container">
        <h2>Lista de Productos</h2>
        <span for="inputEmail3" class="col-sm-4 control-span">Tienda</span>
        <div class="col-sm-8">
            <p> <?php echo $_SESSION['name']; ?> </p>
        </div>

        <label for="fecha-entrega">Fecha en la que el pedido sera Entregado:</label>
        <input type="date" id="fecha-entrega" name="fecha-entrega" min="<?php echo date('Y-m-d'); ?>" required>


        <input type="text" id="search-bar" placeholder="Buscar producto">

        <select id="product-dropdown">
            <?php
            // Cargar datos desde productos.json
            $productosJson = file_get_contents('productos.json');
            $productos = json_decode($productosJson, true);

            foreach ($productos as $producto):
            ?>
                <option value="<?php echo $producto['nombre']; ?>"><?php echo $producto['nombre']; ?></option>
            <?php endforeach; ?>
        </select>

        <form action="enviar_correo.php" method="post">
            <label for="unidad">Selecciona la Unidad:</label>
            <select name="unidad" id="unidad-dropdown">
                <option value="libra">Libra</option>
                <option value="mazo">Mazo</option>
                <option value="unidad">Unidad</option>
                <option value="vaso">Vaso</option>
            </select>

            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad-input" name="cantidad" min="1" value="1">

            <label for="comentario">Comentario:</label>
            <textarea name="comentario" id="comentario-textarea" rows="4" cols="50"></textarea>

            <button type="button" id="agregar-producto">Agregar al Pedido</button>
        </form>
    </div>

    <div class="order-summary" id="resumen-pedido">
        <h2>Resumen del Pedido</h2>
        <div id="resumen-fecha-entrega"></div>
        <ul id="pedido-lista"></ul>
        <button type="button" id="guardar-enviar-btn">Guardar y Enviar</button>
    </div>

    <div class="pedido-enviado" id="pedido-enviado">
        <h2>Pedido Enviado</h2>
        <div id="pedido-enviado-fecha"></div>
        <ul id="pedido-enviado-lista"></ul>
        <button type="button" id="nuevo-pedido-btn">Realizar Nuevo Pedido</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var agregarBoton = document.getElementById('agregar-producto');
        var unidadDropdown = document.getElementById('unidad-dropdown');
        var comentarioTextarea = document.getElementById('comentario-textarea');
        var fechaEntregaInput = document.getElementById('fecha-entrega');
        var cantidadInput = document.getElementById('cantidad-input');
        var pedidoLista = document.getElementById('pedido-lista');
        var resumenFechaEntrega = document.getElementById('resumen-fecha-entrega');
        var guardarEnviarBtn = document.getElementById('guardar-enviar-btn');
        var productDropdown = document.getElementById('product-dropdown');
        var pedidoEnviado = document.getElementById('pedido-enviado');
        var resumenPedido = document.getElementById('resumen-pedido');
        var nuevoPedidoBtn = document.getElementById('nuevo-pedido-btn');
        var searchBar = document.getElementById('search-bar');

        // Original options for product dropdown
        var originalOptions = Array.from(productDropdown.options);

        searchBar.addEventListener('input', function () {
            filtrarProductos(searchBar.value.trim());
        });

        function filtrarProductos(termino) {
            // Remove existing options
            productDropdown.innerHTML = '';

            // Filter original options
            var opcionesFiltradas = originalOptions.filter(function (opcion) {
                var producto = opcion.value.toLowerCase();
                return producto.includes(termino.toLowerCase());
            });

            // Add filtered options back to dropdown
            opcionesFiltradas.forEach(function (opcion) {
                productDropdown.appendChild(opcion.cloneNode(true));
            });

            // Show the dropdown
            productDropdown.style.display = 'block';
        }

        agregarBoton.addEventListener('click', function () {
    var producto = productDropdown.value.trim();
    var unidad = unidadDropdown.value;
    var cantidad = cantidadInput.value;
    var comentario = comentarioTextarea.value;
    var fechaEntrega = fechaEntregaInput.value;

    if (fechaEntrega === '') {
        alert('Por favor, ingrese la fecha de entrega.');
        return; // Evitar que se agregue el producto si no hay fecha de entrega
    }

    if (producto !== '') {
        agregarProductoAlPedido(producto, unidad, cantidad, comentario);
        guardarEnviarBtn.style.display = 'block';
    }
});


        guardarEnviarBtn.addEventListener('click', function () {
            var resumenFecha = resumenFechaEntrega.textContent;
            var listaProductos = obtenerListaProductos();

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        mostrarPedidoEnviado(resumenFecha, listaProductos);
                    } else {
                        alert('Error al enviar el pedido por correo.');
                    }
                }
            };
            guardarEnviarBtn.addEventListener('click', function () {
            guardarEnviarBtn.disabled = true; // Desactivar el bot贸n para evitar m煤ltiples clics
            });

            var datos = 'resumenFecha=' + encodeURIComponent(resumenFecha) +
                '&listaProductos=' + encodeURIComponent(listaProductos);

            xhr.open('POST', 'enviar_pedido.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(datos);
        });

        function agregarProductoAlPedido(producto, unidad, cantidad, comentario) {
            if (resumenFechaEntrega.innerHTML === '' && fechaEntregaInput.value !== '') {
                resumenFechaEntrega.textContent = '' + fechaEntregaInput.value;
            }

            var div = document.createElement('div');
            div.className = 'pedido-item';

            var textoProducto = producto + ' - ' + cantidad + ' ' + unidad;
            if (comentario !== '') {
                textoProducto += ' (' + comentario + ')';
            }
            div.textContent = textoProducto;

            var botonEliminar = document.createElement('button');
            botonEliminar.textContent = 'Eliminar';
            botonEliminar.addEventListener('click', function () {
                eliminarElementoDelPedido(div);
            });

            div.appendChild(botonEliminar);

            pedidoLista.appendChild(div);
            guardarEnviarBtn.style.display = 'block';

            // Hide the dropdown after adding the product
            productDropdown.style.display = 'none';

            productDropdown.value = '';
            unidadDropdown.value = '';
            cantidadInput.value = '1';
            comentarioTextarea.value = '';
        }

        function eliminarElementoDelPedido(elemento) {
            pedidoLista.removeChild(elemento);

            if (pedidoLista.children.length === 0) {
                guardarEnviarBtn.style.display = 'none';
                resumenFechaEntrega.innerHTML = '';
            }
        }

        function obtenerListaProductos() {
            var listaProductos = [];
            var itemsPedido = pedidoLista.getElementsByClassName('pedido-item');

            for (var i = 0; i < itemsPedido.length; i++) {
                var productoTexto = itemsPedido[i].textContent.trim();
                var productoSinEliminar = productoTexto.replace(/\s*Eliminar\s*$/, '');
                listaProductos.push(productoSinEliminar);
            }

            return listaProductos.join('\n');
        }

        function mostrarPedidoEnviado(resumenFecha, listaProductos) {
            resumenPedido.style.display = 'none';
            pedidoEnviado.style.display = 'block';
            document.getElementById('pedido-enviado-fecha').textContent = resumenFecha;

            var listaProductosEnviados = document.getElementById('pedido-enviado-lista');
            listaProductosEnviados.innerHTML = '';

            listaProductos.forEach(function (producto) {
                var li = document.createElement('li');
                li.textContent = producto;
                listaProductosEnviados.appendChild(li);
            });
        }

        nuevoPedidoBtn.addEventListener('click', function () {
            // Reload the page to create a new order
            location.reload();
        });
    });
</script>

<?php require_once 'templates/footer.php'; ?>