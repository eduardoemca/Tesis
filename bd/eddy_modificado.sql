DROP DATABASE IF EXISTS `eddibd`;
CREATE DATABASE IF NOT EXISTS `eddibd` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `eddibd`;

DROP TABLE IF EXISTS usuario;
CREATE TABLE IF NOT EXISTS usuario (
	user_id bigint NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
	fecha_registro DATETIME NOT NULL,
	usuario VARCHAR(45) NOT NULL,
	tipo_usuario VARCHAR(15) NOT NULL,
	pregunta_secreta VARCHAR(45) NOT NULL,
	respuesta_secreta VARCHAR(45) NOT NULL,
	clave_seguridad VARCHAR(100) NOT NULL,
	correo VARCHAR(65) NOT NULL,
	telefono VARCHAR(12) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(user_id),
	UNIQUE `user_id`(`user_id` ASC),
	UNIQUE `correo`(`correo` ASC))
ENGINE = InnoDB;
  
DROP TABLE IF EXISTS cliente;
CREATE TABLE IF NOT EXISTS cliente (
	fecha_registro DATETIME NOT NULL,
	cedula VARCHAR(10) NOT NULL,
	nombre VARCHAR(40) NOT NULL,
	apellido VARCHAR(40) NOT NULL,
	direccion VARCHAR(100) NOT NULL,
	correo VARCHAR(50) NOT NULL,
	telefono VARCHAR(12) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(cedula))
ENGINE = InnoDB;

DROP TABLE IF EXISTS proveedor;
CREATE TABLE IF NOT EXISTS proveedor (
	fecha_registro DATETIME NOT NULL,
	identificacion VARCHAR(15) NOT NULL,
	razon_social VARCHAR(40) NOT NULL,
	direccion VARCHAR(100) NOT NULL,
	correo VARCHAR(40) NOT NULL,
	telefono VARCHAR(12) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(identificacion),
	UNIQUE `razon_social`(`razon_social` ASC))
ENGINE = InnoDB;

DROP TABLE IF EXISTS categoria;
CREATE TABLE IF NOT EXISTS categoria (
	fecha_registro DATETIME NOT NULL,
	id_categoria bigint NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(20) NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(id_categoria),
	UNIQUE `categoria`(`nombre` ASC))
ENGINE = InnoDB;

DROP TABLE IF EXISTS unidad;
CREATE TABLE IF NOT EXISTS unidad (
	fecha_registro DATETIME NOT NULL,
	id_unidad bigint NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(20) NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(id_unidad),
	UNIQUE `unidad`(`nombre` ASC))
ENGINE = InnoDB;

DROP TABLE IF EXISTS iva;
CREATE TABLE IF NOT EXISTS iva (
	fecha_registro DATETIME NOT NULL,
	id_iva bigint NOT NULL AUTO_INCREMENT,
	iva DECIMAL(65,2) NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(id_iva),
	UNIQUE `iva`(`iva` ASC))
ENGINE = InnoDB;

