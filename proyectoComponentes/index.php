<?php
	session_start(); 
	if(isset($_SESSION['Usuario'])){
    	header("location: dashboard.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
	<title>Login - SalchiVentas</title>
	<style type="text/css">
		.fondo {
			background-color: #eaf56e !important;
		};
	</style>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  	<link rel="icon" href="img/icono.png">
</head>
<body class="login-page fondo" style="min-height: 466px;">
    <div class="login-box shadow">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
            	<img src="img/logoRecortado.png"  >
				<h2><b>SalchiVentas</b></h2>
            </div>
            <div class="card-body">
                <p class="login-box-msg h3" style="padding-bottom: 0.5rem;">Inicio de sesión</p>
                <form action="logica/login.php" method="POST">
                	<label for="user">Usuario</label>
                    <div class="input-group mb-3">
                        <input type="text" name="user" class="form-control" placeholder="Ingrese el usuario">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <label for="password">Contraseña</label>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Ingrese la contraseña">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa-solid fa-key"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding-bottom: 0.7rem">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 0.7rem;">Iniciar sesión</button>
                        </div>
                    </div>
                    <?php
						if (isset($_GET['error'])) {
							switch ($_GET['error']) {
								case '1':
									echo '<div class="col-12"><p>* Falló en la conexión, intente nuevamente.</p></div>';
									break;	
								case '2':
									echo '<div class="col-12"><p>* Usuario inválido.</p></div>';
									break;	
								case '3':
									echo '<div class="col-12"><p>* Contraseña incorrecta.</p></div>';
									break;
								case '4':
									echo '<div class="col-12"><p>* No pueden quedar campos vacíos.</p></div>';
									break;							
								default:
									break;
							}
						}
					?>
                </form>
            </div>
        </div>
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>