-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2022 a las 08:56:33
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectobdd`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `spAgregarVenta` (IN `paramIDVenta` INT, IN `paramIDProducto` INT, IN `paramCantidad` INT)  BEGIN
	DECLARE varExistente INT;
    DECLARE varMonto FLOAT;
    SET varExistente = (SELECT Stock FROM Productos WHERE ID_Producto = paramIDProducto);
    
    START TRANSACTION;
    IF(SELECT COUNT(*) FROM detalleVentas WHERE ID_Venta = paramIDVenta) THEN
		IF(varExistente > 0) THEN
            IF(varExistente >= paramCantidad) THEN
				SET varMonto = (SELECT Precio FROM Productos WHERE ID_Producto = paramIDProducto) * paramCantidad;
				INSERT INTO detalleVentas VALUES(0, paramIDVenta, paramIDProducto, (SELECT Precio FROM Productos WHERE ID_Producto = paramIDProducto), paramCantidad, varMonto);
                UPDATE Ventas SET Monto_Total = (SELECT SUM(Total) FROM detalleVentas WHERE ID_Venta = paramIDVenta) WHERE ID_Venta = paramIDVenta;
                
                UPDATE Productos SET Stock = (varExistente - paramCantidad) WHERE ID_Producto = paramIDProducto;
                COMMIT;
			ELSE
				ROLLBACK;
            END IF;
		ELSE
			ROLLBACK;
		END IF;
   ELSE
		ROLLBACK;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spEliminarDetalleVenta` (IN `paramIDDetalle` INT, IN `paramIDVenta` INT)  BEGIN
    DECLARE varCantVentas INT DEFAULT 0;
    DECLARE varStock INT DEFAULT 0;
    SET varCantVentas = (SELECT COUNT(ID_Venta) FROM DetalleVentas WHERE ID_Venta = paramIDVenta);
	SET varStock = (SELECT Stock FROM Productos WHERE ID_Producto = (SELECT ID_Producto FROM DetalleVentas WHERE ID_Detalle = paramIDDetalle))+(SELECT Cantidad FROM DetalleVentas WHERE ID_Detalle = paramIDDetalle);-- WHERE ID_Producto = (SELECT ID_Producto FROM DetalleVentas WHERE ID_Detalle = paramIDDetalle);
    
    START TRANSACTION;
    IF (varCantVentas > 1) THEN
        DELETE FROM DetalleVentas WHERE ID_Detalle = paramIDDetalle;
        UPDATE Productos SET Stock = varStock WHERE ID_Producto = (SELECT ID_Producto FROM DetalleVentas WHERE ID_Detalle = paramIDDetalle);
        UPDATE Ventas SET Monto_Total = (SELECT SUM(Total) FROM DetalleVentas WHERE ID_Venta = paramIDVenta) WHERE ID_Venta = paramIDVenta;
	ELSE 
		IF (varCantVentas = 1) THEN
			DELETE FROM pagos WHERE ID_Venta = paramIDVenta;
			DELETE FROM DetalleVentas WHERE ID_Detalle = paramIDDetalle;
            DELETE FROM Ventas WHERE ID_Venta = paramIDVenta;
        END IF;
    END IF;
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spEliminarVenta` (IN `paramIDVenta` INT)  BEGIN
    START TRANSACTION;
    DELETE FROM pagos WHERE ID_Venta = paramIDVenta;
    DELETE FROM DetalleVentas WHERE ID_Venta = paramIDVenta;
    DELETE FROM Ventas WHERE ID_Venta = paramIDVenta;
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spModificarDetalleIdProdDiferente` (IN `paramIDDetalle` INT, IN `paramIDVenta` INT, IN `paramIDProducto` INT, `paramCantidad` INT, IN `paramIDProductoDetalle` INT, IN `paramCantidadDetalle` INT)  BEGIN
	DECLARE varMonto FLOAT DEFAULT 0;
    DECLARE varTotal FLOAT DEFAULT 0;
    DECLARE varStockOriginal INT DEFAULT 0;
    DECLARE varStockDetalle INT DEFAULT 0;
    SET varMonto = (SELECT Precio FROM Productos WHERE ID_Producto = paramIDProducto);
    SET varTotal = varMonto * paramCantidad;
    SET varStockOriginal = (SELECT SUM((SELECT Stock FROM Productos WHERE ID_Producto = paramIDProductoDetalle)+paramCantidadDetalle));
    SET varStockDetalle = (SELECT Stock FROM Productos WHERE ID_Producto = paramIDProducto);
    
    START TRANSACTION;
	UPDATE Productos SET Stock = varStockOriginal WHERE ID_Producto = paramIDProductoDetalle;
    IF (paramCantidad <= (SELECT Stock FROM Productos WHERE ID_Producto = paramIDProducto)) THEN
		SET varStockDetalle = varStockDetalle - paramCantidad;
		UPDATE Productos SET Stock = varStockDetalle WHERE ID_Producto = paramIDProducto;
        UPDATE DetalleVentas SET ID_Producto = paramIDProducto, Monto = varMonto, Cantidad = paramCantidad, Total = varTotal WHERE ID_Detalle = paramIDDetalle;
		UPDATE Ventas SET Monto_Total = (SELECT SUM(Total) FROM DetalleVentas WHERE ID_Venta = paramIDVenta) WHERE ID_Venta = paramIDVenta;
		COMMIT;
	ELSE
		ROLLBACK;
    END IF;    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spModificarDetalleIdProdIgual` (IN `paramIDDetalle` INT, IN `paramIDVenta` INT, IN `paramIDProducto` INT, `paramCantidad` INT)  BEGIN
	DECLARE varMonto FLOAT DEFAULT 0;
    DECLARE varTotal FLOAT DEFAULT 0;
    DECLARE varCantStock INT DEFAULT 0;
    DECLARE varStockProductos INT DEFAULT 0;
    DECLARE varStockDetalle INT DEFAULT 0;
    SET varMonto = (SELECT Precio FROM Productos WHERE ID_Producto = paramIDProducto);
    SET varTotal = varMonto * paramCantidad;
    SET varStockProductos = (SELECT Stock FROM Productos WHERE ID_Producto = paramIDProducto);
    SET varStockDetalle = (SELECT Cantidad FROM DetalleVentas WHERE ID_Detalle = paramIDDetalle);
    
    START TRANSACTION;
    SET varCantStock = varStockProductos + varStockDetalle;
    IF (varCantStock >= paramCantidad) THEN
		SET varCantStock = (varCantStock - paramCantidad);
		UPDATE Productos SET Stock = varCantStock WHERE ID_Producto = paramIDProducto;
        UPDATE DetalleVentas SET ID_Producto = paramIDProducto, Monto = varMonto, Cantidad = paramCantidad, Total = varTotal WHERE ID_Detalle = paramIDDetalle;
		UPDATE Ventas SET Monto_Total = (SELECT SUM(Total) FROM DetalleVentas WHERE ID_Venta = paramIDVenta) WHERE ID_Venta = paramIDVenta;
		COMMIT;
	ELSE
		ROLLBACK;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spRealizarPagos` (IN `paramIDVenta` INT, IN `paramMetodo` VARCHAR(30), IN `paramDinero` FLOAT)  BEGIN
	DECLARE varDeuda FLOAT DEFAULT 0;
    DECLARE varCambio FLOAT DEFAULT 0;
    DECLARE varNomCliente VARCHAR(45) DEFAULT '';
    SET varDeuda = (SELECT Monto_Total FROM Ventas WHERE ID_Venta = paramIDVenta);
    SET varNomCliente = (SELECT NomCliente FROM Ventas WHERE ID_Venta = paramIDVenta);
    
    START TRANSACTION;
    IF (paramDinero >= varDeuda) THEN
		SET varCambio = (paramDinero - varDeuda);
        INSERT INTO Pagos VALUES(0, paramIDVenta, varNomCliente, paramMetodo, paramDinero, varDeuda, varCambio);
		COMMIT;
    ELSE
		ROLLBACK;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spRealizarVenta` (IN `paramIDProducto` INT, IN `paramNombre` VARCHAR(45), IN `paramFecha` DATE, IN `paramCantProduct` INT)  BEGIN
	DECLARE varExistProduct INT; 
    DECLARE varImporte FLOAT; 
    DECLARE varPrecio FLOAT;
    
    START TRANSACTION;
    IF (SELECT COUNT(*) FROM Productos WHERE Productos.ID_Producto = paramIDProducto) > 0 THEN
		SET varExistProduct = (SELECT Stock FROM Productos WHERE `Productos`.`ID_Producto` = paramIDProducto);
		IF (paramCantProduct <= varExistProduct) THEN
			SET varPrecio = (SELECT Precio FROM Productos WHERE Productos.ID_Producto = paramIDProducto);
			SET varImporte = (paramCantProduct * varPrecio);
			UPDATE Productos SET Stock = (Productos.Stock - paramCantProduct) WHERE Productos.ID_Producto = paramIDProducto;
			INSERT INTO Ventas VALUES(0, paramNombre, varImporte, paramFecha);
			INSERT INTO DetalleVentas VALUES(0, (SELECT COUNT(*) FROM Ventas), paramIDProducto, varPrecio, paramCantProduct, varImporte);
            COMMIT;
		ELSE
            ROLLBACK;
		END IF;
	ELSE
        ROLLBACK;
	END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `ID` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Stock` int(11) NOT NULL,
  `Precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventas`
