<?php
// Establecer la zona horaria a Panam芍
date_default_timezone_set('America/Panama');

// Conectar con la base de datos (reemplaza con tus propios detalles)
$conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

// Verificar la conexi車n
if ($conexion->connect_error) {
    die("Conexi車n fallida: " . $conexion->connect_error);
}

// Incluir el encabezado y otras partes comunes del HTML
require_once 'templates/header.php';
?>

<style>
    .resumen-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        background: #fff;
    }

    .resumen-table th, .resumen-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
    }

    .resumen-table th {
        background-color: #f2f2f2;
    }
</style>

<div class="content">
    <div class="container">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <h2>Resumen de Productos para Pedidos</h2>

            <!-- Formulario para seleccionar la fecha -->
            <form method="post" action="">
                <label for="fecha_entrega">Selecciona la fecha de entrega:</label>
                <input type="date" name="fecha_entrega" required>
                <button type="submit" name="generar_resumen">Generar Resumen</button>
            </form>

            <!-- Contenido de la tabla de resumen -->
            <?php
            if (isset($_POST['generar_resumen'])) {
                $fecha_entrega = $_POST['fecha_entrega'];

                // Muestra la fecha seleccionada para depuraci車n
                echo '<p>Fecha de entrega seleccionada: ' . $fecha_entrega . '</p>';

                $sqlResumen = "SELECT 
                        SUBSTRING_INDEX(SUBSTRING_INDEX(p.lista_productos, ' - ', 1), ' -', 1) AS producto,
                        SUM(CASE 
                                WHEN p.lista_productos LIKE '%(%'
                                THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p.lista_productos, '(', -1), ')', 1) AS UNSIGNED)
                                ELSE 1
                            END
                        ) AS cantidad_total
                    FROM pedidos p
                    WHERE p.resumen_fecha = '$fecha_entrega'
                    GROUP BY producto";

                $resultadoResumen = $conexion->query($sqlResumen);

                // Muestra la consulta SQL para depuraci車n
                echo '<p>Consulta SQL: ' . $sqlResumen . '</p>';

                // Muestra la tabla de resumen con estilos CSS mejorados
                echo '<table class="resumen-table">';
                echo '<thead><tr><th>Producto</th><th>Cantidad Total</th></tr></thead>';
                echo '<tbody>';

                if ($resultadoResumen) {
                    while ($filaResumen = $resultadoResumen->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $filaResumen['producto'] . '</td>';
                        echo '<td>' . $filaResumen['cantidad_total'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    // Muestra cualquier error SQL
                    echo '<tr><td colspan="2">Error SQL: ' . $conexion->error . '</td></tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }
            ?>

        </div>
        <br>
        <br><br>
    </div>

    <?php require_once 'templates/sidebar.php'; ?>
</div> <!-- /container -->

<?php require_once 'templates/footer.php'; ?>
