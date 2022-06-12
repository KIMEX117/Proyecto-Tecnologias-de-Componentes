<?php
	session_start(); 
	if(!isset($_SESSION['Usuario'])){
    	header("location: index.php");
	}
	include_once('logica/baseDatos.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
	<title>SalchiVentas</title>
	<style type="text/css">
		.li-venta{
			border-top: 1px solid #4f5962!important;
			border-bottom: 1px solid #4f5962!important;
			padding-top: 0.3rem;
			padding-bottom: 0.3rem;
		}
		.btn-venta{
			background-color: #fb8d77!important;
			color: #FFFFFF!important;
		}
		.lbl-formulario{
			padding-top: 0.6rem;
		}
		.btn-registrar{
			margin-bottom: 1rem;
		}
	</style>
 	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
  	<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  	<link rel="icon" href="img/icono.png">
</head>
<body>
	<div class="wrapper">
  		<!-- BARRA SUPERRIOR -->
  		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    		<!-- MOVER MENÚ E INICIO -->
    		<ul class="navbar-nav">
      			<li class="nav-item">
        			<a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
      			</li>
      			<li class="nav-item d-sm-inline-block">
        			<a href="logica/logout.php" class="nav-link">Cerrar sesión</a>
      			</li>
    		</ul>
    		<!-- BOTÓN DE PANTALLA COMPLETA -->
    		<ul class="navbar-nav ml-auto">
      			<li class="nav-item">
        			<a class="nav-link" data-widget="fullscreen" role="button">
          				<i class="fas fa-expand-arrows-alt"></i>
       				</a>
     			</li>
    		</ul>
  		</nav>

  		<!-- MENÚ LATERAL IZQUIERDO -->
  		<aside class="main-sidebar sidebar-dark-primary elevation-4">
    		<a href="dashboard.php" class="brand-link">
      			<img src="img/logo con fondo.png" class="brand-image img-circle elevation-3" style="opacity: .9">
      			<span class="brand-text font-weight-normal">SalchiVentas</span>
    		</a>
    		<div class="sidebar">
      			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        			<div class="image">
          				<img src="img/user.png" class="img-circle elevation-2">
        			</div>
        			<?php
						if(isset($_SESSION['Usuario'])) {
							echo "<div class='info'><a class='d-block'>".$_SESSION["Usuario"]."</a></div>";
						}
					?>
      			</div>
      			<!-- OPCIONES DEL MENÚ LATERAL -->
      			<nav class="mt-2">
        			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          				<li class="nav-item">
            				<a href="dashboard.php" class="nav-link">
            					<img src="img/dashboard.png" class="nav-icon">
              					<p>Dashboard</p>
            				</a>
          				</li>
          				<li class="nav-item">
            				<a href="productos.php" class="nav-link">
              					<img src="img/cubiertos.png" class="nav-icon">
              					<p>Productos</p>
            				</a>
          				</li>
          				<li class="nav-item">
            				<a href="ventas.php" class="nav-link">
              					<img src="img/ventas.png" class="nav-icon">
              					<p>Ventas</p>
            				</a>
          				</li>
          				<li class="nav-item">
            				<a href="usuarios.php" class="nav-link active">
              					<img src="img/group.png" class="nav-icon">
              					<p>Usuarios</p>
            				</a>
          				</li>
          				<li class="nav-item li-venta">
            				<a href="realizarVenta.php" class="nav-link">
            					<img src="img/Venta.png" class="nav-icon">
              					<p>Realizar venta</p>
            				</a>
          				</li>
        			</ul>
      			</nav>
    		</div>
  		</aside>

  		<!-- DIV PRINCIPAL DE CONTENIDO -->
  		<div class="content-wrapper">
    		<!-- DIV ENCABEZADO -->
    		<div class="content-header">
      			<div class="container-fluid">
        			<div class="row mb-2">
          				<div class="col-sm-6">
            				<h1>Usuarios</h1>
          				</div>
        			</div>
      			</div>
    		</div>
    		<!-- DIV DE CONTENIDO -->
    		<div class="content">
      			<div class="container-fluid">
      				<div class="row">
						<div class="col-md-12">
							<!-- BOTÓN PARA DESPLEGAR EL MODAL QUE CONTIENE EL FORMULARIO DE REGISTRO -->
							<button type="button" class="btn btn-primary btn-registrar" data-toggle="modal" data-target="#registroUsuario">
	      						<i class="fa-solid fa-file-pen"></i>
	      						Registrar usuario nuevo
	      					</button>
	      					<!-- FORMULARIO DE REGISTRO DE USUARIO NUEVO (MODAL) -->
							<div class="modal fade" id="registroUsuario" tabindex="-1" role="dialog" aria-labelledby="registroUsuarioLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
							    <div class="modal-dialog modal-dialog-centered" role="document">
							        <div class="modal-content">
							            <div class="modal-header">
							                <h5 class="modal-title h3" id="registroUsuarioLabel"><strong>Registro de usuario nuevo</strong> </h5>
							                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							                <span aria-hidden="true">&times;</span>
							                </button>
							            </div>
							            <div class="modal-body">
							                <form method="POST" action="logica/registrarUsuario.php" class="card-body">
											    <div class="form-group">
											        <label for="nombreUsuario">Nombre de usuario</label>
											        <input type="text" name="nombreUsuario" class="form-control" maxlength="20" placeholder="Ingrese el usuario (min. 5 / max. 20 caracteres)">
											        <label for="password" class="lbl-formulario">Contraseña</label>
											        <input type="password" name="password" class="form-control" maxlength="16" placeholder="Ingrese la contraseña (min. 8 / max. 16 caracteres)">
											        <label for="repetirPassword" class="lbl-formulario">Repetir contraseña</label>
											        <input type="password" name="repetirPassword" class="form-control" maxlength="16" placeholder="Vuelva a ingresar la contraseña de arriba">
											        <label for="rol" class="lbl-formulario">Rol</label>
													<select name="rol" class="form-control" >
													    <option value="Empleado">Empleado</option>
													    <option value="Administrador">Administrador</option>
													</select>
											    </div>
											    <?php
													if (isset($_GET['errorRegistro'])) {
														switch ($_GET['errorRegistro']) {
															case '1':
																echo '<div class="formulario"><p>* No pueden quedar campos vacíos.</p></div>';
																break;	
															case '2':
																echo '<div class="formulario"><p>* El nombre de usuario debe ser mayor o igual a 5 caracteres, hasta un máximo de 20. </p></div>';
																break;	
															case '3':
																echo '<div class="formulario"><p>* La contraseña solo admite  8 caracteres mínimo y 16 máximo.</p></div>';
																break;	
															case '4':
																echo '<div class="formulario"><p>* Las contraseñas no coinciden.</p></div>';
																break;					
															default:
																break;
														}
													}
												?>
											    <div class="card-footer" style="background-color: white!important; margin-top: 2rem; padding: 0!important;">
							                        <button type="submit" class="btn btn-primary">Registrar</button>
							                        <button type="button" class="btn btn-danger" style="float: right;" data-dismiss="modal">Cancelar</button>
							                    </div>
											</form>
							            </div>
							        </div>
							    </div>
							</div>
							<!-- DIV DE LA TARJETA "TABLA USUARIOS" -->
	        				<div class="card card-primary">
				                <div class="card-header bg-gray-dark">
				                    <h3 class="card-title">Tabla de usuarios registrados</h3>
				                	<div class="card-tools">
				                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
			                        		<i class="fas fa-expand"></i>
										</button>
				                    </div>
				                </div>
								<div class="card-body">
								    <table id="tablaUsuarios" class="table table-striped table-bordered" style="width:100%">
				                		<thead>
				                			<tr>
				                				<th>ID</th>
				                				<th>Usuario</th>
				                				<th>Rol</th>
				                				<th class="text-center">Acciones</th>
				                			</tr>
				                		</thead>
				                		<tbody>
				                			<?php
				                				$query = $conexion->query("SELECT * FROM usuarios");
												while ($dataUsuarios = mysqli_fetch_array($query)){
													echo "<tr>";
													echo "<td>".$dataUsuarios['ID_Usuario']."</td>";
													echo "<td>".$dataUsuarios['Usuario']."</td>";
													echo "<td>".$dataUsuarios['Rol']."</td>";
													echo "
														<td class='project-actions text-center'>
															<a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarUsuario".$dataUsuarios['ID_Usuario']."'>
																<i class='fas fa-pencil-alt'></i> Editar
															</a>
															<a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#eliminarUsuario".$dataUsuarios['ID_Usuario']."'>
																<i class='fas fa-trash'></i> Eliminar
															</a>
														</td>";
													echo "</tr>";
												?>
													<!-- EDITAR USUARIO -->
													<div class="modal fade" id="editarUsuario<?php echo $dataUsuarios['ID_Usuario']; ?>" tabindex="-1" role="dialog" aria-labelledby="editarUsuarioLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
													    <div class="modal-dialog modal-dialog-centered" role="document">
													        <div class="modal-content">
													            <div class="modal-header">
													                <h5 class="modal-title h3" id="editarUsuarioLabel"><strong>Editar datos de usuario</strong> </h5>
													                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
													                <span aria-hidden="true">&times;</span>
													                </button>
													            </div>
													            <div class="modal-body">
													                <form method="POST" action="logica/editarUsuario.php">
													                    <div class="form-group">
													                    	<input type="hidden" name="editarId" <?php echo "value='".$dataUsuarios['ID_Usuario']."'"; ?>>
													                        <label for="nombreUsuario">Nombre de usuario</label>
													                        <input type="text" name="nombreUsuario" class="form-control" maxlength="20" placeholder="Ingrese el usuario (min. 5 / max. 20 caracteres)" value="<?php echo $dataUsuarios['Usuario']; ?>" required="true">
													                        <label for="rol" class="lbl-formulario">Rol</label>
													                        <select name="rol" class="form-control" >
													                            <option value="Empleado" <?php  if($dataUsuarios['Rol']=='Empleado') echo "selected='selected'"; ?>>Empleado</option>
													                            <option value="Administrador" <?php  if($dataUsuarios['Rol']=='Administrador') echo "selected='selected'"; ?>>Administrador</option>
													                        </select>
													                    </div>
													                    <?php
													                        if (isset($_GET['errorEditar'])) {
													                        	switch ($_GET['errorEditar']) {
													                        		case '1':
													                        			echo '<div class="formulario"><p>* No pueden quedar campos vacíos.</p></div>';
													                        			break;	
													                        		case '2':
													                        			echo '<div class="formulario"><p>* El nombre de usuario debe ser mayor o igual a 5 caracteres, hasta un máximo de 20. </p></div>';
													                        			break;					
													                        		default:
													                        			break;
													                        	}
													                        }
													                        ?>
													                    <div class="card-footer" style="background-color: white!important; margin-top: 2rem; padding: 0!important;">
													                        <button type="submit" class="btn btn-primary">Actualizar</button>
													                        <button type="button" class="btn btn-danger" style="float: right;" data-dismiss="modal">Cancelar</button>
													                    </div>
													                </form>
													            </div>
													        </div>
													    </div>
													</div>
													<!-- ELIMINAR USUARIO -->
													<div class="modal fade" id="eliminarUsuario<?php echo $dataUsuarios['ID_Usuario']; ?>" tabindex="-1" role="dialog" aria-labelledby="eliminarUsuarioLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
													    <div class="modal-dialog modal-dialog-centered" role="document">
													        <div class="modal-content">
													            <div class="modal-header">
													                <h5 class="modal-title h3" id="eliminarUsuarioLabel"><strong>Eliminar usuario</strong> </h5>
													                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
													                <span aria-hidden="true">&times;</span>
													                </button>
													            </div>
													            <div class="modal-body">
													                <form method="POST" action="logica/eliminarUsuario.php">
													                    <div class="form-group">
													                    	<input type="hidden" name="eliminarId" <?php echo "value='".$dataUsuarios['ID_Usuario']."'"; ?>>
													                    	<h4 style="text-align: center;">¿Estas seguro que deseas eliminar este usuario?</h4>
													                        <label for="nombreUsuario">Nombre de usuario</label>
													                        <input type="text" name="nombreUsuario" class="form-control" value="<?php echo $dataUsuarios['Usuario']; ?>" disabled>
													                        <label for="rol" class="lbl-formulario">Rol</label>
													                        <select name="rol" class="form-control" disabled>
													                            <option value="Empleado" <?php  if($dataUsuarios['Rol']=='Empleado') echo "selected='selected'"; ?>>Empleado</option>
													                            <option value="Administrador" <?php  if($dataUsuarios['Rol']=='Administrador') echo "selected='selected'"; ?>>Administrador</option>
													                        </select>
													                        
													                    </div>
													                    <div class="card-footer" style="background-color: white!important; margin-top: 2rem; padding: 0!important;">
													                        <button type="submit" class="btn btn-primary">Aceptar</button>
													                        <button type="button" class="btn btn-danger" style="float: right;" data-dismiss="modal">Cancelar</button>
													                    </div>
													                </form>
													            </div>
													        </div>
													    </div>
													</div>
											<?php
												}
				                			?>
				                		</tbody>
				                	</table>
								</div>
							</div>
						</div>
      				</div>
      			</div>
    		</div>
  		</div>
  		<!-- PIE DE PÁGINA -->
		<footer class="main-footer">
			<strong>Proyecto final:</strong> Punto de Venta - Tecnología de Componentes
    		<div class="float-right d-none d-sm-inline">
      			<p>IDS 6to TM - UABCS</p>
    		</div>
  		</footer>
	</div>

	<!-- COSAS IMPORTADAS -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="js/adminlte.min.js"></script>
	<script src="plugins/chart.js/Chart.min.js"></script>
	<script src="plugins/toastr/toastr.min.js"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

	<!-- FORMATO PARA TABLA DE USUARIOS -->
	<script>
		$(document).ready(function () {
		    $('#tablaUsuarios').DataTable({
		    	"language": {
		    		"search"	: "Buscar",
		    		"lengthMenu": "Mostrar _MENU_ registros por página",
		    		"info"		: "Visualizando página _PAGE_ de _PAGES_"
		    	}
		    });
		});
	</script>

	<!-- TOASTR PARA MOSTRAR MENSAJE DE "USUARIO REGISTRADO EXITOSAMENTE" -->
	<script>
		<?php if(isset($_GET['success'])): ?>
				toastr.options = {
				  "closeButton": false,
				  "debug": false,
				  "newestOnTop": false,
				  "progressBar": true,
				  "positionClass": "toast-top-right",
				  "preventDuplicates": true,
				  "onclick": null,
				  "showDuration": "300",
				  "hideDuration": "1000",
				  "timeOut": "5000",
				  "extendedTimeOut": "1000",
				  "showEasing": "swing",
				  "hideEasing": "linear",
				  "showMethod": "fadeIn",
				  "hideMethod": "fadeOut"
				};
				toastr.success("Acción realizada exitosamente." , "AVISO");
		<?php endif ?>
	</script>

	<!-- MOSTRAR NUEVAMENTE MODALS EN CASO DE ALGUN ERROR --> 	
	<script>
		<?php 
	    	if (isset($_GET['errorRegistro'])) {
				if($_GET['errorRegistro'] == 1 || $_GET['errorRegistro'] == 2 || $_GET['errorRegistro'] == 3 || $_GET['errorRegistro'] == 4){	?>
					var modalRegistroUsuario = new bootstrap.Modal(document.getElementById("registroUsuario"));
					modalRegistroUsuario.show();
		<?php	} 
			}
			if (isset($_GET['errorEditar'])) {
				if($_GET['errorEditar'] == 1 || $_GET['errorEditar'] == 2){ ?>
					var modalEditarUsuario = new bootstrap.Modal(document.getElementById("editarUsuario<?php echo $_GET['idUsuario'] ?>"));
					modalEditarUsuario.show();
		<?php	} 
			} 
		?>
	</script>
</body>
</html>