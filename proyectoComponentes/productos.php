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
		.lbl-formulario{
			padding-top: 0.6rem;
		}
		.btn-registrar{
			margin-bottom: 1rem;
		}
		/*input[type="file"] {
		    display: none;
		}
		.custom-file-upload {
		    border: 1px solid #ccc;
		    display: inline-block;
		    padding: 6px 12px;
		    cursor: pointer;
		}*/
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
            				<a href="productos.php" class="nav-link active">
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
          				<?php
          					if($_SESSION['Rol']=="Administrador"){
          				?>
          					<li class="nav-item">
	            				<a href="usuarios.php" class="nav-link">
	              					<img src="img/group.png" class="nav-icon">
	              					<p>Usuarios</p>
	            				</a>
	          				</li>
          				<?php
          					}
          				?>
          				<li class="nav-item li-venta">
            				<a href="realizarVenta.php" class="nav-link btn-venta">
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
            				<h1>Productos</h1>
          				</div>
        			</div>
      			</div>
    		</div>
    		<!-- DIV DE CONTENIDO -->
    		<div class="content">
      			<div class="container-fluid">
					<div class="col-md-12">
						<!-- BOTÓN PARA DESPLEGAR EL MODAL QUE CONTIENE EL FORMULARIO DE REGISTRO -->
	      				<button type="button" class="btn btn-primary btn-registrar" data-toggle="modal" data-target="#registroProducto">
	      					<i class="fa-solid fa-file-pen"></i>
	      					Registrar producto nuevo
	      				</button>
	      				<!-- FORMULARIO DE REGISTRO DE PRODUCTO NUEVO (MODAL) -->
						<div class="modal fade" id="registroProducto" tabindex="-1" role="dialog" aria-labelledby="registroProductoLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
						    <div class="modal-dialog modal-dialog-centered" role="document">
						        <div class="modal-content">
						            <div class="modal-header">
						                <h5 class="modal-title h3" id="registroProductoLabel"><strong>Registro de producto nuevo</strong> </h5>
						                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						                <span aria-hidden="true">&times;</span>
						                </button>
						            </div>
						            <div class="modal-body">
						                <form method="POST" action="logica/registrarProducto.php" class="card-body" style="padding-top: 0.6rem!important" enctype="multipart/form-data">
						                    <div class="form-group">
						                        <label for="nombreProducto">Nombre del producto</label>
						                        <input type="text" name="nombreProducto" class="form-control" maxlength="30" placeholder="Ingresa el nombre">
						                        <label for="descripcion" class="lbl-formulario">Descripción</label>
						                        <textarea name="descripcion" class="form-control" style="resize: none;" rows="3" maxlength="200" placeholder="Da una breve explicación..."></textarea>
						                        <div class="row">
						                            <div class="col-md-6 col-sm-6">
						                                <label for="tipo" class="lbl-formulario">Tipo</label>
						                                <select name="tipo" class="form-control" >
						                                    <option value="Alimento">Alimento</option>
						                                    <option value="Bebida">Bebida</option>
						                                </select>
						                            </div>
						                            <div class="col-md-6 col-sm-6">
						                                <label for="costo" class="lbl-formulario">Costo</label>
						                                <input type="number" name="costo" class="form-control" min="1" pattern="^[0-9]+">
						                            </div>
						                        </div>
						                        <div class="row">
						                            <div class="col-md-6 col-sm-6">
						                                <label for="stock" class="lbl-formulario">Stock</label>
						                                <input type="number" name="stock" class="form-control" min="1" pattern="^[0-9]+">
						                            </div>
						                            <div class="col-md-6 col-sm-6">
						                                <label for="precio" class="lbl-formulario">Precio</label>
						                                <input type="number" name="precio" class="form-control" min="1" pattern="^[0-9]+">
						                            </div>
						                        </div>
						                        <label for="imagen" style="padding-top: 0.8rem; margin-bottom: 0rem;">Imagen en formato .JPG</label>
											    <div class="input-group">
											        <div class="custom-file">
											            <input type="file" name="imagen">
											        </div>
											    </div>
						                        <?php
						                            if (isset($_GET['errorRegistro'])) {
						                            	switch ($_GET['errorRegistro']) {
															case '1':
																echo '<div class="formulario" style="margin-top: 0.8rem;"><p>* No pueden quedar campos vacíos.</p></div>';
																break;	
															case '2':
																echo '<div class="formulario" style="margin-top: 0.8rem;"><p>* Falta subir una imagen o el formato del archivo no es válido.</p></div>';
																break;	
															case '3':
																echo '<div class="formulario" style="margin-top: 0.8rem;"><p>* Surgió un error al subir la imagen, intentelo de nuevo.</p></div>';
																break;							
															default:
																break;
														}
						                            }
						                        ?>
						                    </div>
						                    <div class="card-footer" style="background-color: white!important; margin-top: 2rem; padding: 0!important;">
						                        <button type="submit" class="btn btn-primary">Registrar</button>
						                        <button type="button" class="btn btn-danger" style="float: right;" data-dismiss="modal">Cancelar</button>
						                    </div>
						                </form>
						            </div>
						        </div>
						    </div>
						</div>
						<!-- DIV DE LA TARJETA "TABLA PRODUCTOS" -->
						<div class="card card-primary">
							<div class="card-header bg-gray-dark">
			                    <h3 class="card-title">Tabla de productos</h3>
			                	<div class="card-tools">
			                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
			                        	<i class="fas fa-expand"></i>
									</button>
			                    </div>
			                </div>
			                <div class="card-body">
			                	<table id="tablaProductos" class="table table-striped table-bordered" style="width:100%">
			                		<thead>
			                			<tr>
			                				<th>ID</th>
			                				<th>Nombre</th>
			                				<th>Descripción</th>
			                				<th>Tipo</th>
			                				<th>Costo</th>
			                				<th>Stock</th>
			                				<th>Precio</th>
			                				<?php
			                					if($_SESSION['Rol']=="Administrador"){
			                				?>
			                						<th class="text-center">Acciones</th>
			                				<?php		
			                					}
			                				?>
			                			</tr>
			                		</thead>
			                		<tbody>
			                			<?php
			                				$query = $conexion->query("SELECT * FROM productos");
			                				while ($dataProductos = mysqli_fetch_array($query)){
												echo "<tr>";
												echo "<td>".$dataProductos['ID_Producto']."</td>";
												echo "<td>".$dataProductos['Nombre']."</td>";
												echo "<td>".$dataProductos['Descripcion']."</td>";
												echo "<td>".$dataProductos['Tipo']."</td>";
												echo "<td>$".$dataProductos['Costo']."</td>";
												echo "<td>".$dataProductos['Stock']."</td>";
												echo "<td>$".$dataProductos['Precio']."</td>";
          										if($_SESSION['Rol']=="Administrador"){
          											echo "
													<td class='project-actions text-center'>
														<a class='btn btn-primary btn-sm' data-toggle='modal' data-target='#editarProducto".$dataProductos['ID_Producto']."'>
															<i class='fas fa-pencil-alt'></i> Editar
														</a>
														<a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#eliminarProducto".$dataProductos['ID_Producto']."'>
															<i class='fas fa-trash'></i> Eliminar
														</a>
													</td>";
          										}
												echo "</tr>";
											?>

											<!-- EDITAR PRODUCTO -->
											<div class="modal fade" id="editarProducto<?php echo $dataProductos['ID_Producto']; ?>" tabindex="-1" role="dialog" aria-labelledby="editarProductoLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
											    <div class="modal-dialog modal-dialog-centered" role="document">
											        <div class="modal-content">
											            <div class="modal-header">
											                <h5 class="modal-title h3" id="editarProductoLabel"><strong>Editar datos de producto</strong> </h5>
											                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
											                <span aria-hidden="true">&times;</span>
											                </button>
											            </div>
											            <div class="modal-body">
											                <form method="POST" action="logica/editarProducto.php" style="padding-top: 0.6rem!important">
											                    <div class="form-group">
											                    	<input type="hidden" name="editarId" <?php echo "value='".$dataProductos['ID_Producto']."'"; ?>>
											                        <label for="nombreProducto">Nombre del producto</label>
											                        <input type="text" name="nombreProducto" class="form-control" maxlength="30" placeholder="Ingresa el nombre"  value="<?php echo $dataProductos['Nombre']; ?>" required="true">
											                        <label for="descripcion" class="lbl-formulario">Descripción</label>
											                        <textarea name="descripcion" class="form-control" style="resize: none;" rows="3" maxlength="200" placeholder="Da una breve explicación..."><?php echo $dataProductos['Descripcion']; ?></textarea>
											                        <div class="row">
											                            <div class="col-md-6 col-sm-6">
											                                <label for="tipo" class="lbl-formulario">Tipo</label>
											                                <select name="tipo" class="form-control" >
											                                    <option value="Alimento" <?php  if($dataProductos['Tipo']=='Alimento') echo "selected='selected'"; ?>>Alimento</option>
											                                    <option value="Bebida" <?php  if($dataProductos['Tipo']=='Bebida') echo "selected='selected'"; ?>>Bebida</option>
											                                </select>
											                            </div>
											                            <div class="col-md-6 col-sm-6">
											                                <label for="costo" class="lbl-formulario">Costo</label>
											                                <input type="number" name="costo" class="form-control" min="1" pattern="^[0-9]+" value="<?php echo $dataProductos['Costo']; ?>">
											                            </div>
											                        </div>
											                        <div class="row">
											                            <div class="col-md-6 col-sm-6">
											                                <label for="stock" class="lbl-formulario">Stock</label>
											                                <input type="number" name="stock" class="form-control" min="1" pattern="^[0-9]+" value="<?php echo $dataProductos['Stock']; ?>">
											                            </div>
											                            <div class="col-md-6 col-sm-6">
											                                <label for="precio" class="lbl-formulario">Precio</label>
											                                <input type="number" name="precio" class="form-control" min="1" pattern="^[0-9]+" value="<?php echo $dataProductos['Precio']; ?>">
											                            </div>
											                        </div>
											                        <?php
											                            if (isset($_GET['errorEditar'])) {
											                            	if ($_GET['errorEditar'] == 1) {
											                            		echo '<div class="formulario"><p>* No pueden quedar campos vacíos.</p></div>';
											                            	}
											                            }
											                        ?>
											                    </div>
											                    <div class="card-footer" style="background-color: white!important; margin-top: 2rem; padding: 0!important;">
											                        <button type="submit" class="btn btn-primary">Actualizar</button>
											                        <button type="button" class="btn btn-danger" style="float: right;" data-dismiss="modal">Cancelar</button>
											                    </div>
											                </form>
											            </div>
											        </div>
											    </div>
											</div>
											<!-- ELIMINAR PRODUCTO -->
											<div class="modal fade" id="eliminarProducto<?php echo $dataProductos['ID_Producto']; ?>" tabindex="-1" role="dialog" aria-labelledby="eliminarProductoLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
											    <div class="modal-dialog modal-dialog-centered" role="document">
											        <div class="modal-content">
											            <div class="modal-header">
											                <h5 class="modal-title h3" id="eliminarProductoLabel"><strong>Eliminar producto</strong> </h5>
											                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
											                <span aria-hidden="true">&times;</span>
											                </button>
											            </div>
											            <div class="modal-body">
											                <form method="POST" action="logica/eliminarProducto.php" style="padding-top: 0.6rem!important">
											                    <div class="form-group">
											                    	<input type="hidden" name="eliminarId" <?php echo "value='".$dataProductos['ID_Producto']."'"; ?>>
											                    	<h4 style="text-align: center;">¿Estas seguro que deseas eliminar este producto?</h4>
											                        <label for="nombreProducto">Nombre del producto</label>
											                        <input type="text" name="nombreProducto" class="form-control" maxlength="30" placeholder="Ingresa el nombre"  value="<?php echo $dataProductos['Nombre']; ?>" disabled>
											                        <label for="descripcion" class="lbl-formulario">Descripción</label>
											                        <textarea name="descripcion" class="form-control" style="resize: none;" rows="3" maxlength="200" placeholder="Da una breve explicación..." disabled><?php echo $dataProductos['Descripcion']; ?></textarea>
											                        <div class="row">
											                            <div class="col-md-6 col-sm-6">
											                                <label for="tipo" class="lbl-formulario">Tipo</label>
											                                <select name="tipo" class="form-control" disabled>
											                                    <option value="Alimento" <?php  if($dataProductos['Tipo']=='Alimento') echo "selected='selected'"; ?>>Alimento</option>
											                                    <option value="Bebida" <?php  if($dataProductos['Tipo']=='Bebida') echo "selected='selected'"; ?>>Bebida</option>
											                                </select>
											                            </div>
											                            <div class="col-md-6 col-sm-6">
											                                <label for="costo" class="lbl-formulario">Costo</label>
											                                <input type="number" name="costo" class="form-control" min="1" pattern="^[0-9]+" value="<?php echo $dataProductos['Costo']; ?>" disabled>
											                            </div>
											                        </div>
											                        <div class="row">
											                            <div class="col-md-6 col-sm-6">
											                                <label for="stock" class="lbl-formulario">Stock</label>
											                                <input type="number" name="stock" class="form-control" min="1" pattern="^[0-9]+" value="<?php echo $dataProductos['Stock']; ?>" disabled>
											                            </div>
											                            <div class="col-md-6 col-sm-6">
											                                <label for="precio" class="lbl-formulario">Precio</label>
											                                <input type="number" name="precio" class="form-control" min="1" pattern="^[0-9]+" value="<?php echo $dataProductos['Precio']; ?>" disabled>
											                            </div>
											                        </div>
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
	<?php
		include_once('logica/scriptsProductos.php');
	?>
</body>
</html>