--

CREATE TABLE `detalleventas` (
  `ID_Detalle` int(11) NOT NULL,
  `ID_Venta` int(11) NOT NULL,
  `ID_Producto` int(11) DEFAULT 0,
  `Monto` float NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalleventas`
--

INSERT INTO `detalleventas` (`ID_Detalle`, `ID_Venta`, `ID_Producto`, `Monto`, `Cantidad`, `Total`) VALUES
(1, 1, 1, 30, 4, 120),
(2, 1, 7, 65, 1, 65),
(3, 2, 3, 65, 2, 130),
(4, 2, 5, 60, 1, 60),
(5, 2, 8, 45, 2, 90),
(6, 3, 2, 55, 3, 165),
(7, 4, 1, 30, 8, 240),
(8, 5, 7, 65, 1, 65),
(9, 5, 6, 55, 3, 165),
(10, 6, 1, 30, 8, 240),
(11, 7, 5, 60, 2, 120),
(12, 8, 3, 65, 4, 260),
(13, 8, 9, 15, 2, 30),
(14, 8, 10, 15, 1, 15),
(15, 8, 15, 15, 1, 15),
(16, 8, 8, 45, 2, 90),
(17, 9, 1, 30, 7, 210),
(18, 10, 2, 55, 3, 165),
(19, 11, 4, 45, 2, 90),
(20, 12, 2, 55, 4, 220),
(21, 12, 1, 30, 6, 180),
(22, 12, 8, 45, 4, 180),
(23, 13, 2, 55, 7, 385),
(24, 14, 3, 65, 4, 260),
(25, 14, 9, 15, 4, 60),
(26, 15, 8, 45, 1, 45),
(27, 15, 11, 15, 1, 15),
(28, 16, 1, 30, 6, 180),
(29, 16, 15, 15, 3, 45),
(30, 17, 2, 55, 3, 165),
(31, 17, 9, 15, 3, 45),
(32, 18, 6, 55, 2, 110),
(33, 19, 7, 65, 4, 260),
(34, 20, 1, 30, 10, 300),
(35, 20, 9, 15, 4, 60),
(36, 21, 2, 55, 4, 220),
(37, 21, 12, 15, 2, 30),
(38, 21, 9, 15, 1, 15),
(39, 22, 1, 30, 8, 240),
(40, 23, 5, 60, 3, 180),
(41, 23, 9, 15, 2, 30),
(42, 24, 8, 45, 2, 90),
(43, 24, 1, 30, 4, 120),
(44, 25, 3, 65, 5, 325),
(45, 25, 8, 45, 2, 90),
(46, 26, 2, 55, 4, 220),
(47, 27, 3, 65, 6, 390),
(48, 28, 1, 30, 5, 150),
(49, 28, 13, 15, 5, 75),
(50, 29, 7, 65, 3, 195),
(51, 29, 9, 15, 2, 30),
(52, 30, 4, 45, 2, 90),
(53, 31, 1, 30, 7, 210),
(54, 31, 9, 15, 7, 105),
(55, 32, 4, 45, 3, 135),
(56, 32, 14, 15, 3, 45),
(57, 33, 3, 65, 4, 260),
(58, 33, 2, 55, 6, 330),
(59, 34, 8, 45, 3, 135),
(60, 34, 4, 45, 3, 135),
(61, 35, 6, 55, 4, 220),
(62, 36, 1, 30, 4, 120),
(63, 37, 5, 60, 2, 120),
(64, 37, 9, 15, 2, 30),
(65, 37, 1, 30, 4, 120),
(66, 38, 2, 55, 6, 330),
(67, 38, 1, 30, 4, 120);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `ID_Pagos` int(11) NOT NULL,
  `ID_Venta` int(11) NOT NULL,
  `NomCliente` varchar(45) NOT NULL,
  `Metodo_Pago` varchar(30) NOT NULL,
  `Dinero` float NOT NULL,
  `Cuenta` float NOT NULL,
  `Cambio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`ID_Pagos`, `ID_Venta`, `NomCliente`, `Metodo_Pago`, `Dinero`, `Cuenta`, `Cambio`) VALUES
