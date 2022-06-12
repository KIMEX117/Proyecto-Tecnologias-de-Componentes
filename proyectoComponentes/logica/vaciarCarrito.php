<?php
	include("baseDatos.php");
	session_start();

	$sql="TRUNCATE TABLE Carrito";
	$result=mysqli_query($conexion,$sql);
	header('Location: ../realizarVenta.php?action=removeCart');
?>