DROP TABLE IF EXISTS producto;
CREATE TABLE IF NOT EXISTS producto (
	fecha_registro DATETIME NOT NULL,
	codigo_producto VARCHAR(10) NOT NULL,
	nombre VARCHAR(40) NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	tipo_categoria bigint NOT NULL,
	cantidad_minima INT(11) NOT NULL,
	cantidad_actual INT(11) NOT NULL DEFAULT 0,
	cantidad_maxima INT(11) NOT NULL,
	tipo_unidad bigint NOT NULL,
	precio DECIMAL(65,2) NOT NULL DEFAULT 0.00,
	gravado VARCHAR(15) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(codigo_producto),
	INDEX `tipo_categoria` (`tipo_categoria` ASC),
	INDEX `tipo_unidad` (`tipo_unidad` ASC),
	INDEX `gravado` (`gravado` ASC),
	UNIQUE `descripcion`(`descripcion` ASC),
FOREIGN KEY(tipo_categoria) REFERENCES categoria(id_categoria)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(tipo_unidad) REFERENCES unidad(id_unidad)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE = InnoDB;

DROP TABLE IF EXISTS proveedor_producto;
CREATE TABLE IF NOT EXISTS proveedor_producto (
	fecha_registro DATETIME NOT NULL,
	codigo_proveedor VARCHAR(15) NOT NULL,
	codigo_producto VARCHAR(10) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'ACTIVO',
PRIMARY KEY(codigo_producto,codigo_proveedor),
	INDEX `codigo_proveedor` (`codigo_proveedor` ASC),
	INDEX `proveedor_producto_ibfk_1_idx` (`codigo_producto` ASC),
FOREIGN KEY(codigo_producto) REFERENCES producto(codigo_producto)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(codigo_proveedor) REFERENCES proveedor(identificacion)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE = InnoDB;

DROP TABLE IF EXISTS orden_compra;
CREATE TABLE IF NOT EXISTS orden_compra (
	fecha_registro DATETIME NOT NULL,
	codigo_orden_compra VARCHAR(10) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'GENERADA',
PRIMARY KEY(codigo_orden_compra))
ENGINE = InnoDB;

DROP TABLE IF EXISTS detalle_orden_compra;
CREATE TABLE IF NOT EXISTS detalle_orden_compra (
	codigo_orden_compra VARCHAR(10) NOT NULL,
	codigo_proveedor VARCHAR(15) NOT NULL,
	codigo_producto VARCHAR(10) NOT NULL,
	cantidad_solicitada INT(11) NOT NULL,
	cantidad_faltante INT(11) NOT NULL DEFAULT 0,
PRIMARY KEY(codigo_orden_compra, codigo_proveedor, codigo_producto),
	INDEX `codigo_producto` (`codigo_producto` ASC),
	INDEX `codigo_proveedor` (`codigo_proveedor` ASC),
	INDEX `detalle_orden_compra_ibfk_1_idx` (`codigo_orden_compra` ASC),
FOREIGN KEY(codigo_orden_compra) REFERENCES orden_compra(codigo_orden_compra)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(codigo_proveedor) REFERENCES proveedor(identificacion)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(codigo_producto) REFERENCES producto(codigo_producto)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE = InnoDB;

DROP TABLE IF EXISTS compra;
CREATE TABLE IF NOT EXISTS compra (
	fecha_registro DATETIME NOT NULL,
	codigo_compra VARCHAR(20) NOT NULL,
	codigo_orden_compra VARCHAR(10) NOT NULL,
	codigo_proveedor VARCHAR(15) NOT NULL,
	estado VARCHAR(15) NOT NULL,
PRIMARY KEY(codigo_compra,codigo_orden_compra,codigo_proveedor),
	INDEX `codigo_proveedor` (`codigo_proveedor` ASC),
	INDEX `fk_compra_orden_compra1_idx` (`codigo_orden_compra` ASC),
FOREIGN KEY(codigo_proveedor) REFERENCES proveedor(identificacion)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(codigo_orden_compra) REFERENCES orden_compra(codigo_orden_compra)
ON DELETE RESTRICT ON UPDATE RESTRICT)
ENGINE = InnoDB;

DROP TABLE IF EXISTS detalle_compra;
CREATE TABLE IF NOT EXISTS detalle_compra (
	codigo_compra VARCHAR(20) NOT NULL,
	codigo_producto VARCHAR(10) NOT NULL,
	cantidad_comprada INT(11) NOT NULL,
	precio_compra DECIMAL(65,2) NOT NULL,
	gravable DECIMAL(65,2) NOT NULL,
PRIMARY KEY(codigo_compra, codigo_producto),
	INDEX `codigo_producto` (`codigo_producto` ASC),
	INDEX `detalle_compra_ibfk_1_idx` (`codigo_compra` ASC),
FOREIGN KEY(codigo_compra) REFERENCES compra(codigo_compra)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(codigo_producto) REFERENCES producto(codigo_producto)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE = InnoDB;

DROP TABLE IF EXISTS venta;
CREATE TABLE IF NOT EXISTS venta (
	fecha_registro DATETIME NOT NULL,
	codigo_venta VARCHAR(10) NOT NULL,
	cedula_cliente VARCHAR(11) NOT NULL,
	estado VARCHAR(15) NOT NULL DEFAULT 'PROCESADA',
PRIMARY KEY(codigo_venta),
	INDEX `cedula_cliente` (`cedula_cliente` ASC),
FOREIGN KEY(cedula_cliente) REFERENCES cliente(cedula)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE = InnoDB;

DROP TABLE IF EXISTS detalle_venta;
CREATE TABLE IF NOT EXISTS detalle_venta (
	codigo_venta VARCHAR(10) NOT NULL,
	codigo_producto VARCHAR(10) NOT NULL,
	cantidad_vendida INT(11) NOT NULL,
	gravable DECIMAL(65,2) NOT NULL,
PRIMARY KEY(codigo_venta, codigo_producto),
	INDEX `codigo_producto` (`codigo_producto` ASC),
	INDEX `detalle_factura_ibfk_1_idx` (`codigo_venta` ASC),
FOREIGN KEY(codigo_venta) REFERENCES venta(codigo_venta)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(codigo_producto) REFERENCES producto(codigo_producto)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE = InnoDB;

DROP TABLE IF EXISTS bitacora;
CREATE TABLE IF NOT EXISTS bitacora (
	fecha_registro DATETIME NOT NULL,
	session_id varchar(100) NOT NULL,
PRIMARY KEY(session_id))
ENGINE = InnoDB;

DROP TABLE IF EXISTS detalle_bitacora ;
CREATE TABLE IF NOT EXISTS detalle_bitacora  (
	codigo BIGINT NOT NULL AUTO_INCREMENT,
	session_id varchar(100) NOT NULL,
	usuario BIGINT NOT NULL,
	movimiento VARCHAR(40) COLLATE utf8_unicode_ci ,
	modulo VARCHAR(40) ,
	fecha_movimiento DATETIME ,
PRIMARY KEY(codigo),
	INDEX `bitacora` (`session_id` ASC),
	INDEX `usuario` (`usuario` ASC),
FOREIGN KEY(session_id) REFERENCES bitacora(session_id)
ON DELETE RESTRICT ON UPDATE CASCADE,
FOREIGN KEY(usuario) REFERENCES usuario(user_id)
ON DELETE RESTRICT ON UPDATE CASCADE)
ENGINE = InnoDB;

DROP TABLE IF EXISTS tmp_proveedor_producto;
CREATE TABLE IF NOT EXISTS tmp_proveedor_producto (
	id_tmp bigint NOT NULL AUTO_INCREMENT,
	id_proveedor VARCHAR(10) NOT NULL,
	razon_social VARCHAR(100) NOT NULL,
	session_id varchar(100) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY(id_tmp))
ENGINE = InnoDB;

DROP TABLE IF EXISTS tmp_producto_proveedor;
CREATE TABLE IF NOT EXISTS tmp_producto_proveedor (
	id_tmp bigint NOT NULL AUTO_INCREMENT,
	id_producto VARCHAR(10) NOT NULL,
	nombre VARCHAR(40) NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	session_id varchar(100) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY(id_tmp))
ENGINE = InnoDB;

DROP TABLE IF EXISTS tmp_proveedor_producto_orden;
CREATE TABLE IF NOT EXISTS tmp_proveedor_producto_orden (
	id_tmp bigint NOT NULL AUTO_INCREMENT,
	codigo_producto VARCHAR(10) NOT NULL,
	nombre VARCHAR(40) NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	cantidad_solicitada INT(11) NOT NULL,
	tipo_unidad VARCHAR(50) NOT NULL,
	session_id varchar(100) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY(id_tmp))
ENGINE = InnoDB;

DROP TABLE IF EXISTS tmp_compra;
CREATE TABLE IF NOT EXISTS tmp_compra (
	id_tmp bigint NOT NULL AUTO_INCREMENT,
	codigo_producto VARCHAR(10) NOT NULL,
	cantidad_comprada INT(11) NOT NULL,
	precio_unitario DECIMAL(65,2) NOT NULL,
	gravable DECIMAL(65,2) NOT NULL,
	session_id varchar(100) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY(id_tmp))
ENGINE = InnoDB;

DROP TABLE IF EXISTS tmp_venta;
CREATE TABLE IF NOT EXISTS tmp_venta (
	id_tmp bigint NOT NULL AUTO_INCREMENT,
	codigo_producto VARCHAR(10) NOT NULL,
	cantidad_vendida INT(11) NOT NULL,
	precio_unitario DECIMAL(65,2) NOT NULL,
	gravable DECIMAL(65,2) NOT NULL,
	session_id varchar(100) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY(id_tmp))
ENGINE = InnoDB;

INSERT INTO `cliente` (`fecha_registro`, `cedula`, `nombre`, `apellido`, `direccion`, `correo`, `telefono`) VALUES ('2018-12-08 17:16:50', 'V22182461', 'Eduardo', 'Castillo', 'Carrera 16 entre Calles 56 y 57','eduardoemca@hotmail.com', '04145648825');

INSERT INTO `categoria` (`fecha_registro`, `id_categoria`, `nombre`, `descripcion`) VALUES ('2018-12-08 17:16:50', '1', 'Lacteos', 'Bebidas energeticas, entre otros');
INSERT INTO `categoria` (`fecha_registro`, `id_categoria`, `nombre`, `descripcion`) VALUES ('2018-12-08 17:16:50', '2', 'Chucheria', 'Chocolates, galletas entre otros');
INSERT INTO `unidad` (`fecha_registro`, `id_unidad`, `nombre`, `descripcion`) VALUES ('2018-12-08 17:16:50', '1', 'Litro(s)', 'Para todo tipo de bebida');
INSERT INTO `unidad` (`fecha_registro`, `id_unidad`, `nombre`, `descripcion`) VALUES ('2018-12-08 17:16:50', '2', 'Gramo(s)', 'Universal');
INSERT INTO `iva` (`fecha_registro`, `id_iva`, `iva`, `descripcion`) VALUES ('2018-12-08 17:16:50', '1', '0.16', 'Para todo tipo de Producto');

INSERT INTO `proveedor` (`fecha_registro`, `identificacion`, `razon_social`, `direccion`, `correo`, `telefono`) VALUES ('2018-12-08 17:16:50', 'V11267857', 'Nilge C.A', 'Calle 55a entre Carreras 13c y 14', 'nilgecolmenarez@gmail.com', '02517702646');
INSERT INTO `proveedor` (`fecha_registro`, `identificacion`, `razon_social`, `direccion`, `correo`, `telefono`) VALUES ('2018-12-08 17:16:50', 'V26370589', 'Diego C.A', 'Calle 55a entre Carreras 13c y 14', 'diegocrespo1998@gmail.com', '02517702646');
INSERT INTO `proveedor` (`fecha_registro`, `identificacion`, `razon_social`, `direccion`, `correo`, `telefono`) VALUES ('2018-12-08 17:16:50', 'V9614969', 'Freddy C.A', 'Calle 55a entre Carreras 13c y 14', 'freddyomarcrespoaraujo@gmail.com', '02517702646');

INSERT INTO `producto` (`fecha_registro`, `codigo_producto`, `nombre`, `descripcion`, `tipo_categoria`, `cantidad_minima`, `cantidad_maxima`, `tipo_unidad`,`gravado`) VALUES ('2019-01-08 05:59:02', 'P0001', 'Leche', 'Descremada', '1', '100', '1000', '1', 'ACTIVO');
INSERT INTO `producto` (`fecha_registro`, `codigo_producto`, `nombre`, `descripcion`, `tipo_categoria`, `cantidad_minima`, `cantidad_maxima`, `tipo_unidad`,`gravado`) VALUES ('2019-01-08 05:59:02', 'P0002', 'Chocolate', 'Galack', '2', '100', '1000', '2', 'INACTIVO');
INSERT INTO `producto` (`fecha_registro`, `codigo_producto`, `nombre`, `descripcion`, `tipo_categoria`, `cantidad_minima`, `cantidad_maxima`, `tipo_unidad`,`gravado`) VALUES ('2019-01-08 05:59:02', 'P0003', 'Chocolate', 'Cri Cri', '2', '100', '1000', '2', 'ACTIVO');

INSERT INTO `proveedor_producto` (`fecha_registro`, `codigo_proveedor`, `codigo_producto`, `estado`) VALUES ('2018-12-08 17:16:50', 'V11267857', 'P0001', 'ACTIVO');
INSERT INTO `proveedor_producto` (`fecha_registro`, `codigo_proveedor`, `codigo_producto`, `estado`) VALUES ('2018-12-08 17:16:50', 'V11267857', 'P0002', 'ACTIVO');
INSERT INTO `proveedor_producto` (`fecha_registro`, `codigo_proveedor`, `codigo_producto`, `estado`) VALUES ('2018-12-08 17:16:50', 'V11267857', 'P0003', 'ACTIVO');
INSERT INTO `proveedor_producto` (`fecha_registro`, `codigo_proveedor`, `codigo_producto`, `estado`) VALUES ('2018-12-08 17:16:50', 'V26370589', 'P0001', 'ACTIVO');
INSERT INTO `proveedor_producto` (`fecha_registro`, `codigo_proveedor`, `codigo_producto`, `estado`) VALUES ('2018-12-08 17:16:50', 'V9614969', 'P0001', 'ACTIVO');

INSERT INTO `usuario` (`user_id`,`fecha_registro`,`usuario`,`tipo_usuario`,`pregunta_secreta`,`respuesta_secreta`,`clave_seguridad`,`estado`) VALUES ('1','2019-01-08','admin','admin','admin','admin','$2y$10$MPVHzZ2ZPOWmtUUGCq3RXu31OTB.jo7M9LZ7PmPQYmgETSNn19ejO','ACTIVO');