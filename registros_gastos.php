<?php require_once 'templates/header.php'; ?>
<style>
    .content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-container,
    .order-summary {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        width: 100%;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-group label {
        display: block;
    }

    .form-group input,
    #registrar-gasto-btn {
        width: 100%;
        box-sizing: border-box;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    #registrar-gasto-btn {
        background-color: #4caf50;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    .order-summary {
        display: none;
    }

    @media screen and (min-width: 600px) {
        .content {
            flex-direction: row;
            justify-content: space-between;
        }

        .form-container,
        .order-summary {
            flex-basis: 48%;
        }
    }
</style>
</head>
<body>

<div class="content">
    <div class="form-container">
        <h2>Registrar Gastos</h2>
        <div class="form-group">
            <label for="producto">Producto:</label>
            <input type="text" id="producto" placeholder="Nombre del producto">
        </div>

        <div class="form-group">
            <label for="precio">Precio por unidad:</label>
            <input type="number" step="0.01" id="precio" placeholder="Precio por unidad">
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" placeholder="Cantidad">
        </div>

        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <button type="button" id="registrar-gasto-btn">Registrar gasto</button>
    </div>

    <div class="order-summary" id="resumen-gastos">
        <h2>Resumen de Gastos</h2>
        <ul id="gastos-lista"></ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var registrarGastoBtn = document.getElementById('registrar-gasto-btn');
        var productoInput = document.getElementById('producto');
        var precioInput = document.getElementById('precio');
        var cantidadInput = document.getElementById('cantidad');
        var fechaInput = document.getElementById('fecha');
        var gastosLista = document.getElementById('gastos-lista');
        var resumenGastos = document.getElementById('resumen-gastos');

        registrarGastoBtn.addEventListener('click', function () {
            var producto = productoInput.value.trim();
            var precio = parseFloat(precioInput.value);
            var cantidad = parseInt(cantidadInput.value);
            var fecha = fechaInput.value;

            if (producto === '' || isNaN(precio) || isNaN(cantidad) || fecha === '') {
                alert('Por favor, complete todos los campos.');
                return;
            }

            agregarGasto(producto, precio, cantidad, fecha);
        });

        function agregarGasto(producto, precio, cantidad, fecha) {
            var total = precio * cantidad;
            var listItem = document.createElement('li');
            listItem.textContent = producto + ' - ' + cantidad + ' unidades - Total: $' + total.toFixed(2) + ' - Fecha: ' + fecha;

            gastosLista.appendChild(listItem);

            // Show the summary
            resumenGastos.style.display = 'block';

            // Clear inputs after adding the expense
            productoInput.value = '';
            precioInput.value = '';
            cantidadInput.value = '';
            fechaInput.value = '';
        }
    });
</script>

<?php require_once 'templates/footer.php'; ?>

<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "d279606_adminsumi";
$password = "vv7820396$$";
$database = "d279606_dbsumi";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}
?>
