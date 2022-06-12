<?php
	include("baseDatos.php");
	
	if(!empty($_POST['user']) && !empty($_POST['password'])){
		$user=$_POST['user'];
		$sql="SELECT * FROM usuarios WHERE Usuario='$user'";
		$result=mysqli_query($conexion,$sql);
		if ($result) {
			$row=mysqli_fetch_array($result);
			$count=mysqli_num_rows($result);	
			if ($count!=0) {
				$password=md5($_POST['password']);
				if ($row['Contraseña']!==$password) {
					header('Location: ../index.php?error=3'); //CONTRASEÑA INCORRECTA
				}else{
					session_start();
					$_SESSION['ID_Usuario']=$row['ID_Usuario'];
					$_SESSION['Usuario']=$row['Usuario'];
					$_SESSION['Rol']=$row['Rol'];
					header('Location: ../dashboard.php'); //INICIO DE SESIÓN EXITOSO
				}
			}else{
				header('Location: ../index.php?error=2'); //USUARIO INVÁLIDO
			}
		}else{
			header('Location: ../index.php?error=1'); //CONEXIÓN FALLIDA
		}
	}else {
		header('Location: ../index.php?error=4'); //CAMPOS VACÍOS
	}
?>