<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$idUsuario = $_POST['editarId'];
		$nombreUsuario = $_POST['nombreUsuario'];
		$rol = $_POST['rol'];
	}

	if(!empty($nombreUsuario) && !empty($rol)) {
		if(strlen($nombreUsuario)>=5){
			$query = ("UPDATE usuarios SET Usuario ='".$nombreUsuario."', Rol = '".$rol."' WHERE ID_Usuario =".$idUsuario.""); 
			$resultado = mysqli_query($conexion,$query);
			header('Location: ../usuarios.php?success=true');
		}else {
			header("Location: ../usuarios.php?errorEditar=2&idUsuario=".$idUsuario.""); //NOMBRE DE USUARIO MENOR A 5 CARACTERES
		}
	}else {
		header("Location: ../usuarios.php?errorEditar=1&idUsuario=".$idUsuario.""); //CAMPOS VACIOS
	}
?>