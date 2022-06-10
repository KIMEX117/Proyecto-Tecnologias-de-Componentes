CREATE DATABASE proyectoBDD;
USE proyectoBDD;

CREATE TABLE Usuarios(
ID_Usuario INT AUTO_INCREMENT PRIMARY KEY,
Usuario VARCHAR(20) NOT NULL UNIQUE,
Contraseña VARCHAR(32) NOT NULL,
Rol VARCHAR(20) NOT NULL);

CREATE TABLE Productos(
ID_Producto INT AUTO_INCREMENT PRIMARY KEY,
Nombre VARCHAR(30) NOT NULL,
Descripcion VARCHAR(200) NOT NULL,
Tipo VARCHAR(30) NOT NULL,
Costo FLOAT NOT NULL,
Stock INT NOT NULL,
Precio FLOAT NOT NULL);

CREATE TABLE Ventas(
ID_Venta INT AUTO_INCREMENT PRIMARY KEY,
NomCliente VARCHAR(45) NOT NULL,
Monto_Total FLOAT NOT NULL,
Fecha_Venta DATE NOT NULL);

CREATE TABLE DetalleVentas(
ID_Detalle INT AUTO_INCREMENT PRIMARY KEY,
ID_Venta INT NOT NULL,
ID_Producto INT DEFAULT '0',
Monto FLOAT NOT NULL,
Cantidad INT NOT NULL,
Total FLOAT NOT NULL,
FOREIGN KEY (ID_Venta) REFERENCES Ventas (ID_Venta) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY (ID_Producto) REFERENCES Productos (ID_Producto) ON UPDATE CASCADE ON DELETE SET NULL);

CREATE TABLE Pagos(
ID_Pagos INT AUTO_INCREMENT PRIMARY KEY,
ID_Venta INT NOT NULL,
NomCliente VARCHAR(45) NOT NULL,
Metodo_Pago VARCHAR(30) NOT NULL,
Dinero FLOAT NOT NULL,
Cuenta FLOAT NOT NULL,
Cambio FLOAT NOT NULL,
FOREIGN KEY (ID_Venta) REFERENCES Ventas (ID_Venta) ON UPDATE CASCADE ON DELETE NO ACTION);

CREATE TABLE Carrito(
ID INT AUTO_INCREMENT PRIMARY KEY,
ID_Producto INT NOT NULL,
Nombre VARCHAR(30) NOT NULL,
Stock INT NOT NULL,
Precio FLOAT NOT NULL);

-- PROCEDIMIENTO PARA REALIZAR PAGOS
DELIMITER $$
CREATE PROCEDURE spRealizarPagos (IN paramIDVenta INT, IN paramMetodo VARCHAR(30), IN paramDinero FLOAT)
BEGIN
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

-- PROCEDIMIENTO PARA REALIZAR VENTAS
DELIMITER $$
CREATE PROCEDURE spRealizarVenta (IN paramIDProducto INT, IN paramNombre VARCHAR(45), IN paramFecha DATE, IN paramCantProduct INT)
BEGIN
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

-- PROCEDIMIENTO PARA AÑADIR MÁS PRODUCTOS A UNA VENTA
DELIMITER $$
CREATE PROCEDURE spAgregarVenta(IN paramIDVenta INT, IN paramIDProducto INT, IN paramCantidad INT)
BEGIN
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

-- PROCEDIMIENTO PARA ELIMINAR UNA VENTA COMPLETA DE LA TABLA VENTAS
DELIMITER $$
CREATE PROCEDURE spEliminarVenta (IN paramIDVenta INT)
BEGIN
    START TRANSACTION;
    DELETE FROM pagos WHERE ID_Venta = paramIDVenta;
    DELETE FROM DetalleVentas WHERE ID_Venta = paramIDVenta;
    DELETE FROM Ventas WHERE ID_Venta = paramIDVenta;
    COMMIT;
END$$

-- PROCEDIMIENTO PARA ELIMINAR UNA VENTA DE DETALLE VENTA
DELIMITER $$
CREATE PROCEDURE spEliminarDetalleVenta (IN paramIDDetalle INT, IN paramIDVenta INT)
BEGIN
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

