<!-- Agrega este contenido en modal.php -->

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Reporte de Compras</h2>

        <!-- Aquí mostrarás el contenido del reporte -->
        <div id="reportContent"></div>

        <!-- Puedes agregar estilos adicionales según tus necesidades -->
        <style>
            /* Estilos para el modal */
            .modal {
                display: none; /* Oculta el modal por defecto */
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
                padding-top: 60px;
            }

            .modal-content {
                background-color: #fefefe;
                margin: 5% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }

            /* Estilos para el botón de cerrar */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
    </div>
</div>

<script>
    // Función para cerrar el modal
    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }
</script>
