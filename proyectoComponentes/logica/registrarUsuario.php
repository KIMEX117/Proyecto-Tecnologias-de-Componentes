<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nombreUsuario = $_POST['nombreUsuario'];
		$password = $_POST['password'];
		$repetirPassword = $_POST['repetirPassword'];
		$rol = $_POST['rol'];
	}

	if(!empty($nombreUsuario) && !empty($password) && !empty($repetirPassword) && !empty($rol)) {
		if(strlen($nombreUsuario)>=5){
			if (strlen($password)>=8) {
				if($password===$repetirPassword) {
					$query = "INSERT INTO usuarios (Usuario, Contraseña, Rol) VALUES ('$nombreUsuario', sha1('$password'), '$rol')"; 
					$resultado = mysqli_query($conexion,$query);
					header('Location: ../usuarios.php?success=true');
				}else {
					header('Location: ../usuarios.php?errorRegistro=4'); //CONTRASEÑAS DIFERENTES
				}
			}else {
				header('Location: ../usuarios.php?errorRegistro=3'); //CONTRASEÑA MIDE MÍNIMO 8 Y MÁXIMO 16
			}
		}else {
			header('Location: ../usuarios.php?errorRegistro=2'); //NOMBRE DE USUARIO MENOR A 5 CARACTERES
		}
	}else {
		header('Location: ../usuarios.php?errorRegistro=1'); //CAMPOS VACIOS
	}
?>