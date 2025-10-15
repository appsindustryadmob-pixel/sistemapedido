<?php require_once 'templates/header.php'; ?>

<style>
    .usuarios-page {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .usuarios-container {
        max-width: 800px;
        margin: 20px auto;
        box-sizing: border-box;
        padding: 0 15px;
    }

    .usuarios-h2 {
        text-align: center;
        color: #333;
        margin-bottom: 10px;
    }

    .usuarios-table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        background: #fff;
    }

    .usuarios-th, .usuarios-td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
    }

    .usuarios-th {
        background-color: #f2f2f2;
    }

    .usuarios-form {
        max-width: 560px;
        margin: 20px auto;
        background-color: #f4f4f4;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .usuarios-label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .usuarios-input, .usuarios-select, .usuarios-button {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    .usuarios-button {
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .usuarios-button:hover {
        background-color: #45a049;
    }

    /* Estilos responsive para dispositivos móviles */
    @media only screen and (max-width: 600px) {
        .usuarios-container {
            padding: 0 10px;
        }

        .usuarios-input, .usuarios-select, .usuarios-button {
            width: 100%;
        }
    }
</style>

<div class="usuarios-page">
    <div class="usuarios-container">
        <h2 class="usuarios-h2">Agregar Nuevo Usuario</h2>
        <form class="usuarios-form" action="usuarios.php" method="post">
            <label class="usuarios-label" for="nombre">Nombre:</label>
            <input class="usuarios-input" type="text" id="nombre" name="nombre" required>

            <label class="usuarios-label" for="email">Email:</label>
            <input class="usuarios-input" type="email" id="email" name="email" required>

            <label class="usuarios-label" for="password">Contraseña:</label>
            <input class="usuarios-input" type="password" id="password" name="password" required>

            <label class="usuarios-label" for="rol">Rol:</label>
            <select class="usuarios-select" id="rol" name="rol">
                <option value="1">Administrador</option>
                <option value="0">Usuario</option>
            </select>

            <button class="usuarios-button" type="submit">Agregar Usuario</button>
        </form>
    </div>

    <div class="usuarios-container">
        <h2 class="usuarios-h2">Usuarios</h2>
        <?php
        $conexion = new mysqli('localhost', 'd279606_adminsumi', 'vv7820396$$', 'd279606_dbsumi');

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $sql = "SELECT * FROM users";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            echo '<table class="usuarios-table">';
            echo '<thead><tr><th class="usuarios-th">ID</th><th class="usuarios-th">Nombre</th><th class="usuarios-th">Email</th><th class="usuarios-th">Rol</th></tr></thead>';

            while ($fila = $resultado->fetch_assoc()) {
                echo '<tr>';
                echo '<td class="usuarios-td" data-label="ID">' . $fila['user_id'] . '</td>';
                echo '<td class="usuarios-td" data-label="Nombre">' . $fila['name'] . '</td>';
                echo '<td class="usuarios-td" data-label="Email">' . $fila['email'] . '</td>';
                echo '<td class="usuarios-td" data-label="Rol">' . ($fila['role'] == '1' ? 'Administrador' : 'Usuario') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No hay usuarios registrados.</p>';
        }

        $conexion->close();
        ?>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>