(1, 1, 'Alejandro Perez', 'Efectivo', 200, 185, 15),
(2, 2, 'Juan Rodriguez', 'Efectivo', 300, 280, 20),
(3, 3, 'Manuel Cota', 'Efectivo', 170, 165, 5),
(4, 4, 'Valentina Gandini', 'Efectivo', 500, 240, 260),
(5, 5, 'Gustavo Martinez', 'Tarjeta', 230, 230, 0),
(6, 6, 'Marco Gonzalez', 'Tarjeta', 240, 240, 0),
(7, 7, 'Daniel Diaz', 'Tarjeta', 120, 120, 0),
(8, 8, 'Juliette Hernandez', 'Efectivo', 500, 410, 90),
(9, 9, 'Chelo Ramos', 'Efectivo', 220, 210, 10),
(10, 10, 'Michell Arroyo', 'Efectivo', 165, 165, 0),
(11, 11, 'Nicole Garcia', 'Efectivo', 100, 90, 10),
(12, 12, 'Javier Amador', 'Tarjeta', 580, 580, 0),
(13, 13, 'Adriana García', 'Tarjeta', 385, 385, 0),
(14, 14, 'Daniel Torres', 'Tarjeta', 320, 320, 0),
(15, 15, 'Cinthya Guzmán', 'Efectivo', 70, 60, 10),
(16, 16, 'Diana Díazn', 'Efectivo', 225, 225, 0),
(17, 17, 'Laura Castro', 'Efectivo', 220, 210, 10),
(18, 18, 'Leonardo Rojas', 'Efectivo', 110, 110, 0),
(19, 19, 'Karen Pulido', 'Tarjeta', 260, 260, 0),
(20, 20, 'José Mora', 'Tarjeta', 360, 360, 0),
(21, 21, 'Natalia Solano', 'Efectivo', 270, 265, 5),
(22, 22, 'Paola Contreras', 'Tarjeta', 240, 240, 0),
(23, 23, 'Pablo Castillo', 'Efectivo', 210, 210, 0),
(24, 24, 'Sebastián Salazar', 'Efectivo', 220, 210, 10),
(25, 25, 'Julián Del Río', 'Efectivo', 450, 415, 35),
(26, 26, 'Laura Ochoa', 'Tarjeta', 220, 220, 0),
(27, 27, 'Camila Dueñas', 'Tarjeta', 390, 390, 0),
(28, 28, 'Andrés Puerto', 'Tarjeta', 225, 225, 0),
(29, 29, 'Oscar Cabrera', 'Efectivo', 250, 225, 25),
(30, 30, 'Elise Zaavan', 'Efectivo', 100, 90, 10),
(31, 31, 'Rafael Castellanos', 'Efectivo', 315, 315, 0),
(32, 32, 'Ricardo Vega', 'Efectivo', 180, 180, 0),
(33, 33, 'Paola Méndez', 'Tarjeta', 590, 590, 0),
(34, 34, 'Mónica Uribe', 'Efectivo', 300, 270, 30),
(35, 35, 'Rafael Castellanos', 'Tarjeta', 220, 220, 0),
(36, 36, 'Sandra Parra', 'Efectivo', 120, 120, 0),
(37, 37, 'Caitlyn Kiramman', 'Tarjeta', 270, 270, 0),
(38, 38, 'Fiora Laurent', 'Efectivo', 500, 450, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Descripcion` varchar(200) NOT NULL,
  `Tipo` varchar(30) NOT NULL,
  `Costo` float NOT NULL,
  `Stock` int(11) NOT NULL,
  `Precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID_Producto`, `Nombre`, `Descripcion`, `Tipo`, `Costo`, `Stock`, `Precio`) VALUES
(1, 'Hot Dog', 'Sencillo.', 'Alimento', 12, 135, 30),
(2, 'Hot Dog Especial', 'Con carne asada, queso americano y aguacate.', 'Alimento', 20, 40, 55),
(3, 'Hamburguesa', 'Sencilla con carne de 1/4 libra.', 'Alimento', 25, 65, 65),
(4, 'Burro', 'Tamaño de tortilla normal.', 'Alimento', 16, 65, 45),
(5, 'Superburro', 'Tamaño de tortilla sobaquera.', 'Alimento', 24, 67, 60),
(6, 'Torta', 'Sencilla de carna asada o deshebrada.', 'Alimento', 16, 46, 55),
(7, 'Torta Especial', 'Incluye carna asada, deshebrada, queso americano y blanco.', 'Alimento', 30, 51, 65),
(8, 'Papas', 'Orden sencilla.', 'Alimento', 14, 48, 45),
(9, 'Coca cola', 'Envase 600ml.', 'Bebida', 7, 23, 15),
(10, 'Sprite', 'Envase 600ml.', 'Bebida', 7, 29, 15),
(11, 'Té Lipton', 'Té negro sabor limón 600ml.', 'Bebida', 7, 23, 15),
(12, 'Fanta naranja', 'Envase 600ml.', 'Bebida', 7, 17, 15),
(13, 'Fanta fresa', 'Envase 600ml.', 'Bebida', 7, 6, 15),
(14, 'Pepsi', 'Envase 600ml.', 'Bebida', 7, 17, 15),
(15, 'Dr Pepper', 'Envase 600ml.', 'Bebida', 7, 9, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_Usuario` int(11) NOT NULL,
  `Usuario` varchar(20) NOT NULL,
  `Contraseña` varchar(32) NOT NULL,
  `Rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_Usuario`, `Usuario`, `Contraseña`, `Rol`) VALUES
