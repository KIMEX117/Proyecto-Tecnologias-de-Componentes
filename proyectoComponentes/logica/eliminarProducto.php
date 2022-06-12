<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$idProducto = $_POST['eliminarId'];
	}

	$query = "DELETE FROM productos WHERE ID_Producto=".$idProducto; 
	$resultado = mysqli_query($conexion,$query);

	header('Location: ../productos.php?success=true');
?>