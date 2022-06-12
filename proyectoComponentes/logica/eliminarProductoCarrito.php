<?php
	include("baseDatos.php");
	session_start();

	if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
		if($_REQUEST['action'] == 'removeProduct' && !empty($_REQUEST['id'])) {
			$idProducto = $_REQUEST['id'];
			$sql="DELETE FROM Carrito WHERE ID=".$idProducto;
			$result=mysqli_query($conexion,$sql);
			header('Location: ../realizarVenta.php?removeProduct=true'); //PRODUCTO REMOVIDO EXITOSAMENTE DEL CARRITO
		}
	}
?>