(1, 'Admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrador'),
(2, 'Empleado01', '8e690d791e34b0defef31979bb1834ef', 'Empleado'),
(3, 'Empleado02', 'd9525a89f99185dddd77345e5717553d', 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `ID_Venta` int(11) NOT NULL,
  `NomCliente` varchar(45) NOT NULL,
  `Monto_Total` float NOT NULL,
  `Fecha_Venta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`ID_Venta`, `NomCliente`, `Monto_Total`, `Fecha_Venta`) VALUES
(1, 'Alejandro Perez', 185, '2022-06-03'),
(2, 'Juan Rodriguez', 280, '2022-06-03'),
(3, 'Manuel Cota', 165, '2022-06-03'),
(4, 'Valentina Gandini', 240, '2022-06-03'),
(5, 'Gustavo Martinez', 230, '2022-06-03'),
(6, 'Marco Gonzalez', 240, '2022-06-03'),
(7, 'Daniel Diaz', 120, '2022-06-03'),
(8, 'Juliette Hernandez', 410, '2022-06-03'),
(9, 'Chelo Ramos', 210, '2022-06-03'),
(10, 'Michell Arroyo', 165, '2022-06-03'),
(11, 'Nicole Garcia', 90, '2022-06-03'),
(12, 'Javier Amador', 580, '2022-06-03'),
(13, 'Adriana García', 385, '2022-06-04'),
(14, 'Daniel Torres', 320, '2022-06-04'),
(15, 'Cinthya Guzmán', 60, '2022-06-04'),
(16, 'Diana Díazn', 225, '2022-06-04'),
(17, 'Laura Castro', 210, '2022-06-04'),
(18, 'Leonardo Rojas', 110, '2022-06-05'),
(19, 'Karen Pulido', 260, '2022-06-05'),
(20, 'José Mora', 360, '2022-06-05'),
(21, 'Natalia Solano', 265, '2022-06-05'),
(22, 'Paola Contreras', 240, '2022-06-05'),
(23, 'Pablo Castillo', 210, '2022-06-06'),
(24, 'Sebastián Salazar', 210, '2022-06-06'),
(25, 'Julián Del Río', 415, '2022-06-06'),
(26, 'Laura Ochoa', 220, '2022-06-06'),
(27, 'Camila Dueñas', 390, '2022-06-07'),
(28, 'Andrés Puerto', 225, '2022-06-07'),
(29, 'Oscar Cabrera', 225, '2022-06-07'),
(30, 'Elise Zaavan', 90, '2022-06-07'),
(31, 'Rafael Castellanos', 315, '2022-06-08'),
(32, 'Ricardo Vega', 180, '2022-06-08'),
(33, 'Paola Méndez', 590, '2022-06-08'),
(34, 'Mónica Uribe', 270, '2022-06-08'),
(35, 'Rafael Castellanos', 220, '2022-06-09'),
(36, 'Sandra Parra', 120, '2022-06-09'),
(37, 'Caitlyn Kiramman', 270, '2022-06-09'),
(38, 'Fiora Laurent', 450, '2022-06-09');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `viewresumenventas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `viewresumenventas` (
`Nombre` varchar(30)
,`Cantidad` decimal(32,0)
,`Costo` float
,`Costo_Total` double
,`Precio` float
,`Monto_Total` double
);

-- --------------------------------------------------------

--
-- Estructura para la vista `viewresumenventas`
--
DROP TABLE IF EXISTS `viewresumenventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewresumenventas`  AS SELECT `productos`.`Nombre` AS `Nombre`, sum(`detalleventas`.`Cantidad`) AS `Cantidad`, `productos`.`Costo` AS `Costo`, sum(`detalleventas`.`Cantidad`) * `productos`.`Costo` AS `Costo_Total`, `productos`.`Precio` AS `Precio`, sum(`detalleventas`.`Total`) AS `Monto_Total` FROM ((`productos` join `detalleventas` on(`detalleventas`.`ID_Producto` = `productos`.`ID_Producto`)) join `ventas` on(`ventas`.`ID_Venta` = `detalleventas`.`ID_Venta`)) GROUP BY `productos`.`Nombre` ORDER BY `productos`.`ID_Producto` ASC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `detalleventas`
--
ALTER TABLE `detalleventas`
  ADD PRIMARY KEY (`ID_Detalle`),
  ADD KEY `ID_Venta` (`ID_Venta`),
  ADD KEY `ID_Producto` (`ID_Producto`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`ID_Pagos`),
  ADD KEY `ID_Venta` (`ID_Venta`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID_Producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD UNIQUE KEY `Usuario` (`Usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`ID_Venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalleventas`
--
ALTER TABLE `detalleventas`
  MODIFY `ID_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `ID_Pagos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `ID_Venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalleventas`
--
ALTER TABLE `detalleventas`
  ADD CONSTRAINT `detalleventas_ibfk_1` FOREIGN KEY (`ID_Venta`) REFERENCES `ventas` (`ID_Venta`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `detalleventas_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `productos` (`ID_Producto`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`ID_Venta`) REFERENCES `ventas` (`ID_Venta`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
