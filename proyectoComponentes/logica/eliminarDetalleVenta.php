<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$ID_Detalle = $_POST['eliminarIdDetalleVenta'];
		$ID_Venta = $_POST['eliminarIdVentaDeDetalle'];
	}

	$query = "CALL `proyectobdd`.`spEliminarDetalleVenta`(".$ID_Detalle.", ".$ID_Venta.")";
	$resultado = mysqli_query($conexion,$query);
	var_dump($ID_Venta);
	header('Location: ../ventas.php?success=true');
?>