<?php
// Establecer la zona horaria a Panamá
date_default_timezone_set('America/Panama');

// Obtener la hora actual
$hora_actual_panama = date('H:i:s');

// Resto de tu código ...
require_once 'templates/header.php';
?>
<div class="content">
    <div class="container">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <h2>Gestione sus Inventarios</h2>
            <div>
                <?php

                // Verifica si el usuario está autenticado
                if (!isset($_SESSION['name'])) {
                    // Redirige a la página de inicio de sesión si no está autenticado
                    exit();
                }

                // Crea una conexión a la base de datos (reemplaza con tus propios detalles)
                $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

                // Verifica la conexión
                if ($conexion->connect_error) {
                    die("Conexión fallida: " . $conexion->connect_error);
                }

                // Obtiene el nombre de usuario de la sesión
                $nombreUsuario = $_SESSION['name'];

                // Lógica para verificar si el usuario es un administrador
                $esAdmin = false;
                if ($nombreUsuario === 'admin'|| $_SESSION['name'] === 'Nelson') {
                    $esAdmin = true;
                }

                // Consulta todos los pedidos
                if ($esAdmin) {
                    $sql = "SELECT * FROM inventario_diario ORDER BY fecha_creacion DESC";
                } else {
                    $sql = "SELECT * FROM inventario_diario WHERE nombre_usuario = '$nombreUsuario' ORDER BY fecha_creacion DESC";
                }

                $resultado = $conexion->query($sql);
                ?>

                <!DOCTYPE html>
                <html lang="es">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Mis Pedidos</title>
                    <!-- Agrega aquí tus estilos CSS si es necesario -->
                    <style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        background: #fff;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px; /* Ajusta el ancho máximo de las celdas según sea necesario */
    }

    th {
        background-color: #f2f2f2;
    }

    /* Añade estilos adicionales para mejorar la adaptabilidad en dispositivos pequeños */
    @media only screen and (max-width: 600px) {
        table {
            border: 0;
        }

        table caption {
            font-size: 1.3em;
        }

        table thead {
            display: none;
        }

        table tr {
            border-bottom: 3px solid #ddd;
            display: block;
            margin-bottom: 10px;
        }

        table td {
            border-bottom: 1px solid #ddd;
            display: block;
            font-size: 0.8em;
            text-align: right;
            max-width: none; /* Elimina la limitación de ancho máximo en dispositivos pequeños */
        }

        table td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }
    }

    .acciones {
        display: flex;
        gap: 10px;
    }

    .acciones button {
        padding: 10px;
        cursor: pointer;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .acciones button:hover {
        background-color: #45a049;
    }
</style>

                </head>

                <body>

                    <!-- Agrega aquí el contenido HTML de la página -->

                    <?php
                    // Muestra los pedidos en una tabla
                    if ($resultado->num_rows > 0) {
                        echo '<table>';
                        echo '<thead><tr><th>ID</th><th>Fecha</th><th>Productos</th>';

                        // Agrega la columna de "Quién hizo el pedido" solo si es admin
                        if ($esAdmin) {
                            echo '<th>INVENTARIO</th>';
                        }

                        echo '<th>Acciones</th></tr></thead>';

                        while ($fila = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td data-label="ID">' . $fila['id'] . '</td>';
                            echo '<td data-label="Fecha">' . date('Y-m-d', strtotime($fila['fecha_creacion'])) . '</td>';
                            echo '<td data-label="Productos">' . nl2br($fila['lista_productos']) . '</td>';

                            // Muestra la columna de "Quién hizo el pedido" solo si es admin
                            if ($esAdmin) {
                                echo '<td data-label="Quién hizo el Pedido">' . $fila['nombre_usuario'] . '</td>';
                            }

                            echo '<td data-label="Acciones" class="acciones">';
                            if (!$fila['enviado']) {
                                
                                // Mostrar el botón de "Imprimir" solo si es admin
                                if ($esAdmin) {
                                    echo '<button onclick="imprimirLista(' . $fila['id'] . ')">Imprimir</button>';
                                }
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    } else {
                        echo '<p>No hay Inventario Registrado</p>';
                    }

                    // Cierra la conexión después de mostrar los resultados
                    $conexion->close();
                    ?>

                    <!-- Agrega aquí más contenido HTML si es necesario -->

                    <script>
                        function confirmarEnvio(pedidoId) {
                            // Pide un comentario obligatorio al confirmar el pedido
                            var comentario = prompt("Ingrese un comentario (obligatorio):");

                            // Verifica que se haya ingresado un comentario
                            if (comentario === null || comentario.trim() === "") {
                                alert("Debes ingresar un comentario para confirmar el pedido.");
                                return;
                            }

                            // Realiza una solicitud AJAX para actualizar la base de datos
                            fetch('actualizar_fecha_entrega.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'pedidoId=' + pedidoId + '&fechaEntregaConfirmada=' + new Date().toISOString().slice(0, 10) + '&comentario=' + comentario,
                            })
                                .then(response => {
                                    if (response.ok) {
                                        alert('Pedido ENTREGADO para el pedido con ID ' + pedidoId);
                                        // Actualiza la página o realiza otras acciones según sea necesario
                                        location.reload();
                                    } else {
                                        alert('Error al confirmar el envío.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                        }

                        function imprimirLista(pedidoId) {
                            // Abre una nueva ventana para imprimir la lista de productos
                            var ventanaImpresion = window.open('', '_blank');

                            // Realiza una solicitud AJAX para obtener la información del pedido
                            fetch('obtener_info_inventario.php?pedidoId=' + pedidoId)
                                .then(response => response.json())
                                .then(data => {
                                    // Construye el contenido HTML para imprimir
                                    var contenidoHTML = `
                                        <div>
                                            <p>INVENTARIO</p>
                                            <p></p>
                                            <h2>${data.nombreUsuario}</h2>
                                            <p>${data.fechaEntrega}</p>
                                            <p>Lista de Productos:</p>
                                            <pre>${data.listaProductos}</pre>
                                        </div>
                                    `;

                                    // Escribe el contenido en la ventana de impresión
                                    ventanaImpresion.document.write(`
                                        <html>
                                        <head>
                                            <title>Imprimir Pedido</title>
                                            <style>
                                                /* Agrega estilos si es necesario */
                                            </style>
                                        </head>
                                        <body>${contenidoHTML}</body>
                                        </html>
                                    `);

                                    // Imprime y cierra la ventana de impresión
                                    ventanaImpresion.print();
                                    ventanaImpresion.close();
                                })
                                .catch(error => {
                                    console.error('Error al obtener la información del pedido:', error);
                                });
                        }
                    </script>

                </body>

                </html>
            </div>
            <br>
            <br><br>
        </div>

        <?php require_once 'templates/sidebar.php'; ?>

    </div>
</div> <!-- /container -->
<?php require_once 'templates/footer.php'; ?>