-- VIEW PARA RESUMEN GENERAL DE VENTAS
CREATE VIEW viewResumenVentas AS
SELECT Productos.Nombre AS Nombre, SUM(detalleVentas.Cantidad) Cantidad, Productos.Costo Costo, (SUM(detalleVentas.Cantidad)*Productos.Costo) Costo_Total, Productos.Precio Precio, SUM(detalleVentas.Total) Monto_Total FROM Productos
INNER JOIN detalleVentas ON detalleVentas.ID_Producto = Productos.ID_Producto 
INNER JOIN Ventas ON Ventas.ID_Venta = detalleVentas.ID_Venta
GROUP BY Productos.Nombre ORDER BY Productos.ID_Producto ASC;


-- DATOS PARA LAS TABLAS
INSERT INTO Usuarios VALUES(0, 'Admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrador'); -- USER: Admin        PASS: admin
INSERT INTO Usuarios VALUES(0, 'Empleado01', '8e690d791e34b0defef31979bb1834ef', 'Empleado'); -- USER: Empleado01   PASS: Contra01
INSERT INTO Usuarios VALUES(0, 'Empleado02', 'd9525a89f99185dddd77345e5717553d', 'Empleado'); -- USER: Empleado02   PASS: Contra02

INSERT INTO Productos VALUES(1, 'Hot Dog', 'Sencillo.', 'Alimento', 12, 220, 30);
INSERT INTO Productos VALUES(2, 'Hot Dog Especial', 'Con carne asada, queso americano y aguacate.', 'Alimento', 20, 80, 55);
INSERT INTO Productos VALUES(3, 'Hamburguesa', 'Sencilla con carne de 1/4 libra.', 'Alimento', 25, 90, 65);
INSERT INTO Productos VALUES(4, 'Burro', 'Tamaño de tortilla normal.', 'Alimento', 16, 75, 45);
INSERT INTO Productos VALUES(5, 'Superburro', 'Tamaño de tortilla sobaquera.', 'Alimento', 24, 75, 60);
INSERT INTO Productos VALUES(6, 'Torta', 'Sencilla de carna asada o deshebrada.', 'Alimento', 16, 55, 55);
INSERT INTO Productos VALUES(7, 'Torta Especial', 'Incluye carna asada, deshebrada, queso americano y blanco.', 'Alimento', 30, 60, 65);
INSERT INTO Productos VALUES(8, 'Papas', 'Orden sencilla.', 'Alimento', 14, 64, 45);
INSERT INTO Productos VALUES(9, 'Coca cola', 'Envase 600ml.', 'Bebida', 7, 50, 15);
INSERT INTO Productos VALUES(10, 'Sprite', 'Envase 600ml.', 'Bebida', 7, 30, 15);
INSERT INTO Productos VALUES(11, 'Té Lipton', 'Té negro sabor limón 600ml.', 'Bebida', 7, 24, 15);
INSERT INTO Productos VALUES(12, 'Fanta naranja', 'Envase 600ml.', 'Bebida', 7, 19, 15);
INSERT INTO Productos VALUES(13, 'Fanta fresa', 'Envase 600ml.', 'Bebida', 7, 11, 15);
INSERT INTO Productos VALUES(14, 'Pepsi', 'Envase 600ml.', 'Bebida', 7, 20, 15);
INSERT INTO Productos VALUES(15, 'Dr Pepper', 'Envase 600ml.', 'Bebida', 7, 13, 15);

