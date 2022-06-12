<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nombreCliente = $_POST['nombreCliente'];
		$metodoPago = $_POST['metodoPago'];
		if ($metodoPago=="Efectivo") {
			$dinero = $_POST['dinero'];
		}else{
			$dinero = $_POST['totalVenta'];
		}
		
	}

	if(!empty($nombreCliente) && !empty($metodoPago) && !empty($dinero)) {
		$query = "SELECT ID_Producto AS idProducto, count(ID_Producto) Cantidad FROM Carrito GROUP BY ID_Producto";
		$resultado = mysqli_query($conexion,$query);
		$firstrow = mysqli_fetch_assoc($resultado);
		mysqli_data_seek($resultado, 0);
		$obtenerIdVenta = "SELECT MAX(ID_Venta)+1 AS idVenta FROM ventas";
		$resultado3 = mysqli_query($conexion,$obtenerIdVenta);
		$idVenta = mysqli_fetch_array($resultado3);

		while($data=mysqli_fetch_assoc($resultado)){
			if($firstrow['idProducto']==$data['idProducto']){
				$procedureRealizarVenta = "CALL `proyectobdd`.`spRealizarVenta`(".$data['idProducto'].", '".$nombreCliente."', NOW(), ".$data['Cantidad'].")";
				$resultado2 = mysqli_query($conexion,$procedureRealizarVenta);
			}else{
				$procedureAgregarVenta = "CALL `proyectobdd`.`spAgregarVenta`(".$idVenta[0].", ".$data['idProducto'].", ".$data['Cantidad'].");";
				$resultado4 = mysqli_query($conexion,$procedureAgregarVenta);
			}
		}

		$realizarPago = "CALL `proyectobdd`.`spRealizarPagos`(".$idVenta[0].", '".$metodoPago."', ".$dinero.");";
		$resultado5 = mysqli_query($conexion,$realizarPago);

		$sql="TRUNCATE TABLE Carrito";
		$result=mysqli_query($conexion,$sql);
		header('Location: ../realizarVenta.php?successVenta=true'); //VENTA EXITOSA
	}else {
		header('Location: ../realizarVenta.php?errorVenta=1'); //CAMPOS VACIOS
	}
?>

