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
		.div-delete{
			border-top: 1px solid #4f5962!important;
			border-bottom: 1px solid #4f5962!important;
		}
		.div-delete p {
			padding-top: 1rem;
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
    		<!-- Brand Logo -->
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
            				<a href="ventas.php" class="nav-link active">
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
            				<h1>Ventas</h1>
          				</div>
        			</div>
      			</div>
    		</div>

    		<!-- DIV DE CONTENIDO -->
    		<div class="content">
      			<div class="container-fluid">
        			<div class="row">
	      				<div class="col-md-12">
							<div class="card card-primary">
								<div class="card-header bg-gray-dark">
				                    <h3 class="card-title">Tabla de ventas realizadas</h3>
				                	<div class="card-tools">
				                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
				                            <i class="fas fa-minus"></i>
				                        </button>
				                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
				                        	<i class="fas fa-expand"></i>
										</button>
				                    </div>
				                </div>
				                <div class="card-body">
				                	<table id="tablaVentas" class="table table-striped table-bordered" style="width:100%">
				                		<thead>
				                			<tr>
				                				<th>ID</th>
				                				<th>Nombre cliente</th>
				                				<th>Monto total</th>
				                				<th>Fecha venta</th>
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
				                				$query = $conexion->query("SELECT * FROM Ventas");
												foreach($query as $dataVentas){
													echo "<tr>";
													echo "<td>".$dataVentas['ID_Venta']."</td>";
													echo "<td>".$dataVentas['NomCliente']."</td>";
													echo "<td>$".$dataVentas['Monto_Total']."</td>";
													echo "<td>".$dataVentas['Fecha_Venta']."</td>";
													if($_SESSION['Rol']=="Administrador"){
          											echo "
														<td class='project-actions text-center'>
															<a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#eliminarVentas".$dataVentas['ID_Venta']."'>
																<i class='fas fa-trash'></i> Eliminar
															</a>
														</td>";
	          										}
													echo "</tr>";
											?>

												<!-- ELIMINAR VENTAS -->
												<div class="modal fade" id="eliminarVentas<?php echo $dataVentas['ID_Venta']; ?>" tabindex="-1" role="dialog" aria-labelledby="eliminarVentasLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
													<div class="modal-dialog modal-dialog-centered" role="document">
													    <div class="modal-content">
													        <div class="modal-header">
													            <h5 class="modal-title h3" id="eliminarVentasLabel"><strong>Eliminar usuario</strong> </h5>
													            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
													            	<span aria-hidden="true">&times;</span>
													            </button>
													        </div>
													        <div class="modal-body">
													            <form method="POST" action="logica/eliminarVenta.php">
													                <input type="hidden" name="eliminarIdVenta" <?php echo "value='".$dataVentas['ID_Venta']."'"; ?>>
													                <h4 style="text-align: center;">¿Estas seguro que deseas eliminar esta venta completamente?</h4>
													                <div class="d-flex justify-content-between align-items-center div-delete">
													                	<p><?php echo $dataVentas['ID_Venta']; ?></p>
														                <p><?php echo $dataVentas['NomCliente']; ?></p>
														                <p>$<?php echo $dataVentas['Monto_Total']; ?></p>
														                <p><?php echo $dataVentas['Fecha_Venta']; ?></p>
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

						<div class="col-md-12">
							<div class="card card-primary">
								<div class="card-header bg-gray-dark">
				                    <h3 class="card-title">Tabla de detalle ventas</h3>
				                	<div class="card-tools">
				                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
				                            <i class="fas fa-minus"></i>
				                        </button>
				                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
				                        	<i class="fas fa-expand"></i>
										</button>
				                    </div>
				                </div>
				                <div class="card-body">
				                	<table id="tablaDetalleVentas" class="table table-striped table-bordered" style="width:100%">
				                		<thead>
				                			<tr>
				                				<th>ID_Detalle</th>
				                				<th>ID_Venta</th>
				                				<th>ID_Producto</th>
				                				<th>Monto</th>
				                				<th>Cantidad</th>
				                				<th>Total</th>
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
				                				$query = $conexion->query("SELECT * FROM detalleVentas");
												foreach($query as $dataDetalle){
													echo "<tr>";
													echo "<td>".$dataDetalle['ID_Detalle']."</td>";
													echo "<td>".$dataDetalle['ID_Venta']."</td>";
													echo "<td>".$dataDetalle['ID_Producto']."</td>";
													echo "<td>$".$dataDetalle['Monto']."</td>";
													echo "<td>".$dataDetalle['Cantidad']."</td>";
													echo "<td>$".$dataDetalle['Total']."</td>";
													if($_SESSION['Rol']=="Administrador"){
          											echo "
														<td class='project-actions text-center'>
															<a class='btn btn-danger btn-sm' data-toggle='modal' data-target='#eliminarDetalleVentas".$dataDetalle['ID_Detalle']."'>
																<i class='fas fa-trash'></i> Eliminar
															</a>
														</td>";
	          										}
													echo "</tr>";
											?>
												<!-- ELIMINAR DETALLE VENTAS -->
												<div class="modal fade" id="eliminarDetalleVentas<?php echo $dataDetalle['ID_Detalle']; ?>" tabindex="-1" role="dialog" aria-labelledby="eliminarDetalleVentasLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
													<div class="modal-dialog modal-dialog-centered" role="document">
													    <div class="modal-content">
													        <div class="modal-header">
													            <h5 class="modal-title h3" id="eliminarDetalleVentasLabel"><strong>Eliminar usuario</strong> </h5>
													            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
													            	<span aria-hidden="true">&times;</span>
													            </button>
													        </div>
													        <div class="modal-body">
													            <form method="POST" action="logica/eliminarDetalleVenta.php">
													                <input type="hidden" name="eliminarIdDetalleVenta" <?php echo "value='".$dataDetalle['ID_Detalle']."'"; ?>>
													                <input type="hidden" name="eliminarIdVentaDeDetalle" <?php echo "value='".$dataDetalle['ID_Venta']."'"; ?>>
													                <h4 style="text-align: center;">¿Estas seguro que deseas eliminar estos productos de la venta?</h4>
													                <div class="d-flex justify-content-between align-items-center div-delete">
													                	<p><?php echo $dataDetalle['ID_Detalle']; ?></p>
														                <p><?php echo $dataDetalle['ID_Venta']; ?></p>
														                <p><?php echo $dataDetalle['ID_Producto']; ?></p>
														                <p>$<?php echo $dataDetalle['Monto']; ?></p>
														                <p><?php echo $dataDetalle['Cantidad']; ?></p>
														                <p>$<?php echo $dataDetalle['Total']; ?></p>
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

						<div class="col-md-12">
							<div class="card card-primary">
								<div class="card-header bg-gray-dark">
				                    <h3 class="card-title">Tabla de pagos</h3>
				                	<div class="card-tools">
				                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
				                            <i class="fas fa-minus"></i>
				                        </button>
				                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
				                        	<i class="fas fa-expand"></i>
										</button>
				                    </div>
				                </div>
				                <div class="card-body">
				                	<table id="tablaPagos" class="table table-striped table-bordered" style="width:100%">
				                		<thead>
				                			<tr>
				                				<th>ID Pago</th>
				                				<th>ID Venta</th>
				                				<th>Nombre cliente</th>
				                				<th>Método de pago</th>
				                				<th>Dinero</th>
				                				<th>Cuenta</th>
				                				<th>Cambio</th>
				                			</tr>
				                		</thead>
				                		<tbody>
				                			<?php
				                				$query = $conexion->query("SELECT * FROM pagos");
												foreach($query as $dataPagos){
													echo "<tr>";
													echo "<td>".$dataPagos['ID_Pagos']."</td>";
													echo "<td>".$dataPagos['ID_Venta']."</td>";
													echo "<td>".$dataPagos['NomCliente']."</td>";
													echo "<td>".$dataPagos['Metodo_Pago']."</td>";
													echo "<td>$".$dataPagos['Dinero']."</td>";
													echo "<td>$".$dataPagos['Cuenta']."</td>";
													echo "<td>$".$dataPagos['Cambio']."</td>";
													echo "</tr>";
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
	<?php include_once('logica/scriptsVentas.php'); ?>
</body>
</html>