CALL `proyectobdd`.`spRealizarVenta`(1, 'Alejandro Perez', NOW()-INTERVAL 6 DAY, 4);
CALL `proyectobdd`.`spAgregarVenta`(1, 7, 1);
CALL `proyectobdd`.`spRealizarVenta`(3, 'Juan Rodriguez', NOW()-INTERVAL 6 DAY, 2);
CALL `proyectobdd`.`spAgregarVenta`(2, 5, 1);
CALL `proyectobdd`.`spAgregarVenta`(2, 8, 2);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Manuel Cota', NOW()-INTERVAL 6 DAY, 3);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Valentina Gandini', NOW()-INTERVAL 6 DAY, 8);
CALL `proyectobdd`.`spRealizarVenta`(7, 'Gustavo Martinez', NOW()-INTERVAL 6 DAY, 1);
CALL `proyectobdd`.`spAgregarVenta`(5, 6, 3);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Marco Gonzalez', NOW()-INTERVAL 6 DAY, 8);
CALL `proyectobdd`.`spRealizarVenta`(5, 'Daniel Diaz', NOW()-INTERVAL 6 DAY, 2);
CALL `proyectobdd`.`spRealizarVenta`(3, 'Juliette Hernandez', NOW()-INTERVAL 6 DAY, 4);
CALL `proyectobdd`.`spAgregarVenta`(8, 9, 2);
CALL `proyectobdd`.`spAgregarVenta`(8, 10, 1);
CALL `proyectobdd`.`spAgregarVenta`(8, 15, 1);
CALL `proyectobdd`.`spAgregarVenta`(8, 8, 2);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Chelo Ramos', NOW()-INTERVAL 6 DAY, 7);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Michell Arroyo', NOW()-INTERVAL 6 DAY, 3);
CALL `proyectobdd`.`spRealizarVenta`(4, 'Nicole Garcia', NOW()-INTERVAL 6 DAY, 2);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Javier Amador', NOW()-INTERVAL 6 DAY, 4);
CALL `proyectobdd`.`spAgregarVenta`(12, 1, 6);
CALL `proyectobdd`.`spAgregarVenta`(12, 8, 4);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Adriana García', NOW()-INTERVAL 5 DAY, 7);
CALL `proyectobdd`.`spRealizarVenta`(3, 'Daniel Torres', NOW()-INTERVAL 5 DAY, 4);
CALL `proyectobdd`.`spAgregarVenta`(14, 9, 4);
CALL `proyectobdd`.`spRealizarVenta`(8, 'Cinthya Guzmán', NOW()-INTERVAL 5 DAY, 1);
CALL `proyectobdd`.`spAgregarVenta`(15, 11, 1);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Diana Díazn', NOW()-INTERVAL 5 DAY, 6);
CALL `proyectobdd`.`spAgregarVenta`(16, 15, 3);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Laura Castro', NOW()-INTERVAL 5 DAY, 3);
CALL `proyectobdd`.`spAgregarVenta`(17, 9, 3);
CALL `proyectobdd`.`spRealizarVenta`(6, 'Leonardo Rojas', NOW()-INTERVAL 4 DAY, 2);
CALL `proyectobdd`.`spRealizarVenta`(7, 'Karen Pulido', NOW()-INTERVAL 4 DAY, 4);
CALL `proyectobdd`.`spRealizarVenta`(1, 'José Mora', NOW()-INTERVAL 4 DAY, 10);
CALL `proyectobdd`.`spAgregarVenta`(20, 9, 4);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Natalia Solano', NOW()-INTERVAL 4 DAY, 4);
CALL `proyectobdd`.`spAgregarVenta`(21, 12, 2);
CALL `proyectobdd`.`spAgregarVenta`(21, 9, 1);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Paola Contreras', NOW()-INTERVAL 4 DAY, 8);
CALL `proyectobdd`.`spRealizarVenta`(5, 'Pablo Castillo', NOW()-INTERVAL 3 DAY, 3);
CALL `proyectobdd`.`spAgregarVenta`(23, 9, 2);
CALL `proyectobdd`.`spRealizarVenta`(8, 'Sebastián Salazar', NOW()-INTERVAL 3 DAY, 2);
CALL `proyectobdd`.`spAgregarVenta`(24, 1, 4);
CALL `proyectobdd`.`spRealizarVenta`(3, 'Julián Del Río', NOW()-INTERVAL 3 DAY, 5);
CALL `proyectobdd`.`spAgregarVenta`(25, 8, 2);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Laura Ochoa', NOW()-INTERVAL 3 DAY, 4);
CALL `proyectobdd`.`spRealizarVenta`(3, 'Camila Dueñas', NOW()-INTERVAL 2 DAY, 6);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Andrés Puerto', NOW()-INTERVAL 2 DAY, 5);
CALL `proyectobdd`.`spAgregarVenta`(28, 13, 5);
CALL `proyectobdd`.`spRealizarVenta`(7, 'Oscar Cabrera', NOW()-INTERVAL 2 DAY, 3);
CALL `proyectobdd`.`spAgregarVenta`(29, 9, 2);
CALL `proyectobdd`.`spRealizarVenta`(4, 'Elise Zaavan', NOW()-INTERVAL 2 DAY, 2);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Rafael Castellanos', NOW()-INTERVAL 1 DAY, 7);
CALL `proyectobdd`.`spAgregarVenta`(31, 9, 7);
CALL `proyectobdd`.`spRealizarVenta`(4, 'Ricardo Vega', NOW()-INTERVAL 1 DAY, 3);
CALL `proyectobdd`.`spAgregarVenta`(32, 14, 3);
CALL `proyectobdd`.`spRealizarVenta`(3, 'Paola Méndez', NOW()-INTERVAL 1 DAY, 4);
CALL `proyectobdd`.`spAgregarVenta`(33, 2, 6);
CALL `proyectobdd`.`spRealizarVenta`(8, 'Mónica Uribe', NOW()-INTERVAL 1 DAY, 3);
CALL `proyectobdd`.`spAgregarVenta`(34, 4, 3);
CALL `proyectobdd`.`spRealizarVenta`(6, 'Rafael Castellanos', NOW(), 4);
CALL `proyectobdd`.`spRealizarVenta`(1, 'Sandra Parra', NOW(), 4);
CALL `proyectobdd`.`spRealizarVenta`(5, 'Caitlyn Kiramman', NOW(), 2);
CALL `proyectobdd`.`spAgregarVenta`(37, 9, 2);
CALL `proyectobdd`.`spAgregarVenta`(37, 1, 4);
CALL `proyectobdd`.`spRealizarVenta`(2, 'Fiora Laurent', NOW(), 6);
CALL `proyectobdd`.`spAgregarVenta`(38, 1, 4);

