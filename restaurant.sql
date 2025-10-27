-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 21-10-2025 a las 11:38:09
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `restaurant`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `Nombre` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  `Categoria_id` int NOT NULL AUTO_INCREMENT,
  `Color` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`Categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`Nombre`, `Categoria_id`, `Color`) VALUES
('Grills and Mixed Grills', 1, '#D62828'),
('Special chicken', 2, '#F77F00'),
('Seafood and Fish', 3, '#3A86FF'),
('Combos and Special Dishes', 4, '#6A4C93'),
('Extras and side dishes', 5, '#83C5BE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas`
--

DROP TABLE IF EXISTS `comandas`;
CREATE TABLE IF NOT EXISTS `comandas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mesa_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comandas`
--

INSERT INTO `comandas` (`id`, `mesa_id`, `producto_id`, `cantidad`) VALUES
(66, 4, 12, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas_detalle`
--

DROP TABLE IF EXISTS `comandas_detalle`;
CREATE TABLE IF NOT EXISTS `comandas_detalle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comanda_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comanda_id` (`comanda_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `comandas_detalle`
--

INSERT INTO `comandas_detalle` (`id`, `comanda_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
(1, 3, 249, 2, NULL),
(2, 3, 250, 1, NULL),
(3, 4, 249, 3, NULL),
(4, 5, 249, 1, NULL),
(5, 5, 250, 1, NULL),
(6, 6, 250, 3, NULL),
(7, 6, 249, 2, NULL),
(8, 7, 249, 14, NULL),
(9, 8, 250, 3, NULL),
(10, 9, 250, 4, NULL),
(11, 10, 250, 1, NULL),
(12, 11, 250, 1, NULL),
(13, 12, 250, 3, NULL),
(14, 12, 253, 1, NULL),
(15, 12, 249, 1, NULL),
(16, 13, 253, 1, NULL),
(17, 13, 250, 1, NULL),
(18, 14, 253, 1, NULL),
(19, 15, 253, 2, NULL),
(20, 16, 253, 3, NULL),
(21, 17, 253, 7, NULL),
(22, 18, 250, 4, NULL),
(23, 18, 249, 2, NULL),
(24, 18, 253, 4, NULL),
(25, 19, 249, 7, NULL),
(26, 19, 254, 3, NULL),
(27, 19, 250, 3, NULL),
(28, 19, 253, 11, NULL),
(29, 20, 249, 6, NULL),
(30, 20, 250, 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comandas_historial`
--

DROP TABLE IF EXISTS `comandas_historial`;
CREATE TABLE IF NOT EXISTS `comandas_historial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mesa_id` int NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `mesa_id` (`mesa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `comandas_historial`
--

INSERT INTO `comandas_historial` (`id`, `mesa_id`, `fecha_creacion`, `total`) VALUES
(1, 1, '2025-06-06 05:46:09', 45.50),
(2, 1, '2025-06-06 05:46:19', 45.50),
(3, 1, '2025-06-06 05:46:35', 45.50),
(4, 1, '2025-06-06 05:47:41', 45.50),
(5, 1, '2025-06-06 05:52:13', 45.50),
(6, 1, '2025-06-06 05:52:27', 45.50),
(7, 1, '2025-06-06 05:55:59', 45.50),
(8, 1, '2025-06-06 13:31:14', 45.50),
(9, 1, '2025-06-06 15:05:36', 0.87),
(10, 1, '2025-06-06 15:05:44', 0.22),
(11, 1, '2025-06-06 15:50:42', 0.22),
(12, 1, '2025-06-06 16:48:49', 6.72),
(13, 1, '2025-06-06 16:49:37', 1.67),
(14, 1, '2025-06-13 12:23:49', 1.45),
(15, 2, '2025-06-13 12:30:40', 2.90),
(16, 2, '2025-06-13 12:41:13', 4.36),
(17, 3, '2025-06-13 12:45:55', 10.16),
(18, 1, '2025-06-13 15:43:34', 15.90),
(19, 1, '2025-07-11 01:10:14', 51.07),
(20, 1, '2025-07-11 09:58:35', 28.10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elaborado`
--

DROP TABLE IF EXISTS `elaborado`;
CREATE TABLE IF NOT EXISTS `elaborado` (
  `elaborado_id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` varchar(2550) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`elaborado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingrediente`
--

DROP TABLE IF EXISTS `ingrediente`;
CREATE TABLE IF NOT EXISTS `ingrediente` (
  `ingrediente_id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ingrediente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=488 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingrediente`
--

INSERT INTO `ingrediente` (`ingrediente_id`, `nombre`) VALUES
(1, 'Salsa Boloñesa'),
(2, 'Aceite'),
(3, 'Queso Rallado'),
(4, 'Champiñones en láminas'),
(5, 'Carne picada vacuno'),
(6, 'Cebolla'),
(7, 'Albóndigas'),
(8, 'Salsa bechamel'),
(9, 'Salsa Pesto'),
(10, 'Espaguetis'),
(11, 'Macarrones'),
(12, 'Salsa carbonara'),
(13, 'Arroz'),
(14, 'Masa de pizza'),
(15, 'Huevos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `restaurante_id` int DEFAULT NULL,
  `timeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo_accion` int DEFAULT NULL,
  `tipo_tabla` int DEFAULT NULL,
  `registro_id` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `comentario` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurante_id` (`restaurante_id`),
  KEY `tipo_accion` (`tipo_accion`),
  KEY `tipo_tabla` (`tipo_tabla`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

DROP TABLE IF EXISTS `mesas`;
CREATE TABLE IF NOT EXISTS `mesas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `estado` varchar(99) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `nombre`, `estado`) VALUES
(1, 'Mesa 1', 'ocupada'),
(2, 'Mesa 2', 'Ocupada'),
(3, 'Mesa 3', ''),
(4, '1234', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

DROP TABLE IF EXISTS `platos`;
CREATE TABLE IF NOT EXISTS `platos` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `imagen` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `instrucciones` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `Categoria_id` int NOT NULL,
  `Precio` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos_ingrediente`
--

DROP TABLE IF EXISTS `platos_ingrediente`;
CREATE TABLE IF NOT EXISTS `platos_ingrediente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_plato` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `unidad` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plato` (`id_plato`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos_preelaborados`
--

DROP TABLE IF EXISTS `platos_preelaborados`;
CREATE TABLE IF NOT EXISTS `platos_preelaborados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_plato` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plato` (`id_plato`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plato_restaurante`
--

DROP TABLE IF EXISTS `plato_restaurante`;
CREATE TABLE IF NOT EXISTS `plato_restaurante` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_plato` int NOT NULL,
  `id_restaurante` int NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plato` (`id_plato`),
  KEY `id_restaurante` (`id_restaurante`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

DROP TABLE IF EXISTS `receta`;
CREATE TABLE IF NOT EXISTS `receta` (
  `restaurante_id` int DEFAULT NULL,
  `receta_id` int NOT NULL AUTO_INCREMENT,
  `elaborado_id` int DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` varchar(2550) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cantidad_producida` int DEFAULT NULL,
  `cantidad_producida_unidad` int DEFAULT NULL,
  `imagen` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion_corta` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`receta_id`),
  KEY `cantidad_producida_unidad` (`cantidad_producida_unidad`),
  KEY `restaurante_id` (`restaurante_id`),
  KEY `elaborado_id` (`elaborado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_ingrediente`
--

DROP TABLE IF EXISTS `receta_ingrediente`;
CREATE TABLE IF NOT EXISTS `receta_ingrediente` (
  `receta_id` int DEFAULT NULL,
  `ingrediente_id` int DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  `unidad` int DEFAULT NULL,
  KEY `unidad` (`unidad`),
  KEY `receta_id` (`receta_id`),
  KEY `ingrediente_id` (`ingrediente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurante`
--

DROP TABLE IF EXISTS `restaurante`;
CREATE TABLE IF NOT EXISTS `restaurante` (
  `restaurante_id` int NOT NULL AUTO_INCREMENT,
  `CIF` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Dirección` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`restaurante_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `restaurante`
--

INSERT INTO `restaurante` (`restaurante_id`, `CIF`, `Dirección`) VALUES
(1, 'Q1673095D', 'C/ de Manuel Melià i Fuster, 1'),
(2, 'E99788119', 'C/ de Murillo, 22, Ciutat Vella, 46001 València, Valencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `restaurante_id` int DEFAULT NULL,
  `elaborado_id` int DEFAULT NULL,
  `ingrediente_id` int DEFAULT NULL,
  `cantidad_stock` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unidad` int DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `moneda` int DEFAULT NULL,
  `caducidad` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `moneda` (`moneda`),
  KEY `unidad` (`unidad`),
  KEY `restaurante_id` (`restaurante_id`),
  KEY `elaborado_id` (`elaborado_id`),
  KEY `ingrediente_id` (`ingrediente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_accion`
--

DROP TABLE IF EXISTS `tipo_accion`;
CREATE TABLE IF NOT EXISTS `tipo_accion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `accion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_accion`
--

INSERT INTO `tipo_accion` (`id`, `accion`) VALUES
(1, 'compra'),
(2, 'venta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_moneda`
--

DROP TABLE IF EXISTS `tipo_moneda`;
CREATE TABLE IF NOT EXISTS `tipo_moneda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Moneda` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Simbolo` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_moneda`
--

INSERT INTO `tipo_moneda` (`id`, `Moneda`, `Simbolo`) VALUES
(1, 'EUR', '€'),
(2, 'DOL', '$'),
(3, 'SAR', '﷼');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_tabla`
--

DROP TABLE IF EXISTS `tipo_tabla`;
CREATE TABLE IF NOT EXISTS `tipo_tabla` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tabla` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_tabla`
--

INSERT INTO `tipo_tabla` (`id`, `tabla`) VALUES
(1, 'Usuario'),
(2, 'Stock'),
(3, 'Ingrediente'),
(4, 'Elaborado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_unidad`
--

DROP TABLE IF EXISTS `tipo_unidad`;
CREATE TABLE IF NOT EXISTS `tipo_unidad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unidad` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_unidad`
--

INSERT INTO `tipo_unidad` (`id`, `unidad`) VALUES
(1, 'Qty'),
(2, 'kg'),
(3, 'g'),
(4, 'l'),
(5, 'ml'),
(6, 'lb');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id`, `rol`) VALUES
(1, 'Gerente'),
(2, 'Cocinero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `restaurante_id` int DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contacto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo_usuario_id` int DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `restaurante_id` (`restaurante_id`),
  KEY `tipo_usuario_id` (`tipo_usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `restaurante_id`, `password`, `nombre`, `direccion`, `contacto`, `tipo_usuario_id`) VALUES
(2, 1, '$2y$10$GeY5yrLaaUehZDa0hOBQIuYFkz5EJxxFVHNtUBsa9/Pm3yFFWBKTm', 'MIguel Gresa', NULL, NULL, 1),
(6, 1, '$2y$10$y33mvtnk0s4iqP4BoNDG5e0yMWez.aVDzKq5mhVWevG9.6V1ouxwW', 'Paco Garcia', NULL, NULL, 2),
(7, 1, '$2y$10$4Cg8Z05zUh/gTZ2TCjAJ7.bvnKz2gN.wZFZ4GI0/4/cTBFUJjkxV6', 'Manolo', NULL, NULL, 1),
(8, 2, '$2y$10$l1kiC4xTnYI4b6KIKby.ouLAAfGSCRITgxhIIkdzpekBFQsJ.Ua6u', 'Adrian Campos', NULL, NULL, 1),
(9, 1, '$2y$10$31DI7vOKYsI9Gg3eob.3Gepwhn/3YATtXzXCViK5OqZZBMzOzapQ2', 'Admin', NULL, NULL, 1),
(10, 1, '$2y$10$m9y3E8BypRwhxJiUMTwRY.LJmXTiEyNuZXzymOX4iwNKhnMUY9Ure', 'asd', NULL, NULL, 1),
(12, 2, '$2y$10$avrh6DmQF4wP6Bi.KJbqiuXvNKqtxmxXHT861eYnbWQ94HXEjWYNS', 'admin', NULL, NULL, 1),
(13, 1, '$2y$10$mVDRTe4l9irsIoW9iO/TQuHnjFUOyHMVm6quysAGbP/NE3PQRa6Ue', 'Toni', NULL, NULL, 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurante` (`restaurante_id`),
  ADD CONSTRAINT `logs_ibfk_2` FOREIGN KEY (`tipo_accion`) REFERENCES `tipo_accion` (`id`),
  ADD CONSTRAINT `logs_ibfk_3` FOREIGN KEY (`tipo_tabla`) REFERENCES `tipo_tabla` (`id`);

--
-- Filtros para la tabla `platos_ingrediente`
--
ALTER TABLE `platos_ingrediente`
  ADD CONSTRAINT `platos_ingrediente_ibfk_1` FOREIGN KEY (`id_plato`) REFERENCES `platos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `platos_preelaborados`
--
ALTER TABLE `platos_preelaborados`
  ADD CONSTRAINT `platos_preelaborados_ibfk_1` FOREIGN KEY (`id_plato`) REFERENCES `platos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `plato_restaurante`
--
ALTER TABLE `plato_restaurante`
  ADD CONSTRAINT `plato_restaurante_ibfk_1` FOREIGN KEY (`id_plato`) REFERENCES `platos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plato_restaurante_ibfk_2` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurante` (`restaurante_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `receta`
--
ALTER TABLE `receta`
  ADD CONSTRAINT `receta_ibfk_1` FOREIGN KEY (`cantidad_producida_unidad`) REFERENCES `tipo_unidad` (`id`),
  ADD CONSTRAINT `receta_ibfk_2` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurante` (`restaurante_id`),
  ADD CONSTRAINT `receta_ibfk_3` FOREIGN KEY (`elaborado_id`) REFERENCES `elaborado` (`elaborado_id`);

--
-- Filtros para la tabla `receta_ingrediente`
--
ALTER TABLE `receta_ingrediente`
  ADD CONSTRAINT `receta_ingrediente_ibfk_1` FOREIGN KEY (`unidad`) REFERENCES `tipo_unidad` (`id`),
  ADD CONSTRAINT `receta_ingrediente_ibfk_2` FOREIGN KEY (`receta_id`) REFERENCES `receta` (`receta_id`),
  ADD CONSTRAINT `receta_ingrediente_ibfk_3` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingrediente` (`ingrediente_id`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`moneda`) REFERENCES `tipo_moneda` (`id`),
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`unidad`) REFERENCES `tipo_unidad` (`id`),
  ADD CONSTRAINT `stock_ibfk_3` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurante` (`restaurante_id`),
  ADD CONSTRAINT `stock_ibfk_4` FOREIGN KEY (`elaborado_id`) REFERENCES `elaborado` (`elaborado_id`),
  ADD CONSTRAINT `stock_ibfk_5` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingrediente` (`ingrediente_id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`restaurante_id`) REFERENCES `restaurante` (`restaurante_id`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`tipo_usuario_id`) REFERENCES `tipo_usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
