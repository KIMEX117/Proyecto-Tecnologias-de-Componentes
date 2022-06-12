<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nombreProducto = $_POST['nombreProducto'];
		$descripcion = $_POST['descripcion'];
		$tipo = $_POST['tipo'];
		$costo = $_POST['costo'];
		$stock = $_POST['stock'];
		$precio = $_POST['precio'];
	}

	if(!empty($nombreProducto) && !empty($descripcion) && !empty($tipo) && !empty($costo) && !empty($stock) && !empty($precio)) {
		if(isset($_FILES['imagen'])){
			$fileName = $_FILES['imagen']['name'];
			$fileTmpName = $_FILES['imagen']['tmp_name'];
			$fileError = $_FILES['imagen']['error'];
			$fileExt = explode('.', $fileName);
			$fileActualExt = strtolower(end($fileExt));
			$allowed = array('jpg');

			$queryId = "SELECT MAX(ID_Producto)+1 FROM productos";
			$resultado = mysqli_query($conexion,$queryId);
			$idProducto = mysqli_fetch_array($resultado);
			var_dump($_FILES['imagen']);

			if(in_array($fileActualExt, $allowed)){
				if($fileError===0){
					$fileNameNew = "producto".$idProducto[0].".".$fileActualExt;
					$fileDestination = '../img/productos/'.$fileNameNew;
					move_uploaded_file($fileTmpName, $fileDestination);
					$query = "INSERT INTO Productos (Nombre, Descripcion, Tipo, Costo, Stock, Precio) VALUES('$nombreProducto', '$descripcion', '$tipo', '$costo', '$stock', '$precio')";
					$resultado = mysqli_query($conexion,$query);
					header('Location: ../productos.php?success=true'); //REGISTRO EXITOSO
				}else{
					header('Location: ../productos.php?errorRegistro=3'); //HUBO UN ERROR AL SUBIR LA IMAGEN
				}
			}else{
				header('Location: ../productos.php?errorRegistro=2'); //HACE FALTA UNA IMAGEN O FORMATO NO ES VÁLIDO
			}
		}
	}else {
		header('Location: ../productos.php?errorRegistro=1'); //CAMPOS VACIOS
	}
?>
