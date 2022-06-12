<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$idUsuario = $_POST['eliminarId'];
	}

	$query = ("DELETE FROM usuarios WHERE ID_Usuario='".$idUsuario."'"); 
	$resultado = mysqli_query($conexion,$query);
	header('Location: ../usuarios.php?success=true');
?>