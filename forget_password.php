<?php require_once 'config.php'; ?>
<?php 
	if(!empty($_POST)){
		try {
			$user_obj = new Cl_User();
			$data = $user_obj->forgetPassword( $_POST );
			if($data)$success = PASSWORD_RESET_SUCCESS;
		} catch (Exception $e) {
			$error = $e->getMessage();
		}
	}
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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Olvidé mi contraseña</title>

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
  </head>
  <body>
	<div class="container">
		<?php require_once 'templates/ads.php';?>
		<div class="login-form">
			<div class="form-header">
				<i class="fa fa-user" style="color: green;"></i>
			</div>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="forgetpassword-form" method="post"  class="form-register" role="form">
				<div>
					<input id="email" name="email" type="email" class="form-control" placeholder="Correo electrónico">  
					<span class="help-block"></span>
				</div>
				<button class="btn btn-block bt-login" type="submit" id="agregar-producto" data-loading-text="Restableciendo contraseña....">Restablecer Contraseña</button>
			</form>
			<div class="form-footer">
				<div class="row">
					<div class="col-xs-6 col-sm-6 col-md-6">
						<i class="fa fa-lock"></i>
						<a href="index.php">  Iniciar sesión  </a>
					
					</div>
					
					<div class="col-xs-6 col-sm-6 col-md-6">
						<i class="fa fa-check"></i>
						<a href="register.php"> Registrarse </a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /container -->

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/forgetpassword.js"></script>
  </body>
</html>