CALL `proyectobdd`.`spRealizarPagos`(1, 'Efectivo', 200);
CALL `proyectobdd`.`spRealizarPagos`(2, 'Efectivo', 300);
CALL `proyectobdd`.`spRealizarPagos`(3, 'Efectivo', 170);
CALL `proyectobdd`.`spRealizarPagos`(4, 'Efectivo', 500);
CALL `proyectobdd`.`spRealizarPagos`(5, 'Tarjeta', 230);
CALL `proyectobdd`.`spRealizarPagos`(6, 'Tarjeta', 240);
CALL `proyectobdd`.`spRealizarPagos`(7, 'Tarjeta', 120);
CALL `proyectobdd`.`spRealizarPagos`(8, 'Efectivo', 500);
CALL `proyectobdd`.`spRealizarPagos`(9, 'Efectivo', 220);
CALL `proyectobdd`.`spRealizarPagos`(10, 'Efectivo', 165);
CALL `proyectobdd`.`spRealizarPagos`(11, 'Efectivo', 100);
CALL `proyectobdd`.`spRealizarPagos`(12, 'Tarjeta', 580);
CALL `proyectobdd`.`spRealizarPagos`(13, 'Tarjeta', 385);
CALL `proyectobdd`.`spRealizarPagos`(14, 'Tarjeta', 320);
CALL `proyectobdd`.`spRealizarPagos`(15, 'Efectivo', 70);
CALL `proyectobdd`.`spRealizarPagos`(16, 'Efectivo', 225);
CALL `proyectobdd`.`spRealizarPagos`(17, 'Efectivo', 220);
CALL `proyectobdd`.`spRealizarPagos`(18, 'Efectivo', 110);
CALL `proyectobdd`.`spRealizarPagos`(19, 'Tarjeta', 260);
CALL `proyectobdd`.`spRealizarPagos`(20, 'Tarjeta', 360);
CALL `proyectobdd`.`spRealizarPagos`(21, 'Efectivo', 270);
CALL `proyectobdd`.`spRealizarPagos`(22, 'Tarjeta', 240);
CALL `proyectobdd`.`spRealizarPagos`(23, 'Efectivo', 210);
CALL `proyectobdd`.`spRealizarPagos`(24, 'Efectivo', 220);
CALL `proyectobdd`.`spRealizarPagos`(25, 'Efectivo', 450);
CALL `proyectobdd`.`spRealizarPagos`(26, 'Tarjeta', 220);
CALL `proyectobdd`.`spRealizarPagos`(27, 'Tarjeta', 390);
CALL `proyectobdd`.`spRealizarPagos`(28, 'Tarjeta', 225);
CALL `proyectobdd`.`spRealizarPagos`(29, 'Efectivo', 250);
CALL `proyectobdd`.`spRealizarPagos`(30, 'Efectivo', 100);
CALL `proyectobdd`.`spRealizarPagos`(31, 'Efectivo', 315);
CALL `proyectobdd`.`spRealizarPagos`(32, 'Efectivo', 180);
CALL `proyectobdd`.`spRealizarPagos`(33, 'Tarjeta', 590);
CALL `proyectobdd`.`spRealizarPagos`(34, 'Efectivo', 300);
CALL `proyectobdd`.`spRealizarPagos`(35, 'Tarjeta', 220);
CALL `proyectobdd`.`spRealizarPagos`(36, 'Efectivo', 120);
CALL `proyectobdd`.`spRealizarPagos`(37, 'Tarjeta', 270);
CALL `proyectobdd`.`spRealizarPagos`(38, 'Efectivo', 500);

