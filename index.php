<?php 
ob_start();

require_once 'config.php'; 
?>
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
        background-color: #4caf50; /* Cambiado a verde */
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
<?php 
    if( !empty( $_POST )){
        try {
            $user_obj = new Cl_User();
            $data = $user_obj->login( $_POST );
            if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
                header('Location: home.php');
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
    //print_r($_SESSION);
    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
        header('Location: home.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistemas Pedidos</title>
    <link rel="manifest" href="/clientearea/manifest.json">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container">
        <?php require_once 'templates/ads.php';?>
        <div class="login-form">
            <div class="alert alert-danger" role="alert">
                <strong>¡Atención!</strong> El horario de pedidos es de 8am a 11pm.
            </div>
            <?php require_once 'templates/message.php';?>
            <div class="form-header">
                <i class="fa fa-user" style="color: green;"></i>
            </div>
            <form id="login-form" method="post" class="form-signin" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input name="email" id="email" type="email" class="form-control" placeholder="Correo electrónico" autofocus> 
                <input name="password" id="password" type="password" class="form-control" placeholder="Contraseña"> 
                <button class="btn btn-block bt-login" type="submit" id="agregar-producto" data-loading-text="Iniciando....">Iniciar sesión</button>
            </form>
            <div class="form-footer">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <i class="fa fa-lock"></i>
                        <a href="forget_password.php"> Olvidó su contraseña? </a>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <i class="fa fa-check"></i>
                        <a href="register.php"> Registrarse </a>
                    </div>
                </div>
                <!-- boton instalar app -->
                <div id="install-button" style="display: none;">
                  <button class="btn btn-block bt-login" type="submit" id="agregar-producto" data-loading-text="Iniciando....">Instalar App</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /container -->
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/login.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/clientearea/service-worker.js')
            .then(registration => {
                console.log('Service Worker registrado con éxito:', registration);
            })
            .catch(error => {
                console.log('Error al registrar el Service Worker:', error);
            });
        }
    </script>
    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault();
            deferredPrompt = event;

            showInstallButton();
        });

        function showInstallButton() {
            const installButton = document.getElementById('install-button');

            installButton.style.display = 'block';

            installButton.addEventListener('click', () => {
                deferredPrompt.prompt();

                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('El usuario ha aceptado instalar la PWA');

                        // Aquí, puedes redirigir al usuario después de la instalación
                        window.location.href = '/clientearea/';
                    } else {
                        console.log('El usuario ha rechazado la instalación de la PWA');
                    }

                    deferredPrompt = null;
                });
            });
        }
    </script>
  </body>
</html>
<?php ob_end_flush(); ?>
