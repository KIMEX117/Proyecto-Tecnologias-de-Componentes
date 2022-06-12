<?php
	session_start(); 
	include("baseDatos.php");

	if($_SERVER['REQUEST_METHOD'] === 'POST') {
		$idProducto = $_POST['editarId'];
		$nombreProducto = $_POST['nombreProducto'];
		$descripcion = $_POST['descripcion'];
		$tipo = $_POST['tipo'];
		$costo = $_POST['costo'];
		$stock = $_POST['stock'];
		$precio = $_POST['precio'];
	}

	if(!empty($nombreProducto) && !empty($descripcion) && !empty($tipo) && !empty($costo) && !empty($stock) && !empty($precio)) {
		$query = ("UPDATE Productos SET Nombre ='".$nombreProducto."', Descripcion ='".$descripcion."', Tipo = '".$tipo."', Costo = ".$costo.", Stock = ".$stock.", Precio = ".$precio." WHERE ID_Producto =".$idProducto."");
		$resultado = mysqli_query($conexion,$query);
		header('Location: ../productos.php?success=true'); //REGISTRO EXITOSO
	}else {
		header("Location: ../productos.php?errorEditar=1&idProducto=".$idProducto.""); //CAMPOS VACIOS
	}
?>