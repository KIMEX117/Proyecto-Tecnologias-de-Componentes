<?php
	include("baseDatos.php");
	session_start();

	if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
		if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {
			$idProducto = $_REQUEST['id'];

			$sql="SELECT * FROM productos WHERE ID_Producto=".$idProducto;
			$result=mysqli_query($conexion,$sql);
			if($result) {
				if($row=mysqli_fetch_array($result)){
					$nombreProducto = $row['Nombre'];
					$stock = $row['Stock'];
					$precio = $row['Precio'];
				}
			}

			$sql="SELECT * FROM Carrito WHERE ID_Producto =".$idProducto;
			$result=mysqli_query($conexion,$sql);
			$count=mysqli_num_rows($result);
			
			if(($stock-($count+1))>=0){
				$query="INSERT INTO Carrito(Id_Producto, Nombre, Stock, Precio) VALUES('$idProducto', '$nombreProducto', '$stock', '$precio')";
				mysqli_query($conexion,$query);
				header('Location: ../realizarVenta.php?success=true'); //PRODUCTO AÑADIDO EXITOSAMENTE AL CARRITO
			}else{
				header('Location: ../realizarVenta.php?error=1'); //NO HAY STOCK SUFICIENTE
			}
		}
	}
?>