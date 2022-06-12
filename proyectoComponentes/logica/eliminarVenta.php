<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$ID_Venta = $_POST['eliminarIdVenta'];
	}

	$query = "CALL `proyectobdd`.`spEliminarVenta`(".$ID_Venta.")";
	$resultado = mysqli_query($conexion,$query);
	header('Location: ../ventas.php?success=true');
?>