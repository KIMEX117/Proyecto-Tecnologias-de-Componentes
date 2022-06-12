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
		.btn-carrito{
			margin-bottom: 1rem;
		}
		#hiddenDiv {
		    display: block;
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
            				<h1>Realizar venta</h1>
          				</div>
        			</div>
      			</div>
    		</div>
    		<!-- DIV DE CONTENIDO -->
    		<div class="content">
      			<div class="container-fluid">
      				<div class="row">
      					<div class="col-md-12">
      						<button type="button" class="btn btn-primary btn-carrito" style="float: right;" data-toggle="modal" data-target="#mostrarCarrito">
		      					<i class="fa-solid fa-cart-shopping"></i>
		      					Ver carrito
		      				</button>
		      				<!-- MODAL MOSTRAR CARRITO -->
							<div class="modal fade" id="mostrarCarrito" tabindex="-1" role="dialog" aria-labelledby="mostrarCarritoLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
							    <div class="modal-dialog modal-dialog-centered" role="document">
							        <div class="modal-content">
							            <div class="modal-header">
							                <h5 class="modal-title h3" id="mostrarCarritoLabel"><strong>Carrito</strong> </h5>
							                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							                <span aria-hidden="true">&times;</span>
							                </button>
							            </div>
							            <div class="modal-body">
							                <form method="POST" action="logica/finalizarVenta.php" style="padding-top: 0.6rem!important">
							                	<?php
							                		$query = $conexion->query("SELECT * FROM Carrito");
							                		$count=mysqli_num_rows($query);
							                		if($count>0){
							                	?>
								                		<table class="table table-hover">
														    <thead>
														        <tr>
														            <th>#</th>
														            <th>Nombre producto</th>
														            <th>Precio</th>
														            <th>Eliminar</th>
														        </tr>
														    </thead>
														    <tbody>
														    	<?php
														    		while ($dataCarrito = mysqli_fetch_array($query)){
																		echo "<tr>";
																		echo "<td>".$dataCarrito['ID']."</td>";
																		echo "<td>".$dataCarrito['Nombre']."</td>";
																		echo "<td>".$dataCarrito['Precio']."</td>";
																		echo "
																			<td class='project-actions text-center'>
																				<a class='btn btn-danger btn-sm' href='logica/eliminarProductoCarrito.php?action=removeProduct&id=".$dataCarrito['ID']."' style='color: #ffffff!important;'>
																					<i class='fas fa-times'></i>
																				</a>
																			</td>";
																		echo "</tr>";
																	}
														    	?>
														    </tbody>
														</table>
														<?php
															$query = $conexion->query("SELECT SUM(Precio) FROM Carrito");
							                				$total=mysqli_fetch_array($query)
														?>
														<h4 style="float: right;"><?php echo "Total: $".$total[0] ?></h4>
														<input type="hidden" name="totalVenta" <?php echo "value='".$total[0]."'"; ?>>
									                	<label for="nombreCliente" class="lbl-formulario" style="margin-top: 1.5rem!important">Nombre del pedido</label>
								                        <input type="text" name="nombreCliente" class="form-control" maxlength="30" placeholder="Nombre del cliente">
									                	<label for="metodoPago" class="lbl-formulario">Método de pago</label>
													    <select name="metodoPago" onchange="showDiv('hiddenDiv', this)" class="form-control" >
													        <option value="Efectivo">Efectivo</option>
													        <option value="Tarjeta">Tarjeta</option>
													    </select>
													    <div id="hiddenDiv">
													    	<label for="dinero" id="dineroLbl" class="lbl-formulario">Pago del cliente</label>
								                        	<input type="number" name="dinero" id="dineroInput" class="form-control" min="<?php echo $total[0]; ?>" pattern="^[0-9]+" placeholder="Ingrese cifra entregada por el cliente">
													    </div>												    
								                <?php
											            if (isset($_GET['errorVenta'])) {
											                if ($_GET['errorVenta'] == 1) {
											                    echo '<div class="formulario"><p>* No pueden quedar campos vacíos.</p></div>';
											                }
											            }

							                		}else{
							                	?>
							                		<h4 style="text-align: center;">Todavía no se ha añadido ningún producto al carrito.</h4>
							                	<?php	
							                		}
							                	?>
							                    <div class="card-footer" style="background-color: white!important; margin-top: 2rem; padding: 0!important;">
							                    	<button type="submit" class="btn btn-primary">Pagar</button>
							                        <button type="button" class="btn btn-danger" style="float: right;" data-dismiss="modal">Cancelar</button>
							                    </div>
							                </form>
							            </div>
							        </div>
							    </div>
							</div>
		      				<button type="button" class="btn btn-info btn-carrito" style="float: right; margin-right: 1rem;">
		      					<a href="logica/vaciarCarrito.php" style="color: #ffffff!important;"><i class="fa-solid fa-trash-can"></i> Vaciar carrito</a>
		      				</button>
      					</div>
      					<!-- MENÚ (PRODUCTOS) -->
						<div class="col-md-12">
	        				<div class="card card-primary">
	        					<div class="card-header bg-gray-dark">
				                    <h3 class="card-title">Menú</h3>
				                	<div class="card-tools">
				                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
			                        		<i class="fas fa-expand"></i>
										</button>
				                    </div>
				                </div>
								<div class="card-body">
								    <div class="container">
								    	<h2 style="text-align:center; padding-bottom: 1rem;">Productos</h2>
								    	<div class='row'>
								    	<?php
								    		$auxiliar = 1;
			                				$query = $conexion->query("SELECT * FROM productos");
			                				$count=mysqli_num_rows($query);
			                				while ($dataProductos = mysqli_fetch_array($query)){
			                					if($auxiliar % 4 ==4){
			                						echo "<div class='row'>";
			                					}
			                			?>
			                					<div class="col-6 col-lg-3">
												    <div class="card mb-3 box-shadow">
												        <img class="card-img-top" style="height: 250px; width: 100%; display: block;" src="img/productos/producto<?php echo $dataProductos['ID_Producto']; ?>.jpg">
												        <div class="card-body" style="padding-bottom: 0.7rem!important">
												        	<h5><strong><?php echo $dataProductos['Nombre']; ?></strong></h5>
												            <p class="card-text" style="text-align: justify;"><?php echo $dataProductos['Descripcion']; ?></p>
												            <div style="display: flex; justify-content: center;">
												                <button class="btn btn-primary"><a href="<?php echo "logica/objetosCarrito.php?action=addToCart&id=".$dataProductos['ID_Producto'];?>" style="color: #ffffff!important;">Añadir producto</a></button>
												            </div>
												            <small class="text-muted" style="padding-top: 0.8rem!important; float:left;"><?php echo "Precio: $".$dataProductos['Precio']; ?></small>
												            <small class="text-muted" style="padding-top: 0.8rem!important; float:right;"><?php echo "Stock: ".$dataProductos['Stock']; ?></small>
												        </div>
												    </div>
												</div>
										<?php
												if($auxiliar % 4 == 4 || $auxiliar == $count){
				                					echo "</div>";
				                				}
				                				$auxiliar++;
											}
			                			?>
								    </div>
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
	<?php include_once('logica/scriptsRealizarVenta.php'); ?>
</body>
</html>