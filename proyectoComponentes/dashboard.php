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
		.modulo1{
			background: #60a4ff;
			color: #edf5ff;
		}
		.modulo2{
			background: #68c683;
			color: #c4ffd5;
		}
		.modulo3{
			background: #f6838d;
			color: #ffd1d5;
		}
		.fondo-grafica1{
			background-color: #19a3b8!important;
		}
		.fondo-grafica2{
			background-color: #ff9f40!important;
		}
		.fondo-grafica3{
			background-color: #36a2eb!important;
		}
		.fondo-grafica4{
			background-color: #ff6384!important;
		}
		.li-venta{
			border-top: 1px solid #4f5962!important;
			border-bottom: 1px solid #4f5962!important;
			padding-top: 0.3rem;
			padding-bottom: 0.3rem;
		}
	</style>
 	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            				<a href="dashboard.php" class="nav-link active">
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
            				<h1>Dashboard</h1>
          				</div>
        			</div>
      			</div>
    		</div>
    		<!-- DIV DE CONTENIDO -->
    		<div class="content">
      			<div class="container-fluid">
        			<div class="row">
        				<div class="col-lg-4 col-md-4 col-4">
							<div class="small-box">
								<div class="inner modulo1">
									<p>Ingresos mensuales</p>
									<?php
										$sql="SELECT SUM(Monto_Total) FROM viewResumenVentas";
        								$result=mysqli_query($conexion,$sql);
        								while ($modulo1 = mysqli_fetch_array($result)){
        									echo "<h3>$".$modulo1[0]."</h3>";
        								};
									?>
								</div>
								<div class="icon">
									<i class="fa-solid fa-sack-dollar"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-4">
							<div class="small-box">
								<div class="inner modulo2">
									<p>Ganancias mensuales</p>
									<?php
										$sql="SELECT (SUM(Monto_Total)-SUM(Costo_Total)) FROM viewResumenVentas";
        								$result=mysqli_query($conexion,$sql);
        								$modulo2 = mysqli_fetch_array($result);
        								echo "<h3>$".$modulo2[0]."</h3>";
									?>
								</div>
								<div class="icon">
									<i class="fa-solid fa-piggy-bank"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-4">
							<div class="small-box">
								<div class="inner modulo3">
									<p>Gastos mensuales</p>
									<?php
										$sql="SELECT SUM(Costo_Total) FROM viewResumenVentas";
        								$result=mysqli_query($conexion,$sql);
        								$modulo3 = mysqli_fetch_array($result);
        								echo "<h3>$".$modulo3[0]."</h3>";
									?>
								</div>
								<div class="icon">
									<i class="fa-solid fa-receipt"></i>
								</div>
							</div>
						</div>
        			</div>
        			<div class="row">
        				<div class="col-lg-6 col-md-6 col-sm-12">
        					<div class="card card-primary">
		                        <div class="card-header fondo-grafica1">
		                            <div class="card-title">
		                            	<i class="fa-solid fa-chart-pie"></i>
		                            	Relación mensual costos/beneficios
		                        	</div>
		                            <div class="card-tools">
		                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                                	<i class="fas fa-minus"></i>
		                                </button>
		                            </div>
		                        </div>
		                        <div class="card-body">
		                            <canvas id="graficaPie" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
		                        </div>
		                    </div>
        				</div>
        				<div class="col-lg-6 col-md-6 col-sm-12">
        					<div class="card card-primary">
		                        <div class="card-header fondo-grafica2">
		                            <div class="card-title">
		                            	<i class="fa-solid fa-chart-line"></i>
		                            	Ventas de los últimos 7 días
		                        	</div>
		                            <div class="card-tools">
		                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                                	<i class="fas fa-minus"></i>
		                                </button>
		                            </div>
		                        </div>
		                        <div class="card-body">
		                            <canvas id="graficaLineal" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
		                        </div>
		                    </div>
        				</div>
        				<div class="col-lg-6 col-md-6 col-sm-12">
        					<div class="card card-primary">
		                        <div class="card-header fondo-grafica3">
		                            <div class="card-title">
		                            	<i class="fa-solid fa-chart-column"></i>
		                            	Top 10 - Productos más vendidos
		                            </div>
		                            <div class="card-tools">
		                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                                	<i class="fas fa-minus"></i>
		                                </button>
		                            </div>
		                        </div>
		                        <div class="card-body">
		                            <canvas id="graficaBarras1" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
		                        </div>
		                    </div>
        				</div>
        				<div class="col-lg-6 col-md-6 col-sm-12">
        					<div class="card card-primary">
		                        <div class="card-header fondo-grafica4">
		                            <div class="card-title">
		                            	<i class="fa-solid fa-chart-column"></i>
		                            	Top 5 - Productos menos vendidos
		                           	</div>
		                            <div class="card-tools">
		                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                                	<i class="fas fa-minus"></i>
		                                </button>
		                            </div>
		                        </div>
		                        <div class="card-body">
		                            <canvas id="graficaBarras2" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
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
	<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
	<?php include_once('logica/cargarGraficasDashboard.php'); ?>
</body>
</html>