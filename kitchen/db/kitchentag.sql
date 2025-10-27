-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-03-2025 a las 22:54:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kitchentag`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alergenos`
--

CREATE TABLE `alergenos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `nombre_ingles` varchar(255) DEFAULT NULL,
  `nombre_frances` varchar(255) DEFAULT NULL,
  `nombre_aleman` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alergenos`
--

INSERT INTO `alergenos` (`id`, `nombre`, `nombre_ingles`, `nombre_frances`, `nombre_aleman`) VALUES
(1, 'Ninguno', 'None', 'Aucun', 'Keiner'),
(2, 'Gluten', 'Gluten', 'Gluten', 'Gluten'),
(3, 'Crustáceos', 'Crustaceans', 'Crustacés', 'Krebstiere'),
(4, 'Huevos', 'Eggs', 'Œufs', 'Eier'),
(5, 'Pescado', 'Fish', 'Poisson', 'Fisch'),
(6, 'Cacahuetes', 'Peanuts', 'Cacahuètes', 'Erdnüsse'),
(7, 'Soja', 'Soy', 'Soja', 'Soja'),
(8, 'Leche', 'Milk', 'Lait', 'Milch'),
(9, 'Frutos secos', 'Nuts', 'Fruits secs', 'Nüsse'),
(10, 'Apio', 'Celery', 'Céleri', 'Sellerie'),
(11, 'Mostaza', 'Mustard', 'Moutarde', 'Senf'),
(12, 'Granos de sésamo', 'Sesame seeds', 'Graines de sésame', 'Sesamsamen'),
(13, 'Dióxido de azufre y sulfitos', 'Sulfur dioxide and sulfites', 'Anhydride sulfureux et sulfites', 'Schwefeldioxid und Sulfite'),
(14, 'Altramuces', 'Lupins', 'Lupins', 'Lupinen'),
(15, 'Moluscos', 'Mollusks', 'Mollusques', 'Weichtiere');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenelaboraciones`
--

CREATE TABLE `almacenelaboraciones` (
  `ID` int(11) NOT NULL,
  `tipoProd` varchar(20) NOT NULL,
  `fName` varchar(125) NOT NULL,
  `packaging` varchar(125) NOT NULL,
  `productamount` int(11) NOT NULL,
  `fechaElab` date NOT NULL,
  `fechaCad` date NOT NULL,
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(25) NOT NULL,
  `costPrice` float NOT NULL,
  `saleCurrency` varchar(25) NOT NULL,
  `salePrice` float NOT NULL,
  `codeContents` text NOT NULL,
  `receta_id` int(11) DEFAULT NULL,
  `rations_package` int(100) DEFAULT NULL COMMENT 'Numero de unidades por paquete',
  `fileName` varchar(150) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almaceningredientes`
--

CREATE TABLE `almaceningredientes` (
  `ID` int(11) NOT NULL,
  `tipoProd` varchar(20) NOT NULL,
  `fName` varchar(125) NOT NULL,
  `packaging` varchar(125) NOT NULL,
  `productamount` int(11) NOT NULL,
  `fechaElab` date NOT NULL,
  `fechaCad` date NOT NULL,
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(25) NOT NULL,
  `costPrice` float NOT NULL,
  `saleCurrency` varchar(25) NOT NULL,
  `salePrice` float NOT NULL,
  `codeContents` text NOT NULL,
  `ingrediente_id` int(11) NOT NULL,
  `cantidad_paquete` float DEFAULT NULL COMMENT 'Cantidad por cada paquete',
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `almaceningredientes`
--

INSERT INTO `almaceningredientes` (`ID`, `tipoProd`, `fName`, `packaging`, `productamount`, `fechaElab`, `fechaCad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`, `ingrediente_id`, `cantidad_paquete`, `estado`) VALUES
(422, 'Ingredient', 'Pan', 'Bag', 1, '2025-03-27', '2025-04-01', 'Final product area', 'Euro', 30, 'Euro', 45, 'http://192.168.1.147:8080/kitchen/menus/elaborations/datosQrIng.php?productName=Pan&img=.%2F..%2F..%2Fimg%2Fingredients%2Fpan.png&productamount=1&pesoPaquete=+30&fechaElab=2025-03-27&warehouse=Final+product+area&costCurrency=Euro&saleCurrency=Euro&salePrice=45.00&costPrice=30.00&id=422', 353, 30, 'Received');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autoconsumo`
--

CREATE TABLE `autoconsumo` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cantidad` float NOT NULL,
  `fecha_consumo` date DEFAULT NULL,
  `coste` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elaboraciones`
--

CREATE TABLE `elaboraciones` (
  `ID` int(11) NOT NULL,
  `fName` varchar(125) NOT NULL,
  `receta` int(11) NOT NULL COMMENT 'Receta del elaborado',
  `merma` float DEFAULT NULL,
  `packaging` varchar(125) NOT NULL,
  `productamount` int(11) NOT NULL,
  `fechaElab` date NOT NULL,
  `caducidad` int(11) NOT NULL COMMENT 'Cuantos días tarda en caducar',
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(25) NOT NULL,
  `costPrice` int(11) NOT NULL,
  `saleCurrency` varchar(25) NOT NULL,
  `salePrice` int(11) NOT NULL,
  `codeContents` varchar(125) NOT NULL,
  `image` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elaboraciones`
--

INSERT INTO `elaboraciones` (`ID`, `fName`, `receta`, `merma`, `packaging`, `productamount`, `fechaElab`, `caducidad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`, `image`) VALUES
(9, 'Espaguetis Boloñesa', 1, 0, 'Bolsa', 2, '2024-05-10', 10, 'Freezer', 'Euro', 0, 'Euro', 3, '', NULL),
(17, 'espaguetti', 1, 0, 'bag', 4, '2024-05-16', 2, 'Freezer', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=&productamount=4&fechaElab=2024-05-16T21%3A25&warehouse=bag&costCurrency=Euro&saleCurrency=E', NULL),
(18, 'espaguetti', 1, 0, 'bag', 0, '0000-00-00', 0, 'Freezer', 'Euro', 0, 'Euro', 0, 'https://cookcode.com?productName=&productamount=0&fechaElab=&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=0&co', NULL),
(20, 'Milanesa', 11, 0, 'Bag', 12, '2024-05-20', 12, 'Warehouse', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=&productamount=12&fechaElab=2024-05-20T11%3A40&warehouse=Warehouse&costCurrency=Euro&saleCur', NULL),
(21, 'Milanesa', 11, 0, 'Bag', 3, '2024-05-21', 3, 'Freezer', 'Euro', 10, 'Euro', 13, 'https://cookcode.com?productName=&productamount=3&fechaElab=2024-05-21T10%3A05&warehouse=Freezer&costCurrency=Euro&saleCurren', NULL),
(22, 'Milanesa', 11, 0, 'Pack', 10, '2024-05-21', 3, 'Final product area', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=&productamount=10&fechaElab=2024-05-21T10%3A26&warehouse=Final+product+area&costCurrency=Eur', NULL),
(23, 'Milanesa', 11, 0, 'Pack', 12, '2024-05-21', 3, 'Final product area', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=&productamount=12&fechaElab=2024-05-21T10%3A27&warehouse=Final+product+area&costCurrency=Eur', NULL),
(24, 'Milanesa', 11, 0, 'Bag', 2, '2024-05-21', 30, 'Freezer', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=&productamount=2&fechaElab=2024-05-21T10%3A58&warehouse=Freezer&costCurrency=Euro&saleCurren', NULL),
(25, 'espaguetti', 1, 0, 'Pack', 2, '2024-05-21', 5, 'Warehouse', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=&productamount=2&fechaElab=2024-05-21T10%3A59&warehouse=Warehouse&costCurrency=Euro&saleCurr', NULL),
(26, 'Milanesa', 11, 0, 'Bag', 10, '2024-05-21', 2, 'Freezer', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=&productamount=10&fechaElab=2024-05-21T12%3A44&warehouse=Freezer&costCurrency=Euro&saleCurre', NULL),
(42, 'espaguetti', 1, 0, '', 5, '0000-00-00', 0, '', '', 0, '', 0, '', NULL),
(43, 'espaguetti', 1, 0, 'Pack', 12, '2024-05-22', 22, 'Warehouse', 'Yen', 12, 'Yen', 22, 'https://cookcode.com?productName=espaguetti&productamount=12&fechaElab=2024-05-22&warehouse=Warehouse&costCurrency=Yen&saleCu', NULL),
(44, 'Fideua', 12, 0, 'Bag', 2, '2024-06-03', 2, 'Freezer', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=Fideua&productamount=2&fechaElab=2024-06-03&warehouse=Freezer&costCurrency=Euro&saleCurrency', NULL),
(45, 'Brochetas de verdura', 13, 0, 'Pack', 10, '2024-06-04', 6, 'Freezer', 'Dirham', 8, 'Dirham', 13, 'https://cookcode.com?productName=Brochetas+de+verdura&productamount=10&fechaElab=2024-06-04&warehouse=Freezer&costCurrency=Di', NULL),
(46, 'Gambas al ajillo', 14, 0, 'Box', 9, '2024-06-04', 15, 'Final product area', 'Yen', 5, 'Yen', 12, 'https://cookcode.com?productName=Gambas+al+ajillo&productamount=9&fechaElab=2024-06-04&warehouse=Final+product+area&costCurre', NULL),
(47, 'Brochetas de verdura', 13, 0, 'Bag', 5, '2024-06-04', 6, 'Freezer', 'Euro', 10, 'Euro', 20, 'https://cookcode.com?productName=Brochetas+de+verdura&productamount=5&fechaElab=2024-06-04&warehouse=Freezer&costCurrency=Eur', NULL),
(48, 'Fideua', 12, 0, 'Bag', 5, '2024-06-04', 7, 'Freezer', 'Euro', 10, 'Euro', 22, 'https://cookcode.com?productName=Fideua&productamount=5&fechaElab=2024-06-04&warehouse=Freezer&costCurrency=Euro&saleCurrency', NULL),
(49, 'sss', 19, 0, 'Bag', 3, '2024-06-05', 3, 'Freezer', 'Euro', 0, 'Euro', 1, 'https://cookcode.com?productName=sss&productamount=3&fechaElab=2024-06-05&warehouse=Freezer&costCurrency=Euro&saleCurrency=Eu', NULL),
(50, 'sss', 19, 0, 'Bag', 1, '2024-06-05', 4, 'Freezer', 'Euro', 1, 'Euro', 2, 'https://cookcode.com?productName=sss&productamount=1&fechaElab=2024-06-05&warehouse=Freezer&costCurrency=Euro&saleCurrency=Eu', NULL),
(51, 'sss', 19, 0, 'Bag', 1, '2024-06-05', 3, 'Freezer', 'Euro', 1, 'Euro', 2, 'https://cookcode.com?productName=sss&productamount=1&fechaElab=2024-06-05&warehouse=Freezer&costCurrency=Euro&saleCurrency=Eu', NULL),
(52, 'Paella', 20, 0, 'Bag', 3, '2024-06-06', 6, 'Freezer', 'Euro', 0, 'Euro', 1, 'https://cookcode.com?productName=Paella&productamount=3&fechaElab=2024-06-06&warehouse=Freezer&costCurrency=Euro&saleCurrency', NULL),
(53, 'Paella', 20, 0, 'Bag', 1, '2024-06-06', 2, 'Freezer', 'Euro', 0, 'Euro', 2, 'https://cookcode.com?productName=Paella&productamount=1&fechaElab=2024-06-06&warehouse=Freezer&costCurrency=Euro&saleCurrency', NULL),
(54, 'Paella', 20, 0, 'Pack', 2, '2024-06-06', 12, 'Warehouse', 'Euro', 1, 'Euro', 20, 'https://cookcode.com?productName=Paella&productamount=2&fechaElab=2024-06-06&warehouse=Warehouse&costCurrency=Euro&saleCurren', NULL),
(55, 'Paella', 20, 0, 'Pack', 50, '2024-06-06', 12, 'Warehouse', 'Euro', 55, 'Euro', 100, 'https://cookcode.com?productName=Paella&productamount=50&fechaElab=2024-06-06&warehouse=Warehouse&costCurrency=Euro&saleCurre', NULL),
(56, 'xxx', 25, 0, 'Pack', 5, '2024-06-06', 5, 'Final product area', 'Euro', 1826, 'Euro', 100, 'https://cookcode.com?productName=xxx&productamount=5&fechaElab=2024-06-06&warehouse=Final+product+area&costCurrency=Euro&sale', NULL),
(57, 'bnmbmghmj', 28, 0, 'Bag', 2, '2024-06-07', 3, 'Freezer', 'Euro', 16, 'Euro', 22, 'https://cookcode.com?productName=bnmbmghmj&productamount=2&fechaElab=2024-06-07&warehouse=Freezer&costCurrency=Euro&saleCurre', NULL),
(58, 'bnmbmghmj', 28, 0, 'Bag', 4, '2024-06-07', 2, 'Freezer', 'Euro', 33, 'Euro', 1000, 'https://cookcode.com?productName=bnmbmghmj&productamount=4&fechaElab=2024-06-07&warehouse=Freezer&costCurrency=Euro&saleCurre', NULL),
(59, 'bnmbmghmj', 28, 0, 'Bag', 4, '2024-06-07', 4, 'Freezer', 'Euro', 44, 'Euro', 10000, 'https://cookcode.com?productName=bnmbmghmj&productamount=4&fechaElab=2024-06-07&warehouse=Freezer&costCurrency=Euro&saleCurre', NULL),
(60, 'ikdrgs', 27, 0, 'Pack', 10, '2024-06-07', 5, 'Warehouse', 'Euro', 22, 'Euro', 50, 'https://cookcode.com?productName=ikdrgs&productamount=10&fechaElab=2024-06-07&warehouse=Warehouse&costCurrency=Euro&saleCurre', NULL),
(61, 'bnmbmghmj', 28, 0, 'Bag', 10, '2024-06-07', 11, 'Freezer', 'Euro', 304, 'Euro', 500, 'https://cookcode.com?productName=bnmbmghmj&productamount=10&fechaElab=2024-06-07&warehouse=Freezer&costCurrency=Euro&saleCurr', NULL),
(62, 'ikdrgs', 27, 0, 'Bag', 10, '2024-06-07', 4, 'Freezer', 'Euro', 121, 'Euro', 400, 'https://cookcode.com?productName=ikdrgs&productamount=10&fechaElab=2024-06-07&warehouse=Freezer&costCurrency=Euro&saleCurrenc', NULL),
(63, 'Fabada', 37, 0, 'Pack', 1, '2024-06-11', 9, 'Final product area', 'Euro', 28, 'Euro', 35, 'https://cookcode.com?productName=Fabada&productamount=1&fechaElab=2024-06-11&warehouse=Final+product+area&costCurrency=Euro&s', NULL),
(64, 'Bolognesa', 33, 0, 'Bag', 1, '2024-06-11', 3, 'Freezer', 'Euro', 55, 'Euro', 70, 'https://cookcode.com?productName=Bolognesa&productamount=1&fechaElab=2024-06-11&warehouse=Freezer&costCurrency=Euro&saleCurre', NULL),
(65, 'Paella', 38, 0, 'Bag', 1, '2024-06-11', 5, 'Freezer', 'Euro', 73, 'Euro', 90, 'https://cookcode.com?productName=Paella&productamount=1&fechaElab=2024-06-11&warehouse=Freezer&costCurrency=Euro&saleCurrency', NULL),
(66, 'migas', 43, 0, 'Bag', 1, '2024-08-12', 3, 'Freezer', 'Euro', 3, 'Euro', 5, 'https://cookcode.com?productName=migas&productamount=1&fechaElab=2024-08-12&warehouse=Freezer&costCurrency=Euro&saleCurrency=', NULL),
(67, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(68, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(69, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(70, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(71, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(72, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(73, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(74, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(75, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(76, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(77, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL),
(78, 'habas ensalada', 44, 0, 'Bag', 1, '2024-08-13', 6, 'Freezer', 'Euro', 24, 'Euro', 36, 'https://cookcode.com?productName=habas+ensalada&productamount=1&fechaElab=2024-08-13&warehouse=Freezer&costCurrency=Euro&sale', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(3, 'Chef'),
(4, 'Restaurante'),
(5, 'Propietario'),
(6, 'Cliente'),
(64, 'adminRoot');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_permisos`
--

CREATE TABLE `grupos_permisos` (
  `id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `permiso_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos_permisos`
--

INSERT INTO `grupos_permisos` (`id`, `grupo_id`, `permiso_id`) VALUES
(6, 9, 1),
(7, 9, 1),
(57, 6, 5),
(58, 1, 5),
(59, 1, 6),
(60, 1, 7),
(61, 1, 8),
(62, 1, 9),
(63, 1, 10),
(66, 5, 5),
(67, 5, 6),
(68, 5, 7),
(69, 5, 8),
(70, 5, 9),
(71, 5, 10),
(72, 3, 6),
(73, 3, 8),
(74, 3, 10),
(75, 4, 5),
(76, 4, 8),
(90, 64, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientesalergenos`
--

CREATE TABLE `ingredientesalergenos` (
  `id` int(11) NOT NULL,
  `id_ingrediente` int(11) DEFAULT NULL,
  `id_alergeno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientesalergenos`
--

INSERT INTO `ingredientesalergenos` (`id`, `id_ingrediente`, `id_alergeno`) VALUES
(307, 353, 2),
(308, 354, 1),
(309, 355, 1),
(310, 356, 4),
(311, 357, 1),
(312, 358, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredients`
--

CREATE TABLE `ingredients` (
  `ID` int(11) NOT NULL,
  `fName` varchar(125) NOT NULL,
  `merma` float NOT NULL,
  `packaging` varchar(125) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `unidad` varchar(20) NOT NULL,
  `fechaElab` date NOT NULL,
  `fechaCad` date NOT NULL,
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(25) NOT NULL,
  `costPrice` float NOT NULL,
  `saleCurrency` varchar(25) NOT NULL,
  `salePrice` int(11) NOT NULL,
  `codeContents` varchar(125) NOT NULL,
  `image` varchar(250) DEFAULT NULL,
  `expira_dias` int(11) DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `atr_name_tienda` varchar(100) DEFAULT NULL,
  `atr_valores_tienda` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredients`
--

INSERT INTO `ingredients` (`ID`, `fName`, `merma`, `packaging`, `cantidad`, `unidad`, `fechaElab`, `fechaCad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`, `image`, `expira_dias`, `peso`, `atr_name_tienda`, `atr_valores_tienda`) VALUES
(353, 'Pan', 0.0001, 'Bag', 0, 'Und', '0000-00-00', '0000-00-00', 'Final product area', '', 1, 'Euro', 0, '', './../img/ingredients/pan.png', 5, 0, '', '10, 20, 30'),
(354, 'Tomate', 0.0001, 'Bag', 0, 'Kg', '0000-00-00', '0000-00-00', 'Freezer', '', 3, 'Euro', 0, '', './../img/ingredients/tomate.jpg', 4, 0, '', '15, 20, 30'),
(355, 'Pollo a la plancha', 0.0001, 'Pack', 0, 'Und', '0000-00-00', '0000-00-00', 'Final product area', '', 2, 'Euro', 0, '', './../img/ingredients/pollo.jpg', 2, 0, '', '5'),
(356, 'Huevo', 0.0005, 'Pack', 0, 'Kg', '0000-00-00', '0000-00-00', 'Dry', '', 3, 'Euro', 0, '', './../img/ingredients/huevo.jpg', 30, 0, '', '6, 12, 24, 48'),
(357, 'Aceite', 0.0001, 'Bottle', 0, 'L', '0000-00-00', '0000-00-00', 'Warehouse', '', 8, 'Euro', 0, '', './../img/ingredients/aceite.jpeg', 100, 0, '', '1, 5, 10'),
(358, 'lechuga', 0.0001, 'Bag', 0, 'Kg', '0000-00-00', '0000-00-00', 'Freezer', '', 2, 'Euro', 0, '', './../img/ingredients/lechuga.png', 4, 0, '', '5, 10, 15, 20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `node`
--

CREATE TABLE `node` (
  `id` int(11) NOT NULL,
  `method` varchar(50) DEFAULT NULL,
  `noun` varchar(50) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `standard_deviation` double DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `node`
--

INSERT INTO `node` (`id`, `method`, `noun`, `unit`, `quantity`, `standard_deviation`, `frequency`) VALUES
(1, 'start', 'recipe', NULL, NULL, NULL, NULL),
(2, 'finish', 'recipe', NULL, NULL, NULL, NULL),
(28, 'Peel', 'potatoes', 'none', 0, 0, 1),
(29, 'Cut', 'them', 'none', 0, 0, 1),
(30, 'Boil', 'water', 'none', 0, 0, 1),
(31, 'Add', 'salt', 'none', 0, 0, 2),
(32, 'Cook', 'potatoes', 'none', 0, 0, 1),
(33, 'Drain', 'potatoes', 'none', 0, 0, 1),
(34, 'Heat', 'saucepan', 'none', 0, 0, 1),
(35, 'Shake', 'saucepan', 'none', 0, 0, 1),
(36, 'Mash', 'potatoes', 'none', 0, 0, 2),
(37, 'Add', 'butter', 'none', 0, 0, 1),
(38, 'Boil', 'potatoes', 'none', 0, 0, 1),
(39, 'Heat', 'cream', 'none', 0, 0, 1),
(40, 'Heat', 'butter', 'none', 0, 0, 1),
(41, 'Beat', 'potatoes', 'none', 0, 0, 1),
(42, 'Press', 'potatoes', 'none', 0, 0, 1),
(43, 'Boil', 'water', 'cup', 2.6666666666667, 0.2087377028029, 7),
(44, 'Add', 'sugar', 'teaspoon', 1.6, 0.17504216426539, 11),
(45, 'Add', 'tea', 'teaspoon', 1.6666666666667, 0.23094010767584, 6),
(46, 'Add', 'coffee', 'teaspoon', 1.5, 0.27638539919628, 4),
(47, 'Pour', 'milk', 'cup', 1, 0, 4),
(48, 'Add', 'water', 'cup', 1, 0, 4),
(49, 'Boil', 'milk', 'none', 0, 0, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_ecommerce`
--

CREATE TABLE `pedidos_ecommerce` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `id_restaurante` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fecha_pedido` datetime NOT NULL,
  `nombre_cliente` varchar(255) NOT NULL,
  `email_cliente` varchar(255) NOT NULL,
  `telefono_cliente` varchar(50) DEFAULT NULL,
  `direccion_cliente` varchar(255) DEFAULT NULL,
  `estado_envio` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_producto`
--

CREATE TABLE `pedido_producto` (
  `id` int(11) NOT NULL,
  `producto` varchar(25) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `proveedor` varchar(25) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `telefono` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_producto`
--

INSERT INTO `pedido_producto` (`id`, `producto`, `cantidad`, `proveedor`, `correo`, `telefono`) VALUES
(1, 'Pan', 15, 'Pepito', 'pepito@gmail.com', 650836030),
(2, 'Tomate', 50, 'Adrián', 'pokepixel@gmail.com', 611186984);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`) VALUES
(5, 'shopWeb'),
(6, 'systemTag'),
(7, 'dashboardProd'),
(8, 'restaurant'),
(9, 'dashboardGen'),
(10, 'shopBackoffice'),
(11, 'root');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `imagen` varchar(250) NOT NULL,
  `instrucciones` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos_ingrediente`
--

CREATE TABLE `platos_ingrediente` (
  `id` int(11) NOT NULL,
  `id_plato` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `unidad` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos_preelaborados`
--

CREATE TABLE `platos_preelaborados` (
  `id` int(11) NOT NULL,
  `id_plato` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precios_producto`
--

CREATE TABLE `precios_producto` (
  `id` int(11) NOT NULL,
  `producto` varchar(255) DEFAULT NULL,
  `unidad` varchar(50) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `merma` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `precios_producto`
--

INSERT INTO `precios_producto` (`id`, `producto`, `unidad`, `precio`, `merma`) VALUES
(1, 'CALABACÍN', 'kg', 2, 0.1),
(2, 'PUERRO', 'kg', 2.9, 0.2),
(3, 'PATATA', 'kg', 1.25, 0.05),
(4, 'TOMATE', 'kg', 21.65, 0.15),
(5, 'CEBOLLA', 'kg', 12.65, 0.1),
(6, 'AJO', 'kg', 0.85, 0.05),
(7, 'NABO', 'kg', 0.85, 0.1),
(8, 'APIO', 'kg', 1.35, 0.05),
(9, 'LIMONES', 'kg', 0.85, 0.05),
(10, 'ZANAHORIA', 'kg', 0.65, 0.1),
(11, 'PANCETA', 'kg', 4.2, 0.2),
(12, 'BACON', 'kg', 9, 0.15),
(13, 'MORCILLA', 'kg', 4.4, 0.1),
(14, 'MANITAS', 'kg', 3.8, 0),
(15, 'JAMÓN SERRANO', 'kg', 12, 0.15),
(16, 'COSTILLAS DE CERDO', 'kg', 5.5, 0.2),
(17, 'PECHUGA DE POLLO', 'kg', 6, 0.15),
(18, 'BACALAO', 'kg', 8.5, 0.2),
(19, 'CALAMAR CONGELADO', 'kg', 6.25, 0.1),
(20, 'EMPERADOR CONGELADO', 'kg', 9.5, 0.15),
(21, 'MORRALLA', 'kg', 9.9, 0.2),
(22, 'SEPIA CONGELADA', 'kg', 7.1, 0.1),
(23, 'MERLUZA', 'kg', 12, 0.15),
(24, 'GAMBON', 'kg', 9.9, 0.1),
(25, 'SALMÓN', 'kg', 8, 0.15),
(26, 'VINO BLANCO', 'l', 1.5, NULL),
(27, 'VINO TINTO', 'l', 2.5, NULL),
(28, 'PIMENTON DULCE', 'kg', 15, NULL),
(29, 'COLORANTE', 'kg', 3.6, NULL),
(30, 'PIMIENTA BLANCA', 'kg', 8.6, NULL),
(31, 'SAL', 'kg', 0.3, NULL),
(32, 'AZÚCAR', 'kg', 0.95, NULL),
(33, 'HARINA', 'kg', 0.55, NULL),
(34, 'HUEVO', 'ud.', 0.12, NULL),
(35, 'LECHE', 'l', 0.85, NULL),
(36, 'ALMENDRAS', 'kg', 12, NULL),
(37, 'QUESO MANCHEGO', 'kg', 11.35, NULL),
(38, 'ACEITE', 'l', 21, NULL),
(39, 'GARBANZOS', 'kg', 10.8, NULL),
(40, 'ACEITE DE OLIVA', 'l', 3.5, NULL),
(41, 'TOMATE TRITURADO', 'l', 0.89, NULL),
(42, 'AGUA', 'l', 0.2, NULL),
(43, 'FUMET ROJO', 'l', 2.1, NULL),
(44, 'ARROZ', 'kg', 0.85, NULL),
(45, 'VINAGRE', 'l', 0.7, NULL),
(46, 'ARROZ', 'kg', 10.85, NULL),
(47, 'AGUA', 'l', 0.2, NULL),
(48, 'AGUA', 'l', 0.2, NULL),
(49, 'ARROZ', 'kg', 10.85, NULL),
(50, 'sedfsd', 'kg', 45, NULL),
(51, 'sedfsd', 'kg', 45, NULL),
(52, 'ssss', 'l', 12, NULL),
(53, 'ssss', 'l', 12, NULL),
(54, 'ssss', 'l', 12, NULL),
(55, 'ssss', 'l', 12, NULL),
(56, 'ssss', 'l', 12, NULL),
(57, 'ssss', 'l', 12, NULL),
(58, 'ssss', 'l', 12, NULL),
(59, 'zz', 'g', 45, NULL),
(60, 'zz', 'g', 39, 0),
(61, 'bb', 'l', 12, 0),
(62, 'bb', 'l', 12, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_pedido`
--

CREATE TABLE `productos_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `sku_producto` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `informacion_lote` text DEFAULT NULL,
  `sku_lote` varchar(50) NOT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `cantidad_lote` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id` int(11) NOT NULL,
  `tipo` varchar(25) NOT NULL COMMENT 'Tipo de receta (Preelaborado / Elaborado)',
  `receta` varchar(50) NOT NULL COMMENT 'Nombre de la receta',
  `instrucciones` text NOT NULL,
  `produce` int(11) DEFAULT NULL COMMENT 'Que elaborado produce',
  `cantidad_producida` int(11) NOT NULL,
  `tipo_cantidad` int(11) NOT NULL,
  `peso` float NOT NULL,
  `num_raciones` int(11) DEFAULT NULL COMMENT 'Número de raciones indicadas para la receta',
  `imagen` varchar(250) DEFAULT NULL,
  `expira_dias` int(11) DEFAULT NULL COMMENT 'Días hasta su caducidad',
  `localizacion` varchar(50) DEFAULT NULL,
  `empaquetado` varchar(50) DEFAULT NULL,
  `descripcion_corta` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `tipo`, `receta`, `instrucciones`, `produce`, `cantidad_producida`, `tipo_cantidad`, `peso`, `num_raciones`, `imagen`, `expira_dias`, `localizacion`, `empaquetado`, `descripcion_corta`) VALUES
(207, 'Pre-Elaborado', 'Mayonesa', 'Licuar el huevo con el aceite hasta que tenga esa textura cremosa de la mayonesa', NULL, 0, 0, 0.1, 5, './../img/recipes/mayonesa.jpeg', 15, 'Final product area', 'Bottle', 'Mayonesa de huevo y aceite'),
(208, 'Elaborado', 'Sandwich de Pollo', '1. Cortar el pan por la mitad\n2. Insertar la pechuga de pollo previamente preparado\n3. Añadir la mayonesa\n4. Añadir el tomate\n5. Añadir huevo frito opcionalmente', NULL, 0, 0, 0.54, 1, './../img/recipes/sandwich_pollo.jpg', 2, 'Final product area', 'Bag', 'Sandwich de Pollo a la plancha con tomate y mayonesa'),
(209, 'Pre-Elaborado', 'Ketchup', 'ketchup', NULL, 0, 0, 0.5, 1, './../img/sin-imagen.jpg', 5, 'Freezer', 'Bottle', 'ketchup');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_elaborado`
--

CREATE TABLE `receta_elaborado` (
  `id` int(11) NOT NULL,
  `receta` int(11) NOT NULL,
  `elaborado` int(11) NOT NULL,
  `cantidad` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta_elaborado`
--

INSERT INTO `receta_elaborado` (`id`, `receta`, `elaborado`, `cantidad`) VALUES
(26, 208, 207, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_ingrediente`
--

CREATE TABLE `receta_ingrediente` (
  `id` int(11) NOT NULL,
  `receta` int(11) DEFAULT NULL COMMENT 'Receta que necesita este ingrediente/elaborado',
  `ingrediente` int(11) DEFAULT NULL COMMENT 'Ingrediente necesario para la receta',
  `elaborado` int(11) DEFAULT NULL COMMENT 'Elaborado requerido en la receta (se especifica o bien 1 ingrediente o 1 elaborado)',
  `cantidad` float DEFAULT NULL,
  `tipo_cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta_ingrediente`
--

INSERT INTO `receta_ingrediente` (`id`, `receta`, `ingrediente`, `elaborado`, `cantidad`, `tipo_cantidad`) VALUES
(314, 207, 356, NULL, 0.1, 0),
(315, 207, 357, NULL, 0.5, 0),
(316, 208, 353, NULL, 1, 0),
(317, 208, 354, NULL, 0.02, 0),
(318, 208, 355, NULL, 1, 0),
(319, 208, 356, NULL, 0.02, 0),
(320, 209, 354, NULL, 0.5, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion_password`
--

CREATE TABLE `recuperacion_password` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recuperacion_password`
--

INSERT INTO `recuperacion_password` (`id`, `email`, `token`) VALUES
(1, 'toni8482@outlook.com', 'cac8495b288b45f3cd2b47cf9e4d67ac'),
(2, 'toni8482@outlook.com', '12eed1261de4233f3bbfb41122205eaf'),
(3, 'toni8482@outlook.com', '2f0a3ebd8767d109dbc85ce08149ed34'),
(4, 'toni8482@outlook.com', '967ce4415ed065766e126228a91efb17'),
(5, 'toni8482@outlook.com', '2f500be3d7a6b81b58ddfbaa5113e163'),
(6, 'toni8482@outlook.com', '380b13c2d6f8f5f01f5d3f9ff73956da'),
(7, 'toni8482@outlook.com', 'c99787f19cda4a19605ae84d5a2ff55e'),
(8, 'toni8482@outlook.com', '3f34421f871114379e88f6f8d4183116'),
(9, 'toni8482@outlook.com', '96731f6a0a78eae6fe3dd4ebe25fa48d'),
(10, 'cooksystem959@gmail.com', '70163c99e1f5ba89fc24c2ad8da91e27'),
(11, 'cooksystem959@gmail.com', '17254e207f29e2f356e2c4c663492c59'),
(12, 'cooksystem959@gmail.com', '515e6bc2b136c2ffe3a52235caa76173'),
(13, 'cooksystem959@gmail.com', '49bcf71544dcc7348d42c75b16f6599b'),
(14, 'cooksystem959@gmail.com', '7a6fe1b52caefadbbf1d3537f3ef2f0c'),
(15, 'cooksystem959@gmail.com', '140791b6f9c59694e5618ab5734b40ed'),
(16, 'cooksystem959@gmail.com', '8d313f61af6c0df00b6ad3e04dafe184'),
(17, 'cooksystem959@gmail.com', '9e30ed4be1f66c9e331cc3accab3ccc8'),
(18, 'cooksystem959@gmail.com', '400ca9560f281bd4b6e9293d894e959c'),
(19, 'cooksystem959@gmail.com', 'ebaefed06c931de67527a5901ad9e004'),
(20, 'cooksystem959@gmail.com', '41cd9f9c5513d3a87232c72c6f836bd2'),
(21, 'cooksystem959@gmail.com', 'd3f8d751c39b7d7c6560ec5228a27a6c'),
(22, 'cooksystem959@gmail.com', 'c56e3e8009b181af0b77dc29b93a3f48'),
(23, 'toni8482@outlook.com', 'e2eb8b578ece9d76070ab48583ff9cf8'),
(24, 'cooksystem959@gmail.com', 'a14258824e6407086979df4422e03589'),
(25, 'cooksystem959@gmail.com', 'd22f6790490e7e10c51b8dd093e85c47'),
(26, 'toni8482@outlook.com', 'fddd8cdf2c36bf5bc82205fd2fba7c8c'),
(27, 'cooksystem959@gmail.com', '598a9bb532f42f6edb2595cffd499214'),
(28, 'cooksystem959@gmail.com', '1831277ea8ac9a1689179ce0904e218e'),
(29, 'toni8482@outlook.com', '4a6ae634a9296d8804289703c1365ca1'),
(30, 'toni8482@outlook.com', '22a1ec009f00d9c8f010ae8021a47ccb'),
(31, 'toni8482@outlook.com', 'a0b09bbae01ae2c2c7645e2db383dea8'),
(32, 'toni8482@outlook.com', '428a282064554806ea748e0a32d8879b'),
(33, 'toni8482@outlook.com', '36fa6e6c88a25a8a3c755ca1ed8b4414'),
(34, 'toni8482@outlook.com', '5666bf35f20bac43f31be6c18b538780'),
(35, 'toni8482@outlook.com', '5ab5f04ef32a3c911e55105af83b4e78'),
(36, 'toni8482@outlook.com', 'cad94bf4fdcda08598865098c047c6ec'),
(37, 'toni8482@outlook.com', '4728eef39c86e4f0773cd137e29779eb'),
(38, 'toni8482@outlook.com', 'aeb4acc2351c5366b5369798fa0212a9'),
(39, 'toni8482@outlook.com', 'b31264d69cfe3b3f1409a95e7950eb7b'),
(40, 'toni8482@outlook.com', 'dc93734955a35b001389b25c09a0a63b'),
(41, 'toni8482@outlook.com', 'f548a08033e399051e260b0b065cd8f6'),
(42, 'toni8482@outlook.com', '1d9a0999d6a115f9ba79417821d59f97'),
(43, 'toni8482@outlook.com', '2b7b452dbe156767212025ed4886a38c'),
(44, 'toni8482@outlook.com', 'c78da6950201080d822dbe0da159dc4b'),
(45, 'gonzalezbellver@gmail.com', '40fa3a907b09531f4f4e3e14bf6aecda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_elab_kitchen`
--

CREATE TABLE `stock_elab_kitchen` (
  `id` int(11) NOT NULL,
  `receta_id` int(11) NOT NULL,
  `stock` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_elab_kitchen`
--

INSERT INTO `stock_elab_kitchen` (`id`, `receta_id`, `stock`) VALUES
(25, 207, 0.00),
(26, 208, 0.00),
(27, 209, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_ing_kitchen`
--

CREATE TABLE `stock_ing_kitchen` (
  `id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `stock` float NOT NULL,
  `stock_ecommerce` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_ing_kitchen`
--

INSERT INTO `stock_ing_kitchen` (`id`, `ingredient_id`, `stock`, `stock_ecommerce`) VALUES
(985, 353, 0, NULL),
(986, 354, 0, NULL),
(987, 355, 0, NULL),
(988, 356, 0, NULL),
(989, 357, 0, NULL),
(990, 358, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_lotes_elab`
--

CREATE TABLE `stock_lotes_elab` (
  `id` int(11) NOT NULL,
  `receta_id` int(11) NOT NULL,
  `lote` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `unidades` varchar(50) NOT NULL,
  `elaboracion` date NOT NULL,
  `caducidad` date NOT NULL,
  `coste` decimal(10,2) NOT NULL,
  `tipo_unidad` varchar(50) NOT NULL,
  `cantidad_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_lotes_ing`
--

CREATE TABLE `stock_lotes_ing` (
  `id` int(11) NOT NULL,
  `ingrediente_id` int(11) NOT NULL,
  `lote` varchar(100) DEFAULT NULL,
  `cantidad` float NOT NULL,
  `unidades` int(11) NOT NULL,
  `elaboracion` date NOT NULL,
  `caducidad` date NOT NULL,
  `coste` float DEFAULT NULL,
  `tipo_unidad` varchar(100) NOT NULL,
  `cantidad_total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tagscreados`
--

CREATE TABLE `tagscreados` (
  `IDTag` int(11) NOT NULL,
  `tempDir` varchar(125) NOT NULL,
  `email` varchar(125) NOT NULL,
  `filename` varchar(125) NOT NULL,
  `fName` varchar(125) NOT NULL COMMENT 'Product name',
  `packaging` varchar(125) NOT NULL,
  `productamount` int(11) NOT NULL,
  `fechaElab` date NOT NULL,
  `fechaCad` date NOT NULL,
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(125) NOT NULL,
  `costPrice` int(11) NOT NULL,
  `saleCurrency` varchar(125) NOT NULL,
  `salePrice` int(11) NOT NULL,
  `codeContents` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tagscreados`
--

INSERT INTO `tagscreados` (`IDTag`, `tempDir`, `email`, `filename`, `fName`, `packaging`, `productamount`, `fechaElab`, `fechaCad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`) VALUES
(1, 'vacio', 'ajaja@ejam.com', 'ggdds', 'addsd', 'dda', 3, '2023-10-10', '2023-10-25', 'freezer', 'euro', 22, 'euro', 34, 'fdsdfsfsdfds'),
(2, 'temp/', 'josu@mendipe.com', 'josu', 'Curry', 'bag', 1000, '2023-10-17', '2023-11-30', 'bag', 'Euro', 1, 'Euro', 4, 'https://cookcode.com?productName=Curry&productamount=1000&fechaElab=2023-10-17T13%3A07&fechaCad=2023-11-30T16%3A00&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=3.9&costPrice=1.2'),
(3, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'pack', 25, '2023-10-20', '2023-11-23', 'bag', 'Euro', 12, 'Euro', 35, 'https://cookcode.com?productName=Tikka+massala&productamount=25&fechaElab=2023-10-20T20%3A56&fechaCad=2023-11-23T19%3A55&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=35&costPrice=12'),
(4, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'pack', 25, '2023-10-20', '2023-11-23', 'bag', 'Euro', 12, 'Euro', 35, 'https://cookcode.com?productName=Tikka+massala&productamount=25&fechaElab=2023-10-20T20%3A56&fechaCad=2023-11-23T19%3A55&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=35&costPrice=12'),
(5, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'pack', 25, '2023-10-20', '2023-11-23', 'bag', 'Euro', 12, 'Euro', 35, 'https://cookcode.com?productName=Tikka+massala&productamount=25&fechaElab=2023-10-20T20%3A56&fechaCad=2023-11-23T19%3A55&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=35&costPrice=12'),
(6, 'temp/', 'juju@jaja.com', 'juju', 'Tandoori Chicken', 'bag', 34, '2023-10-28', '2023-12-05', 'bag', 'Euro', 12, 'Euro', 44, 'https://cookcode.com?productName=Tandoori+Chicken&productamount=34&fechaElab=2023-10-28T20%3A02&fechaCad=2023-12-05T20%3A01&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=44&costPrice=12'),
(7, 'temp/', 'josu@mendipe.com', 'josu', 'yogures en pack', 'pack', 1, '2023-11-17', '2023-12-04', 'bag', 'Euro', 5, 'Euro', 12, 'https://cookcode.com?productName=yogures+en+pack&productamount=1&fechaElab=2023-11-17T16%3A19&fechaCad=2023-12-04T13%3A15&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=12&costPrice=5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tagselaboraciones`
--

CREATE TABLE `tagselaboraciones` (
  `IDTag` int(11) NOT NULL,
  `tempDir` varchar(125) NOT NULL,
  `email` varchar(125) NOT NULL,
  `filename` varchar(125) NOT NULL,
  `fName` varchar(125) NOT NULL COMMENT 'Product name',
  `packaging` varchar(125) NOT NULL,
  `productamount` int(11) NOT NULL,
  `fechaElab` date NOT NULL,
  `fechaCad` date NOT NULL,
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(125) NOT NULL,
  `costPrice` int(11) NOT NULL,
  `saleCurrency` varchar(125) NOT NULL,
  `salePrice` int(11) NOT NULL,
  `codeContents` varchar(250) NOT NULL,
  `image` longblob DEFAULT NULL,
  `receta_id` int(11) DEFAULT NULL,
  `rations_package` int(11) DEFAULT NULL,
  `expira_dias` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tagselaboraciones_ingredients`
--

CREATE TABLE `tagselaboraciones_ingredients` (
  `id` int(11) NOT NULL,
  `tag_elaboracion_id` int(11) DEFAULT NULL,
  `ingrediente` varchar(255) DEFAULT NULL,
  `cantidad` float DEFAULT NULL,
  `unidad` varchar(50) DEFAULT NULL,
  `alergeno` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tagselaboraciones_ingredients`
--

INSERT INTO `tagselaboraciones_ingredients` (`id`, `tag_elaboracion_id`, `ingrediente`, `cantidad`, `unidad`, `alergeno`) VALUES
(57, 77, 'azucar', 2, 'cup', NULL),
(58, 77, 'sal', 4, 'litre', NULL),
(59, 78, 'azucar', 2, 'cup', NULL),
(60, 78, 'sal', 4, 'litre', NULL),
(61, 79, 'azucar', 2, 'litre', NULL),
(62, 79, 'sal', 2, 'gram', NULL),
(63, 80, 'azucar', 2, 'gram', NULL),
(64, 80, 'sal', 4, 'cup', NULL),
(65, 80, 'huevo', 2, 'units', NULL),
(66, 81, 'azucar', 4, 'gram', NULL),
(67, 81, 'sal', 2, 'gram', NULL),
(68, 81, 'sal', 2, 'gram', NULL),
(69, 81, 'sal', 2, 'gram', NULL),
(70, 81, 'sal', 2, 'gram', NULL),
(71, 81, 'sal', 2, 'gram', NULL),
(72, 81, 'sal', 2, 'gram', NULL),
(73, 81, 'sal', 2, 'gram', NULL),
(74, 81, 'sal', 2, 'gram', NULL),
(75, 81, 'sal', 2, 'gram', NULL),
(76, 81, 'sal', 2, 'gram', NULL),
(77, 81, 'sal', 2, 'gram', NULL),
(78, 81, 'sal', 2, 'gram', NULL),
(79, 81, 'sal', 2, 'gram', NULL),
(80, 81, 'sal', 2, 'gram', NULL),
(81, 81, 'sal', 2, 'gram', NULL),
(82, 82, 'azucar', 5, 'cup', NULL),
(83, 82, 'azucar', 5, 'cup', NULL),
(84, 82, 'azucar', 5, 'cup', NULL),
(85, 82, 'azucar', 5, 'cup', NULL),
(86, 82, 'azucar', 5, 'cup', NULL),
(87, 82, 'azucar', 5, 'cup', NULL),
(88, 82, 'azucar', 5, 'cup', NULL),
(89, 82, 'azucar', 5, 'cup', NULL),
(90, 82, 'azucar', 5, 'cup', NULL),
(91, 82, 'azucar', 5, 'cup', NULL),
(92, 82, 'azucar', 5, 'cup', NULL),
(93, 82, 'azucar', 5, 'cup', NULL),
(94, 83, 'leche', 2, 'litre', ''),
(95, 84, 'leche', 2, 'litre', 'Leche'),
(96, 85, 'leche', 2, 'litre', 'Leche'),
(97, 86, 'leche', 3, 'litre', 'Leche'),
(98, 86, 'azucar', 5, 'teaspoon', 'Ninguno'),
(99, 86, 'sal', 4, 'tablespoon', 'Ninguno'),
(100, 87, 'pimienta', 2, 'gram', 'Ninguno'),
(101, 87, 'perejil', 5, 'units', 'Ninguno'),
(102, 88, 'huevo', 2, 'litre', 'Huevos'),
(103, 88, 'sal', 2, 'gram', 'Ninguno'),
(104, 89, 'azucar', 12, 'litre', 'Ninguno'),
(105, 93, 'azucar', 2, 'cup', 'Granos'),
(106, 94, 'huevo', 2, 'cup', 'Pescado'),
(107, 95, 'sal', 5, 'cup', 'Ninguno'),
(108, 96, 'sal', 4, 'cup', 'Ninguno'),
(109, 97, 'sal', 2, 'cup', 'Granos'),
(110, 97, 'sal', 2, 'cup', 'Granos'),
(111, 97, 'sal', 2, 'cup', 'Granos'),
(112, 98, 'azucar', 2, 'cup', 'Mostaza'),
(113, 104, 'azucar', 2, 'cup', 'Leche'),
(114, 104, 'azucar', 2, 'cup', 'Leche'),
(115, 105, 'azucar', 2, 'cup', 'Leche'),
(116, 105, 'azucar', 2, 'cup', 'Leche'),
(117, 0, 'huevo', 5, 'gram', 'Soja');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tagsingredientes`
--

CREATE TABLE `tagsingredientes` (
  `IDTag` int(11) NOT NULL,
  `tempDir` varchar(125) NOT NULL,
  `email` varchar(125) NOT NULL,
  `filename` varchar(125) NOT NULL,
  `fName` varchar(125) NOT NULL COMMENT 'Product name',
  `packaging` varchar(125) NOT NULL,
  `productamount` int(11) NOT NULL,
  `fechaElab` date NOT NULL,
  `fechaCad` date NOT NULL,
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(125) NOT NULL,
  `costPrice` int(11) NOT NULL,
  `saleCurrency` varchar(125) NOT NULL,
  `salePrice` int(11) NOT NULL,
  `codeContents` varchar(250) NOT NULL,
  `image` longblob DEFAULT NULL,
  `ingrediente_id` int(11) DEFAULT NULL,
  `cantidad_paquete` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tagspreelaboraciones`
--

CREATE TABLE `tagspreelaboraciones` (
  `IDTag` int(11) NOT NULL,
  `tempDir` varchar(125) NOT NULL,
  `email` varchar(125) NOT NULL,
  `filename` varchar(125) NOT NULL,
  `fName` varchar(125) NOT NULL COMMENT 'Product name',
  `packaging` varchar(125) NOT NULL,
  `productamount` int(11) NOT NULL,
  `fechaElab` date NOT NULL,
  `fechaCad` date NOT NULL,
  `warehouse` varchar(125) NOT NULL,
  `costCurrency` varchar(125) NOT NULL,
  `costPrice` int(11) NOT NULL,
  `saleCurrency` varchar(125) NOT NULL,
  `salePrice` int(11) NOT NULL,
  `codeContents` varchar(250) NOT NULL,
  `image` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tagspreelaboraciones`
--

INSERT INTO `tagspreelaboraciones` (`IDTag`, `tempDir`, `email`, `filename`, `fName`, `packaging`, `productamount`, `fechaElab`, `fechaCad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`, `image`) VALUES
(1, 'vacio', 'ajaja@ejam.com', 'ggdds', 'addsd', 'dda', 3, '2023-10-10', '2023-10-25', 'freezer', 'euro', 22, 'euro', 34, 'fdsdfsfsdfds', NULL),
(2, 'temp/', 'josu@mendipe.com', 'josu', 'Curry', 'bag', 1000, '2023-10-17', '2023-11-30', 'bag', 'Euro', 1, 'Euro', 4, 'https://cookcode.com?productName=Curry&productamount=1000&fechaElab=2023-10-17T13%3A07&fechaCad=2023-11-30T16%3A00&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=3.9&costPrice=1.2', NULL),
(3, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'pack', 25, '2023-10-20', '2023-11-23', 'bag', 'Euro', 12, 'Euro', 35, 'https://cookcode.com?productName=Tikka+massala&productamount=25&fechaElab=2023-10-20T20%3A56&fechaCad=2023-11-23T19%3A55&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=35&costPrice=12', NULL),
(4, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'pack', 25, '2023-10-20', '2023-11-23', 'bag', 'Euro', 12, 'Euro', 35, 'https://cookcode.com?productName=Tikka+massala&productamount=25&fechaElab=2023-10-20T20%3A56&fechaCad=2023-11-23T19%3A55&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=35&costPrice=12', NULL),
(5, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'pack', 25, '2023-10-20', '2023-11-23', 'bag', 'Euro', 12, 'Euro', 35, 'https://cookcode.com?productName=Tikka+massala&productamount=25&fechaElab=2023-10-20T20%3A56&fechaCad=2023-11-23T19%3A55&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=35&costPrice=12', NULL),
(6, 'temp/', 'juju@jaja.com', 'juju', 'Tandoori Chicken', 'bag', 34, '2023-10-28', '2023-12-05', 'bag', 'Euro', 12, 'Euro', 44, 'https://cookcode.com?productName=Tandoori+Chicken&productamount=34&fechaElab=2023-10-28T20%3A02&fechaCad=2023-12-05T20%3A01&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=44&costPrice=12', NULL),
(7, 'temp/', 'juju@jaja.com', 'juju', 'Tandoori Chicken', 'bag', 25, '2023-11-06', '2023-11-22', 'bag', 'Euro', 3, 'Euro', 15, 'https://cookcode.com?productName=Tandoori+Chicken&productamount=25&fechaElab=2023-11-06T15%3A59&fechaCad=2023-11-22T15%3A59&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=15&costPrice=3', NULL),
(8, 'temp/', 'juju@jaja.com', 'juju', 'Tikka Massala', 'bag', 17, '2023-11-21', '2023-11-30', 'bag', 'Euro', 1, 'Euro', 15, 'https://cookcode.com?productName=Tikka+Massala&productamount=17&fechaElab=2023-11-21T16%3A01&fechaCad=2023-11-30T16%3A01&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=15&costPrice=1', NULL),
(9, 'temp/', 'juju@jaja.com', 'juju', 'Tikka Massala', 'bag', 17, '2023-11-21', '2023-11-30', 'bag', 'Euro', 1, 'Euro', 15, 'https://cookcode.com?productName=Tikka+Massala&productamount=17&fechaElab=2023-11-21T16%3A01&fechaCad=2023-11-30T16%3A01&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=15&costPrice=1', NULL),
(10, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'bag', 5, '2023-11-28', '2023-12-13', 'bag', 'Euro', 1, 'Euro', 3, 'https://cookcode.com?productName=Tikka+massala&productamount=5&fechaElab=2023-11-28T16%3A05&fechaCad=2023-12-13T16%3A05&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=3.1&costPrice=0.7', NULL),
(11, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'bag', 5, '2023-11-28', '2023-12-13', 'bag', 'Euro', 1, 'Euro', 3, 'https://cookcode.com?productName=Tikka+massala&productamount=5&fechaElab=2023-11-28T16%3A05&fechaCad=2023-12-13T16%3A05&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=3.1&costPrice=0.7', NULL),
(12, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'bag', 5, '2023-11-28', '2023-12-13', 'bag', 'Euro', 1, 'Euro', 3, 'https://cookcode.com?productName=Tikka+massala&productamount=5&fechaElab=2023-11-28T16%3A05&fechaCad=2023-12-13T16%3A05&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=3.1&costPrice=0.7', NULL),
(13, 'temp/', 'juju@jaja.com', 'juju', 'Tikka massala', 'bag', 5, '2023-11-28', '2023-12-13', 'bag', 'Euro', 1, 'Euro', 3, 'https://cookcode.com?productName=Tikka+massala&productamount=5&fechaElab=2023-11-28T16%3A05&fechaCad=2023-12-13T16%3A05&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=3.1&costPrice=0.7', NULL),
(14, 'temp/', 'juju@jaja.com', 'juju', 'Tikka masala', 'pack', 3, '2023-11-29', '2023-12-20', 'pack', 'Euro', 1, 'Euro', 3, 'https://cookcode.com?productName=Tikka+masala&productamount=3&fechaElab=2023-11-29T16%3A38&fechaCad=2023-12-20T16%3A38&warehouse=pack&costCurrency=Euro&saleCurrency=Euro&salePrice=2.5&costPrice=1.1', NULL),
(15, 'temp/', 'juju@jaja.com', 'juju', 'Tikka masala', 'pack', 3, '2023-11-29', '2023-12-20', 'pack', 'Euro', 1, 'Euro', 3, 'https://cookcode.com?productName=Tikka+masala&productamount=3&fechaElab=2023-11-29T16%3A38&fechaCad=2023-12-20T16%3A38&warehouse=pack&costCurrency=Euro&saleCurrency=Euro&salePrice=2.5&costPrice=1.1', NULL),
(16, 'temp/', 'juju@jaja.com', 'juju', 'Tikka masala', 'pack', 3, '2023-11-29', '2023-12-20', 'pack', 'Euro', 1, 'Euro', 3, 'https://cookcode.com?productName=Tikka+masala&productamount=3&fechaElab=2023-11-29T16%3A38&fechaCad=2023-12-20T16%3A38&warehouse=pack&costCurrency=Euro&saleCurrency=Euro&salePrice=2.5&costPrice=1.1', NULL),
(17, 'temp/', 'juju@jaja.com', 'juju', 'Falafel', 'bag', 5, '2023-11-14', '2023-11-22', 'bag', 'Euro', 3, 'Euro', 8, 'https://cookcode.com?productName=Falafel&productamount=5&fechaElab=2023-11-14T16%3A45&fechaCad=2023-11-22T16%3A46&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=8&costPrice=3', NULL),
(18, 'temp/', 'juju@jaja.com', 'juju', 'Falafel', 'bag', 5, '2023-11-14', '2023-11-22', 'bag', 'Euro', 3, 'Euro', 8, 'https://cookcode.com?productName=Falafel&productamount=5&fechaElab=2023-11-14T16%3A45&fechaCad=2023-11-22T16%3A46&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=8&costPrice=3', NULL),
(19, 'temp/', 'juju@jaja.com', 'juju', 'Falafel', 'bag', 5, '2023-11-14', '2023-11-22', 'bag', 'Euro', 3, 'Euro', 8, 'https://cookcode.com?productName=Falafel&productamount=5&fechaElab=2023-11-14T16%3A45&fechaCad=2023-11-22T16%3A46&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=8&costPrice=3', NULL),
(21, 'temp/', 'juju@jaja.com', 'juju', 'Falafel', 'bag', 5, '2023-11-14', '2023-11-22', 'bag', 'Euro', 3, 'Euro', 8, 'https://cookcode.com?productName=Falafel&productamount=5&fechaElab=2023-11-14T16%3A45&fechaCad=2023-11-22T16%3A46&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=8&costPrice=3', NULL),
(22, 'temp/', 'juju@jaja.com', 'juju', 'Falafel', 'bag', 5, '2023-11-14', '2023-11-22', 'bag', 'Euro', 3, 'Euro', 8, 'https://cookcode.com?productName=Falafel&productamount=5&fechaElab=2023-11-14T16%3A45&fechaCad=2023-11-22T16%3A46&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=8&costPrice=3', NULL),
(23, 'temp/', 'juju@jaja.com', 'juju', 'Falafel', 'bag', 5, '2023-11-14', '2023-11-22', 'bag', 'Euro', 3, 'Euro', 8, 'https://cookcode.com?productName=Falafel&productamount=5&fechaElab=2023-11-14T16%3A45&fechaCad=2023-11-22T16%3A46&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=8&costPrice=3', NULL),
(36, '', 'toni@hotmail.com', '', 'pasta', 'bag', 0, '0000-00-00', '0000-00-00', 'bag', 'Euro', 0, 'Euro', 0, '', NULL),
(37, '', 'toni@hotmail.com', '', 'pasta', 'bag', 0, '0000-00-00', '0000-00-00', 'bag', 'Euro', 0, 'Euro', 0, '', NULL),
(38, 'temp/', 'toni@hotmail.com', 'toni', 'Flan', 'bag', 12, '2024-04-05', '2024-04-21', 'bag', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=Flan&productamount=12&fechaElab=2024-04-05T00%3A18&fechaCad=2024-04-21T00%3A18&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=12', NULL),
(39, 'temp/', 'toni@hotmail.com', 'toni', 'Flan', 'bag', 12, '2024-04-05', '2024-04-21', 'bag', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=Flan&productamount=12&fechaElab=2024-04-05T00%3A18&fechaCad=2024-04-21T00%3A18&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=12', NULL),
(40, 'temp/', 'toni@hotmail.com', 'toni', 'gggg', 'bag', 12, '2024-04-05', '2024-04-12', 'bag', 'Euro', 22, 'Euro', 55, 'https://cookcode.com?productName=gggg&productamount=12&fechaElab=2024-04-05T00%3A19&fechaCad=2024-04-12T00%3A19&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=55&costPrice=22', NULL),
(41, 'temp/', 'toni@hotmail.com', 'toni', 'pizza', 'bag', 4, '2024-04-15', '2024-05-03', 'bag', 'Euro', 15, 'Euro', 18, 'https://cookcode.com?productName=pizza&productamount=4&fechaElab=2024-04-15T00%3A32&fechaCad=2024-05-03T00%3A33&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=18&costPrice=15', NULL),
(42, 'temp/', 'toni@hotmail.com', 'toni', 'ffff', 'bag', 42, '2024-04-25', '2024-04-27', 'bag', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=ffff&productamount=42&fechaElab=2024-04-25T14%3A15&fechaCad=2024-04-27T14%3A15&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=12', NULL),
(43, 'temp/', 'toni@hotmail.com', 'toni', 'ffff', 'bag', 42, '2024-04-25', '2024-04-27', 'bag', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=ffff&productamount=42&fechaElab=2024-04-25T14%3A15&fechaCad=2024-04-27T14%3A15&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=12', NULL),
(44, 'temp/', 'toni@hotmail.com', 'toni', 'vvvvv', 'bag', 11, '2024-04-25', '2024-04-28', 'bag', 'Euro', 14, 'Euro', 22, 'https://cookcode.com?productName=vvvvv&productamount=11&fechaElab=2024-04-25T15%3A11&fechaCad=2024-04-28T15%3A11&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=14', NULL),
(45, 'temp/', 'toni@hotmail.com', 'toni', 'hhh', 'bag', 21, '2024-04-26', '2024-05-11', 'bag', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=hhh&productamount=21&fechaElab=2024-04-26T13%3A54&fechaCad=2024-05-11T13%3A54&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=12', NULL),
(46, 'temp/', 'toni@hotmail.com', 'toni', 'hhh', 'bag', 21, '2024-04-26', '2024-05-11', 'bag', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=hhh&productamount=21&fechaElab=2024-04-26T13%3A54&fechaCad=2024-05-11T13%3A54&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=12', NULL),
(47, 'temp/', 'toni@hotmail.com', 'toni', 'Tallarines', 'bag', 12, '2024-04-26', '2024-04-27', 'bag', 'Euro', 12, 'Euro', 22, 'https://cookcode.com?productName=Tallarines&productamount=12&fechaElab=2024-04-26T15%3A30&fechaCad=2024-04-27T15%3A30&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=22&costPrice=12&ingredient[]=sal|2|cup|Crust%C3%A1ceos&ingredient[]=sal|', NULL),
(48, 'temp/', 'toni@hotmail.com', 'toni', 'Tallarineskkk', 'bag', 12, '2024-04-26', '2024-05-04', 'bag', 'Euro', 12, 'Euro', 65, 'https://cookcode.com?productName=Tallarineskkk&productamount=12&fechaElab=2024-04-26T16%3A51&fechaCad=2024-05-04T16%3A51&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=65&costPrice=12&ingredient[]=azucar|2|cup|Huevos', NULL);
INSERT INTO `tagspreelaboraciones` (`IDTag`, `tempDir`, `email`, `filename`, `fName`, `packaging`, `productamount`, `fechaElab`, `fechaCad`, `warehouse`, `costCurrency`, `costPrice`, `saleCurrency`, `salePrice`, `codeContents`, `image`) VALUES
(49, 'temp/', 'toni@hotmail.com', 'toni', 'Tallarineskkk', 'bag', 12, '2024-04-26', '2024-05-04', 'bag', 'Euro', 12, 'Euro', 65, 'https://cookcode.com?productName=Tallarineskkk&productamount=12&fechaElab=2024-04-26T16%3A51&fechaCad=2024-05-04T16%3A51&warehouse=bag&costCurrency=Euro&saleCurrency=Euro&salePrice=65&costPrice=12&ingredient[]=azucar|2|cup|Huevos', 0xffd8ffe000104a46494600010101006000600000ffe1010645786966000049492a000800000006001201030001000000010000001a01050001000000b00000001b01050001000000b800000028010300010000000200000013020300010000000100000069870400010000005600000000000000070000900700040000003032333101910700040000000102030000a00700040000003031303001a0030001000000ffff000002a0030001000000e803000003a003000100000033020000869207003d000000c00000000000000060000000010000006000000001000000415343494900000078723a643a444146302d63534174486f3a3232392c6a3a353838383836373737313332333133373837362c743a323331323034313700ffe1055f687474703a2f2f6e732e61646f62652e636f6d2f7861702f312e302f003c783a786d706d65746120786d6c6e733a783d2761646f62653a6e733a6d6574612f273e0a20202020202020203c7264663a52444620786d6c6e733a7264663d27687474703a2f2f7777772e77332e6f72672f313939392f30322f32322d7264662d73796e7461782d6e7323273e0a0a20202020202020203c7264663a4465736372697074696f6e207264663a61626f75743d27270a2020202020202020786d6c6e733a64633d27687474703a2f2f7075726c2e6f72672f64632f656c656d656e74732f312e312f273e0a20202020202020203c64633a7469746c653e0a20202020202020203c7264663a416c743e0a20202020202020203c7264663a6c6920786d6c3a6c616e673d27782d64656661756c74273e494d477320426c6f67205061756c696e6120436f63696e612053686f727420426174636820283139323020c3972031303830c2a0707829202d20726563657461206465207374726f676f6e6f666620646520706f6c6c6f205061756c696e6120436f63696e61205265636574617320436f63696e613c2f7264663a6c693e0a20202020202020203c2f7264663a416c743e0a20202020202020203c2f64633a7469746c653e0a20202020202020203c2f7264663a4465736372697074696f6e3e0a0a20202020202020203c7264663a4465736372697074696f6e207264663a61626f75743d27270a2020202020202020786d6c6e733a4174747269623d27687474703a2f2f6e732e6174747269627574696f6e2e636f6d2f6164732f312e302f273e0a20202020202020203c4174747269623a4164733e0a20202020202020203c7264663a5365713e0a20202020202020203c7264663a6c69207264663a7061727365547970653d275265736f75726365273e0a20202020202020203c4174747269623a437265617465643e323032332d31322d30343c2f4174747269623a437265617465643e0a20202020202020203c4174747269623a45787449643e66363337643862312d373564642d343930332d613533352d6161323765373136643534623c2f4174747269623a45787449643e0a20202020202020203c4174747269623a466249643e3532353236353931343137393538303c2f4174747269623a466249643e0a20202020202020203c4174747269623a546f756368547970653e323c2f4174747269623a546f756368547970653e0a20202020202020203c2f7264663a6c693e0a20202020202020203c2f7264663a5365713e0a20202020202020203c2f4174747269623a4164733e0a20202020202020203c2f7264663a4465736372697074696f6e3e0a0a20202020202020203c7264663a4465736372697074696f6e207264663a61626f75743d27270a2020202020202020786d6c6e733a7064663d27687474703a2f2f6e732e61646f62652e636f6d2f7064662f312e332f273e0a20202020202020203c7064663a417574686f723e5061756c696e6120436f63696e612044697365c3b16f3c2f7064663a417574686f723e0a20202020202020203c2f7264663a4465736372697074696f6e3e0a0a20202020202020203c7264663a4465736372697074696f6e207264663a61626f75743d27270a2020202020202020786d6c6e733a786d703d27687474703a2f2f6e732e61646f62652e636f6d2f7861702f312e302f273e0a20202020202020203c786d703a43726561746f72546f6f6c3e43616e76613c2f786d703a43726561746f72546f6f6c3e0a20202020202020203c2f7264663a4465736372697074696f6e3e0a20202020202020200a20202020202020203c2f7264663a5244463e0a20202020202020203c2f783a786d706d6574613effdb0043000604040504040605050506060607090e0909080809120d0d0a0e1512161615121414171a211c17181f1914141d271d1f2223252525161c292c28242b21242524ffdb00430106060609080911090911241814182424242424242424242424242424242424242424242424242424242424242424242424242424242424242424242424242424ffc000110802a304b003012200021101031101ffc4001c0000010501010100000000000000000000030102040506070008ffc4004610000104010303030205020404040501090100020311040512210631411322516171071432819123421552a1b1163343c12462d1e1537292f0f1173482084454a22563b2ffc4001b01000203010101000000000000000000000102000304050607ffc4002e11000202020202020202020105010101000001020304111221053113412251143215612306425271812433b1ffda000c03010002110311003f00c943a6c95cb6d1a0c796193db6da5718e5849149328336f6e578bc9c4508366c92eb633f393401a4bcf0adf47ea42c755f2a95f1fa90f1ca8b8d1ba3938f959bc65b253d260ae5b475cd3b5e665441ae70b53a39209ceee0ae6d8393242473dd5fe06a4e60e49afa2f46ad921251dfb3798f858d90caa6a33b40808b0d0b3ba6eb1235d4ce45f72b5989a83646b775590ba74e53d19a78d1655cfd3f1edfd21419742881fd216bed928f1482fc163cf0b64328e7db88fe8c71d0a1a3c04c1a2c23fb5ab533e9f574abe6c72d3415ab21b314a992f652c9a442c8dc69aaa7d0806486d05a89e02f85e38ba580d5329fa76ae19238d38f092cc8d2466b9f1d1aa668f1168218394a7468eab637946d1f304d8ed176acc511d95bf3bd6cba3a7d94834184034c6da46e891075b9ad002be039e02a6ea4d6a0d2718979a2aa9e5f15b63b5d7453ead9185a7b1c4ed143b2cd6375461e5647a60b0006b9589eaceaa9b50c97b6193d856619959111b63c8fa82b9367969a96a2595d7bf6773c8d670b0f1cbfd48cb8f600acaeb3d478e7165787b413da8ae74752ca7f0f95c7f751e69a492edc4848fcb58fa34aa9486e5e73f2267924d5abce8785b91aa805b6b39b79e56e3f0c71dafd4cb8b6e91c6b9ce7b66875a8c4eaf8fd3cd96104b0238e9a8c7f6b568f0630fc71400477b1a3e1775e4339dc11981d3d134fe86a77fc3f15dec1fb2d13b6b45709292fccc575a33e7408873e9a8b9dd30c963ad960f85ac6b01f09db40f16a994d496995cab4d1c77a87a564c5619218e879a0b1d93098ec3ada57d119ba745971b9ae60e7e8b98f5cf4c0807ab0b001dc801799cfc0717f2449527166031626be766efd36ba5e9b263458d13581bcd03cae6863746fa3c11d95ff004ab66cbd4626be471603c859f0332544f51fb34cd6e275ed2b4b8f2630f118a593fc428e3c2c570a605d0b4e886269e0b4f60b8cfe2d6b5ba47425e17a8b72755727eccf62db48e4d9641ca95dff009908b6f8095c37b893c926d1618f73a970652fb3a552e2b432380fc2b0831038764ec783c2b3c68401d964b6e24a5a23c7866bb2930e9e41fd2795698d89bc0577a56971c9289272368fedf9583f90dcb8a651cc83a2748e56a545b196c63c95d13a67a471f4b8cbe60375773e5019af6169b8f436b6be151eb5f89113632c89fcaede2db8d5aefb915b7cba207e25e5c2c89f0c75dd72d2ee4d8a563ae6bb36ab90edeeb16ab03377755bee4e7fb2daa3c50a5c07286cc87b64dcd4f732cd2918b880f7083928ad97a276920cf200f5d3fa5b4dc782112383791cf2b9e6042d8dc081455f45a9cf045b5b21afbae6cee70b54e25728268e978da5e165921bb2caf4fd2d1871018da593e8fcfc89b3c35cf711f75d4a120c6370b34bd6f8fcd95d0eccff00198e7f4c462fd8104f4dc7e58d5b491a09e028ef6349ada174a3630a8e8c937a663ff2b529e97888fd2d5a86440f84ff0045aac56b1f4644f4a4241f6b5467f4a30766356dfd16a43037e029f2b276619fd2d18af60a4c3d310fff000c2dc3b1dbd937f2acaec10f909a3127a5a13dd8130f4a43e18d5b8184d3dc05e386c1c5047e4276617fe148aff4b52ffc2709eed1fb2db1c367c049f946fc0479936cc4ff00c250ff00957bfe128bfc8b6a7147c05efca8ff002a9cc9d98c1d251815b179bd2317c52d98819e6d7bd08fe14e64d331ede92840ec97fe14847f6dad88818539988c3f28f30a4630f4ac3fe45e1d298e7fb16d3f2d1dd522371631e0257607462dbd29091ff2c26ffc290dff00cb0b76d8221e13863444f653e409856f4ac60f118afb2f49d270bafd83f65bcfc9b2b8010dd86daec10f901c99cf4747421d61aa7c3d291167e81c2d78c2168ac81ac142915681f6637fe168aff4b539bd351b7fb1ab5df961f0120839fa29cf40d197674ec6efed014bc7e97849e434ad1b60601fa42246d6b4a2ad61e1b33cee96c41751b49423d2f8dffc31fc2d5b037714e735b5d826f9583e2320de98c7068442bec8e3a5e0ae226ad4318d03b0446340174117693e246623e9a60ed135498fa6da3fb1a168406fd13ec0097e507c68cc6a1a14431ddb8341ae171aeacc27366787760785ddf5f706e1b9fba880b8af58e48909008bb5c7f292dc4d986bf339fcb002f3c26b30dae3ca2cb90048501d95457163cbe8ecc5743bf2db4f0784be9b81ee867305774833077ee8ea45ab439f1c9e100b5c0f2a57e71bb7c28feb87b8fc268b9226cf358e0384f6b1c6ac22c4e6a991358e09256345f5c4063c0ebb0158e3e3977847c6c3dece02b6d3b4a73de081c2c37e4697b36d70fd91b1b0def361a7e14f669729a3b4ff000b4fa4e82e751d9c2bf8b44f6d1673f65c4bb3649f48d90e28c660e973b886ec34b63a274db9ecb215ae0e90d6d5b05ad369988d88506858a8b659372adad0d7cd46b6d194cfe9db1b7d3b50874adf78c2e8f2e2b5d44b414c38f18fed0beaf835c69a5411f3eccdd963673693a500ff00a6133fe1703fe985d165c469f0104e2b6fb05b3e43328e8c0ffc2ed3fd8528e961fe42b7df9667f94273719bf0133b07308ce942eed19456f493873e9addb31c0f0149642d03b05539878a39fb7a59c3bc49ede98fff00d63f85bff45847e90bde833fca12a99389851d2fbbfe9a237a5401cb42dbb71da3c04f6c22cf011e60d187ff0085403749ff00f0f31a398ff75b6f4db4780a0658dac7d0aa0a720a4711eb6ffc0c92303680ecb9c1c9dee773e5749fc4b63a6749b472172704c6e3cf36b8d91dc8ebe0ff0056172392557bdbc9b52a49aca0b86e59e26d6059dd2bbb25a03b2f55a714187522b5dc2618fe8891e3b9e38083d688b6159300d4764edaee919a6b9cd1414b834192416b3cb897c790164cd27ba951b87148b174e4b7d8a9d0f4e6403c34acd64e0becbe309322b196eb53b1a3dae054a87a7f27b6c2acb17a6b2de4534feeb14ed5f45d1aa44681ad6f653f1fddf2ac31ba532aadc15b61f4bca3bb562b139334421c51510093b0b53198990f3ed0e5acc0e972482e65abfc1e9b68703b07ee8d786a5db23b1a305069196f1fa5dfc29f8bd3b92ffec715d2b13448d87960afb2b1874b859cd0fe17421891454ee6739c6e949ded1ed209eeae707a50c2d0d700696dd98b137fb53bd18c766ad0b1a28afe6d99dc5e9d8d8e0ed815c371591c61a1bd949e00437b85728fc718839365766e1b658c8aeeb15aef4ac594d7532cadde448031c2d55b69eeae0d94b53d5ab45b28f2a9ece6eee848ddcfa27f84d3d0ec68ff00943f85d5e0c28cb2cf3fb26cb8309b007fa2f674f175ada3c4deb8cda47253d1119ef17fa2f37a22107fe51fe1753ff0d67c2f0d3e21dda15dc2b33a7d1cc1bd0f0971fe99fe13d9d09034dfa63f85d463c18473b51060c4e3fa47f0870ac64dfd1c4216004dd050f3660d755f0830eb31c8d34e16ab350cfb7f0578bcb719438a6766db1289798b2b5ec22fb263c35ae06d5162ea4e6122cf29f99a9384576b874572aecda2ba6c5a3491e663c2d05d27faab5d3b53c79486b1e29728975894922cf0a568daf4f16644c06c13c85dbf965ed22c56c5bd1de34dc57399b859055c4123e320538528fd2d53e146e009e39574fc6dc0dd05d7c7dca1b609687e2ea0e0e009e15a439f19fd4e0167e46888120df95519fd43161c94e7f0ac94d43db037fa37a1f1cd74e14812e0071dd76b33a3ebd164d16c960ad1c1a8c4180bdc004d0bbadec4952a6bb013e05034deeb99f5fe03987f31b396f95d822962c86ff4dcd70fa2cc757683f9ec5906cb15f0ac9d9b8b3979581b5b4603a435a696318f7dd71dd6fa19592b039a6f85c0f35d95d3babb9ac2e01afe47d1757e92d7a19e18d8f901de3bfc15561e646cfc26fd18215b8f4cd24b2fa71b9de405c77f12ba81efdd8ed70249fdd74fea4d4e2c2c29250f68147caf9dfa8f537ea3a84af2eb01c69539d7a4b84596c53e5a29ddb8df3dd36ca7948b92bd1bd43a186e92037dd3dc3e0269690114c315a1ae166d6f7f0d5fe865ef3c93e1627131a4ca9c45130b9e4f002ec1f87dd0b36316e564b887116005d0c384a4fa05d7ad691d3f4df7401d740a23c0249b1dd43ccd420d2718be77358d68597c7ebcc1cbcef45990d04fcae94f2e15be0cc2a5b362fa0384ac3610f1a78e689aedede4775218c1dc1047d15ca69ada0bd9ee404e045271000e426ecf3e12bdfd89e8730871a547d558903b10990f34acf2f363c389c5ce0085cc3ae7ad4343e28e4bfdd61cbc98c6b717dec3bd9cf35f99b8da94ac63ac6ee168ff000e2464d9eddc4f7583ca9dd9790e94d924dad3744ea0cd3f31ae7f1cf95c2a92ae51932f5b713e80ce9db8fa61e768a5f397e23e67e6f547d1b0174eea1eb7825d30b3d40d205707bae2fa94e73a77484eeb257572326338a48455be6b655c4cdc5596363fc841c7c71bc00ae20c324835c2e65d6a48d8e5a1b063df65698d89c5146c5c20da34aca3c76b45d52e4dd7fd22894b60e18db032ead2c9a83a36fb384e95a6be8a39881b36b2c74fb650caed432a67825ce34b2d9d2973cfb8ad66a7106c4e2b25911dc84fd575f0b5ad8d5eb6436b497f72a60ec8623522266ee385be7234a1238379beca7e3b368a43003450527161b772b2d92ebb189f890ee175ca90e8cf20a642ed89659eaecae7b6db06cd2f45cf0e2e5b9d2baa9746675369ac60feb0baf95c3c6a2ec7b2c245a8336a792f7122578fb15d4c2ccb295a480f47d031f50604ff00a656ff0028edcbc79ec46f692be7266af9f09f664483f75a5e9eeaccf824609ddbdbf2e2ba51f2934f7241e29a3b55f17c25001543a6752c398ca739adfdd5c43970c9c31ed77d8aec53935d91524c5e2d07da13693ec7292ad5f29106ecb3ca52c09c1a9c024202da42f55f708bb52865fc23c8288e1bdf84859f4523d2a3e1218d05611a23b99410c8214a2d4dd89d4c5237a64a4f4ca93b0a4d8a722028d9dd15a384e6b404a0709b7d04196f2bdfb2211e53785590f0349ec752624ddc2802535dc2f1aa51d921f0881e7ca8416a92763d938387caf7750835cdbf2900a09c3eabc8ed9343375256385f2bc5a9a428985121946ca7d1ae1466485bc0450f247253f226c204ae7d263579c2d020f63fea9e0951c022e979d90626173cd0aee8a924857bde8cf75a6a6dc583d2be48ecb867506799a67f7eeb6bf88bd5cc13c91020968e0ae4f9bab3672e35cdae2e6b763d23a1890d76027790e26d477cb63b942c898bbb1416c85550874747618c89049668142325a687f29f88764bde48ab4684150d8edc694fc6889176ab9f48b60b64dc761711c2b5c4c32e2103071daea5a6d3b11bb42e3e4dfc4e8d356d12f48c300005a0fdd6bf4bd3226b41dbdd53e9f8ed6814b49a792dda28d2f3d6e4ea7b669707a2ff004dc4634343452b88700b8f0abb4d759a5a1d3e325dcd95d5a1c2c8f48a39388d6e98473c0a4589a22241f0ae23c6dececabf3719d0924f0155652e992b2280e7c9388f243a3b4126caf473b1d1d5d26070ddc1bfb2f65e3b255d5f5ecf31954ca126c47a686829ee165780a5d248c0dec6ed0bc0729528e5300f0b466f64c684e05068887f849496ed7a95690c381a5edc5252f52843d74a2e5b0ba37296004199a5cc700a47d80e37d7f864199f5d971bcbc722479edcafa03aef05cfc798edee170bd46173267b48ae562ba1a96ce8e159f45439bcaf009f20e48430eeeb135a674f631e395e6d0e579c6d32e946ff0040082bba9313c347750c3d3da48e6d234d853d17b8b911f6e15de9d950b5dee22962d9296f6b47873246f92b3caa6cd30b123a9613b127028b42d269d85892c42cb6d71cc2d62589c29c695fe2754be2adcf35f75cecaaa4d7e26faae8fd9d6a0d271bcd7fa2b3c6d2b15a41ab5ca713ada406b71fbdaba675abd8c69f57bfd57365f247e8d719425e99d3a1c3c5da1a0341faa9d16040caf6b4fd5731c4eb52e36e92ff00756b075a0e3739185cfed0b28efd33a54506380050054989b133b10b9cc7d6910ed27faa28eb76017ea85aabbe3eca5d0ce8c278da7b85efcdb01fd4b9cb7ad58e3cc8a4c7d571bc58942d51bd09fc737873d80774376a00762b12eea703fbdaa3bfaa01bf704dfc9d11639b77ea6d02cba9024d4987b3ad61a5ea32e1fa8528efea57f60ea54cb2365d0a11b2ccd51803bdc143c1ccf5660012795907eaafc9770e5a5e9c8b7bb797775af0e2e76229c9d42b66cb19c0c5f54bb3e52c30ec8be4fd13876e42f6d5f51d33c2df2dd8d822db4df4abc94f71da7b256fb9145490c6b48e13da39ee976a46f0531347c7b8d95203c1a530cae987bbba8f1c21a54a6338b5f3e9b5ecb9dbf41f19a00e52660dd11a44c703b274ad0ef6acfbfcb63a9e974528c432715ee579d1ba09cbd6e163d808079438b180f72d0f4b64fe43516cae6803e55f1c8d496fd020f72d9dcba734e18b8ed0d14d014cce984639143e55160f57617e5c032b5b43959bea8fc4189cd763e2bc103c85dc966d55d7d336725a2c7a8baa20c08e46b2405d5cd15c97a8baa5f9cf263791cf2143d5b54cacb95e4c8ea77d554478dba4f7127cae4d994eded94bbd6ce9ff0087dadb25843643fa7bd95a3ea7ea76e060fad1480ed1d815c931a5970c038ef2dfa051f5bd672a688c6f9096d728c32e525c50ceee8eadd23f8a90644cdc77170909aeeba9e26b10e762b839cd76e6af8f74cd41f8596d99940b4dae8fa4fe2888636b24b6d7176b6d792e0b4c7aace51ecb2fc46d231c66be78fb93cacce9da865e9afa80fb7c5a95acf5543ac3cec7582abe395a790b879174d5ae5139b930fcb68f751f51ea39707a4f9096fd163034b9c49be4f2b6cfc78b207b8286fd0d8e2760ee9a199d7e7ecaa2d232ee84350dc15be769fe8151a0c76b9f4e6dad51b535b2f5610844e23b27c58ee91e180593c05a2c7d259237814acb45d004d9f19341a1c0aa96545be25365dd691a1fc3ff00c3e2edb93334d9a3dbb2ea8f38da36272e1c0f2abf1752c5d274e6d39a36b795ce7adbaddf96f7450bcec1c705775e7554d6947d9952fb21fe227581cc91d8d8ef3b47079583c5cf931266cdddc0f64dc891d952b9eeb25c86217790b973b1c9f26688435ecdde17e27e563c4d88420803e5744e87ea7935d8b7edaf95c2b0f0dd33ea9748e90d50687865a480eee8d7e415335cdf449e8ebe5cc77914140d4755870a12ededbfbac06775f4ae8dcc8c53be5667375ed4b500ef7f0b559e720f6a26772ecb7eaeebb8dcc92281d66bbdae579d993674c5ee24da91aa41926773a576e27ca0c18926e04f6589d8a5ff0023668ae292e43f1314ecdceee8790e7c1fa4969f0ae71b16c528f9d84f7b490deca88da9cbb2e8b5f666b2356ca79313dc48084cc9dd4d363eaa4e5698f69738f7513171259670dfaaea45d6e1c8d3ca1ecbcd3b15d200e5a0c484d6da45d1b4690c2cf6abfc4d1cb48b62f3d95951e4e3b314ecdbd22162e31701c2b16e1fb0db7c2b3c7c26c540b4044c90d8e32780b0fe32ef60fa32d976d3b6a901ac76d248e14fd41f183bda4154d97a9d30b5815b5a72e9142837222eb12831901669edb26c2b5cc74991d9a5441a7caff0baf8e9423a6cd51af4573b8349f1fd14eff0797b95efc83a3eeb4fc917d6c6d342c116e1cab28231c051b1da1a39174a744c2f236b0ac7636de83cc739a428594e22d5ab31667b7736179b40c9d3321e0ff49caa86f7d8bb29492ff29a45056434d95b7ba321469e311920b68ad4a7f480dec84401c94f6e618ee8d81d93a40d2daa53b45e9d76b12ec8ced57423cde8653d10e2d7b2a03ec711fbad6748eb1abe664b5ad2768f254fd3ff000b5ac9992caf7bc03d9cb7fa4f4de1e9f137663b1a40ef4bab460cd34df40e7b26e9ae99f00f585394c212b1a00a0d02be1388e17712e31d0531ad4a9404bb500894539adf95eec872e4c710f73b941b51ed813d040003dd2eddde5478725b23a81b52f6a48494bb436c0965794dda8c40f284d2d2eab4dbfd0ba1a5a90378440daee9436d44dfd90152f5230885240c02d3a6403f44948a5a1348509b054693369b45da57b6a8418d0425f08819c1485b4a101d9b4e6bc85eae57b6a841deaa734da0f64acbbb4511122934b0a51ca7816804186729e1949cd02d38104d20d83435bc25167b048859194dc761739d42956f92efe831ec7c9308985c69603adbae22d3a392185e09af942eb1ebb8b1e17430494fedc2e43ad66e46739f24ae243963bb212ea268aebd959acead26a992f96427dc55448d0d69521ce68e3e141c99893415104df6742292400bcfca4de53372f51255e90e9a08d712512ad362889521b09f091b48b121d0b2e8ab7c365b544c5c626b857385886961c8b1246ba62dfa2669ed2296974e54f878b54afb0a2d807cae065cd33ad52d17b80070b4387440a545810bdf54169749c379ab0579fb6b764b48b9cd4517fa640e7b4168256b74dc70c602472aab43c33601056ab131186815e9bc7e238c56ce55d6764881addbd944d4f19b2c2e279a0ade3c56d504dc8c30632176e78ae50713246e4a4736cac8386e75df07b28d8fadc664fd401f8571afe9ad0f792b03a8ff00e1e72e693c15e62bc8bbc7dffb89b5d11be3fecdf4196cc80003ca3fd163b42d609a05cb5904ec99a083caf7387970c882717d9e6b2f0e554c29090709fb526d5adcbf462e8569aecbdbacaf0140a6dd22983d046b92ee4206ca7a3b0a63b7a56bad0bca7b5de10680d85ae137693694135ca757b504828cdf51e9cdcac67823c15c07ac3457e3cef95ada68257d35958ed9a22d23b85cc7ad74264b148c6460903e125d1e486a2c7091c05cc04936846300956daae9efc2c87b5c2878554f3c95c9b133bb097240cc6137d2b441ca235aaad9624471111e13c30d76472da4e6445fd8a1290f1891c3785e6b4a99f96a08622a293996a88d8c90a437714c6c68cd1c2adb43a41e17b9a2be3ca31cc7d56e519bf09ae0550e29becba2f45963663db477156916a526dfd66966a273ad4d8643b69516d29fd1a21617cdd40bbfbca2c5a857771546d7123ba246e75f0567f8522f53d9a1ff001220704a3e3eaefed615131ceae7b2731e41e0a6f88b1334bfe2af77636906748e772aa2273be54c89dc7295d63a2c7f3a5cda3dd35b239c7b9416bdb4a4e346652291857c9e859be2b65b68b82e9de0d922d750e9fd3591c4c35e1657a534c25c0b9b61743c18c45100d002f49e371b8be4cf33e4b27ae29924b06da1c21399566d14bc21b8d82bd01e6df6f6477f3dd399402716f09813242fa1db876b4d1dd26de6d2a74147c90c28ede40a406b1cdeea563b2c72be6f36374d858184a2988eeeca4c3180cba09f181bfc2cae7d8cc87b4b1dc8e14a8329ac6d5d21e63c59aa0141712586bba2a3cd763c568b2c9d4f60d8d7ba94319360926d57b619df2f20d29ad8699cf0ac704bad8b39bd74032325bf299f996b288a0a3e6531d4157ba5b3dd6aaea4d18e126e5d9a8c27895a4daabd72aa8291a4976c1f551f5963cdd02a9aa3c6d35fb451034517d5a085282d412f72ea6b9766aad2489d8f94e8e4b0568b0739af6825d656377153f4fcbdae02d517d0a4b6516eb46d629c13c2b0c77070e4acf61e46e1c2b5c594822fb2e25d568e626f626a187eb1ed6a01d39f172d61b1d95fb4871ba462d639966b855c322505c4b974bb206046e0c01fdd4d8a5742ef6120fd1476cc03884f6b8b9dc1e15726df6ca644fc9d4677e2b98f7b9c08589d5c39f310db00adac50ef6528793a0b2625c404f465284bf21d2e8c4c30bb7552b3c5d35d2f25aac4e8de8cbd8d29d8f0358ded4b55b949adc457711f1305b08ec02b06069e3e10f67c21be4315901619373ec472d8f9e16b8a48a0afd2a29ce75f2148873232c3668a66a5a02ec85a961b0fb880ab018587f5008dacea8c01cc69b59b7e592e2412ba38d44a51ecd557fb348dc88c7620a4932da4570b3b1cef3e4a7991e6f92adfe2a4fd9ab827e8b1c874527268a9da361e1876f71682b34e7c80dd94e872270ee1c55b2a1b8714c30825ecead81938c1ad636b8e052bc8f7323dc63b15dd73de888e6ced5218dcf71683ee5d77588f1b074ddcd70aaee5618f83e71763666b2c51664f3b528e106c80566752ea034e6b5ddd567506b0e190f0c3edbe29530c9130b77754d1e3543b604f9f64c9b5392435bad1b19ad946e7016ab0d5d8448f2dd10e095b5d4b5a8964748d1438d16eaa0a645a74121f0b2edd6241cda9507513e3f712b3ff001e69ed8ea5b3472e970b19616775663223edf9473d521eda2554ea5a909cdb794f0aa5cf62bd85d39d17e6417d512baa74fe9da665630dfb2e971864bcd83455962754ea183ed8a4e3eebab8f355cb6d6ca9c5b3b7c1a0e25532269443d350beff00a2d5c9307f12753c777bc5feeaf30ff16720101ec3f5e574e39f4fdd6848d7fb3712748e33c10e843563ba97a218252f87fd95f695f88b8f9e435f2007e095a08f37075165d8bf856f0a3216d2d1671d1cc70ff0d9f99107bdce1f65a4e9ee87768f30735ee2b690b2363408fb78447765a68c1aeafc91386c0b22a011b685e6f64a16fe4c651d1e002f52f52f25dec6d9e0d4ee026da5e1426ca8d73556e9d03de6ec05cfe4eb2c8c8cc2c63a9ae34b41d77335b8b35df0cb0b92699a908f506b9c6c07762b87e46d9f2518b21ddba703df8ed73897123bab6c8cb6e38b7387659fd17588d9a7b64611fa6d51eb5d4ce9642c67fa2ba79b55157bec8a2f668333a9431c5ac369da5eb232e4207ea587c7cb333fdee34559e26af8ba6bb73641bbe0ae66379373b3937d16b8e91d05bb9c2c828d10f69e5739775f96bb682483f0aef41ea6fcdfeb7704f95d78f91aa52e3b15766b48a6da18a2179b95148c201e693455795ba324fb4c0d8a405ed8913c035dd36c1b0743e178b2c7744bfa269165140181b490b6c2780940f84761d0211f74bb116a8261536005b05a735a2d2d2f526411ed6a7809adec9e0a04136a568dbcda6b9dc1429250c639c4f0106401a86a11e1c4f739c01ab5c7bad7f139d8d2be0c779738d8b1e15b7e21f54bd8d7c1013bc8a06d71bc9d2b3f25cf9df197126eed65bac7be28d74549f679fadcd973ba699c4926f94ccbd5cc8d318eca11c57b2c3da41082f69f9589463b36c6292072cd63851fdce3dca90d885f28d1422fb04fc947d0f18ec87e938f8468318df6563163b5de029f87a76e374a99e4248be1436c81061977853a1d3cd7657d81a435c390ae20d09aea01ab95767a4f4746ac4da33781a7973a8b568f074a040b6ab8c3e9c0d20d2bac6d2846de405c7caf229f48db550a25241a5807b2b0c6c0af0ae99a700db0023c585b4d10b8f6666cd2a091ed331c002dab59a763b2360e28aa3c587d32adf1b236902d0c1be3f2b722aba1b46ab4b716f2afe098000f9590c4d51b1d0f856075a634020f2bd7d39314b7b399657b66b63cd606f2543cdd6a388100f2b2d26baf36038dfc05065cbc9ca04d15a659f26b512858e93db24eb1a8b256b8ee0b07ab8df21201e56b5ba3e4648bda7940c8e98c977023b5cacda27747d1ba99c63e9989c774b8efdccbe0abfd275c91afd8f72913f4dbe11ee63839534d82fc69c9a20ae762dd918766fe8befaebbe3a7ece838792cc98775f289bb959dd0b2252765d85a0008efdd7d0f16d57439a3c6e651f14b4101b5ef0535a693ad695d191ae868ee9c9385eb4508854a2d781052eeae11432091bad12ed059c9a08a383451da18f2a6d5f4a664826b957486f607774ab5f6238fe8e17d6fd29ea1796c66c7634b93ea5832e1ca5b2b4837f0beb2d6b448f3207d47669725eb2e867c919736220df069537d0a4b68db897b8ff638d8347ec8cd90145d534ac9d3a6736463b682a0190b572a553475eb9a92da26d829cd7edeca089ddd91e394572a9944be0d134497c2735b668a8ad940777525920a1caced68b9341db100384ac8092bd1b813dd4b8dc0155361d0038a47202f084790a7b002d20240c17c8b41bd1645115b8d49e22a3d9480c446c4084390f15a001bf08ad69f8478e007ca33600dedca1a43c583dc430505e6bc01c8e51cb057643d9ca57d16720cc92c0a34a43242df2a1869279a0149c5c49a7700390a460e4476a893f15b24cfa672b61d39a33def6b9ec283d37d33271216fd795d1749d2db144df60b0bb585e3df4d9c8cecfd6d264ed1b1190305b68856e1e00e005118d0d6f02939a682efc2b515a479cbad737b61c484f74a1fc206e3f2bc1f4add14682ee1cda4a087b81e42734a8903439a1780e5282bc9d111f2ecba7b013c2136111a9d2cd193dd479e31b0b872be571937d322991f232fd368683e1476e6ba8d1e50b25d40a8865118249016a8549a16563de892ec97c84ee28d04b5cbaa95449a831a08b51cea2e2ea0e5a163b7f45f05b34ecca8ef8a5e9b21a1879e155e1b1cfa37c153a78bfa5cfc2cceb51902ddeb45366e40dc79b50a0719a403e4a1ea2f2d91cd08da4c2e2e0feeba6a2a35ecaab8a49b66af4c87646d6d291a8c4df48db4764cc275345a999517ad11af85c394ff00e4d957372969187cfa04f0ab0c9448577ab62ba326c2cf4bc39dc1e177f1bf246baa6f417d50023623fdfe156ef4e64fb0f7a5a5d7b458d6d68d761e6b6214e215a45aa44d00eee56106a0403ee5e1a9b87f72c33c0e66478d26f68e8add7a303ba63f5c041a3dd6023d464be5e51bfc45c4fea2b3ff008c4983f8d2fb368dd59b7dd4dc6d4987925601ba8387f75a998faabeaad2d9e3fa11e3c97d1d2b175261005ab187318f1c90b9ce36a72068e5496ebb3c66bdcb97678d6df45724d2d1b9c87c6e376801ad2b2f06b72ca45ee5738993ea35bb9dcacf2c5956bb32f65936204a5763c6e14e1c2f45645daf64c9e8c6492b377bd226c899d831065b7c2cae665be191cc69ecae351d6b6c45a16667c8f59e5de4aebe1d52d7e4352d4e5d1132a47b9feee50dac08f2538d94d14175a2fad1d482ef435a36f62538389f2bcc1769e1812b2d06470794b0300368823042938f8cdae42594d24226f65974f6b67469dd28f3e4299d41d7d93a843e831e5ad54795135b1b80e2967729eedd7b8dab289392d6fa334a8f925c89d3663e524b9c4a6c59241e55636670ee88c90b9dcabfe1491a635e917714c1e6ad4c6e33a56f0155e151e56dfa4f163cf77a55642c538bdea2532e99969718b5db48e428ef8fb8ecba3f51f4518223930b4d7914b21368d21069a84a52a9eace8119a4fb33cfb163c246bcdf72ac3334e92069dc2957089ddc2be12525b4685a6b6123773dd16c15103b6929ed9147114901c13c3e9b414569e7ba25d041c42a482c52be17ef638b4fcdad0e8bd61938723637c84b7eab37b6db687cdda31daed30b699f4074cebf1ea1034ef04ad0eedd7442f9f7a6b5f9b4a941f51db2fb5aec9a0f52e16a18ec26766eae6caede266292e327d89a48bf6d84e09ad9239002c7037f0941e174d34d6d105484d26ba500267a80f95029209b97b70a24f843bbeca36a79c3130e577634977d6c3a473afc48d59b53318fbe2972ac6b7643483e55e7586a7f9acc91a1f76554e1c54e0e1dd79ecbb3949b0c23f6ce8fa4e4b9980185c7f4a85212e90955589a9384619baa958e2ce253caf397b93ed8cf5f47a791f132dbc154391912c9312e715a2cb2cd94b3b980364349b1b44932d34e6878163957b8dba16ee8c969fa2cf696f2085a4c6f7c6b36437196d31a2968d0f4eea992fc8d8f76e04795b16b8902d653a6f0e8fa840f85a904d57c2f6be194a58e9c8ae490fdd49dbe8216ea481dcaebf02a0dbfea943d01cee1358e36a20c568956125fd50c3ad7afea88cdf42ef20f74ebb280e77288c7f9a50543ed2129372473b84c1081c9e390a3b5dc728cc70a409b11e38e1576a6e2dc57f2412acc8dc141d5630f80b6b85088f9fbacf26576a45dbb76d2a0e3ebb088b6c8058154aefae701ccca90b5b4b053c0e693c95cab9fe6ce9636b413509e29e47b982afc2aa7f9472c70bbb4df4895527a35240588cce0af18a8701234d1e422decb21d12a07d395be26608e951b1c0047866ae2d5164392ecd75d891b0c5d4d800e55f606ab18005f2b9fc1915c2b3c5ca208e5723230d337d579d2f135261fee0ac63cc63f8b5ceb1752734d6e56d8daa3f8a72e1dd80f66c8d9b37f8d90d755a9e0c6458eeb0f8bab11e55ce26ae1c05b972adc4944b5346818e0094ff508e42a866a8cbef69e35269e2e965554e2f680d6cba82579701f2ad31b1e4948f21673173da2adc395a0c1d498da1b82eff8e7bea4cc96c345d616901c77385dabcc4d1e2045b452abc5d5e1d82885630eb5135a3dc17a9afe35da39d6727f45ec3a7c0c6fe868443890107da153ff00c431b5b5b828b2752004d15b3f915a4645558d93351c08083ed0b0fafe9f131c5c295d66ebe5c092ee166752d4ff0032eefc15e7bc8e6d5f474e8aa697613468361dd54af88557a6536205c40b560e999b7f52f4fe0d3741c1f28b94f429e3b2506d457e40f052c791c77b5d8d1c77b5d12e801693728c67e0f29a266917680112c9a4ddfca8a7200ec533f3346ed4093db21038ee8b1cbc72ab5b960a7b72c5f7412d9132c84812fa81406e50f94efcd37e53701c960877750f3b4b87318e6b9a0a7c7381cda911cad7728a7ae85d3398f54f42e3e4893fa2391de9719ea4e89ccd3a673a18dce8ff00d97d5f938b1e435c1e02ceeabd318b96d203073fea9675c64ba2caee9c1f47c8cf0f89c5ae69042f3722b85dd3a93f0b3172c9db1fa6ef96ae6daefe1ecda535ee8dc5c0734b9f66338f674eacbe5ecccb67368d1e4f2abbdcd2414f6494b1cab46e8dc5d43921496e534f95491ce2ad1993959e549aa166cbf872c1156a447382a8a198d77526398ff9950ebd17c5a2ed9202519ae1f0aa61c920f752a3c824aa5ad16a48b28dc111b28ba50e392d1c007b774aa4b6371e83b9d42e93631eabf6b4157ba3f47656a91892f6b4ad9e8bf872d8a9cf3cfc95b69c57677a325b93187d986c0d027ca7b6d840bf85bad0fa4c44d6931d9fb2d8e9bd331630161a6be8aed98b1423d8d00aee6360463db387939edf499034bd31b046d058070ad991b216d0426f17ca702ba8a2a2b48e44ec727b6c73877213394eddc24b0a11497d8df2948497657ad31040e238095afa48928a22ec90c75a728edb08ac72243e4cff00116970e42b1c7ce8dd18048ecb1fbc837ca34394fdc0071017cf678a9fa152e3ecbbccd8e71d8e14b3ba94ae63a81e2d5db01919df954ba9c0e1314f8c927a62ed365539ce2e36e561a5e03f22417613b134c3300e23857b871478e01ae42d57de9478c7d9ae324912618840c684591924d1d3012bd144ec9236b78568c89b89017bc76eeb8b3b34ff00d892dfb30da861164877f0558e93000c099a9cf165e5388e3c29982dd9185bacb1fc69333b9f5a2c20b07bab08661d8a8110e2fe511a485cc9c762463a7b1353d346430ba961b57c6304a5b4ba13b28323a791d9623a8e66c931d802e878e94b7a65f1da33c7ca61fba5758ee9b7dd77d234c4f5f1e125af37ca5da89621cd7009dea2125fa28d208f6bdd7c2b1c063e4781e14281a09579a644db0b35f34a22cda2f34cc03255d52ba8b488e4e36851b4d01ac57788d71e42f339174b7d19a515ec891e8957c0fd9163c238e6c82ae603469d487aa4b147038923b2c3f3ca72e266b229fa23c5991b69bbc28daa6a0c6c47de0fd963b335873677b5848e5463a84d2f0e71a5d0afc77fdcca152d9372f24484f72a358238e131aedd60f74a5a48e174231e2b435347c6c694d4e6b5c3b8487b94e8d899e69a29fb8fc215d14f24ed40b130b1483ca96c9a870a042d24a91d82ae480c665bdee6136a8f2ac1e55ebdb7c15073316cdd70aea24a3d114922a293d879089242196bd0349705b5be8752e8b3c1693c1f2ba57e1ae1ec99d2381e7b2c5f4fe8b36a32b046d3b6f92bb374af4f7f87e3b1e41bae52e2d32b2ddfd14368d0cd8ec9f1cb0b4511d8ac36bdd382291cf8b804dd00b780ed145479e30f06da085d5c8c585f1e2d7654e09fa38c6a1082f31cac3debb2b9c0fc3e8b50c23342e0496d814b6399d2b8f96f2f2c1cfc2b6d274d760c423887b405ccc0f192aec71b3d089493d1c1ba87a6a7d2a420b0d03f0b3e5abe82eafd062cfc473dccf757c2e1bace09c2cb7b36d0b5665633a9ed7a2fae4f7a64269e515a79400516336b032cd07238a0bde9a58f94fdaaa6c0de80bc16f65234ed6a7d3e60e6b9db7e0141730f280e6907b2b2b96bd117676be89ea41a846c69b07ea56d83bd977c2f9dfa7f5f97499d87710d05764e9bea28f53c760dd6485d7c0cbffb241d17eee4a1bb83dd15c428f25d95dad7da00412d79593eb9d546340e8efb85a12f2d05732fc44d48c8e7b2e88e164c8970adb26ce79947f3398f901bb2a640c0c6050314fb89eea6b25e179ab76cb174b44b84fbaed5b61e47a6152c249e54c63cb42c36c362fa27666658e0aa89642e937774699c5dc129ac80bbb7285715040d961a6bb710b5da2c424786b8ac96040f6b8537dcb51a7c5981cd7359b5677072b1348b2323a1e998ad8631414c26bbf0ab34dca7438ed7cc6e8281ab753c516eda79f0bd9d7934d34a12c96fd17ce70a3c85e6bbc5ae6199d659e273b6f65a9185f880f6383646d94b0f2f4b7a2b506ce8ee3ed4d61551a26b9fe2b1ee6856ec7785d2aac562da187872f17d04969879053118d7b8da7c4f27b9519c5cdb053a37150089b6909bec98d710394e6bad06c8789aee112378a42772539828a6d7400ed7774dc987d686a92b3b9451c8a4088e61d5fa03e612b80b2b936a3a6be199c1c08e57d339fa645900ef1c90b9cf56748b1fbcc51907c10b065e3b92dc4d145dc1f671b931530e3d0ecafb50d1e6c304b9a682ac7168e2a9727725d33a50b3976880e880be1467c46cab521a7c843fcb879e13ab35ecbcaef4cd1a5e8daf6f34ae20c1693451ff00c31bcf083c948748a88e520f214e8722aad15da6b424fc8edecab94e3245d09341e1c924f0acb1b3b6559558cc62d4f6b083dd659c62cd51b5a2fa3cf2d777e14fc7d4c83dd676204d72a7436d3dd61b288b342b8d1c7a913e5498f50faacdb25238b47648e6f36b14f1a25b1b4d5c19c4f00ab1c7d42469fd456534ecc65d48efdd5bcb9f0320dcc7f216678d38f7119d89fb3518dad380a2f36a58d71c2bdc56099acb49e0a94dd69bb6894d0b2e8fb2708b372cd778b2fa48ed71bfe75cf67d688ece080fd788eef4ce574bec8ea8c4de646b05f601b500ea22c59f2b1b175139cf237708799d45244f635b0b803fdcacc3f16ec9eec653764c6b89bf93a9a5858c634820795330ba87d72773e8f7a5cbb3fa865646c730ee2eee10f4dea59038bacd9e085ee313fe0828a3ce5efe49b6761ff126bdb6d7584e1a8ed1de973ac3ea6611fac82076b56f0eb71e4c43de010ba4af4609566bbfc4af8dc1786a000a2e16b24fd4c8ecf0579baa1b16e09be4895f035a73fea8673c171e5668eaa7613b94476ae438904a8ac4c1c0d9b336bca43a90beeb1bfe38eff3da51ac83ddc8f344e06cc6a607f704f66a8c23b858676b1eefd452b358f69f725e63a81bd66a4d03f505222d5a303972c03359ff00cc95dac7c3caaf6c68c3674176af11141e0211d6a0634dbdbfcae65aa7574780cdae75f1ded6573faf5ee2431dedfba12bf8a1fe1df48ec99faf60169dd334bbefd9731eb4d631cc526c76eb15c2c565f53e464bced908fdd54e7ea72cac3be4b59279529f48ba9c569ecaa9e3daf26d462fa4f93209b25417cf652422dfb367ae896d90a3327f04aad129f09ed98da2eb2c8d9a2de39ebca3c792154326348f16e79e0aa25523442d2e22ca17dd58634c5d4a9f1b1defa5a1d374e7bc76582f5148d7437264fc381d2f6b5abe9fe9dfcd3dae7b6c051b40d277b8070e5747e9dd3440c1754b8d3949cf48df286a25c687a5331a08c34500b4f0b63d802cee5671c48c35a69023ea46b406b9fcaf57859708c5459e772f1672db46c07d293aac7caa8d3f52664b43b72b78a46c8cf6aef576297a385653283ec191dd3587928ee67080ef69b5619da1fe12526b5d69ea134089a5eb48eeebca0c2da569f94d4a136c50ad17d91182bba0b5db7ca34720f2540a3e29dd569b14c1b203f08124f5d904cb5cdaf1eabeb45dc768d1e36603c92859e58f7070f2a8dba83a21c76468f3ccdc12a958ee2f6551a7b2ce1cc6c2ddadf0a4e1e43b22468015546dbbaee559e09fca8de69556c569ebd963868d869d8c1ac0f3de956750ea6591ba16b4fc5ab7d27263c981ae07ba4d5f49865c773c775c384f8dbff2144acfa39c1987aa0f376b4384fdd135578d3dbf993c7956ad8c4200b5d7be71924915aefd12e37d0493e6b218c92531b2465a7dc146ccc33951911958e108f2fc87857df6536a1af936d6b953cd3fadee3e51b50d3e585e6c2866376ce78a5dca6bae315c4d49223b8db8da42d149aefd45783a96d48b120b0c768c71ec764fc46070b560cc7b67659ecb34c2fa452c91ede2931b19255acf8e07841108ae1342c4c09f4478ac156b8737a7c95004547ba931349156525a948ae46b34ed4a3616eeec42d262ea3006825c29607158e20736adf1b70a04d2e164e345996d9b48becfd77d224c4b37a8eb1919268934a63f18c83929d8ba1be77591ed494aa6a5b7eccbcfeccf0c596771706b8da910613dadf734feeb5f069b163343768bfa85e9f0a37dd347ec9de7a7d2f40fe4ad992dbb0d52931b37529799a7b98e2430d20c2083dbb2b3e4525b45b1be3240dd8e6e87648fc6686f1dd58362245d21ccce2aa922b3b0c67d952f8dc0a7c716eee149f481448a1aecad761a2320422af0943694a1092131f150eeab53db1b647720c8d0e6d233d33d3b5645e83ecaf9710922bb2b5d07a626d5670c6b0804f26bba6c7173cf3caeb3d058b84618fda03c0bbf95af1e4acb142457327749f49374ec66b5d1801a6ed6b43363406f002201b5b43809879f2bd2c62a0b8a1102ee795e3f09ce0863bf74761034438908f1cfb4764c202f760a6f5d8e874fb276169f21729fc47e9c68719e061e7b95d4f72aad7f4f666e0480d5a4be3f247b137d9f3a6c2c76d23dc9e00f8563af609c1d41ed1c5f655edb26979c9a69e8b130b09a3c0529a43b828b8ba7c863de5bc774d960d85669bec9268243135e689efc2bc1d1debe199a376e20580b351e488df449e16c7a5faa636b463ce6bc0251a9252fc80d75d1909f4e9219087b08a5a9e86cb74396232fa1e02bed5b46c3d4b1dd363380711d82a0d1b47c9c2d4dae702005a9d7ab2328be858d8e2f4ceb9148258c3aecd21bdc4941c27bbf2edddf08a392bd5572daecb1f60e52031c7e02e3bd71389b2a5e7cd2eb5aa4c31f15f217782b87751e60c8ca791c8dc573bc8cf8c78035f9229d8691e21bb951d86cf652e114170e45cc90c76c6f74e6ce7e5447bf920a58dd6a970fb2bd1304bbca9d8d3b583955cc1ca90d68aeea99c535a068bbc3d4e08a505fc7d96930fa930c340b24f85800d70e47fba34392e6f72923ca0bf0223a0c9aefaad2d0f01a54091ac9cd936b2b1e5bcff729f8b9ef69e4958ae85afb6cb5348b33a599dc76b784d3d3e19ee2c57ba2be2c88ec916a7e7c50c7093dd2c22d437bec493fd0de9e9a2d3e10cb000e15ec5a840f75078b3f55cef3729ec2e0c7103e8a965d7b3319e4b66758f92bbb83e5e4ab506bd15b7a3b5b4b5dd9e0dfd53c340ecb9774af58e64d9cc8721c3d327bdaea3148c9181cc3b81e57a3c6cb8df1614f60251ca460e3b5a942012f7e10350d430ba7b4e933f51943228db75e5e7e02d6e4a31fcbd138b09b0fc2f0b68edc5f7f82b3bd33f89fa1752650c46093127269ac97fb90ff0012facc74ae98d871a8e76482d8c1eed1f3ff00a2a65757c5b440dd45f883a274d64371b3262f9ddfdb134bf6ff000a6e87d5da2ebec2ec2cd6123f536425ae1fb15f3abdf2e6486799ee91ee365ce3c92a6e9f23e099af6b8b40f834b92fc8ca2fb17968fa7a3602db1c8f90782bdd8aa1e8dd5b1a4e9fc6f5b2a3de073bdf455e89229412c918eaefb5d6bab56446c49a7a632d31db43bba899783164021ccbfaa93eff000db446b3d9fd5735aae4d3f61e26075ee8a8e78de6268b3cedf95c67ab744c8d1f31d71398ced63b2fa5b372f1e3639a1c09ae1727fc42304d8f202017159b26b835d16d12946471dfccb9aefa77468f3be4a0e5b436477d3850dcfa5cdf8d48eac66fecbc87520cec54c66b0d22880b2ec908f288d94fcaae58c996a99a476731e2c5211ce0d2a95af247ea2bdb8fca458c9079978dd4c0097fc45a551879088d90853e043c6d65c8d4e80e5199abd79543ea7d528912bc7459f2b340cd4c93dd1cea6edb41cb36d9883dd1ff003208eeaa78c8babb765e47a9baff00529235373856e59a64dcf75260949f2aa9632f65aa6cbe666bbb93ca7ff89380e5cab19b9dd9498709f391c1b59a55c17b2d8d8c9714f2e63bd361fdd1e7d1f3bd12fe48fa2d174cf4dbcb9ae2c147e56dc684dfcbb98582abb52d18b5427db12dba497471bd32a3d4236643b6b77736bae60606265e23227c51be27b7b782b19ae748973df2c4d2d703e020e89afea5a187e2ce77b3fb77785d4ad570fea722ef924f6ccc756e9b91a2ead918ed7ee8da7db4aa31335c643cd2bfea8cc973727d77d1beeb3b343fdf1d5f957c65b33b45a1ca7306e69e5323ea29b19f4e2542c2cc6b9de9cbc7d5132f0c4beefaf055d12ad16dff001738816e207ca233ab4061a7d9596762c91dd82e6a8afc779bd86c1fe42660e1b3791756b5c289088dea48dc68b82e7f043217517105493064c7cb1c4fd14405046e5daf435c382637596b8fb5c161a49725bdda6d2479d9319b2d348393d0782376755377b93c6a6e03f5775841acc8d3ee2517fc75e6b914148dad07e346d7fc5dc0f74c9b5a21a7dd4b231eb824ab46933d9236b726f91e81186816bbaabde4fbad524791bfb94ed564690769b552662c3dd67d7234c3a45e4730f90143cb97dc68daaefcdbbe4a363b25c97546d738fc23c35d96a96fa40647128241a57d1f4bea1380590388f94293a6f36234f85dc20b220bad972a272fa299ad25118c36ac468f90091e99458b469c9fd07f845df0d7b2c8e349fa4418d842b5d3a004d9089168d3023730ff000ae30b497b6bdbfe8b0df931d74cd74e24b7d85c2c4697034b5ba563b43003d8aa7c3d3dedab0b45a644e69008ecb819590f5d33a9450a28bfd2cc58c471cad2e2eaad8d9c15998a271f0a6c18f216f95c7966493d9a1c63f65a66ea072072552e53a4a25a4ab08b09f20e6c2910e981c0b5e6d68afc858d692ec46ab8a09d259790e706c8e27eeba3600fe98592d174c10c80ed5b3c660633b2f73e127375ee6793f2ce2e7d05ae108b2d1bb84c257a1f679efb0263fa25af0896983ba608cda9a41452136d41018052869f09e00f29cd6a832062c2735d49c5a99545009f0ab6571ee523e4b40a78340a6fbbcdaf3bc51b34829793c29fa7c1c87d2838f1973e8abbc184fb401c5aa6f97189345c69985b8efab4cd54988ed6f015be016c7157d1556b4d2e9010382b8d5cdcaceccf26dbd0ed2b587e1900b8968f0ae0f529c9618eb82b26fc79591976d34838d90e6c81ae255b2c4859f97d944abecd7c5033fe61fbaa8d63558e1b0d70b0a74721fcbed079aecb1bad4728c87920d25c4a54e6f93f43d7c762bb5e983fdaef2afb49d75e5a05d8f36b104ab2d31ef8dddcd2eae46241c0bac8a4b68d4ea72b72897342a4ca89c1a78a57186e6c9c1213354818d88968b58299f097032c2def4ccac829dca4634129d3b4ee2524365c000bacbd1ba0d68b2c2889e02b76c258c1685a461978b7052b2c885845ae55b6729f14573915d9045d151c3693a67ee3f55e60b57a5a4483e8688b715361c7e070bd8f05f70ad71b19ad367b2a2db7424a42616239ce0005a4c1d15d2341a0a0e386c3c8015ce26b0d89a005c6c9b6c7fd4ada52f6498346673b80a52443163329b4a20d59f258ec0a14b393fdcb9ce336ff002660947f41252d91d62f84e8d8db04a031f61181f6f09a49a2b5a5d0ccd8daf690d0aa19a71de411c156dcbad78b40f2ac858e2b45337d91e3c5606d2839d88eecc0adc47e53277318df7268db24f65d5cde8cd985cce1c1162889fd23ba932ba3924a047c2978788e7bbd8c240f34b5b9b6bd177cae2bb21ba22d6107baae9a421c42bad49f1c4d3e1de42cfe4481ce2427c7fcbd97d52e4217da7c4d2e0690d8cdc452b3c0d3dd33f68f2af975d17ffe8845c59cd2bbe9deaf9348900945b6d3e7d01c61240b215364e9d2301dcd28c25c5a7f68adb6fa675ad37f10f0f29a1ae907eeaff135ec5ca1ec782be76709b19c1d1b9c0fcab0d3ba8350c536c9de2beabab0cf925b7d8d1497b3e8474ec95bed70fe534023cd85c6f13afb363a12107ecb55a275ec790cd931e568abc945bd4ba0b8efd1b924829a5c7b2aa875d8246076f007dd0a5ea3c5613fd46ff2b6ff0026b6b7c81c59705c524acf5232d27bacfbfaaf11bff519fca61eb0c56ffd667f295e654bec4e0617f1074b31cc26635619a1cd9055d5aea9d47a9e2ea38ee0d731c6be56231312239637b46db5c6c8b63cf498c6b740d3bfc434b0cf4c0716f06954ea1a0e440f70747c0fa2e85d38dc78f158181bc0a577260e16533dec692b5db810b609c1f64706d6d1f3ee56139b23bda47dd476b5f17209b1d8aec9ab747e1ccf739800fb2c4eb3d2cfc5738c6d710b9d6553a97e5e85db8becafd23a8b220a63de681f2ba174fe6e3ea2c0e7b5a5ff002b97cba7c919ec415a0e96c99a29845641b4b8d6a8d89fb1b6a5f47546b368a6d50440d247751309ce73059b2a5ed24f75ec20bad97c61b467babe730e0385f82b89649f52579b26c92baef5e645e33e30e028795ca21c674afb02f9a5c5f2762e5d8afa6478e12394473c30500ac0603f670c3fc28eed3277934c77f0b931b13f61525fb203de5c6fb27c72060dc54dff0005cadb62079fd9032f4e9e2c5748e89c1ad22cd2b134fa0b9443324045823b2b6d2f11b9307aa4df3447c15946c8e6762480b49d2ba908e49207d164bf3e0acf915b506e266b64d2fc59ad9fa4a0cac60fc476cc868b2cbe1df659fccd1e6c436f69da7e8b4b164ba2f4f6920b7f4bbe8892e7332a46c529058efd42bb2e3c32269e9996192d74cc96240d7b8ee3d94f8e28eb808d260322d427646e0e8c76fe13646362228f756ce7b63cef7f44ac4cd38b5b4d05365d58cb1905fdd54189d23496f8458344d42566f6c2f2dabecab55727a5ecbea9392e80cf2b9e491e5417e03a77591dd5d7f836a2c1eec57ff09eec39f199ba685ec1f2427e52afd21a50932ab1b4bf45c1c1c43bc2dc74ef53c58bb20cc9581b55cbb9585d4b29f25c7112daee42ce7e4b224c9ba327dcae9605f3adb9365714d33e97c6963c88daf89ed735dc820dae21f8b1d42fd6ba8a4c186427130e98d65f05d5c93fbdabce8deae9341d2a48f22396488586bbbed34573ec827333279cf2e95ee793f366d75eff0020adaf48d8dad11310498b99066424892173641fb1ecaefabb5397aab59767ca490236c6c69f007fee4a851c2edcde2fea8c222de573a59124b49944a7a21b309cdec0952f1714b985ae6fd6d4fc48c3d84d2911c205d058e790feccce5b22081e19b048faf1ee3c2b5e9fd5f2f47d560921c9907bc35ed738b811f62a1c8ddbca4c1c693333e1642c748ede0bb68ec11a6e9f2e986be4de91dcf235366247bdef038b594d6bafb131777f54123e0ac7f566bda908df0c70e439f5400695cc72bfc5722721f14c5e79da41057a5fe639748eac6a5e99d2337af7f373d46f70fadacd7516b2729c7fa9b82a28348d747ba3c1968f634925d175d703bf066ffe951ce4d765f1a944a7ca7fa8f771e6d437315cbfa7b56165d8337ff4a18e9fd4c9ff00f629bffa4a58c92f65dc9151b5383b6ab39741d46316ec4987ff00ba5463a466827ff0b37ff414ca717f63a688e25e12198a9034cca00eec6947ff00ba535da6e47ff0241ffee953700ec136729e26714f1a74cde5d1c807ff00294f6e13abb3906e018b63048084e6b93c6291c109f1e292470ab728972635ad73bb22458f248780559e26083c502af3074c8432c816a89dd145908f7b46659832f6687156ba7e85973d6c8dc7f65abd3b4c8b703b015b5d134d8f68f6803e8b1cf237d23447660b4fe99ca7380730f3f45bae9efc3f76400f91db3cf216a70f4a803da41f2b5788dc7c6637f4f03caa151c9ee4fa2e454e93d311e234022ebcab19f06364645052e7d4a20cf6b82a8cfd55bb0869e534ed8416a2155b9329750c26d380ec564758d0daf61796f23b2d5bf29d2bb9ba2852c2d9d9b48428b65263595ad68e43a969ce6121cd34ab62d3daf690010ba86a3a235c0fb45aa67f4f88e373c339fb2db8d952959c11cccac6e11da39e67e8ef6377c7c7ca8f8990f86e29c173479f85b8382ddce63da3f755fa974e35cc32c2391dc7caeb46cfda39bbeca96e382cf51877b0ff00a28b3e8ce9bfa98eedaef852b144b852d576eed2b41838506a641c77064be587ca3c88cc78c091ae1eb30b0fc84c984b8e6c02f6fc85d25ba1b24608f2a2a278ba51b23a0676c2e9711beab0f25a3ba75242e8c2472c33b6cd581d93ff002b14adec294dcde8ec80e73a06ba378eed702aa0c7958b23a2983e370f053f24c1a6859b4a888a214193450e043490a63b2e66f7163e4a28cb0e038e50d226ca4934ac88bf4f3f64195b9309f746ea5aa826864e0d052e3c3827ee4151a436d9cf662e7ddda0381be42e8b91d338b3721a2caabcce92da2e349ad0f199908a1dee1c70ba074469f88e91bea31a5665fa1cf8ee3e6948c0d4b2b4a977b58ee165ca8ca51d45f66ac79c79767d05a368ba77a5c44ca44c9e8fc0ccb2d8803f65c8307f12b320006f2085a8d3bf169bb0367e5c7c82bcb5f8d93bf47a0aa70fa34cff00c3cc7dded89bfc28efe86c789f5e984987f88f06410db038f95630757e2c9e5b67cdae464bc987ad9beb70210e8a81c3fe5b6fecbc7a398cfd31b7f85a3c7d4219e3de1ec03eea4c7911969dae69afaae74b2b263ef659d7d1998ba5db7b762b4c4e9501b65a052b58ded2eb0a6c39003082adc6b5d8ff003299b6bd1571e8cd8456d05488f083450685226ca6b79240416e732ef72ea2951169332b537dec7b709c4d06816a6e0e8af95f651706789c0124157fa7e563b3825a17a0c1c5c797e5b39b932b12e84c1d20c62c8539f1fa6c443991134d78a429678deda0f057a8c7aa105a89e76f9ce5fd90d691b7ba4349a00dbffba5155c2e8c1fd183623b84c26b909cf3c7d50fbf0ac0762eeb480794de0795e0f2a074112ef20784c6bb85e278ec805046bc38f2bc5b6a36e20a346f35c85110f86cc2c24d504092121dc26fa8ef949bdc4f2579c8c1a37f126634346d5c62c8d8db66952413f34a436579e167be3cba15bd745cbb542cf6b4d84fc57bf2a66fa9cb556e341248e1c7056874fc40d03e573eee35ae8cf2d456c93958cdfc9101bcd2ccfe4643917b0d02b758f0b5d1ed78b0932f0626c44b437f85869cbe1b464736f65361c676805333f498f258ea659f952a28c871ae1486b8808fcae32e51288c9a66423e952e792e040b538e80c863040ecb46dda7e14818ec7b436bba7967d8fd964af7ad18cc58248df4d04ab37613a7869c0ad0c3a640ce4d259e38a2aaaa55cb3549f48cee6f7b30ba8e88591178055669d8f5901ae1e56db507b1e0b455159e9710e34bb8fcdae963e4b9435234e3ddb7a65f44c8f1e11d8585499f335f2385a49b507b9bb6fb2812496e24942aa752726697b184db948c765bc28ddf952f15e0005689ef4345345ac2c000e14b6481a1568c8a1c23c53d8b2b04a0d892458b2427ca9109239b55f14b6294c8391dd659c746765a445b495ceb3dd0a20691d8c1e563968c929687c4ea14a5462c28cd85d6a642dae1679b450ded8d2dda0f85066caf4c9dc55bbd80b08ab59ad66322eac27c7d4de99670db5b1d36be23340855999ac4b3b8edeca04cdb759b48d0baf0c7847b37c295a2562641f5773dc7bae89a66a5a68d2c189ecf540e5a57311ed7774564cf8c535ce03e8559c527bd15db4f25a45d6bb90c9721c5a6efe3c2a570fdd78ca5ddc93f74c2e1f2825af459456e04bc5682f016c7448a38d8d73aacac4e34e03ad5de1ea8636000f649be13db34c5a37044207354a1666163491b8d059e7f50117ee51a6ea17969a71a56d990a5ea20714df40753c78e27380a5521a012025cdd53d526cf2544c6c92e9b9e4258c1eb615534b4c9de9170b229131dee80f054e8a0f5e0b6b507fc3329fc3237154c64e5d07e29041aace06d13380fba23259a7162471fdd473d33aa4bfa21773e55ce8dd25ac462df1920fd13caa9eba1952df440f426750e495231f479f20f65b9d27a4249003347455ee374b32170a685a31b02c9f73f42ba25b30787d265ed209254dc3e8b8e276e7827e02e890e931422b6845fc946d1d82ecd781447eb615448cb60695f976d4775f0ace2c694b7b90add9044dbecbcd0c6fc15abe38afea5d0a348a87624a7bdfdd0a4d0bf34da70b5a00f880fd21158e07f4b420ea8c96991d0becc84bd070cad371827e50f1fa11b8d207b0515b86cce671b4148e9dcefec01551c3aa2f9240544514d87a24b18a2e0a6b74a78fee0a6b43cf28803c05b549a43fc69196d53a1e0d51e5d29bbf0a362fe18e9b00fd216ca9e784be9bebbacd65119bdc96c5f8d1961d05a7b3b301fd9119d19a7b057a4dfe168c40f3e52fe58f93ca558b5afa22ae3f452c5d2981b68c6cfe1633aef1fa659a46a38306a18ccd41b1ee6c1cd9e7f85baeaac9c9d2fa733f2f11bba686173983e0d2f979b03b50d49bf9a99c2695db5d297793ffbacb93084749228bb517ad1124f6f079bf212e3cee8646906959e4687918b38872699b4d3bebf054e97a6637e17af8a1db9a3969f2b9d65d5c7a66695b05d16fa56b6dccc70c7507c43f942cbcd2c9b7b0917c10abf41c19048c7b810d7f01586b7a74d8f2b1d541d4572a55c158f4513e3cba018f971b3512fcb7ccf888036c7deebeaa749b2625f138ecf1bbb85572c0e135d7c7fb29f88df6b8216eb5b416d30f8b248cb37ed5d4bf0ab588735efd2f32363813ba124720fc2e471b9db8b6f8b5aae85c99713a9705cd344cad6ff002a54d46d523452f52d23bc3f44c1dc6a168fd9677adb4ac387a6f529fd1697c70db78ec6fbad83da4934b39d72d737a5754e2ee1aff50bd45f8f075ef4756493833e758a12e6fb8d93e7e54ec0c38fd401cd06f8b4adc470da47c052a18cb5c2fb8e42f196d9ef470a537b2462e2c4c8f3b19ec25860739a3e1c08590662f725b5f0b5ec73ce58601ee7b0b7ef7ffe154e762361fb8ab4f4dda8e8bf9e91030a00670c3e7b291361b6c8080d94c3235c3b8700ad9b092d048efca6b26d3e4516b7b22e3c0d60aa456c203913d3368ad8370bab5439ecad4bf445cb8db1c5ea571563eaba97e1ee9e323408f50ccd3a1c799fed66c1cb9bf25657a47a3327aa73d8d7b08c289e1f3483cd7f6ad47e2275c63f4d639d2b4c2c33ec0d2e6f6880f8faae962fe10727ecd98cb8ae72f442eb0ea2c2d203b17120867cb3c17100b635ce592cf95ab459990d89d2070ba1408b55b8f92fca9ddbe479dc7bb8d92af3130a474d106f671002495f284d6df64964373e8eb4dd1b033b063986246c739809ae3c28ece95c520ee6ff00aab48276695a3324c92d0d8230e713f65c7755eb8d5b50d65f978f97243135c7d3633f4d05e8259508569bf66df9d462b933a44dd2986e2383c1e533fe18c160fd02d5974b6a126b5a241973347a8f145c3fb958bb1afc2d95d71b22a48ba32dada334fe9bd39e39881483a534d70af418b44ec36f86a418b5e133c6458a46624e88d325ff00a607d820cdd11a73dbb763457d16ace3f7e0a1fe5893c5a578a9a1a33d3d991ff8034e99bb1cd007d905df85da61076861fb85b4fcbbae82618a469eeb34b119a2372396eabf846c2e261019f6550ffc28cd885b0970fb2ed5ba415b8129e27dbdda3f854cb1a64562386b7a0b50c67d061fe14a8fa5351c7ffa76176773e271b742d3fb21c90624bff4c0bf858edc49b2faee48e4ccc19f140f52323eaae30351763017656ea5d0f07218416ff2a0cbd258c492ddb4b9d678fbbdc4dd0ca8fd959075113c06d152ceab9992d0180a998bd2d1c66eda55be369f163b47b5a554b0f25f5265df343450e30ce7bbdf63e8a7fe45f21167eeac65635bfa5a2d00bdedf0b4d785f1f7262fcbdf4409b0dd19207280627b55cc6df57b842ca6c71464d2b2cd416e259196ccf4dee90eeb0a4c5821f07e85127c90fc9e0716b4ba6359263825a12781b9cf2e4e464f29bf8fa39feb9a23839f2319455363c8e84fa73378f95d7b274c8721a46c06d63b5fe970ddce68af8a5ec275efb479a8cdae998dd4344872d9eb43424ff75598304b8994777b5e3b10ae9c67d39db24e5b7dd176e3e5807807e550d34bb2f8cb65f685970e7c6d8b2ab77872d3334d7e27be101ec3de973981cfc197924341e16e3a77a9dae6886670208aa2b3f3d32c45abb41c1d4d8373035e7e02a0d77f0d31b35af6be10e1fe60390b718d04190cf531df4e1cd23c59be8dc7931020f94f1985a3e76ea0fc33cfd3438c173c43902b90b1d3e0cd8af2d9237308e390bebcc8d37073a326391ad757621731eb9e92c578797c2d6b8767378b57c64995ca3a388c71c83c2958ee91878b0ae1da7b319e63b0421be063470022968af9006673e31cbad3e4d69b54ea4297137b0ec2a9b3b1a665d1e4229057ecb19752c72fb34a04d2c32b8fe9fb2a691990f356549830267d1dc5494078cb43e6c68ffb6904e13bb82a7b748c922cb94bc6d27201b3cacce122e57c97a6529932f1f90f3c27c5aee6c26cbddfcabc97479dcc36d1fc2a99f499c388f4ae956e98bf68d10cb97ec9f89d739b0d3448efe568b4dfc4391ac3eab8f3f5582382e6121cda4c18ce71a048592df1f4cfda36579b35f6760d3ff10e2750748495a3c1eb0c7cab21e38faaf9f58268f96b9dfca347abe6e370c91dfcae7cfc2c1afc0d75e7ff00e47d0aeea0866b01c39faa3c198c78e071e17cfd0f55664541d23bef6b51a57e21ba18dad73f77dd629785943bf66c8e6d6cebcdc9945ec791f448357cd86e8b89588d3faff1e76f25b6ad23eaec291bcbc0293e1b2bf5b1e3657234917566542e21e1c5486759c83bdd059fc6d5f1323b3da4a94f6452c7fda414619d9107ecaa78d54fe8d2e275932676d2fafdd5ae375331cdaf51a7f75cfc69f0b5b61c45fc27370666731caeafbae951e7e50ea4629f8684bb474a675045256e23f95246a78ee6fea16b973df96d14d91df74e8f54cec7166df4bb3479e83f673eef0725e8eab1e44531a6b814f229737c5ea8c887697b8056d8bd5e4fea7020aea57e4e99fd9ceb3c5db1fa3640a7822b8599675435e4765618dad4727901698e4c25e999658b647da2d5d1794e8da7e5478f5089c3f504766431dd9c15ca450e27c1bb894ad77d1252516b85bd9ad4d8688d95638a0170b55d1775618e45859ae0365fe1002ab956d88f0ce480b3f8d29681454e64eedbdd71edadc9944d6cbc8f39b1f9b4b2e7ba514d1c2a58e436a544e58dd297654aa2635bcdd2f10918fe1789e5214db1498a05152237504101158cb3491ebeca5c3ec37a8e2146c8def0e6d9535b18a4c7c75d87eeab849266768cbbdce6e4806f82adb3309b938cd786f21335080324dd5ca9d88e6fe569ff00eab64ec7a4e222969f4623360f4e522bb28af6f1d969350c48df2b8b79b55e704b9c46d5d2aaf5a5b3b154b6bb2a36fd116169055a37477bbb0456e8af1e0a796443f65dd10d9748b1836a7c7a2c8079451a448c3c8b599df0fd88d0dc68816ab3c6829bc28f1e1c91d0a56d878afd9642c1758be8c772d0b1c740052e38c002c248e037d94a6c4682e7ca673671930428764e63e8af3984129b493d94f19afa2435f629526b316eb5711b7eaabb556d03653d0f53e8d35a9bf6656481e5e401c274784e3f652cbb6f6169f07acf34d8c9f8e17639bd7474a2a5a0034ff2531f87b5a695c63e999f90ea18eeafb2b2c7e8bd467161845a5529ecba14cdbf462fd078b0bcec691adb683fb05d3b4ffc362f6ee9c957d87d05810b69f183f70b4c23397d1ae38d37ece291e1cee162379fd958e269d9728dad824bfb15dd71ba574981bff219fc2990e91a7443d9037f85a3f89297d85636bd9c361e95d4f2386e3bbf856589f871a9cc29ec2d695d9db0411fe989a3f6440e6d50681fb2b23e3dfec68e3c576729c5fc202fe653c2b4c3fc25c0c67ee7816ba28dd5c2f085d2f7eeb5c71125a65ea11466b03a334ec7e3d36d7d95b47d3da7443db130d7d15ab305c7b22374f70eeadaf0a097a03d15d1e9d891fe9899fc22b23880a6c4d1fb2b16e0fca7b705a15dfc68814922b9ad737f4b40484484f656831da385efcbb55aaad2d014ca8732427caf0c67bd5cfa0cf809444d1f09d57a039152dd3cd27334f24ab5d83c2f6c099450bb657b70183b8e5162c603823852f684a8e92200fcb33e17bd0011d2134a6d20021004be9809fea05e06d1da20d0c1e025d8957aca04d3019391060e3cb9394f6c70c4097b89e005ce733f1d7478657371f032670d756f27683f508ff8e5939f074e62c38c76e3cf386ce41e48a26bf95c3258ab9f85cacccc9427c519acb38bd23b9cdf8c7d2f9ba64acc96cf1995bb1ec31df7faae17993c3239e601d8db4f9086f75c658ee41519b6c24fc9b3f558676caced99ec6e5ecbb8b50c8d4e68dd934f7b408cbfcbabcfff007f0b59d3fe99718481565a561b4fc863246ff68056a34bc910e61a77b49dd7f2b919d0724f473aea9fb3470e951e066ec74778b39b047fd379ec7f9e158cb898da9c7b1ed69919c16946c7c8872f16afddd88549a999f4dcf3246f27c8fa85c9a2e949ea40f51d99aea184616a5246dfd1c10818991ee0dee0a95d4328cedb9238e28aadc0e5fcf2072bac9270d922dbf45a7a54fdc057d51b133a4c4d4217c6e20b5c29344836f0bc2376f8e5db61a6d6652d3db1ebb9f33e99d03578b5cd2a0cd848dd234070ff0029f287d4d8ff0099d0b3e2a1cc2e14b8b74b7e22e674935d1c81b2e33ddb8c6e3c83f2a67567ff00c4107e31c7d230c35f2376c8f94d81f40bd2519aaca78bf675d65425168a0c5c6788db6470d1413db0dbebb2668f9c33b0d931018f77240528d075daf176b6a6d1c79f523cd8cb278e402f673fe8b31aa64112381faad489b82079593d599be779ecaec4ee5f916b96f5a20ba60f9a304d806cabf867f523f6f654b87a717b897f0ae716211b289a03f95af21c5ad212d96fd0fdcef04feddd5df4874a6a7d579be8e34663c66f32cfd835b7cfeea4f48744e5755e6d36e2c261b9273c71f45a6eb2eb6c1e94c03a074c86b76b76c93b0ff3cf929e8a16b9dbd2ff00fd2da29d2e53f44bea6eb8d2ba33099d35a33db24ed6d4d337b34fdffcc5707d7339d9f9f239af71f71712ef250f55d45eece717c8e7b9c6cb8f7b28d8ba44b90d12c66da7b92b5ca4935397ff000b2db39761f071b6b7d41eee7bab71ab334f6076e3ea37b350b13065c083734b5cc3e0f84c1d3d2ea591eb373b1597d83dc78ff458f6acb36ccebbed3099fd4daa6b18fe8cd33c427fb03a82afc4c4972666438cd32cae3b035a09256e741fc368b2cb0e6eb38c183970806e3fbaeaba074ce8fa263c634fc661a1cc940b8fdcaec63f8fb2eedbe8d54d6ec7f932bfa43449b47d031b1670e6cc1b6e69f16adbd136a7b9a4dd0efca6fa4ef85e8ea87c71515f474e31d2d101d8f68671e95918be89862fa2b062b8e3f091b8b565583a1091b10a50857fe5f94c763956661086e84944856983e89a71811d95918527a3f44bc48551c209a70c8ec695b187e89a60ae54e098548a838ce6f62531cd7b0704ab8f40263a069ee10f8621e6ca80e907ca4f5e50685ab5fca0fa26bb0dbde82ae58d1632b24577af21ee0a56cfe08537f2a0261c50ab961418eaf9200325805d20644f1cade5487e2d821477e180b3cb0132d8e5c9158ec680bf7700ab3c09238d9c3828b36093d931b86f8c704aaa9f1f1a65ca04b325ce3a65d0ca0df208439a48721bb1ed145557a73f8269239d330765b94a4ba666e09f60357d031b298e01a01ae0ae7b9da664e9d90f018e2c07c05d1ff312b86d700531ec86705b2c60da8e49fb40e07388b35aff006c9dfea8e1af61f5b1dfcfc05af9fa574ec93b83769fa200e946466a326be1669c531945a2b749eb29f0a411cef736b85b5c1ead87508c46e2d78aabbecb1fa9f4a3a489c5adb72ce4ba6eb1a29f5216bdf1fd150f920a4cec21e430ba0903c7c2c3f5eea5910e9b290e2d2d55fa275ccf0b766445234f636140eb5eaec6cfc57e386d970f856d6d8269bfa39b65eb6ef54b9fdfe5473aeb80fd4abf50c7c96b9c4b0b9aab5b1cd23a991bdc7e02da845146919aeb0fea348926a304c283ac95430e8da8e43a8634947e8ae703a4352948b89c3ee8a4c9a04435c7daa6e08f7827c29d1f48e74564c4f71fa27c5a2e4c37be37b4fd93a60689d89b1db77342b26321038a557140f88d3c1047d11493cd1a4749fb2b6993db1c6e3760053b074fc69cd3c036b3beb48d241e54bd3f51744f06cf1e0a1a88bca48d37fc0d87a8d9f4da1409ff0008e59377e59cd00ab5d27aaa363ea5e16eb4ad730a789a5b20ddf097e38b2d8d92385eaff871a96093fd2b1f40b3399a0e562c95242fedfe55f5881879cc3bdb1b8aa3d5fa5f0b30d36065fcd2ade3fe9964725fd9f2ebb04d51613fb2637008e40a5dff003ff0c71e6638b2000fc80b2fa87e154ec6b8c25d7f05532ae48d10be2ce492c1245ee648426b333322e3d534b7199d01a8c21c1cd1c78a59fcdd072f1496c98efe3cd2af49ff00645eaed7a6070baa72b11a0173b857985f883334805e6964b230f6f7041fe105b8c7edfbaa678954d768d11ca92fb3a9e1f5fc6e2374aaf713adb1e4000735c7eeb886d7b3b1295b959501dcc7387eeb9d6785ae4f68d50f22e3ecfa0b1b5fc5c925bb80fdd4c665e31142402d7cff0083d5199886f7bafeeaf71fae65a1bcf3f75967e2a70f46aaf3e12f6763db1bc7b9ad3f74d18d036dcde09f85cef4debd838dee77ee55f63755e2e410e320682a8953755da3546eae7ecd4c60b791654b664cac1c159b835c8fbb240f17d815670e735cdb27ba10cfb61ec4b31ea99730ea92b782e5618dadbd83f5159d66544a4473308e0ad95f99b7d26657e36afd1f31ba22137691dd4ac8f6b9318df50765dd523c7a190c66d4e81945018c20a931f755d8f680da27446870a5464d775121ed6a4c4ee560922bda2431c6d4b85f4795063367b292db59a488c9d1beca283cf0a1c4ef0a444e59a5128b23bec9918ddc2970c7ca8b8fc90aca202963b1e8a9eb43db18a4d74768d1b492a3ea133f1d85cc0b3c7b968cf6c7a216563895f7f0925c61e9d03b5506a3ad64325201a416f51cf5b5fcaea4712c714d029af7db2ea3c569b0ee7ea8f1e1463b859f6ead2ca7d8695ae2e749b46fb46caa7147462925a2d62c36345ed0a536281ad0768556739db6af84c767d704958dd5397d916cbc6bf15a39634a14b363914000a89d98ea347fd50fd77bbb94638cfed9365dfaf0764f666c718a14a923905f24a3358f94fb038a8e90387245cb75188784566a7138d155d8ba0ea197fa2277ee15e699d039b90774ae2df90a2c352f434719bfa22fe61b238eda21369eebd9192b6983f87b0441a5ee712b4189d35878ac03d204fcd2b61e3a4fd97c7c737d9cdf0b4ccdcbe59111f70ac60e85cbcc78f59a434fc2e95060c1156d8da00fa296cd8050a0b7d1e36117b9335430630f673f83f0cf163af500e15be1f45e9f8c411034d2d63dbbd0c435c05d08e2453e91a234c1154cd231611ed89a3f6446c4d60a11b7f8567f9373cf28d1e16dee2d688e1efe87f92102a1b1b88fd3497d091de15f0c58c0fd217bd0603d9698627111e42652b709e45f348d1e9e5df2ae3d3601402f0634760af852a254edd95a34da1cd94f6e037c8561e17b857249157223330980764a31034d8523803ca56d14fa40d828e3d9dd3f8294fd53772043c4243c05eb5e3ca9ec835797a92037f450029a4969095e6a2414f092ca5dc3e421cb91142d2e7bda00faa572496d80785ebb5593f50e9f03771c869fb155991d7186d69f441791f4544f2aa8fb64d9a71479be123dc00eeb0f275967cf2110633f6f8e13e2cdd7737fe99683f2b3cbc8d6bfaad839a35afcac76121d2b41fa94276af85183ba66ff002b2cee99d7331fb8ce230a545d08f781f99ca73cfc0557f32f92fc2b2b7297d16e7a8f041a1202abf51ebac1d3b1e49e489ef6c62ced0a563745e04206e05ff7530f4ce94f8dd1c98b13dae14416854bfe6d9f4903f37f6721eabfc56d07abf11980ec79a089926e133be688edf1cac7cfa4b1cd32e348d961f0e6fc2e9dd4bf80fa464325c9d09f262ce6dc61dd6c7fd05f65cba4d3f55e96ce7e36563c98ae07f4bc535e3efd961cdc7b232e7becc37a927b65765e9fed2791c5aaa95a59c1bb5b46cb85a8c04c85b113dc8ed7f2aa751d0a468df196cd1f8737b2c946434f532b8dbb33ac7ed7abcc1cd10b6275d8028aa69e0f4dc6c5109b0cc6335cd2d76415912cd724744d3f5c8c39a44800570ed9a8421dbb73aa87d972e8324b1d40f9e15de9bd453614c01343b72b8d91e39a7baccd3a9a2f33f4c223963aa1ddaaa998c2088bc03c85a1c7d461d5a3da5c049e0f850db8ce32c98840b23daaaaad92fc67f4678bd32162b4bc03e148d5351874fc111f791df0971a318d148e92816d800f92b239f952e54ce2e3643a805a29a95b3efd20c526f67b275296771dce242ac9a4f7d0525cd2c06fbaae95c4ca2bbaecd35ad746886949746dfa6b586e363462522bb05a8fcd32466e6d107c85cc67ce8f1b062889a90f2ae3a6b59c927d191dba370b69f82b979581cb76a3a99980be2528fb35ce9dc2ebb2893e39cb2c7b4773ca3c0c74f0b9ed3c8e294dc3c4786005a6fe172b9281c58d728f4c83b5ac24551563d3ba1cbaeea4cc5639b1b3bcb21fed6264fa64af25ed140793c28cc9f3f158e8b1dee8daff00d4f6773f44d5ce2ded8d1f7b66e3abbafb1743d3874f74e00c8626ec96768e5e7cf2b92e6f513887c7b038937b88e54fcd8a60c75b8907bacf6462c8f7935f6a5d3ae51b7b9be97a2f94dcbff447ff009f319a416ebb5a2c4d536c4d89ac0d15e15343011c39a6d4b10ba3613445794f77192e2c0d6d168332591c1a45b3e026cba862b1a58e81c08ee4154873e589f61c4009a751f51bee167e556b1fbd8563f5b45ae0eb3269d9832b0e49585bfa985c4b5edf85b3e81fc4eced3b067d28b1d933ba6bc66bacfb49fd3f2b9c61bbf33931c5b9ac6b9d45ceecdfaafa1ff0dfa53a5f4ec48f270f27173f388b7cce70dcd3f4b5d6c294e32d72e86a60d3e99b3d38cd3e1432643436573039c07607e11cb11369ddd8f293d785a4832b38faaefb9c60b726762109340cc4137d252a3daf16d2085e747f45627bed025d10cc569be8d8a52cc54bcd8c28854c8be82698abb29a62e10f6f3549b43113d309a61537d1090c410210c45c2430299e8a618fe851210cc49a600a67a3f44822bf088485e97d134c428f0a6ecfa263e3e3850840f4c7298e8f8eca77a56131d17d14095ee8be898e8be8a7988fc26ba2e10642b9d08f84d3058ba53dd1123b2198cf6a4340d907f2e3e0263e0611d829ae8cda6fa23e12f126d958ec369ba6841760fc056a61a29ae8cf80838264d951e891d826963c7cab430fd10dd0fd127c49962b1a2b4b9e01b3c1409a50da05808ed54ad0e2d8eca3cb840f34aa963fe831b5eca897170a6b2608c13de82adcfe96d273c7fca6877cabf7e19e46dfdd467e23dbfdbc2a658f2fa65cad5f665727f0f316488b585a87a67e1cc38b26e2c611f65a673e58be5162d4248db4522f92237e0caa9746c4c56fe868afa26b3f2b08a0d6ab897231f25b52b79401a3e1643f8757d15b1bdc7fb154ea4d7e245664c3c6d0dfad846fcac5337706b4dfd029b1e858d15eda723374c17b41da16c859192f662946516534da3e2648707c4db3e69506a1d1ddce3b8d780b6b2697234931b9c7ee84209a024bdb7f54f28ad023267309742d42291cd1017d76f954f9396dd3e6f4b2a17c325f67b485db638e19003237906c784ecdd134fd65bb33618b278a06468ddfcaa9c1962d3389c5aa42e3624015d69dae18480d711f5b5aad4ff08744cab38fea623c72034f0b23ab7e17ebfa7b5cec29064c63b00ee5238c916248d362f563e1adb29bfbab7c2eb63bc6f75ae2f9116b9a4cc5b9104ccaf969438baa32a17ff55ae439322a91f4961f5762ced01c47f2a7b750c6c903696d95f3863f5b86387bd6874cebc0da3eb557d5346c15c3476dc9d3f17219ef89aeb54ba8748e165b482d681f0563f0bf114ba8fe6011f757ba7f5c4729f7b9841f94fb83f62352440d4bf0bf0b298488987ec164356fc2a6c5bcc36df80bb062754613dbb4b80b46933f0276d7b0df9297e387d054e48f9af2ba2351c7247a65dfb2a6ccd272b1810f89d63c10bea53a76065591b795599dd0f83961c3631d7e484553fa0aba5f67cb2fc77559690533d327e577dd5bf0a71dc1c238793e5a162b56fc2fc8803843bad5528345b1bd1cd0ba469f6934a441aa64c7eddc480ae32fa4750c67163a179fb05592697918e489627b7ee154e316bb46b85ffec998dd49930914f2168b4feb8744d025792b0ef8cb0f909b441bb596cc2aac5da36579925d1d570bae2191db5ce03e15ee1f5242e20b6606fe4ae1ed7bdbcd9148f16ab9107e991cb9d678583ee0cd71f21a5f922d26c36ca6cf05363c2d9e11992ee2a5c62dbd96873947a6791d95ae8c73c26c63dd4ac1f173d90fd0e6c05159b42b1f1b7da0290c8e85a1c4c357f0a4b7f4acf2654df67a3041eca4b50473d9163beca99319308d1f08b1b8814784b137c5221c726895436bec8c9788777656d03090a971dee80fd15be16746e203962ba2dfa3358b7e8b08e3a42cdc4f5a3214a68656e69e131f2df14b9ea4d4b68aa5da319aa684e739cf00d2ce6461ba0928ae9f2c4d92320d2cbead84c2e71e176b0f31bfc64574c9a7a3398d6c3dc2b28f28340f2a38c6be027c5813bcd3584adf27191d35dfa2637203c709b2bcf7567a574867e5d39e0c60ad769ff87ad680f92de553c127d17431a523010413cfed646e713e695d6074a6a3965a0b0b5a7e8ba560f48c50eda840fd96930b458e203903f657c319c8d11c3fd981d2ff000f4461afc8b72d461746e244d04463f85ac8f1638da05128cd8db54d0b447122bd9aeba211f653e268d04028307f0a6b31a38fb34298582b84c6c44b95f0a3f48bd28440869269a293c42e27e54f8b1001ca3b20682b5c317ed95cef4bd1022c607ba33309b7d94df49adec138001698e3c5195dad915b860f74e6e234124a35d24dcac55c50aec6303034a758e5238d266ea5624909db1fded0dc57b7243ca0c83ec7ca4b4ca294701020f0bc9a1c9772201578709bbb849bd1441d698480937f084ec86307b9c0528da5ec1b0d617ac55daae9f5ac481a4ba46d0f92a8b50eb9c381aef47deef80b2d9974d7ed939246b4b9a072405167cfc7c76dbde3f95cfe4ea9d5352b6e2e3bc03c02962d1b59d43fe7cae603e173edf2cdfe34d7c8add9bf46a733abb060341d655565f5b3837fa1139df543c2e8b8a337953b9e7eeae31f44d3b186d0c0eaf955a9675abd71026d99f7eb3adea000c789cdb1dd7a2e9ed7f24933e5535de16b620c886d8e303ec11c3a570e1b4ad8604e4bfe6b1b7fa192666f07a1a3610eca98bfe8ad71ba674ec60435809fa8564c63dc3929df9727cad75e0530f4b64d323b30f122e046d15e691bd58631ed1fe89c3187929c319a3bad2a2975a2711a33406d31a4a69c998f66d294c818d1c04e0c68f08f14324430fca7764f0dc93dc80a501f149db385344d11db1c800b75d7f2b37d69aff4de9784e8b5a6b72c9ffa0c68738ab7ea8d63fc0742ccd41a2df1329807971f6b7fd4ae01a9439f9f299e573e47cbee26ec8257333f36147e0d7666bece3d3447eaad47a63259eae838f9f8535f314a1a5847d28f0a831f539e20407503e15c4da2b5b0ee9886b8783dcaae7e263b0936e2b89f3c2c7bd1824d3ed039d8dcb1bb6d3957c98526fda1bcad0e1c706d0446e2ad20c0c6c86762d72a9e57c6c48cda3191604efe034ee07e11df8f3b7f5b6881e42e9bd35d34e859265bf1c4ac70a88b877289a96062e690d9b1a36b87f9052a25e4e29f6892b7f6739d1f537634a77f6f0af1bab08b32199c7da48e7e1133fa30098bf0e40e04d98dddd4ac2e998f3f4b9e07485b99112f8cfcd784276d166a5f4c46930dd4b89eae1333f129d13fdae0df0561df098a52f734abed27599f4f63f0b27df138ed7077828590c6bf21f1399c3f96d04d8dce9dd6fd7ec1f1b8ad99fca0256f028aaff418266b5c68f75a09f4bc8ecc89ce0a149a2653ddfa2abbdae8557452f6570b3f349fd159abe98d7c0320c9c8e294ae8ad2f2f2f52c6642e3b03bdf67b052b1fa6b50cf9bd221de90ee7e16b748d130ba7630f7c86492bf55d73f085f9b1854ea52db676eccc5645421ecd8e1e978d0ccddef6061e0f2a6e40c5638b71dec343872cf6269b97aa47bff0034616116d6d77fdd249d1dad076f8b28962f1f2ae2e5ff00259a6688f89ba51e4d163369f3641b321af8f09b2c18f871d3dcd27ee93fe18d4e1c4266d49d193d99f2a8b3f4cd434f7b7f32d91c1e2c3cf956d708cdea33de8c7778e9d4b721d92f8e59a8b4359f5f2967d2f15f017c45a0817dd46744fcbc730bf83e2952e44f3e9f6c2e771e0fc2df5d4df516658d4d936189a1e439a1dcf055c98f1f2709d188581e02c9e2eb71871dc54f66a964ec77055d6d166c475491eff873f34d2e8b6923b8517fe1f2c2770aafa2bcc3ce61e4db5df2d533f30cc8f6b86e1f3e527f26d874c74da5a328710436037fd14fd2f372f0246ba091ec377ec34ae7fc1c64c8d640e0e73bc2f4ba547a7ca62ca9991bc780996527f7d97518775fff00f33a3f4c7e21e64fa34989991976606d47303c11f54916a42394992525e4df7e16374ad4b1b0a27c717ebf2f3e42566a2726727713cae766e46464fe3293491eebc5f8e75d7ff37b3ade85d4906d103cf27b12b4b14f1bcfea0b91e9dbb635ed71b5b2c28b2b27118e6e4169ecba5e13cdceb97c13ed229f21e397f781ac7969ec9180054d1e9faa462d9921df421143b548ff005441df65ee6391b5b71d1e65ae0da65b0a3f090b0780ab1ba864345c98c401dc84f1acc5d8b5cd29d5f027344da1f2bdb2d019a8634a00123423b1f1b8fb5e0ab236a97a26c6969be0250d05100202f01f29f64d8cd810cb281e1480db294c61141db21fa43e1218c10a4969f84d0de7b29b0adfd911d08fb2198afb1b560580f74c308536315e612394c7c5b82b03104c30844857984f643309e5583a2e50df111d94215c624d31d78539d0128660a508417b6fc2198cab0300ab43308408402cfa2618be429e6223c213a34084231900a0ba327bab1f4c521ba20542107d209af85ae154a518e8a6ed37d90683b29f2302fb050a4c034452d13a30508c2d37c2470444d99693064049f0a149eb4525590b5d2e282380a1cfa6b5e09daab95498ff00268a287509623c9242990ebaddd4e09f3e97ede02accad3dd1720154ceafd0f1b17e8d041a9c120e5d45198593eee6c2c77f56217653a0d6a7838b34151ff247d31bf17f46c1ac61e1c021e448c85971821cde6d52e3ebec781bbbab087263c9e37727c2cf664dd12e8d3168add5fac5d8116f7444d772141d3bf11f4fcdf6be66b45d7b94ed67a70e6e3c86321ce3e0ae59adf457e42595c5b3c2e71b0ebf6a38fe4a527c642d98ba5b475cfcde95aab0890c13dfcf759fd63a1342cf04b23f45c7cb171a76a1ab68b2fb32647579b56fa7fe246ab8f4257095bfeaba6a4a465ed17bae7e153c43ea60cac908f1e56272ba6b55d39ee1246e681dd6f707f11b1b207f55ce89ff0052aeb1b5ac1d521707fa33070f214e015238e7e67260076bc8aeea4c1d47990d0123b85d3337a1b4cd61bbb1c089ff0003b1595d43f0f3270dce047d94e0d0534c858bd71971385bddfcabcc7fc417168dd23afeeb1997d3f998e4ee8caae7e34f138d870fa28b64704cebf81f88c0168321fe56b34efc42c59000f905fdd7ce62591aebdce14a4c7aacf17691dc7d53a9b452e3f47d4789d5189954448dbfa952e3cac3cb24399193f36be64c1eaccbc6e7d47ff2afb03f116781c3748efe53ab9fda15c0eef95a169d92e05ac62a7d47a070731a4fa6d247c0583d33f138c8ee5e47eeb4f81f88f1701ef06d4528b2a51d7d947aa7e14c128798d95f60b2b99f8559111718f71e3e1763c7eb0c09da2dcd1b95863ea3a76512db61b47e3ae43ab6513e67cde8bd4f08bae17380f80aa5da664b5e5ae89e0fd97d593e8ba7e483fa0dfd15464741e0ccf2f1134dfd12bc6fd0eb21fd9f3dc00ee56710f6287145b5cac206d8a5e76d9195bd0be9ee1d934c7b7e8a746c0d6287972000f3d9678c9b7a2a6db18d207168ac700aa86653e94b64ed1449b56cab615fb2c632df9476b03b9b54ae9dcd3c1e13dba9398392aa74b623f7b2f6373586ad498e66d2cd7f8bd9e1163d4dd5c1554b164c6d3343be33dca17675b5d4a9999fb89b7292c9dcefb2afe171271668b0f537868638dab264bb9b6b23165163811c95a7d171b2f340b69a2b1dd8df71427f1e563d0f7cdc1e7854f36265e7ca5b0c4e209ef4b7787d26d93dd293f65a0d3fa6e181a363057cd2bb131a7bde8dd8fe375dc8e75a57424b239a67242d8e97d278d8ecda230e77cd2d643a63231fa429f1e343132fcaecd78abdc8e8c71e31f45269da236320387657f162c51b45570856edded09e2095e2c5ad71a3e922f8ca31f618ed229a394c313eb80a5e1e2ed67b8736a57a2d0b5c31ba2a95dfa2be364838a52638891ca91b1a07609381e5688509153bd8c6c37dd38461a53b784c2fe559c1154ac6c3b5c00a5edc01b40de121953a88848f52d2ef0145f5527aaa6884adedf94c7387842f502699029a205dd7dd7ac207aabdea5f6448829212837e5472eb5e1200a043eefaa473820896c708324f577410ebec56c96240477484df95592ea30c0097c800faaa1d53af313058e2c78739be2d5565f5c17e42f235cf9db1fea78fb12a16675061e130ba595bc78b5cc333ad754d66530e1c4fe47ea4ed3fa4f56d48efd4672d61f16b996f936df1a56d953b1ae91a6d53f11a0845637f52ff00ca2d547fc41abeaeeffc3e3c9b5de48575a6745e06100e7343abe55f47063e3b2a26b5a3ecaa54655fff00f4971ff42ee523210f4ce6670ffc5c8fe4f656b87d238187dd81c7eaaf41b0bdb2d68abc6530f6b6ff0065b18111b1b31c06c51b401f088d7cee7500694a6420f70a4471ed1c05d08d508ad241e24266248f36eb5262c60cee149e18ddcea000b366857dd61b5feba74798ec5d31cd7861a7495c2a32b2abc78eec7d97d38d3b1ea3e8dbb18dbed48b1b782b99e3f556ab23bffdac77f8571a7f55ea309dd3064cdf83c2e3c7fea3c6e5c65d1bdf8bb52e8dbeda5e59f8fad2375fab865bff00cae478fab74e3ff3048cff00f756eafcbe2cbfac8cf2c3ba3ed174d3f44b44f654faaf56695a3e9ff9fc8c96fa07f4b8793f0b38cfc67d036bc96cc5c070037bad4f2ead6f663938c5e99bb0c7278638f9594e9ffc45c4ea0ce18b160e54762c3dcde16a4ca186aed5b5dd19adc42bbf410447e53db1d7951ce4d1e17bf33fcfdd59d3f64d186fc5cd7b131b4eff00079227fab396bcbabda0020d5ae6b83ab636334b6477b7bdfd1777d5f43d3fa9718e36a58ed95b476bab9695c47ac7a071fa3b3db219bfa1338ba1f50dd57c85e63cbe239cb9cded1743128c88bdcb4d17b2f4ee0eb9a2b33b169c6e8d73fcac667684719ef6f7e78aec8fa765cd8392f7bf31d28753aa37532abb52b039adca25ee8036bb7b57026e553d47d1c1c9a1424f83d99911cd8ee14c3c2b6c37b68b8b4035ca97286cbee746d00790da55d9aff4a3718c8faa3cfe4e84ae0e6d451d4fa3f55c4d574b186e631b2c2cae147d5346747906a2f6fc80b1bd05d5385a2e4c8dcc0435fc071e574ec1d5b1b57619629592463f90ad9d109c384de8d9761c92fc8c849d3d2cc3732377ecd44ff85f50c1c67e6980fa6d1649eeb43abea1261c570bd8d0470b23a975fc7a6ee8e4749972eda11eef6dfd7e8a858b5a5f1f2db30fc71462bad74e7e3c233f163258f3ef3f0a9f44ea9307a6dc98d928078791cb54fcceaecac89676cb8d1fa12f06369e1ab312e07f54cb0bdad6937457531a95f1fc777ff19d8a2baacab523a4e26ad879db4c3237776da124da47a92ef73281fee2b0ba5ea5878b96c748d2483eeaecb7b1f52e06662fa2d99ac6817cf8586fc69d4f50f4706ff1d3f9128be8859b9acd2c7a51b03cfc855fa7cb3e7e6874adb603d8f840cad571a5c87188ee0dec4a6c5a9bf7ee61da2fc230a1a83ebb3da78af135d6958fd9d3748646d8da1b43ecb4d842c0e785ce7a7f5373dbee72dae9d9e481caf2b9f44d37b3d470da2ee5c48725804b1ef0d3610baa1d06b1a13701b8cd1247fa6c73fb2958994c781bfb7ca9b2478d235be8f2eee6d62c6c9b294da325d8f19ae32470ec8867c4c97b24dcc734d7213b23160d5f1dcd93689c0e1df2baa6b7a0e9faa31cc900127f9c0e42c766fe1e6740c76460ccd9dace481c15dcc4f255da96fa91e6f2fc4ceb6e50f471ed530e6d3721cc7b38bee810644d76d77ecb71af68d2e7445b23436567147bdac34b8791a7cce6b9a4107b9ecbd6e35eaeaff2f660f8dae9a2d3135231bbdeaf70b37d500b7cacc33d3c88c9a1b82b4e91dc75bc7864e58e7805a7c85464d51e0e5fa04285635135ba7e3e465c9b21638bc9e368ecab3ae46562e7c4276c8d7b594491dd770d2340c6690e8616307db9527a8ba5349d770df8f978cd326df64a072d3f2b818794e53766bf147a7c3c58e2b4d7b3e75d3f31c5b64dad06841d2bc9f0abf5dd01dd3ba8cb87b83b6f20f6e15bf4db9a202e70e56ecb945d7ca3e99e86134d1acc1c8d80356974dcd97d331b5f4d1cd2c5c53d3d69f41c8639e371bfa2f3176eb7f247a649a4d3d9a783aa25898d6b9a6c7051dbd60daa7b552eb3938f831995d45a7c7c28903b1f3a112455c85d7a3fea7ccae094d6d238ef0299cbbf66a59d558ce164270d7b4d9bf586ac99c6dbdbba418a5c0f26d6a8ff00d5f6fdc457e26a360d934ac9fd2584fd13d9818c4931bcb3ea0ac67e464af63883f753f0d99b07f7b881f2b6e3ff00d535cffbc7466b3c3a5e99a47453c47fa590e70ffcc511993920539a1ca999939000b3c8eea3e4ebe709d7283b577f13cc63d9ed9cebbc7590fea6a22cd3d9cc2148f5d8faa3456360eb5c471adca6c5d458d28dcd736feebb30c8835d193835ecd35b4f95e54f16af0bc0a705362cf89c3f5ab63645834d12884cdb65247931bcd0369fbbe02b134c8239a99b2d12ef8482ed30019604c7c60a29ec9b4a0ac8ef8a8f74df4bf7524848470a016c86e6784c31294e626ec2a0cbfd90dd120ba2f0ac0b055108662fa2812bdd1168422c752b17c5c2098c01f2a1080e69ba4c2cfa29ce82ecd2618be42842018d31d1d72a6ba2010dcde3b252108b7e89a580da95e9d9ec9a62fa21a2109f00345469b09b234b4f9566e8fe88662fa29a26ccfe4e94d2d20055195a29a24356c4c5bbc20cb8c1cdaa091c363f239fc98524478045220c99a11c12b592e9ad20db452adc8d2bbd37854cb1d3f6591b35d95d8fd41345c137f7533fc4f17399b7218c2c7770aab374e7b0db5a557d4b13bb114b9d76241fd1a217b266b3f871a36af1ba4c57fa12bbb06f0d58ccdfc2fd4315aea8c4801a0e6f95ae87569e22393c2b4c5ea101a4388a4b0b2ca5697a23519fb385eafa24da6c8e8e68a463878703caafc4c9cac075c32ba33f00afa2a56695ae34c7970c6ebe2c8599d57f08b0f38be4d3a76b49ecdf0afaf362df19154a8ffc4c0e89d77978af026b70f92ba874f752e0ebf8ec8a72d27eab9de4fe176b58f906238e5fcf042d274efe186bd8643c97461bcfdd742126d19e5b469b52e928e48ccd89b256fd3bac6ea9d3d1b1e43e12d779042db44fd5348b66540f21a6ac7214ef5b0b3da06431bc8ee55ba42c6cd1c6b23408cbcdb47f0aaf2340a71da385d8751d334f8c9dad1caa39f4dc4909ae156d0dcb672e9746959c00544383332c6c20fcaea12e898ee3fa943c8e9c616921c290d0367377479117201694fc7cfcc85ddddf75b597408df74f6b88fa2873e84030fb013f453402a59d47991d548410ad74eebec8c6780f79b1e556cda3d0236107eaa0bf4b732c8ee82f60e099d2307f140500e9082b59a2fe25412509656fee570538b2305a564d343d9ce0ac52681c11670648df455ac12376df0b218d9121773dd5ac194f0cae570aec732b89a02f05bc1e544963df7b8aaffce48d1c595efce4d5cb492b3c6992f454f4ba0ff906ddee09edc21fe6501d9335dd14e6e6cadee0ab5c27af63249fd93248001f2a24d111dbb263f5070ee505f9d7ddc13d75cc651fd08ef63ab94a325cce3ca64514f9b26c818e79f80b53a1fe1ee6e796bf21ae6b7c8573d25d9a2144a450e23dd348035ae7b89ec16c745e99cfccda5ed2d69edc2d9f4ffe1f62e072d88177c90b6f81a2c70b5b6391f458e7f93e8dd5617fe463b47e818c6d7c8c0e216bf0f416c20089a1b5f0ae31b1d91834384567f4dd61595d4bf45f1aa30f40a1c02d6805a14b8e21130809ed71704f1038f95aa34bfa1a562480b18e24d728adc679164a930e3d0e5176d05baba5af66595ebd203043c7b805258035b49011e0a52452d3182452e7bf611a4570534bc82845f5d8a6194f84c26c397a148f29824776291cefa224d846489e4b6946df5e527a9f5409b0c5c0246b812500ca534c853036492452617d790a399091569a5c3e500a24875f9094be9460e03ca4327268a8b64d0674887eba0b9e7ca1c990c8797103ea4a56f44dafb257a8483ded23b2991597101a3e4acbeb3d6b81a735c1d3b4b80ec0ac36a3d69a9ead3bb1f0627963bb3bc2c576742bea1db11d915f674bd47aa707081fea8a02eed62755fc4e6c9ba2c4dcf7f8a55381d21a96a320933e695cdff2d95acd2ba1b0f15e1ecc66977c90b24acc8bbfd157372f466319dd45d426def74311e3b2b9c0e87c763c3b2de66779056db1b49f4da185a001f453a1d2c7c584f5f8d8c9f2b1ed91424ca2c1c0c5c26edc7c668fad29e048e770cabf80aee1d2da3fb5498f0034fe95d0ae9515a4b43fc650b717225e396a3334a95dfa9e568061b4223616015415aa0d8e968a9834fda289b520613478538c6d1d852f0670994120364666235be0247358d7500a49a00a0bda0f23ca12e8584b6f4713fc48eb0d432b57c8d2a098c1890101c1868c87ea55069d2ed69be4a83d4cdf4ba975063dc5e44ee164fd54bd3834801783f2764a727c8f67858eabad34593720b1c28714ac21ce7cb1863090422626931e6b00076baa81286ed366d3b27d390104f671ecb81b8c93ff0046fe458450e7184ca232f8c0e5c3c28eed463db4e25a4706d5a6993e4b609218dce2c78e41589eab7cd0ce6087d929e69dc13f6468c7f91a68cb3c884534caaea9cb76a1ea69632ea26c9bdac7f6085a5f4e491e1cd94656cfe977d9e3eca0c5a3bb39e1f90722293fb8bbf4ff00aa33b07274d21916506c727b4b5afeebd124a30e1b3c55ebe6bba349d27d799381a842c8dcdfcbee0d90380b0176ac5d4b1b35864c79d9230d1041bff45f37e141160ba47582f7773dd4c8bacf2b44789f0f24b25678f1fc2db89992aa5c52da3a4b038d7cb6779d6fa9b4ad02232ea194c885586f773bf65ccf3ff1c323335218ba4c31458f75ea482c95cef372f2b5fcbfce674cf95af3b9ce71ff0065132b270f165271a211b40eddf95a6dcc94fa80d8d87dee5e8ec797f89d9f89805afc96194f9634585cdfaaf59ccd71dfe213664f91144e0ddb291edbf8af0b3536ba647b417d81dd3a6d4a39207b5afa0e2090b1d75dcbbb24d8735460f8d6b5b2d23d426c88da216d571f40b41a5419b90e89af9d81bc7f75959fd258d9e06b62f0ae74e8e2c2c86bb258e74639706f0560c84bb8a4647e3b947a36d978d163e08f58b5d43bd2c4e73d9231fb481cf608bd45adc66e2c1925115767bae963e4d4df1d92e376a8c4c192dbd8b87e39d526e45a6e2e340d342d074eeb33e9fb843941b7ddae3c15843ab9374692c3a9c8686eb1f50ba36624a4b476a7542c5c648eb39797979e181f2ee716d8631de3e56572744c9c9cb716dbb9e544d167c895ecf4a47b5c7e0ae9dd1dd2726af14c64ca7c3296fb5c0795cad4a89ea2fb670eff000328b738b30b174d5e3ba4c8fe98ec0b850545a9e8ce8c36b96f7b1e5740ea4e8cd50caec6932a69980f1b9d4a361e832e2ecc3cc81f242456e02c8fdd1af3386f72eff453fe26d8477b3963f4d2d712db1f2a4438b445034ba26a5d072c6ef571add19f914ab1dd2f910d97c7d96c8f93ae6b499964a51e8cfe4695bf09d3c5ed7305903e140c3cadc072a5f51750b74b6c9a7e373311b5e7c0fa2cce9f99726d26be56fa299ceb729fafa3bde26e9f16ac37fa46a06323ddc2d9e99aa80d04bb85ca06a4d86b6b94cc7ea591a2b7d01f55c9caf1cedf47a6aa7a8f67678f5b8db1d892a90cf59b2176df53f85c9c7553dcc2d0e50ddad4ae9c1dfdd73a3e077fd8be2e2df6769c2d7ce5c9bb758256b348cdf7b49360f75c7ba5f512f0395d23449c16b6dcb899f8aa9975f40be94e3d15ff008c3d3d3603f1b5fc1847e55cdac80d1fa4fcac56061699af43fd42d2f3e17d07958116bfd2f938330b6cd116af91f28e774beb9938dea383a190b4b4f1c03ff75eb6bc6f9e88cea7a7ad9e51ea36b535b46e3ffd3bc195ce7452961f0015a9e86fc3dd374fca76666132cacff977d82ca74b7524ba810d93b9e574ad1e62582afb775c0f21939742709c8ead1814bfcd2365a340c746f2c3fa4f9526681d192e70b045aa7d1a6963cb680490f3456a25041aaf68f2bafe12baefc3d35a922bc897c73f7d1cd7ac3a2f075ec9192f7164b55c70b3d0f45334b696096c1ed6ba8eaba649945af899c77bb586eabcb769d27a4e77b80f0b1e7d1757b4b7a3a1896a974533b4a6b6f6cbcfd5261e53f4e9ff00ce07c2a0ccd7a48c921d4a037a8bde0b9d65658e25928f7d9b36f4fb3792cf91aa3899319ee8fc5146d34498a4d445ad3c51541a6758383431aeb1f0ade1d7db31b15f5b586ea2c8fe3c7a31fc6f96cbb6e55bacf0ac7d5846382ca2e590cad4a42eb8f9fb2f626a794d7762566fe3351de9177c2dadecd40249bb215a62c0e959ec9bf65538d92f9c35b2c157e40a56b811189e5f0ff500164339dbf757f8daf56f6b7133dd6a82d6c96ec70053be39598ea630b711c4b9a4d1e7e159751757e9ba6e33dcf97d595bc7a711b37f55958608badf1257c6f9b15cdfedf90bab914c6162941f5fb28a7260ff0016fb30035291b3494f2407770a541adcf17695ca4ea9d0fa96921cf1199a16f3bdaa8f6917c723b8f85eb712f84eb4a0ce55f53536da34107576645ff54ab1c7ebaca6fea7921634b4fca4ed7c95b15d28bd328e099d2b0ff1076901c48255e61f5e44e1cbd71b6c8e6f94f6654adecf2ae8e5c977b27c316779c5eac8276f1237f756916ad14ad14f07ecbe798b57c98bb48eafbab3c3eb2ccc7e3d5240faad15e7afb11e37e8ef8cca8e41c11fca787027bae3f81f88849a712b47a6f5d4520e6403ee56a864c64532a5a37ddca421526175362e4817236feead22cf865ecf055ea49fd95f1683d24a0bc2463b869b4a78476880dcde52399c5a23aaf8e5250a4c2ec8e597c109861af0a56da3c2f101426c8463437c5c29ce68210dd1da84d900c3698e840be14ff48263a34025698c0432cbb562e86d0fd202d4215c63286586fc2b17c3c1403080510109d1a198d4d2cb4331fd10d0486e8fe96a33e00ebe1599670784074609534429e7c16bc1b685579da4b48b005ad34b1027808126383ddaaa94530f2660f274d2dba6aa7c9c3963b2db0ba3cfa746eb34aa72f476b83a82aa74ed0f197ecc44199918a6ec90ae707a97d36869e3ebf09727462c24b42a3cac792279a695cfb7122fb66985bf4745d1baaf1dc03650d791e4adef4f6bba5e438097681e6e97cf30e53e037c8a575a6f53ba0a69777f292bb6547df434a2a48ee9d471690222f8d9139a4785cb75a7e9ae748df4c36cf0e8ca743d4233a2314b26e6b8577541a974de53e7f5b4fcb735a79f4dc6ff0085be9cd8cfda31ce8640934cd426ca11c3235f093409ee02b17f4764005df9917f14a6e87d3bac199a658cbbeaba060e86f01a6668baed4b6d71e6b68cedeba393b3a6329cf2d73dd5f40ac62e8dc9962a0f77eed5d5c69310e7d31fc22c786c8fb3537c62393471eff80670eb73cff0bd274498cddd9f8a5d7e4c36f7da3f85165d3e37936c51d60563fb38d65f44b9f6edbfe8aa27e8679dc5ad75d7c2ee0fd21b679ee983478e88db7fb25f886f98f9df2fa624c7b0e61e3e8ab1fa335ce2d046ef85f46e674be264b4ee8c5fd965b53fc36c69de5d137613e5aa7c6c65723e7886200ab4c485af6d280d1454ec49c3385c3b9b6ba33b4cb2870011c298cd2daee48099893b4b42b3866686765c9b6c9a30dfb4476685149e021e5e86d6c069adb0ad1994d68348904791984b236d859fe7b176d95d519cdea261b374c7b78da6feca4e89d179baa4c3fa4e6b01e491dd74fd1ba3464481f3c7ee1e0adb69da0478ed03d30d1f40b6559b64d6a07a2c5c2971ff0090c974d74461e9b134b20064f248f2b6da7694c8e3a74601fa29b0e1b1878154a63658e315c1f0b4d7193edb3a31828ad038f0db18b6d22b5e1aeed69ad25e68762a5c3861c2cadb563362cac481c43d534385263c224f84f8f1835d6deca5021816e850919677016e36dee8ad00709c5c084db0d0b4c62a250e5b1cd750290bc2139c7ba6190a6426c36f03b7298e9af8a43de909b500c70376bc384c0ea149a5c540eba1ce7526179f95ee534a9b00e077794a36b5083da179cf04775362842f050cb9303be178b91ec2b63b726978ba49bb8e7b2afd4758c4d399be699ada4b27c57293049e8b02f1b4a8936a78f8ed26591adaf92b13ae7e2462e3db6077a8e3c00d594919d41d5b3ef87d48a127fd173edcf4baafd95fc86d3a83f11b07001642f0f77d0f958d9ba87a8ba8f236e1b646444fea3da9697a7ff099a641366ee9dddfdcba4695d218d8b1b43200d681e02a6145f6be537a15294bd9cb349fc38c8c9904b9f2195c79a5bcd23a3b1f0d8d0c85a08fa2db6368ad8c535bdd4e874d0c1fa42db4e1c23debb2c8d517ecce62e8cd6f0194a7c3a500eb215e3311acfed4a581a4f016b4925a2fe28ad6e9acee6d1d9871b476532810784220da64920306216b7b04e6b12d149749b90a23994866877463ca1bc041c8092430f3d9203576949a3c21b8d823e557bec8da1aff007127b52138d36fe05aabea7ea6c4e9ad3df9992e05dd98c3e4ae39d47f8c1ab65bc330dedc7681fdbe42c7766d704d3f65d463ce6f69196ea194cbd479d238d974efff00753b4c947000ecb2d26a2f9725f2c8eb7bddbc9569a7ea6d6ff72f23975ca7b691ecb1ba8f137fa6e5161691e16cf274a8f56d17f325dfd5681cae5b81aa35e47bbf75a7c5eac9b1b1bd16c80b3e0ae2421f1c9a92f61b93f68b4fcdc9a0e879ba846c63e480580ef85cab33ae32b59ea38b326646f919c0b1c05b0d6354935688c469ac770e03cac9e474be3b7286545fd303bb7e57631aec78c1411c1c9c7b6c7b45aebbd4dd3f95855ace9f2c6fd80366c578eff242e54755f53527b609a57c4de585fde9693527e3c39f2b33670c8a4a2c8deddc2be856626d132ae5ccc460fcbf346ef85e831b1e1f1f27f672e2be296a5ec933eb73349daee1428e7c8d465db640f255663b649f2031cfe01e55d4b902166c85801ed7f299d71afa8aece9c6729aff0045bbf34458ccc769e1a3bfcaa3cbc92e71faa9da568fa8ead96c86389e038d6f3d82e8527e17e9da2c503f272db973bd9ba46b47b5ab1ceeaa87b93357e4d28a392b23c89cff00498f7fd829bfe1d971c2d32b365f83dd7486e1e9fa5073fd167d5ade02cbf536a10e6647fe1d8d8e303800da5ab3fe67a847a1278ebfee09d373ff0086481b94d15dead693333b1f269d190d6d2e7bf9c7b1dc9b3f5446eacf0283a955761b9cb916d6d25a3459a5af27dc3e167f568198f206c733640ee491e134ea2f75d9255766cdb81164df95a31e8945f62cf5ec6bde18f3454cc3a781caa736e376a661e58c7abecb6595fe3d02b9ad9d37a3210e998e23b2ebba164c9865af84d578f95c63a0f5a87232841d9de175ed3721ad6b795e0bccfc955bbf4cee4211940d3453992774f2c4c91ceed7e115a1f200c970a17c67e94541872811c15a0d335285cc6c52305fcae46049db6356cb462ba1c7d14595a698e37cd0b3d9fe41cd2c6750689af674adff000dc9c5859fddea03bbed54baccf821ae2e68a69f0a972bf23167c51cf2089f28a048e2fe175a585653352af5ff00d30fc154e5c9a386f527e11e465c72e487d65b85ba87b1c7e85737cde97d5f4390fe6f0dec038de0582bed36e86c1018dec6b9a7c3b90b15d61d1b26346fca863fcc6257be2736d76aacdcac74be55b8962553febd1f2ab9ee24df84c0e7d9fe5753d5fa3346d5b25bf933fe18e029e0fb9a4aca6b9d03a9e8e4c818dcac5dd4d9a2edfbaeb539b558ba65fc64bd19d8e47b7ca3c791b9c2cf217a4c29613b648dcd3f514960c5f75ab64e3ad96c366bfa5751d920638d2ea9a14e486105718d1cba19811f2bac74bce5f034b8af23e6a95fd91d2836e2762e96caf5719d138f60b8c7e317e1ce767f58c795a4e3fa873181ee6fc11c7fd974de95ccf4b20b09e1cac3ae5d938fa4ff00896096fad8c0f245f0aff0b929e3f1df713cd6753c6dd9c3744e9c9f47c81893c4e8e68c53810ba6f4fe18c831c65db6d034391ba89fcde43639677f2e242dae262e1cd8a1fb1ac734f05bc72b93ca39994d7d2fd9bd58eba749129b0e06930372269591868adce341546a3d7da6c6c7b315afcc97b06c63baccf55c72651114d98e6c61d6637762a9e1d56589eec7c48600d029af68a20fcaeaff009358ebe1a5692398e3293ece97a4333b538d93ea3ff8784b6db00ee3eeb9e7e25e3331720bdaf14fe36f952b07aa755d2e12325ef9afcb8f34833e972f586e91e4b48ec9b27ca4322b50f6f66bc4adc25c99c8737336bdfb9b63e3e15534cb2db98095d235bfc3d934f75cc3733e88181a3e2e3f0e8da2be51fe6d75c7d76742767e9987d39daab67db0e33de0f95a7c2c5d6d8ddce8401f04adae9b8f8238606077d82b17e3b361a0172b27cb293d701612dfa7b32789952b5a04cdda4775acc4ccd164d30c90e5c2dcf6f02173859598ea1ac68dce11b89afec1cacfe1e8716b3339d1eec5903298e3765c8d34d56c79cfd19f3b227086a1ecb5eafd5faa1fa73cc18ae860de07acc70bfb2a5c5ea7ccc085accccc7b5edbb86ec8ed57f7e557646675169392307326786dd86ca7da7ecb2b9b365e6eb334a5a5e5de476e177b1b0a0a1f1f491e52cbac93fcdf6751d07aa31f552607401921758e3f512b61a1be2c7cb2c92510c1d8b8795c834ac7ca39189069d3b5d925ad71becd71f95b2e9ac1d4358c4d4601ea3321ac6b84933bda4ef1cb560c8f1ef9b70f5fa0d16c95875718feae2be58a68e56b4d6d71ee173eea8d00e54a7270a001cd3ee6b55be9bd1fae00d6cba816340b76d3fa96cb40e9f8f00912482773873b821451385abe37d1e8a33e50d4ce0b244f8dee63da5ae1dc1f085b5758fc4ee8e8441fe2987186399ff358d1fabeab961673dd7a34f7ed98270d305b5238505223c774965a3b214ac2d2414b1b16dad8211db6477142f28ce1ca1d729b916eba065ee61e094e666cd19e1ee4920422d4d096bd317896f89d4593191b6422beab51a475c4d08db23cf6ef6b9ef6e427b5ee6f36568864b5d08e099d9f4eebb8f8b7ff002b49a7f55e3653797b7f95f3d479b2c7d9c7f956587aecb111ef23f75aebcb29950be8fa2e3cf8251c3dbcfd519ae0eec415c474ceb2963dbbdf7fbad9695d70c7d34bc595b6192a467952cde85e1caaac3d7b1f240f78b56314cd7f675ad317c8a5c741697abe892d3bc26d0a09cd1e10cb5188e535c10d1601da130b11a9216d8508467301080f8ebba9bb3941958a1086e8e90cb7e8a5b9899b0520421b9a10248efb29ef6051df1153642216a139a54a747450dcc408419196080a3fa7dd4ff4e89b42310e4a9a01533e135c0f0a9f27480f26dab4ef8cf3480580920855ca098ca5a307a868941c40e150e4e04b08b03b2e9f9182d7b7b0ab54b9da38209ae1649e3a6f63ab19868332585c002452bfd3ba8dcc204a2fe0a0e668fe9924054f3b1f01bae02cf3c5fb45f196fd9d6fa73ab3123735aff703dc2dce0e6e365b3742f691f75f37636acf85e369a2b59a17594b8c434bc8fada6af2654bd7d0b2a39768edcf00f1487e92ce685d5f164b5ad9de39e2d69e1963c866e8cee6aead3910b57e2fb305b5493ec0fa56108c7562ad4e0d149be9b4f2b4716666995ef86f9210fd2ffcb4ac9d15f609a6135d915117b2a9d8b66d79b8a3e1583a3a1ca46b02648291f183706ad0648fd2f0ad5b2075f651b222dd6bc8466dbec45392f6468f31f1b7dbc2938da8e548fd81c7ec9d87a1e4e738358de09eeba074f741c18fb2495a1f255f215391935416bdb34e3624f265eba2b3a6fa732b3a664b92488eff00495d274ce9d831aa99e148d3b4d6421add8287d15bb06cec380b9fd58f723d0d3870a23a4bb0b8f82c899c34295ed6b401fc28c2721a38b0a4e38f54eeaeeba78f4eff0018a0c9a5d8c7b9c7b02384ec7d3df27bacab38b19af1e14b8616c6c5dba70d4752662b2ef7a22478a18007052a36860e0252024dd4b62497a32b93638babb84824bb1e131cf714d0eabb5003c917c2693c7743ddc948e7222b1ce268f2864a42e4849f85008f1928a432f94d34509c5120612b4f74e0f50efca77ab4dee8077d124bf943748802624f75eb37650021c5c5259239481db8d289a96ab8fa747be5918c0073b8a0e492db60724bd92fd50c04d8a01576a3d438581099249982876b5cf75efc51c764b2438ae323bb7b5649b8fd43d5b9836c723227158edc9df50ecadd9bf46d75bfc526ec74785b9eeec0356622c2ea3eb09bfa9eab21278b0b7fd1ff00840d81ed972c07b8d1e42ea9a3f4862e1b1ac631a00fa2aa38b65af763e85d391cbba67f0831b1f649921d2beec97ae95a674b63e2303042d142b80b53069ac85bb40007c292d8002b7578d5c3d22c551558ba5471b68340fb29d161b184f1e14ad8026d2bff00f65a90cf4c31b61a179a7e89f4bca6d874088b3ca6398df8467a61eca13604b07849b6ad1535c38403b0247286e08c42610a0a04929b448ee88e69432a15b8827536c93dbe103227871622f964646dabf73a8a92eec476bf2be79fc4eea2cdcdea6c989b3bdb04476b5ad770b165e47c31d9a3131fe69e817e206ab26bdabe4cdeabdf031de9c6dbe0579587c9c6b948df7c7f0af74a937364749ee6b58783f2abe1c67cb31a1fa9cbca3be529394cf4b5d31ad704438348339f37f28b91a1e46237d565b852d5e1e952e3c6d74b1ed0ef27b2b48b0b1fd17faa406016495867e41a969768db05a5b39ce36a8580b0f042b4c1d54b9d4e7f083a8f4c4fa84d3cfa44526431a493b18785978a7c98f27f2ce6b9b2dd6d7020dfeeba7fc78dd1e4919accb82eb66e7335f830d966404d2cbea3d6b2ca5d1b1c68aceeaafcd665be29376e07b0567d31a1b32b5081fa9b0bb1cbadcc67048fa2bebc0a6a8f39f6736ece7ea253ea71e66a5346fa7c87b00b5bd3dd27af43a76f9cfa18f2fe96b8dee1f6569d47a1e068fabb8e9e4181f189617571b4f2ad713ab31b1b4d8d99f2033463fa6076afafd535b993e0a3544e5371b25f9147a77e1ee3624e727289703cd552bcd3fa3f4d93244cd8da40ed6983a831f50738c6f34eed6a6e9f96c805872e2e4e4e4c93e4fb3bf878f5b46a74ad1637e5c30441ac0e2070acfadb4fc5c7c56b706291ee8dbb5f201c02a931354fcc1618cec7b4f04226b936a92b0b273206122db7dd732ab1e9c67ecd96d5a7b89ccf5366a12b25904723a11c6e6b6da3f7593cf8df11dce75fd9750d43024731f1e3c8e7c47b8ecb27a8f4ecef04ec2bd06165c174cc76a933238f3433174721dae3d89f0aca1d072a70d73227b81ec430d7dd45cad2e4c671a6027e085befc35ea0d4048fc3d53508e0d3236398f74a2c815c068fbd2ebcec4e3ca0ce6d964a12d68ca47a0ca2daf3447c2afcdd35cc27cd2eb7274cc792f73e321c1c2c16f163e55365f45bbdc6891f45c7afcb4149a6cdf1adca3b472e38fb491483232851f0b7d91d1af6125cd1fcaacc9e92adce2ea01752acfaa5f62fc4d19ad2b529f4bcd8f2a124398ee085dfba5ba8b1f58c08726170f7004b6f9695c3e4d04871dbcd7c2b1e9dced47a6f2bd480931b8fbd84fea58fca62d7995fe0ff00246fc5b6507a97a3e8cc7c9047254fc7cb2d70a2b01d37d598fab45c7b251fa98ef0b510650f6f2be7b95872aa5a97b3a0e0a5d9bbd2b517e5931c928baf283aee82cd5b01f03b820ef6bdbddae1d96731328b1c1c1d5456bb4ad5a09cc70bc17170a5d9f179509af8ac7f97ece66455f1edaf447e84d67273a19f4ad46407331bdadb1cb9a3b15a8f403da58f00df707b14cc7d27131331d9714404ef6ed2fae48524b00b00daf5f5c1a8a52ef47193d3e8e63d73f8705ed933f496804f2f882e55939d9183bf1653230c679dde17d4bb43851161603f11bf0f31759c297330e0032d82ced15ea7d961c8f1da7f240e8636569f191c175cd4bfc56063666c4658b80f2cf79fb959e870048e70b0083e15b64421af7465a58e06b6bbb8fba8d1c6192db7f755d72718b5b3aa92f6174fc37324036d85d07a771a831c4f1e42ce6910324734968256bf4c7471d34117f01713c8dd292d235d7eba36783190c6cd10363bfd96a30f222d5301f04c373246ed734fd567344cb63283cfb48a2ae65f4345733204f1ba29bb0be57230272adb9a7d7da3959897f597b32d8ba549a0ea52e2b88744dfd0e3e56af4e78fcbbe67d06379eeb25d6dadce7104f87b69879bee562733f107271b09d149296970aab5aa8a1cf25df5ae9895af961a8933ab754395ab4e637db2e872abb4e99feb001ceb2b18ed7c6465ee32136ee4dad96872c126d91ae16b764512aa1b9235575475a4699fa74d3e23647bdce04d515bbe95d3236e9ec15cac1b35863636c01f601b5d33a3248a7d39ae0f050f075ab3238cd14e5ffc55b6806bda462c7a64f9134b6d6b78b3e5720c9c09f21af3058ef4ba0fe20eaed7658d398ddf1ff77c1599639ac650002d1e76d85790956bd1971232b62f9188c3d175d8b33787bb6df75b8c03347006cee2e78ef6a3cf92236f0428ff009fa1fa9716fb6790bb46cc7c28d3fd4b87ba173097341e164f3fab74fc49dec31d39a7b10a4e4eb91e3b1c5cfe00f95cd3ac7a8b1f28bfd2e1c3c85b7c678f774b535d157909baa1b8933ab3afa6ea4cac78a46336c00b237b5beea26f95aae869f4bc9c72d762c465f363f505c3a6ce21ed730f20ddadd7426a790499a38a4958c16fdbcaf559784e352703c75d194972fb3a049d00cd435479d29edc59724d0bfd23e8ad313a37aa3a6347d3e5cac932c787312cc703dd1b483fabe452cc8fc51d3193b6398be2f4cd10d1456d3a63aef47eaacd3040ecb6cad07b9e2bb72b02f9abadf35d166145c9adfb3a4e1ee971a3941003980d8edc85330a305c5ce776512091f918621c76d3e3afdd24b1e661e33a5918411e027ae7c21cd23d02f5a2df2f0a1cdc792090021eda5c97a8fa161d3a499e2376c365a4760b5f8fd51235eede4d8f0a44fabe36a901c7ca8c6d70ef5d9637e6289ad37a619634f5fb390e838e4e54d1bda76551251f5cd058c824c980f0da242d4e574e1d3e57cd8a3d58de7b0ee84ed3dd978f263cf71fa8dab3e1726199ff00e8524fa391285d5dbb6ba39b3d97676d2038226bf9f169596ec3e64734d5b4504910f5e31236a885eb632528266e534d11de10dc14a921284e8cd29cf4422969be12728fe994c746514f62b04bcdee9fe994ad8fe8ad8ed0bb1ec7165515371f35f0bac39c0a84d6fca5be51f924bd057669f4eea6c881dfacf07cad6e95d72642d6b9f4572e6923b708b1cf24277309b5aaacc944ae5526779d3ba9e299801905dfcabac6d4239dbc3815f3f61ebd3c247b8f1f55a7d1fad9d0d091f43eeba9465c5fb33ce93b18737c15e242c8697d590e4538c80df8b5a3c4ce8a716085ae36297a29716893b7e42f017e11184385a5a09c004b109cce54a238432d509a22b9a508b7e42986329be971d90268845bc1422ce14d74650cc5481080f8d01d12b27440a13a050856ba241746ac5d15770812c5e428420b98428ee62b1747638407c3df850857bd9c151e5883c510ac4c2794274450d10cf65e0ee0768e167752d25c41a6addbe1fa287918024078091c068cb4731c9d39f0f25a54788be17559a5bcced2b78229677334cd84fb563b284fd9746c6bd03d3b5b971641eff685d0ba67ae3dcd639e385cab2202c2971b365c475b49b58fe39d2f940b9719aecfa370f5f8f2803c72ac1b911bb92405c3f44eaf963735b2ba9bf75bdd3fa899931805d47c72ba98b9bc96a464ba8ebf136bebb0f672f095bf2166bfc4251dae935daa4a3c15d08cf7e8c128b5ecd1c8f69f84c0f68f859d3aa4ceecd282fd47201ece4790a7caee7ba304df2adf43d23235291ae7348629da774e9ce99bb9bc5fc2e87a36831e2b5ad0ca007c2f03939c92e10f675317c6b93e5603d17a6d90358fd8380b518f8ad8cd81549f04418c0d028052d9171dbbaa29c74ff26bb3b5071ad7180b18f3e1103687c8286cdcc9030f95670e9fea3411c2e963e23948aedb14576c1e1e3890555ab3c7c66b3821263627a2a58008faaf454d0a08e6596ecf31819d93b76d0bdc0086e7fc2d5f4646297d9ec985c937a13dd6784a2ec2175794c2eb422e49bc01dd40ec26ea485d6984f909a1df5448297514d32148f703d94791df54481ccbf64373cf8a51dd25149ea122942052ea14985df54373ed341b28102035e52ef3f299f72903813439508814f24d25b6276db1e1613aa3a375fd6657b62ca2633cd15d27134d9253dbcad0e9fa2ee78dcd596da5d9d093a94ce27d1df8392fe604998ddef07cf65da3a77a1f1f4f89971378fa2d36168f1403735a01564c8835b54aca71e35fa2574a89160c2862e5ac17548c18d1db84f2003c245a3bfb2f14f2bc912d284d9e4949535c400a00f784db4b76131c79a50221369a539210a006244b4bd4a036308432110a6150235c020b9bdd1e935cdbe00e54d6c0d10e68f731cdbae1700ebbe8ad4b0350c8ca31ba58e479702073f2be86317241a439f0b1f2a3f4e78d9237b722d63cbc6f9e3a9745b8d90e9972d1f29c25d8f872ee15bdc1b47bab2d1308cd283b687cadbfe3174c60699163e561402369dd61bf3f2b9de95d7cdd12338ed8239a727873c580bc76562cf72ae077eaf2154f5291d19b1e3c91c78f38e001b41e29673a874b6647a986ccd30b9aee1a39dffbaacd63a93506e8efd51b1b648f86191bcd159ae99eaa962d6f1f2337de0497b5c6c3b858f13c75d15c998bc879273fc6af476ae82ea6d3f49c48b47cac66b246b6df2c6ce1df7fd972cfc636e9daaebb1ea5a2c46015ef9183da4dae953ea5a0e4e9beac31360d418c258d00d38f7a27e151eb59781ab69ee87070f1e38d8ddc5dc5170f80bb51c89d50d3d1c7ad4b7db39461e819f9108ca38f24ce7ba9af70ee8ef8b2b02785d9903e22c0435bdbba8fd43ae66c190586590471f0d6071007f0aaf135b9e7c96bb31ee99a3b7a9cd7f2ac50b271e4cb9c4d967eab16aba161e9aec76c5958a69937f9d9f056373f1f237864acdb47bf82b498f2b354735c000c1ed6d0ecb45fe03165697269195001962a58a4aa247dd555dbf1bd3134d981c22ec6aa249eeaef4ecc9a5b2d6b9c00e7e8a441d2395b2479c796879ae11b4ed0b508a4231dae01c28f1e1537dd53edb3bf85b49179d359d8f95208a67ba3703edaf9faadd40f9f370a3196f63b6025ae68a71fbace74b74f981e7f330fb89e090b718da6b636578f0179bc89a73fc3d1d194f6bb334ec0631c680a3e146974f8de08d81697234c77ab4d6f0bdfe09c5d8b58a339a623699cfb33a5e19dc4d51f1c23e2f4d60e16317e435843459711e56bb3f4d7c109706dd7c2c56b3a8eac2392182112c777b437bd7cadf4dd6dafe3e5ad19ed4a2b6911e4eb838127a0c819231a685287a9f5c65e5c619850b6171344f72832e8993d433b24c6c17e24a6bd5a1409f90b5fa1fe12b03639f2277992ecdf65be7fc3a7f297f6395ffe99b6a1d184c5cfd5f50c830b0be493fcb4168f43e88d7759c96c73b5d1447925e1742fff004c74c9b363cc687c72b392e8ddb49fe16c745d23f28382f78ed6eb28c32abb3fa2d06ba6e4f729190c0fc20d3db037d6a7bbc90a8f5ffc15749397614ac6b7c021769646dec2b8468f0f78b367f65d1ae2a4b699a6364a3f67cf2cfc36d534891ae0d2e23cb15cc4cc8c401b2b5db80f2176f769719049603f555b99d2f0e41f5044d71fb2e7e6605963da66fa73f8ad339963e5923b775a8e94cd63735a2468bf04f8568fe8cd3b259718f4651f1d94dc1e9d822c238d2061c969dcc7b782e5cda3c55aad537d6bb0dd99194389790484b7787b8dfcf84674f1b459202cdc597978cf74792fa2c355f44993aa7a9ed691caf413c9b3ed1ce8d099a1765b00bb0420bf526105848e566a3d44c6d7b0bc9f8e52e0ceecd323af962ade4db27a88ee84bece5df8b5d019d8daacdad69b8ee930f23df2860b2c77dbe172c765331add2bc36bb83dd7d7b84f3231f13c7b483b87edd97c7ff89da2e468fd63a962cb0ba26190be36d7b4b4fc2d38f08ddecd35644a3f833ceeb0918cf4f15b43b6e51b0fad3370b34486673abb8256798f2c691e1417c9ba520d2db0c1aa49a68d0f21c56ceffd25d77fe22d01cea24729daa7504197972c79ba86562861222dbcb3f75ccff0fe7db9144f0385b3926c3c6907e7e2326348e25ed0bce598555393c5217c849ca9e4bd957175ce5cf30c6c879730f637fa903a8a0c9cac47e6c703cc0def201c12b51a7697d312ea2cc9d29e67888b6b65656d3fbae81d39d3d81360cfa739fbf1e76d6c756d69fa293c8c6c7b94545a671b0a9bea6eddf4fe8f98e396413d90472b6da2ea4e6638602edcb51d45f838344cddec24e3bcdb09fbf652f4be92c6c4dbbd8d24057e7790a1ae2fd9d9c66dff0052af065c97b8535ceb5bbd073b55d2dad7c333d8cef4458fe109d8f060e0cb263c21d2b5b6ca1e53ba7bf1021763cd8faa63450b98dbf51a050fbae3e3ca57cf956f8e8a7c8667c50e3244ed57319aa4864123fd707dd6052a0cac931b883e3eab49a4cd87978f9d088f1df9018f7b280bec48591e9dc69ba89d9272d92c11406a43b2a9dcd7fb15659e3ec93f91be5b29c1f25571d3e8afc9d4aaceeb2aa733590dbe48567aa68c71e475481cd22db47923c2cc6569393239d4090aea28ad3d48ecc32135b4536bdd40f31bdac71b22962267cd3bc9249dc795b6c8e96cac87921a4a741d0f3bbbb390bd0e3e4e3d11e99cec9aa573efd189c4d3e47c97b4bfcd1f85b887321d0a3c73a2bf271ccd17fe2449446fbf1c76571a3f439190d3234b6eb95d166e8bd2f374e8a2763b7d5677781dd5391e66bdeb7d191f8efad9cab03f0ef50eae73b2b4fd9bc3bfa8e71ae7ecba974af44c5d318a58c789325dc3de3bdf91f65a1e89e9ac4e9fc8333232edc28b4f65b387070df9266f41801e7681e563fe57f2abe1bd0218aa9655f4feaafc37ed7f355dd6933b568e7c291a402e70e15166e9ec8f20c9130007b052e2c6f5611bbba4c795b5a7527d32cb20a5a97d9919b1cb262ee794d76508855abbc9c76990b016d2a4d4b477becc44af3191832e6db3a34ddf4c938daab450be029b950bb51c09a4c5da72361da3e561f2a6934e77f55c5a02a8d5bf13727a6e213e0ed9240768079afaa6c4c194ec514b686ca843e36e45ef4e74fe3751626663e7e144fca89c7de47b82a09f426614f263b1cd88c6ea2c72c8c3f8adab61e4e46763cad8f22677bc0e0146d3fab1dafcaf9b25fea643b971faaf40f072297cf7f8fe8f317e4462bf02e72715d138b4d1a511d16ebaeca4c66677b85b81f25384448354b6e3dcecf63e3ddf247640f46d0dd1529ce696936101d44ada92f65ac8a58bcd6291b3e8bc1b414d8847f4d286008b4130a46c2861e1781b1caf1e5201c264c3b3d740d2187b9a4f26938a0c8e1d82b13d7d90b4c3d5e5c670dae701f75aed1face4869ae79af9b5cf1c78ee53d990e6f015f5e5388255a6779d23ac6099a1b23c7f2b4b8ba8c392cdcc782be70c4d5e689c087915f55b1d0fad6481ad8de7f7b5d5a331497666953af47670f0470bddd66347ea48f21a1a5cd24fd55fc396d78e1c16e8c93f450e3a2477494903c12bc0da9b00d2c432d05189f099411d03600b10dd1a97b509cde500109f184231822a94c7b282196f1d910901d10168263e4da9ee6f72845a2bb20420ba202d05f1853decee80e882842bdd020c90d70ac5d18080f86cda802a67c6ddd8055597a6b5fdc2d24b08a2a1cd8e0da56864cc16a5a516bdd438543362ba390d85d272f01af1db959fd474a049202aa75ed1757331a5ee85e3c00aff0043d75d11dae7922f82a0e7601e682af6c2e89c0f21736754a3e8d0a49ad1d8749d7a2c885b1936e3e55919db19b70b05724d3f529719c36bc85b6d2b5efccc41929155dd3d1972adea467b71b7da3618b3c0faeca71821781da965a32e8c6f61b6f7b53f135407daf7014baf5daa6b662957c7a31fa6686cc503816afa089a006d7648d681e1198687017cf7128fcbf6cf51b508e87318ddd56a445b9ced95c0f2921c4749ee56b8d861a2c8b2bd262e0cb7b918277a5d2233305ce7070e559c3b98c0da4e63768aa4abb55d318182db9cba0d7c5829865a4d24015698558bd99d363fd5b0530b8f84cba5edddd426c4749483ea73ca579e50ca041e5c3e5343c72866d783b685023fd4e08487b26daf6ee110a109407bb9ab4f7cc1b6a3be404d844835ee481fc94d7948d202040817b7069ee99ea714113170df2ca093c2001226be77fb4156da768ef73adc15969ba547ed1b792b4b89a7c7101ed0990e901c0d1a3646091cab28719acec11e2686b5a13c504741d1e602d09c5c0849b825e0a24065351084d21020d4a178f64d05420e2109e09efc22f84c70b5080f9038497c7d5136a6572a10f775e3d97bb243d9400d0bcbdb80427cc1bdd072d0121484c3dd4797398dbe5419f52dbcb5ca89e5c6287e0dfa2cdf28677283266c5134ba4918caff0031ae160fae3f11a3e9cc1735acf572a4e238effd573c7757676b1189b3b21fbcf3b2e805c6ccf36ab8fe31db36e3f8f76fb3a76b7f895169d92e8317186435bdde1d56a3e2fe2a413c8d649832b7771c3815caa7d51a0124d93f55459dd4b9514a1b82ddd3dfb00e795c4abc966db3e9f46db30abaa0f66c7f16ba965d473e58e3b6410b012d3dc12b816a39e7f34e7b5c775ae85d5667c4c163b51c873f52c91b9f1dff00ca6fc1fa9ffb2e78fc46c9259f2575f162f9ca76fb3990c693db5e8347afe74386fc464ce18f2b81733e52e28c9c99dae8c965736a469fa14992f04f60b63a1f4d343b716fd92e4e6574c4d9478e725d9a8e9d89fa2f49cf3644cec9cbcb66d85aeed083c127eab118f1ea139c88dd990637a2df6b65247a9f6e3bae8d89a73842d613b83470d3d946c9e968a72e7ba26171f9174b810f2d5a9be68bbfc535e8e3190d966ca77a8c738b4d5dd82a660691919525ec347c5765d423e8781d217edfd88f2aeb4ee9382020ec1fc2d1779eae31d447afc735eca6fc33e92c3c8ce6ff008a3cc71c66c0238715d3f5cff0d8729bf968192d46181ff1dfff00555989a23631710fba91f92362b9a5c1bbcdd93e922f58708fb050c5fd1f45a298456daec961d222c736d68368fe9b99ca7366737bae57f26527f9b2f8c54574488606c745db4296d7338a214074825a174bc3737b14d0ccf8df436b64cc87fb496d5aaf7643e8873bb2f4b2399c38940796b9a52597ca6ff4328066c8d93da6ca6cda7c03dcc8da09fa26629d8ef9525fb9f7f0abe4a1e986516458e38a1366afecac61ce8638e838765552c0f73b82a0ea3065371a5763d994b69a1055ab5ae520eb8ada468f07a9b1e49a48232eb8ff00512d35fca266f5664e2460e2e37ac4fd69730e9dc6ea364ce6e6ce4477cb1a16ff004e81f26d6c8db5af2698e2d8b84b7ffd33d75bb13e4324eb4ea733031c11b1aeec1bcd2e8fd2d26af362366d4d9130b858a36e2a83134c88517378af857b8f96f8a21131c4b5bf2bafe26fb2c9fc938ebf460bf178be997b3ccc6b496d123c2af7eaa1aea0154ba7944ae3bc907c2097b8d9b5e954e52fa1615457b6173b51a90be2344aae7e7e413bc388aee51dcd64ac2eb514c66881d927c2bf63a6814d3be4b797125439277339ec548964019b7cf655f234bde6cf012ca090ea478e6107952b48d4cc7956d14d3dc7caab9a8034bd8ce3112f07b2cb67e0d3458a5bf66ce2d403b39d5dbe0795cb3ffe25b49966d234fd5e18816b242c99cd1c8b1c13f4e0ad869d9ae1900b8f255e6b7a2e375574e65e9796d263c866d2e1ddb5c829702e70b65b166bada3e2a73fc122fb286f89ee71730134b4dd4fd25368dade4e1404cb1472168701cf75d2ba774bd262d2608df8503dfb41717b79b5d9caf210c68296b7b2eaa895a603a2592b1af7ed20fd95ceac751c8696c504cf3e29a4ae818d8d830ffcbc68983e1ad575a73a16b810c681f60bcbe47984ecf97e33a2e95c3848e4fd3d26769fb4e441244476ded216ff0047ea97c540babeaba569383a66a2d306663432078ab73471f62b9c7e27f4545d25910e660cce38d94e706c5fe422b8ff00555c6c8790fcf5a66256c60f8335f85abc7d430330e797826b71f08baff4cc9a4c2ecac621d8f1b3738f92b95689accf0cadf739a4d7ecb7b8dd4d90709d893c8e96391b4772cf3ae354651b63cbf41956f927595b06a6ed425762e3b0d96f9543d51a1e78c532b207465a45d8fd42d6e3419f4cd3f35b92216bbe54eeb1eb5e9d930648a61236703dad6b42d3e32143db4f52fd18b3a329c78eb660342eaf821cccc66697b327201fcb82da0d347853f07a9b55c76fe61d86c32114039c45d1f800f3f75cc759cc932b398f81ae05b25893c0f1c2ef5f875a733a8ba77146706fe621da3d40de4917dcfeebb55d4e6a314f4ce24b1675f68c14df9a9b2a4cacc12174ee2f25c3e7c7d11dd898f1359ea3c3647f2d6792badea9d278598f8f0e460865b2f2e6ff007f3d9637aaba019035fa84196193c0d2ea77f7b473c2a327c45a9b9721a19b740add3b438e7843f6503ded4d6e8d044e236036b35d1fac6b3a86659c6747851b8b1ce77f7adcc30faa4d0f715e4b394e89f16cf478b7cac86da058da14723c5347d15f63e94c8a30daec13f06268da2b956423d8de56cc3aa328f2dec36c9a645c481a1c5b602b38319f5c10a2e2c4df5b975295366c58ad71de385d9c68d708f26ccf2939113332190921e390aae4d69a090d780144d575a8a479a72a964d1c84bad7272bca4a326aa5d1a69c76fd968331d3bef68099a867b31602778b1e1374c7033b5805d9e55a6bfd3381369924af2f6485b6083dd26146cc852922d970ada4ce31d6dd4c260f6b7da01eeb95eabaa4b93bb639c369e696b3ad70e7c695e035c5a1c689551d3ba1ff0088ba46e45b6391a4020795eb7c651084549fb30791b528e93322f9e494d38773cadcfe1f61b6473dc4803c52ce4ba048d93218d78261716d792b5bf8791b23a6ba6646ebaf72d7e4dea87c4f356ff5d9d6fa73a25dade14ce136c737868f04a83aa744eada5b4ba4844ac1d8c7ca1699d75369e26c6c195ae87b077d55f695f88790e7362ca60958e35c2e262df0a61a97b659464c63f8a30d340e6921cd70238a23b28aec71caef0ce9cd2f5bc7131c6634bc5f03959dd6bf0bda039f86f70279af95d755c9ae51f46f53d9c9cc45a3e89a40a577aae839ba5c8639e0701e0d2a7923703d92b4fec640084d2d45da908092488477368a4238462c287b4f951050070412ce5492db29a589862391b5309b4670b4dd8291d041034a44394e8fb1e504b52527849c457d9a1d375d9b1a46b84a457d56fb40eb30fdb1bdf6e5c80170f3c2978b9efc69039a4f0b6d394d154ebd9f46606aaccb602d22ca9f1bef9f0b8b74e7583e1706b9e4571dd74ad1f5e8f2e368dfc90bab55ca68c33ada344bc84d9016d8369c1f615e995e98f4d784ad2948506404b6d0dcc148e42616a6188ce60a422ca0a4b82196da0422b98109ec529cd4c2cb4084273021399c153248e820b99ed5084191963851e48c529a401684e6072842b668852aecbc50e61e15dc9177515d007821006f462f50d3cb5c4816153cf8a003ede56f72f11ae0785419fa716fb80e0aa670d96c24f6649d0b8388a214bc1cc931c8b3c05272610c078e556bf712785cfbaadfa36c67fb37da16bac7b0324a734f1f65619b8eee27c624b4f80b9ae366c98c40174b6dd31d42241e9cae040f95863992a27c5964e88cd6cd13a304117ca1c1139aff0071e10a099cf9695cc1842468253605319be5a0e44f4b4c93891b760e14d650ec81147e9b40051815e8e1eba3972f63fea984a52ee0a139c894b3dbcda5de8767e126e5111042e0532ed25a4b5022a1d8b4ebb4d2cf840830f64c26979cea4c7382843c5f450dd2bad35c79bb4274964fc22323d23c93dd32d21e4af2811c5c1a39519f302edadb44986e6d026d1f4fc12eb2e16900d0fc4c7f54d5714b4ba669829bc71487a6e9a38b1dd683160f4595fc27404836340d8876eca7467daa231d6a446ea098725b4fb53ad476c84a2075a84096bc0a6250a107daf26daf6e5087bc2183ca258a51a595b1936e0dfba9b5f6441ef84db551a9f51e2e0405dbb7bfc06acfc5d6d9064feac40349e36acf2c884475093f46ded34959a675635e46d61521baf3a6a0d6525fe65637c322e2499b1b4d950e4d458d0790a0cf2cd2b4fb955e43650792a8b32dbfea4553df6594dab38df2028326a52bcd6e501c24b3c92bc2373aaed64765b27d97a8a4487cce7ddb8aa8d7b5cc6d0b4e933b31c4068b02f97153a5231a37cd23836268b739dd805c4faefabddd439eec685c3f258eea611d9ff559b225c17fb2fa2af925d14fa9eb13eaba94f9b3c8647c8fba3ff4fec8126a21ac241a50b780287850b267345a02e5fc4a6f6ceec57c71d0fccd5e4758dc54ae9ad6a7d37325ccc78a09666c46bd503db7c123eaa85f77698c79dd74b6c2a8a5d192c5c9e99373a49b3a77cd2b9cf738920bbc84b89a66f702425864042b2c49dbe1576d924ba2eaa88969a6603630282d66998a0341597c3ccd8400b53a56435cd16e00af3d9bce4b6746b4a2b48bdc48b8e54b8f103b92a2e34a38163f65698d442f3b749a23f433f28d8db748b8f1877148ef682394f8760fd205acce6e48a2530f1405b4eec136ff00a8483c1294e4bc8da40fd90cc91b459701f4463371f4ccb2ff0064a1044f1c9694c9a0c48817492b1ad1ceeddd9617adbaea2d16138f8f20f5de3b83d82e7fa6eb7ae6bafc883144933f697364df42c735caef6278c9e4d7f271d6ce75f9b1ad9d1f33ac71e2d6a3c0c48dd3349feac84501f65a882689fb7dc3917dfb2e3bab49d4faa69b8124d8a627c0e22491ad2dff00eaf93c2d368baabf1e06095deea00f3dd1f21e255715c3d8f819aee969ae8ddcac6ca7bf29830eeec2a3c7d798792e5638bad8938dc2970e745913bd0a9241e48dd01b0d27e802f1ca0d145a5bf4215df4f67e33b26b236869ece2bdd671e9ee636581c3d41f1e56da7014f1ddce5dafa28958959c74517e7a21dca18c86c8ef6aa793d491fede15ee89a4cb290e7834ab8e236971edb2d97182d9374ed304ef0e2ca6ad143a763e3b7735a0b92e3630863a028298d881603f55e9b07c2423152bd6d9cbbb25bf40279298da1452e1b8bdf47e53330b7d5ab3c764ec6b89f679b5dfaf1e305f898a53d879a3a929be5025db18f77fa29330246ea50725e43c0ab0a6bb139307036da5ade1398d3b4df829623b0124774a0db4d79447d953a88f4dd7f2ab4e488cb813dd5b6a4d0e6ace663099000a8b031f64d64427889b55d9133a37100f0119b33f1d9b5a6c281992ed04bf8b5867ad97a5d06c7d40895bcf95b8d2f39cfc07b58fa76c76dfa1ae172d8b24364e5dcdad0bba921d1749972a69035ad613dfb9aecb1adfc8b89728f4715d575191fabe5b725dba56ccfdeef93b8ab8d2f54a6016b0791aa7e7b509e727dd2cae93f924ff00dd5d62666c8ebe8ba9938bc924ce85162e26f31f538dcea0f05de42bcc0ca0e228d2e61a4cef6e67aae27bad7c1a918dbb9a7c2e0e660f1e91b2b6a5ece9ba46588e5638ba9bdc927b2b7d4a4c4eab971e189b85990e303bbd57025ae3f0be7cea2fc40d4e10ec7c6218d70a2477a4de8aeb9974fded94bda5dedbabe3e56cf13e2de337759e99e6fcb5af9eab476983a20cd9f264c58916331876b5a482d70af16abbaa74aff0dc9616426389edf68b1e14be9ceae64f8f19c7d45f3cee7777c55b7f92a56a9a56a194ff00cd67005a07b4f1d959e52743adbafd94614ed52efd189f5e58ddfd3242acd658ec967bdbcfcad87f86c46e9a0843c8d123c96edaa5e72acb845ecee239a7e51ac93706f62b7dd13d52ed2e68e2dce6c6e205051e4e963ea380a23e8a7e9dd29b1fb83795be5e421d4d7b42590838bd9d535574b978116a58f28df18164f36dee541927c1d6b02d8d6cce670ede2c84fe9cdb83a7498b3bada7b349f0b35a86bf8fa2cee6e8ac664ef3b726307f4397a77991b7179cdfb3ccd94b85bd16434f8595b6368f1c258f0dcc92c014a3626a12e5c4d73a2f4dce3fa5594523ab6bc510bc1db285d6b7f48edd7b4912b161110b3dd3e7c80c6924a6ba41b2da6caaeca91ee07bad96d8eaaf558c9727d81c9d4a4b223242cc6bba9667a6e6b5e413fddf0afc8be2ad31da49cb1cc56df3c2e5d375b397e4b6688f15d3311a4c79b90f7195ef9b9f2ae63c4c86bacb1cd0b4318c2d398620d6b5df651727526004066f5bede1adb7dfe8684e482e915ebb03a81279256af58d634dc0d2dcfca70746c6fb881647d960a2932dceded6709d33bd663e19e3fd7de9ff00f65b7c566538b196fdb3264a9cfd1cebae7abb42d4e39a1d2f02573dc7dd2ca48afb05affc36e9cd3359e9bd9331ec940ad8f656dbf829bff0ae8f1b8bcc6d166edd5dd5d6899189a5769db1c6c157e1a174b1fce571b12e1d1ccb689c976737ebbfc2bced220fcde8c25cc7b64b99ace4d7cd2e71160be2c87479123f1a66ff00d393db56be9d1ade9f8b90e93088caf578710ee0ae71d79d2b06bdaebf50c383d2dc035c09e090176edf21428f26cc90c3b26f8fd192e91ea08b449f664c4cca865fe9b98e65f1f23eab7bd3ba4c7ab6a113b0849262eedc0b872dfa1594c1e887ba402561aedfb2ec9d07a247a1e335f8d23f9a0f85dd8fd42e2db7d3758a316097859c1f293d9bcd2b0ff2b8cc6576014f6b6c1498e592c41ed23b76f849bc006d7a8a1250491628f1e8aed5f49c3ca86e78c5792b9df557e1dfacd393a6d7ff002aea32ccc7336b858faa17f49cda00049624df639f3666e9d3e0cc629e3731c3c90a2f85df3a8ba330b58697be2fea91c1a5c9fa93a33374691ce630be2bee3c2cb2a9fb439982908e12ba9ae209ff00d97a926b5d31903213484421211c76434122962616a92589a6308ec846db69a5b4a4165764c2c250011ee934946315f84d3094f17a06874123a378702b57d3fd45262c8039c69659b09aa169e3731dc7042d355bc58b2826bb3b9689afb32da0178fdcad2e33c39962a9707d0f5c9719e1bb8f07e574fd03a87d789ad2ee6975e9bd4918a74f166c014b68104dea301e1141b5a52fb297d1e48e1c271a4d25303609c10c84621308506405c130b42339a86e080413da0a0bd8119c109c102119cc1cf0845a39524b7ba0c8142222be304a149106f2a411ca0ca0950257ced07c2aecac71270ae25670a1c8c02ca0432b9f80003c2a0cb883090b6f9b00702b33a8618de557386d0f097666e590b4f0a7e9399e8bda6f950f3210c7150e391d13edb6b83998bcd7674abb348ed58b86e2eb2b43831ec868f3c26c5035be023b780405dac5c7554748e7dd7bb05dbe5349aec9dba8526120f65a91996cf17121352f749489069e3ca694a53094082da4b49e1337d2241e5c90be82119026ba4348046cafa4073ec14923ed05ceafaa81d0a6426c26ee4d25228c2102473f6f74c2f0d092189f3ce3c352ec88978b03a5703b785a3c0c2b6555289818b555d95f40c11b69048849c68db1441a00b520389416516fd93c584cb640ad4fb23c9410e4fb4c12444ffba90d70faa84c79456486fba842587a50e400fbf29c1d4a1030e52129824f0179cf01ae75f0144d6f4c9b1e5d468ae7dd7b999c751861c598b230db751f2af75feb1c2d1e1754a1f2d5000ae4daf7574ba8e53897017f0573b3b2e35c748d14d0e658bb52ca92731ca2c0e377ca950bc902cf9eca830f38ca47b8dfdd5ee10f52979b9f90537c51d1863b82ecb6d3e3739ddb85a9c3c20630ee02afd3700fa01c452b6c42f8810790b6d507c76c46fe90af6804848cc76cddc04b2105e4a58cfba82b549c5f424a20b234d635b6d0a10836ba8ab778716904aaf9585af56ab7becab5b4613f15f586e97d37f9563809b25db5b5fe5f2b841f630f15cdd2de7e2b6aafccea37e339c766334068f827b9ff004580c894b8d3572ee9f39b3b38752843649c287d7f51d5c3459fb283911b4b8ab1c39db0e932900ef91db01fa2ac2d2f36ab8ad3f66a6f642962b75784dfcbd0e54e7e349561a4f296481c40f695a3e44915f020369a0f2a4634bb0f74e6e1177349c30de0f0d41ce2d0609a61db985bdac708da46b199f993bdc7683c0515b892eeaa2a6c38663ae3959e7f1f169972e5b3a068fa8191adbae56a71270e685ce349c87c35656b74fcf1b7972f2b9d8ddb7134f1da34ee362fc28d2e536037b801e54766a40464ee15f558cebaea9c7c5d2e66c136ec93d830dd2c7898165b62868c96ca3545c993ba9bf12b1748061c70259c7737c2c6e6fe31e6386c10c760d763ff00aac0e5e73b25cf7b89dcef24aa7748e19047279e3eabde61f80c6847f25b679abf3e7393e2fa36399a97f8ae4bb32688b89f71b375f60b51a074c6ad95843330726289ae8ccdc3b9007347eab9db72decc62cb2d24515a7e9aeabd534dc09f02090371f319b5c368247d8f70b55b438474bd2399646764bb2ff49d627cbc69609a59dd287d925e4b5df42158b592168a278555a546e6b0581cfd2968b07691ee16bcfe659f9ecf55e37178c3656bb265c6751255861674ae7b4071ec9f9da61c8171b0928787a2e731dbc31e2bc52ce946d5a5eceb39f1f66b7132a5f4a27b493e1ff753721b914d74a490ee42abd39934545ed26bc10b5d838b26a38b1091800672081fe8b911a5ce6e312ab66a2b6c668da53271bdf12d4e26347143ed680425c1c3663c22f852a2635ac713d97adc0f1d1a129bf6722dbdc9fb0503378a20224b13b66d06a92e36d7bebb046cbdb137baeca69228d94d2b7fab64926d5bc10c73421e45387c2ae2d0e7fdd5a418eef44907c2aa32d81a2264646db8f6850dc448eec2d7b2b76e250a391b557ca5df62e8398ed94a24b37a4e0c53f11a2699cd50b53c47372406b6f8b4afd74322bf325697ed2555e744d0eb61b45d53744ea3c39446c96cb7775925265912b731ef040068aafd57236c3cf24052f349926a69f2b3fd4d9871213bac5f6faaa1c7916c5f7a2b72b558e1697b9f55cac3f587566467b46336470801b207944d5b53748c759593cf90ba37389e16ac0c45cb948b676a51d0ec4addbc1042b3832f7b8059ac0cb6876d2ea5718a1c5e2b907caeadf577f915d17f7a4697124db4415690654b2dc51024d2aad3a3b70dc682d7e8470a1c9b2e6b8d72179eca9a86deb6752163d197c9d2667e407cacbbf90b43a268b010373458fa2bfcac6c5ca34cab5230345a6ee61705cebbc8c9d5a7d09c60fd9374cc58b140f4981a7e42d28d47332f1db0c931731bd82adc4c031b06ee4ab4c6808003415e7aebe6fa8b2463147a0c673ae870a4478ae3c52b0c183b8229487c4187808d587f276c495897a2be1d20c22dc01b5321c368e40aa565080e651010b303a38f731a3b2ecd5e3e108f2324ec652eb7acc1a263faf3c81805d12b19a0750e9397a865c72b5acf51c641234fb9e49f3f450bf1033750ca734b606ccd88d0691c1543d10661accb31d123903ff53651c379f0b5575c2cadf29691caba73762d23ade9ee6c843a2ec3b2b5746e7b8b8f1c2aac68c36664b142206d72c07857f8ecfccb3db478f95c4c7c74a52847b3af09b70ed1183c06ed07dc984eeb0425cb67e5ddb80e57bd6dcc1c515a9d292ec9196cf62e332593ecac7332e0d3f15c0328edee5074d6dcbc58b44d5f4697286edced9e42e978fc7ff8e4e085b1f7d9cbb54d70bb2e4928d5a8d8fd491b64f7114b57ac74245342e744e735fdf95cc759d133b4c9a4b63b603faa964780b7b9fb36d56a92d1d2b4fd770b220a2e6b0aa9d77528610e114b77d8ae6526bb9188ddae2e6a8195d4d2c8082f3fca75e2e736b65ae31fb66bf664654bb9f9afa3fdb6b53a6cd8d169b2624b09977b68b89e5727d375e90647ba4201f92b79a46a4266b6fcf9532e8b69f457fc7adf68b985a30583d280168ec1a6949d3f5ac2cc73a17b0c6f65820f28b8cc66445ed160f04203b478da5c583d32efd45bdd7194e136d58fff00a4709456a08d045a6c2c70734b1f7e02d0e9384fdc0dd0f859cd226970f4c2279a295b17fcbdd41ce5b4e97cc8f53d3c4fe87a2f6bb6399775f55d9f1de36b95df246664b6e6a3a68b7c38cc34cb1445a2490124d1eea3674cf871cbe31c854c35d98902d7aa965471ff0006733e294ded17c718d72bccc66b7caadc7d5647f0e563165b5cce4215e5d56befd81d6e3ec796715e02879fa64199118e460208f2a697ee1c14d3d8df75b3ff005e80726eaffc386c4c764e0b689e481d97369227c12ba29185a5a6b95f4c6447bdb4e008f8585eade88c7d4c3df031ac98f208e1516437da22d9c7f6824fd1788e3b2b2d4343ced3647367c778038dd5dd57920dfd151a683b04e60a42704704722ed31e120db2394805a7909cc6d9eca018c11f29cd84b91db159a015861e203ddb6939680980d3b4f0e77b958e4686c7c76c1eea5370f1db7c342b7834f13d592168a60d94cecd3ece793e3c9872f208e55ee87ad3b1dedb7114af759e9c86480bb69240bb58a7b0e34a6c105a56d49c3b4452524766d0b5a190c6fbbbad340f0f65ae3dd37ab9639a372e97a5e7fa91b0837c2ea51729c74649c7b2e69252f35c1c2d796829684a4da4f48428188270e0a1382311c143280e05c105c14872139a81111ddc21385a3b859ec86e0a048cf6f74070529c294795b5ca8422c83ba8b236c29af1c1519cda0a10ae9d963b2a5d431aec80b453b452afc8843ef8f083405ecc16a38a6dc69543985a4f0b67a8e1727859ecac4da4f0b35b56d1a6b91df0000240eab480f09a4ad665f6389be69341fa246baec26975222b09698e7526efe130bad420e250c9e5212525f0a105be109ceaee9db931c479508337a6b9e2bba47b804173d40a18e26930381ee91efae132d418713ca41f2909e10e49046eda7ca56043e389d34bf4575838dd85283a6c45cefa15a1c38082384a1266145e9d0a53d062606f28c394e883e3e01e5103ed037505edfc2242407794ef52c70a2879e794e6c9ff0099426c961f4139b28ae542328f94d33579509b2c5b2809debb7e555bb2c01dd467ea8d8892e3c053a0722e27d421c689d23de1a00beeb03d43d7d3640743876c68357f2a0f587507a8f8f1a2908b3668f85999246979b757c2c77cdfd16d6b641d435491fea3e5248ffccb247507bf2c917b6d59f51e670f89bc159b88906d79dcc96fa3b98b1e2b66b303520c2d25dcad5e87aa7af3340f0b9c62c85cf016a34097d19413c2f3d28aae7c8d724a48ef3a33db9386ca1c808d21dae2da59de8ed558e84c6e773e16888323edbcaf598f38d94ad339938b8c81bd84f646c485c5d67b22c30d8e514bc422800ac8c35dc84724c431edbb5132197740224b98082b35d57afcba4e959399100e744c2402a4a71f4846bad9c6bf1474f923eae9c806a6602d3f2b18fd1f2c92e631c42375675b6a5ace5b33321f1ef670d681d82bbe8dd4ceb18ef74a07f4bf55795cab20e1b9fd1d0c6c8e4944ad7e1fa78d042fa1b793f742fc831a7da2d5b6a9235d39696ed2deca3400925bdc9582563f68e8a4061c42e70018a7bf466c91fb472acb0b14067b9a2d5be2e237658eeb23be5296a237497662dfa04a3903f64e8f47940a730adc1c66d7609acc11774295ae56e8af9a465e2d0dae613b4da4ff00082382d2005b466136a80da993e9edf4f8702550ddcbd8caf8a6634e33a269e2a942c9d42486373438fec56a3334edf1b83451598d4b4a746c717129e99272fccd0ad4e3d196cfd5736ddb322403e37159dc8ca99ef3b9ee2e3e3c1575ae31d8d139cd1cac98c97c727aae37cf65ea70ab8b8ed23cee758f9686cce9093c114871b88903bcb4dab513c52873dcc1ee0ab4c6d32b8b3b785d35a47235b64d709332de455f80ae340c23265451176db3dca85a7467d3a7053e37985c1cd344762b9f7cdb4e28ea63e3af6ce810c2cc366d2f0eafaa9d8734576b1b8194e919724a49efdd5b6064bb7d02bcd5f8cfbd9e8f1e3f8e8dde14ec7b0004585b7e986e0e661cd066c31fa8df731fb793f4bf0b9b688f73e4da7e5749d0f10398de2c95c49e44f1ac5f1adbf41caa54a1dbf4099a0b0658d9c309baaffeed6971308441ac670d09a71c366b1d80e14d868117e57a5f1784abfca4bb671eeb1b5a084069da7b20e5cadc78092f1c9da07d51321cd63c5a899a20c99e08f921a770af2575ec7c568c690ac13b2105ed2c2852e44ee706936d0af5f1c6ec701c3954f93054db5bd8a128b4ba6400c86495dbdaeb015ac196e8a0707780858f8ae8a2b5e690f639a5245710ec825c65693454590c71fea3b7eaa5b5cd658f82a06635b3c6e6dd14ac9b0ba4677a72b9ce75856f0566c864e38e02c5be7fca3b639caf341d5226101eff000a9859c5ea43f16d15fd578ee832ef69a2382b339193e8025c69744ea07e3ea18048adede415cd755607b4b414976b7b4182fd91a2c812bcbaf8f958ff00c43d518e38f00345b67eeaf9b3b71e195c0f118dceb5ca7aa75f6ea19ef95ce01806d0aca2ae483c927b21e63f734acbeab9e180c4d3651b3b5bdc0b1a5504cf74b21738ddaee626338f72325d727e8f3642d3615862eb1938d5b0ee0156a363b6dc16f9462d768a2b9b5e8d8e9bd4523c3778e56aba7def9b2a59412411c2c5e069ce984662ef6385d33a474591b23439bed205af2fe4e755506d1d3c794e6d2df45d45a665cd8fba39b61f957dd238b998d96464e4faccaed4694dc783d28c343000ac31e68a037ed0e5e22dccdae3ada35ca3a7ecd1c58f18683b1a118060e1ad0aa22cf2f6f127f08f1e5ede4959de5d696b88ca127e8bbc78f8baa457b1b77c2af83568f6ed7795e9f39ae1ec2b4c33aa4b4901d725fd89feb867901469f5168b0082ab0cef909dceb50729ee69e0959e5e52725c628b6bc7fb6172e286773b73186f94cc3c1c7fcc470b035ae90d37ea7ba84ecca7774f8b52640f6ca2896f7bf03e8aaa652762727d0ee85c5e9167a86b5a6e80f10e7e408dc45b5a3927f85ee9feb3d1757c97418596f64e013b48eeb9c75c363d5f29d2c32ba1b6d35cf37489d3d8fa563e892e4e0b9edd5f14537cfa9439ff005b5e9b1ebab5ca070326fc886f6ba3b4cd019e20f908362f8559330b486b05fdb9590d23afb362d2dd26542f9e56f2ed8d35183dad5ff4aebf2752e33e69210d8c1e1c1b5fb775664d719c7f1436367467d1a3d2698f05c15bcf90e7fb5a3855d8cd646d22b69f8f8537d46860f95b7053aebe1b35cff27b05286885e5e3c2cb6a5a741961cc9030b4fc85a5cc76e81c07c2ce649702794b9324ba0c368caeafd1ba766376181975dc05cefa83a0998b13dd1b08ab2176333063f69e4577597eb290334e79679e16385928bda65e9b382881d8f945849e0addf4dcee923601cd2cbe6e13ff0031b883c9e2d6bba530dd6de385a73ed4eae4cd98eba3a6f4be00ca958c269a55df5168c311b1cb134d3f82103a3a1114cc79f016975377e6410e2280e02e778fc0af231a6e4bb28bad709e9184961734d3db44fd385a0e8fcf38b9871cbdde9bfc1ed6aab54706cfb49aa47d219ff89649dbe0ae5c13c7cae307d263cb8ceb7d1d1676896078aab0b3834b9237921a48bb57f8d90c9a00d0ee40532010cb151aecbdcce88e4b8cb7d9c9537599fc5841751e15a3228d8d00b80b40cdc6f4cb9cce164b56d4730ced10bc80d2b25b2863bea249cf92d9b76168ece057aed66b4e3a8b9ed7b9d6168da5db01777ae57471ee564768cd16f7d88eedca8efc7de2e823978be534ee7723b2bd7fa1b657e4e9906434b248dae691e47758cea0fc32c5cc2e9b0cfa2f3e1743d8e23b21381ba2109288347cff00ad7496a5a2bee488c8cff300a909e6bb2fa4f2f0a1cc8cc591107070a229733eb0fc3910efcbd3070397467cacf3a75e83b39bd0a4f8c527bf1df192d730b5c3823e112286ff00576546bbd11be8262377baa95be147eee558f4a682cce26497860eca4750e92ed39ee9b19bb98c0ac8d0df65129898d0b431ae0afb021698cd770aa3a73231f536d34fbfc85a98628b0e32f7f60395be9492334a5b1aec28dd8e5d29007d5733eab18edc97b6302fe8b45d4bd5cd11be184edae173ccccc7e4485ce7124a175a974595c5fb26693905b28e574ae9bd40d3413e17228324c4ee16cba6b5471ae7b2af16dd48b6c8268ebf8d307b072a48ee550e8f95eac4395791bec52eec1ed191a084243d93a92526d0a9023c03684519e2d088a418c08f6487f4a7909b5e1022239ee78437f28ce6d14370e14091dcdb4299b6da524842907086c8417b78ec83b7836a639bc2048006a2420c8cbb51648f93c29b20a5166164a8429b50878269653526169752d9e656d20ace6763090b924d74345e8eb80f0984f252594d71a5695a14943714bbc521eee4a82b17724ddf549bc14d2e0a10573d0cbd35eeb099654204dc10cbaca4b48485080e43ca0b8a7ca501ce502847775e4ddcbd6a0c3ac521c6d64b38245d26643dcd85c4774ed183a536e1ca49322468b4f801e42bcc78c8ae15769d1ecae15e424003841220ad4a5fb12915d90e41613a0085f69864a4c2e4091e7e5120732a6fe629463271dd05d29b503a27fe65a9afcb6d0fbaab7e4968eea0e66a66361e4204d16b9da93236b8870e16235aeaf6e307dbc0a55faf7533a18a4f70baef6b916bfd4936548f66f344f7b54ce5ec68c36cd5c9d5e350d5dd44b88ecada6cc30c7eb38d58ecb21d07a687997372478f6dab4d5f2df317323340714b95936b8a66ea2b4bb643cb9dd9729738a8e1a6f84b1432b8d0b5371f4f95c7b15c67b93db3a319686e312d7f7573852bec06d92970742924702415a2d3f40f4dc1ff00e8b1d98fcd8ff2a459f4c646542f069cba7e93964b1a643cd79590d2e08e1881a16ad9b93b5bc15d2c4aa352f666b5f2355366c6d1c382aec9d43b96bbb2a37ea2f23ba8536a06882569bb213455181732e7d8243d64baeb2cc9d359ed61b798d1a4d4434104aa5d47506ca1f138ee6b8510b14ae4bb1dc76b4704fcbbb2662d2e3c77b5d03a730a0d33a78be277f5a53b9c9bab68982d73a58c869bb42c6ca8db07e5d8efa2a2fc8738e91762d718bec59c7e65db8775e869b20be08428a40d948f09642e71b0b024f5a3acb6d9a6c5c989cd03e15a629696f0b18dcf38d18739d415de95ade3cacade3f754d54494f684bda48bfb0e468580aae8f3a2712039aa54396cfed782b7a7a5a662e499665a36a8ef14384bebdb2bfd50de4fca3ec896d9172e201b60aa1d4213235ce702405a1783441e544c9884916da0165b68dcb68d35cb499c8fa9b2b1a12e85cfe7e3e16563c3fcfc876021bf5f2b63aff004b3a5d4649cf2094e8b4385b1b4369ae03c2efd17c2bad453ece4caa959636ccbb74d113446ce6c21334e7324a2df2b412e1b992503652c986591ee70e532ca7fb2e588885e9b58cfb203adc784495cedc426b1bdd48f5db34c63f489386d7823e169b4c8b739aa930d8c2c1fe6f85a2d31be9d38dae765cfa675f1d746d7a7b143a46f1d9750e9e846c276f60b9ef4cc7b9a1ecbe42ea9a2e37a5a70711cbb95e6f12a77672fd22af21628c742eddeff9a4f6873a41c2f43ee7103ba901a6305cef0bdcc63fa3cf3640cb9c07ec3dd05b8ef8e43234dda1d9c9cd71a340ab48e10d65d72aab20a7d30264376a92c40870b5099ab7a9921a5b5ca2ea3fd371002ab6d99438774966e0968896cda45231d071e42a4d466971c6d67627947c6c97320b7760aa357d5dadabfd29a53dc361d076c12cf192d344a0330df1b1fea93f7533a7f50832a3354acf518637e2f1c248b4c9c4e75ad07070f242858b9b3324007015c6ad0825c0770a8d9b98f24ac374137d1656d97cfd5c9c7d84f34b3f96e2775106fe50f2b2cc6d772ab1da8ddf37f28c7ad447d7d981ebfea17694f96189ef63a414f17c10b93e66a4fc827dc7bdad1fe20ea8ed575995cd04b23f6dac8d734bd3e1d118c137ece6dd636f4861e5376a26d5ef4cbbb2de999920748f8c3de1208bdbcf747821b70abee9652e87874fb363a034ba48f6f8a5dbba5e2c78f0c4af0da23924d00b8c69b2418388d948a247f2b57165e6eaba4458d1bdd133f51a3dd78cf2f8d2b5e9bd23b18b6a841fdb3a36a3afe9f006c70e544e7bb8dade4a8707e7b28384476b5dfdf2735f6592e9de909a3cd6cf2c85c015d23171591c6d0dbe02f35951a71faadecba9539bdb1b898f95a763b0ba56c84ff7289a8eb3362c9cf20f7a566f06bdc6c0f05556a6c8dcd738d2c1538ce7b92f674ea5a411bafb0b5a771edd9566a5d559114bf9685ef63cf77edba58fd775b769b9cc1bfda4f2d06ad6cb42eb6d35d801831229a69db5bdc412d5da878d518fcad747232f22c9cd5559a3d0e6ca76301932b6577c857b061b338ed79daa8b46c889f1b5a280f85b3d1a38def6d1e579f54f3c950fdb36ea5086d947aaf45cf1b4498f239e1c2fecb15aa643b4f91d03dc0c80ed02f95db75d967c5d1a6971a30e918db00f95f2d75b3f57c8ca933a78dcd8dcfa3b5c281257a8ff001515351317f324a2d973d49a36b90633730e13cc0e1bb70e78fa8f0b3ba4ebf3e9b23fd293d373851fa82b59d07afeb79ba7498321c8c981a0c6e88510d6fcf7ff005557d5da06242d1362500d14479b5d0f8e1525032293bd34cebdf8599da6e7e8061f5e1967ba919c8dbc1e0fcad3e9bd3585a74d912e244d8bd575967f68fb05f2d68bfe218794d97032a48656d1dcd24515f40683d70ec9d1a08f221f4f31941ee69e1ff556dd7d10af8cfd99a8c3b213e91a6ce0e84fb38213e39a67b00bb7528391aa479b8cd9622d2e1c23e9f900ed7ef0e374405cbaaf84a7c60fd9d755c92ed0b364968731dfa957ea8238e160adb206d93f2a6e5b5b34af94102beaa9b5bc93363c6d229cce2c7c2b1ebb4d935f654e46608b758249598ea2d4d8c884520bddcd7c299d459434d929e4935c7fbae7bad6b4f9a52e73c9e3c954d7194a5a4698afc7610e9eecdc932f76f85a9d131d98db401cac968badb9ecf49adbe56aa09c31ad37eea54e629afc19ba85f8ece89d333b4cb448015cea4e6b1cfc91c39a3804f75cf74ad50c043838836ad73f559739cda2e02ab8f2a8c4ce78f54abfd99afa394b915d9fa83dd2bdceb249ee8fa46a12c730e5c5b7fa545947a8680e55be85a34cfdb38e5a0fc2c49bb65bfb19a5181d034e89efc133463dc5b607d556e9faacec9047335c0dab1d2b2063c1b5e6b8a0a7e99a7c73b5ef958d24f6242f5f0c695d18b84b4d1c4b6ee12d30ac709a2e4d8214393a7f172c9706fbbe55b0d3dac0434f08d8f008fb72bad5d129a4ad5e8a2562d74675f86fd34fb4921325d5446ce4ad06746c7c6eba584d7a51039c01be5265a74f51f43531e44e3ad46e78f7729d9fd5983a4623a792392573470c68eeb1c335ac782f26ad4fc7d56394398f0d2df16b90f3f8a693ecd1fc76d7469fa4ba924d7daf7cb8e6261e5a08ae1689d8f19e6965b4acb8236ffe169a40f7057116a6eaa715b70729f06a6f6ccf2a649e8b2fcb35c08a0a0656810cb3095ce7b481fa41e1c8accc0e7f0ea0a6b261576ba954e328e8adc5a38df5f7497a723b2b1db567dc02c4b719cc9034b4f7a5df7a87023cec59050a23bfd5721d4b4e76266fa4ef69be0959ec8699345d6932371301a19dc8e50357d519f977c6e3b8b8765186508b18b7b1aae151e5e5174a4d9525669691571d8fd063760ea0d99aedad3c956dd49d540c26089d5cf70b3efca31c64f9f954f973998f3caa95ac91a7b199997eb9712e2e2556b9ddd1e4ee5463e527bf65ea3a5a197cad3e83148ec77e4b6aa37721658d0777573a06a0f80fa009daf3c8f94697c595491d4ba6f3b7002d6cb15fbbcae6dd3321dc395d074f75b41257a1c69724669ad166941a48280fbaf585a4ac63c794228eee4209ee832b7db0442616a29ec984f14868b10270e1336f1ca7bfb7050b95344d8c9001d947909a521c5024509b00efd280f05c2ad18da0bc381e14d136469586bba8b2b3eaa5c8d37ca8f2b68a012bb221dd77d952e6421a5d416827ec553e591ca120a3a01726487dbc26dda6da6106b8d36d0cc9b5b67ca7bd01ce148907b0827ca5721b5c425dca004a4852920242428406e34bc6a93253cd26efe140686c87ba8eee519c775a1116a050c3c240e29c4709b4a0c32736ddbe4a9fa443b2a8282e6d9bb56fa60e0256886870a3dc158c6da1c950b12c30298c048e542062531dc82940faa47020145108cf686d9e5467bad4999d4d254173c725120c79e0f2a24b286826d16592ad55e5cdc1e50083caceda0f2a8355d4298ea28b9f9140f2b3ba8365c9b6b0937dd236431fd57aabdcd7319cfd82a0d13a533b5697d67c4e6c37765749c3d0711aedf911891df055db5f0c717a71c6d60aa01a3858adbd25d17d713278fa74903598ec1b40e282b38ba59d27b88bfba96cc690e58781edbe568f1980b7806e972672e6fb36c17450e374cc4ce4b7956b8da1c11f3b47eeaca28bdd640a45742defcaccff0043f6061c189838600a4b626b00a0135ceda383c043fccfd5532b12192d9359357d139d9543baab765d1a43932f8eeb3fcb26f48b12d13e4cb14402abb273a8104a813ea1e9b492ea54f99aaf0ef7856ebf62a64ecad518c0773e965f51d7763dc5af50b57d606d203812b319b9e5e4f28aa797a232db375774ad23777556ccf30c9bacaaf19049e51e06090db870ae54a8aec307df46971e51342d985d1532221d1b89f0abf4eca83f2edc71c39bf2a63dc7d1219de973ad8ea475ab9ee0566ad981b0b87c2cc1d77271ddfd2908a3c2b4d424df1cac770b3e607f86120795d8c3aa3c7b393977b6f48d2e97d539ae63c3c926b8215a69fd51991ca0bee8fcac6e34d2b1fc821be5591cf876303cd27b7123233c2dfd9d8f48d446440d73dddd596f6b9b60dac3f476509a16d12e67d4ad843ec1b6c2e3ca128cb4d1beb698671f6135d943ca240701fb23999a3db7cacdcb1eaf1eb33492bcbb11c3d83e39525a6bb2ded7a1b9910923735db6ed67339be993b4d2d1666fa2e2167333def72cf4b6e5d96a5d7a2a9cf3bc9ee8e58e9a2b1d91598cd9dc06dfe14b8310c7fd33fa56c95897a1a28cee76098da5e3ca891c7e56a33b07d48dcd68b37c059cc993f2e1c037906a96ba2de6b427151ed96ba6c6252d0401cf75aed37003982c5ac3e8d9b23e50d737eb6ba6f4ec43263650375d972bc9ca503a144e3c4da747e9a26d8d0280eeba4c7018b19ad60edc2a2e99d2460c0d738539de15b644b240f0d6b95de131386ee92ee47233aee52e884d2f83308f16ad1c3d462a9c9d443a76fa8007578fbab28266c8c1cf3f0bbb09adb460fa05162b23937579535cd6ec343b2198f94b3bfd285ce3da91e3a7b1514f990ef949728f1e20f53f423e44f603bfd148c6958404925163a07200d88b48a594ea0c77c8df68e015adca7077654f9d00918e0ef8554e1b8689f665f4cd59fa749b2a96aceb5ea62fcf1f2b1d3c0d192ebf0a7c392190966d5ca8ce519e8b7e8667e635ce27e553cf2d1e11f32406c855734c3e53c9b0c2242d5731b1b0973a82c96afd478b8d8537a126f9a8803e14dea6ce10c67dd43cac0679602f941a6382bb0eb6e7b9146558e0ba3219d9523e4937721eebb2a091cda9f90d6bc3803e6ed43aa35dd7acaffa9cf4f976c68168b18a09ad693e1103081749f63242803ba3415bc140dde291a0203c5aae5d93a34bff00ed03131c1f75dbbecbaa685870410465c380070b9369326fce6bbcb5bc2de69da94db447c9e5798f2f0949714ce860ca3bed1d031b36185b40008e7586347056460c87b8d7a81c7e029c71e72386937e5794962477f91de838e8b4c9d7806916b3babf5208a279ddcfd11ce979339da1aee4f743cae86c99a22fe1d66aad6ac6a698496c329f14f89ce7549a6d5324c8e26bc2b2e9b0ec5968b8d2b9cae89cac691db632e6b7e1476e97361c9ee639a4726c760bbd3c984ebe1139f0a529f266f746d4b6b1a0bb95bde9ed6e38641bddc5775c874f95cda06c15a7d3335e1a1b66cf65e57268e13f923ed1d3718ce2d33aaea5d4d04b88f84bc1dc0ae01f880dc87c3336373b6176edbe3badfb77bde00249f843cde97fcfc60c8ce7ecb663e7db29c64d6f4736cc6871d6cc7747f56cefc4c5c66e1c38d99840c6276d8f598eee1c3cf65135dc991f3c8c7369bdeefc9eeb5cde8e8f03faa18da23935ca147a269d92e01f13a4759bb3f55b6dce5292da28a68e0ce7f8b92609adbdafb2d769baab8307ba8854baee8bfe1d90e6ed2d1e142c6ca742f0377085d5c2f8ecdf5496ce8f85ac3e806c847d159e2f52c9812dc6f6d91cee5cfb1b540c1c3a8a6e56a8f3203bad73161494b6ba362e32f6749ff888bdc64320b7724784d76bf01639b37b83bc8ee173c8b56706feaff54b36aae2de5ca2c5b14b6d81d10d0eeafd7db93932358eb637b13dd73bd63521b480ee5596bb94e32b9cd3c1f0b1ba8fa93494d3dd7a9f1f8cb49b39d7cdd6b48d9f4be4b3d369079f36b5f8d9248ef6b01d371ba18da0ad9e193b470b0791ae3cdb46cc69ee0687065dce0b55a4c64c8c25a1c0770b1fa79f3f0b63d3992c8b2237bc6e6f91f2bcf5ea2a69bf45d6b7ae8959da2cb146ecc8dbfd3f853ba775091db708c818d71e1c51b54cc98633d9136a07783e15163be9e0b05869ee87f2210b79d5d98bfb474ce87043b1a5a69db7b91e55f698eb8810b35d3f334e386bdddc71657b2faeb46d03546e99959404eeec00e17b1f1b7c611f924f470f321f9248da6e49b8055b8daf62e5303e37820f62967cc716ee8cdfd1761791a67fd599655ca226b198c8b1dc6b95ccf5ccf12ce6dcb5daf4d972623f6c4fbaf85cbf3e59bf34ef541690790785c5f2d95eb89bf112093c97c92697a29fb528e5d6da3ca631c58ebbe1792c8849cb676a1ae25ce2eab26349b9ae36ae61ea42f687171e3eab25ea0773d95465f52c78390e841047956e15f6a7a464ca9d75ae527a34bd47f8852614ac8f15f6f69e7e16c3a2bad0f506311280246f06970fced2e4c89e2cb89ee9192b8020f34bb3fe1ef493f4cd3c4cf616ba501df65ddc5764e7b8b3895dae7637f46d1d236588b2b8598d7ba663d4a173c00246f2085a96401aca4c741ed217793725d964bfd1c5f53c3931e43110416f1f754b911ed3eeb5d5baaf400f8fd78dbee1c9a5cdb508773e80f34b3da9c430d14d30f6904955d2fb4956992dab0ab27068aa132c22c9cf2a249c120294e1c28f2349369d3011cb7947c27fa7234d9053084e8000f169a1ec5d1d07a6722b68b5d1f4c797c4002b96f4c9b734fd574ed19d4c6aeee23e8c36fb2f5bfa40f80bcbc08f09696e4676c4dc7b261145293492ed0047d8c70e109dd915dd909c141c11f29bc729c4213eda1408c7f7427f2884da13cd28400e4091c42904da04a39a408457b9c4a048ebee8f29da6946988d9d94091329e037854f9409ba56b3b6c2adca15d9230a36e1dc24b4ddc9a49b4c28f7fe95149e794624d774c201440c41c04c73a92dd84d7a22a634bb70ee9a1e7b250c3498e3b540a629702980dda76f0426d850834f00a637cda7929808b5080dc5dbbb2f271fd4535406c63fbab5d25c5c3ecab39b561a54d44b690191adc201cc0a73597c00abb4e76d16ad038f04051045645f29648e9a8910b288f8ada512153386d1b55f306b45ab7c98768255366f634810aecac802c2a3cfc90d07dcac73ae8ac7eab965b296ee54ca7a21ec891b34b5caf1dac1ede14064a48ded3ca2b1ee71e573efcafa46baa9fb61459723c518bbb4d85a094dc999b8b1b9e7c2c1b726684922c71d841ec14f81c1ae34b298bd406407637953f1b5274c78bdcabb20d763a7b34d1bc14af70ae0aaec67ccefed472d9777e9599c5bec643defb045a8c5bb8f251df0c9c7b4f64d30bf69e28acf2a5b2c441c97be33c72101f292de429f2639af77755d9d1c8c8888eb7278d6a2b6c3b2a35592c1e6a965352cc2c2435cad75566788dcf744eafa72b25a8caf24dd82843f26492d10f2f2b7124bacaae74bbdd69321cedc421c609b2ba55c12456d963a6619ca90d8e02d56068914918b205acf69394d85a7c12b45a7e71681e405cfca94f7d1b2982d6c4c8e9f744e73a325c19ddc025c49bd3243c761e56f744d7f4c3d2d9d839786d6e44b7e9ce477becb1f918acbdacf23b859ad7a4b65b437b68cb6bd1b58f13b45c6e346be57b1598f241c6d5712699bc3a3772c777b593cdc79f4ec87c7640bf6fd42e8e25c9ad231654384b65d3702020ed0152eb3a596ed733b5a7e36af2630a7349faa76b39a26c063e17725dcae8572efb3158d6b68174e1d71b940e9e64203f9e7da42edf82f924c385f334094b46e03c15cebf0ee2323c3bfb4936ba644d1543b2c59738366ac6da5d8e0d6fc774d7c360b68514f68e52d8be5736cd68df1652eb508663fb452ca4b0db8d85b0d6ecc41a3cacf987ddc8585cf8b2e8b2b2280b5dc70a63622e1f508d4c68ecbcd77c051cdb185c2c50f9addd877551d4bd34ec4ce6cac8c9866e6fc5ad061c323e4b6dddad0cda4cd95a7166d3239d5b6fc1552cc74cd0d6437133bd2fd2785941aec8f6b3fb88eeb5bd092e0e66bf938b8ec2c8f1dd51ee1fac04fd3743cad3b109963d9b850571d17d3d1e1e74f9f4373c715f55442ff009ac719f7fa33cb945746ff0015c4300aede5469a7697b89e5c886430c4e2df0ab5d9408707b793e57abc55c23a39f676cafd4896e6c6e2ebf6ff00dca9b89965b2379e144c985b2fbb90e0157fab244eabe167b5ca12e432d6b46f71e66ced144129d9607a66c58599d1f5076ea04956f36a025670781dd6ba323e445528e88b3ec6fb881450b7358ddc3b14b2ff0057c70135c5ad002b1eb604344c5ee2d4dcb6b4307cd2494fa6e27b7940c8c96967eabfa855ca5d68264f5191ed94d339dc96294399cf74fd51e3d420107caad9652c6d82b9d28ea5b2d5e816a53888396532f3dee95c002295a6ada802361eea8669dad064b1f54b28b93e8b23e8c9f556a52ca3d00d3decacaeb7a801032069175cd2d1f53eab8058ed86e55829dc65797bbb95ddc0a7f15b39f91f93d11c97577e120af2885b60af3401dd75d15714bd0d0e212faa6a884eda9a58a13430bbe13a379de2fe526de51191f7a400d1bae91d2599d90c97b8f2b74fd3db000001c7c2cdfe19c61d110392b7b9d0b1ace6ae9788f29912591c4ebe254b8eca4c4c72324120d13e16d313d338ed632406b817dd66b14b18ea3dbe559c126d918e8cd6d36172b224dbecdd14cb2984918f7b5edfdaad4ee9fca8db90c74cc73c13c002ed4c7e5bf52d3218b36311b41b6ccc1fe85563b19b853303676c8d773b986885914e307ca21f7d1aa189ba671920d8d2375100d281ae69f873471b198f1125a5a5e477b52301929c37e48c81250aa2790a3e4bdd3b059a2d5d2776e1b5ed942849329f13a2a12d3258007cab61d26cc7c2f56104ca28fdc7d12b65c87fb5a46d1cab0d2f596c538664034e1b07d3eca98d95a7ab7ec7973fa0180f8d8e74536306b87724775638db9cf0ea1e90e4051350cd0734b8c5b80140ff00dca73f2a567a2e648c7340ba56472ea83718ae85f8e4d7646d4064974d03a01e93c5b4d72b2ec90c121616f2d2b73a9ea4e9b1e373e121db4804765cfe77ba2c976fefbad26534f52892b8bde98cea185ba87f54b7dd5c8586ced3a48497b7b7c2dd3a769611764aa3cf87dceddd8abe8bba0b5fa324c9e463c870289eab9c2c953a7c0de5c42af9237444b4add194643c2c6ba62b67737b948fcb73b808479084e686f36ac50468567445ce69702e3d953bb1ee406bcab99f223782ddc3851435aeedcadd53714639ea4cb6d17183c035c2d661c00340a595d227f40804f16b5983911c8051e571f3b96f66ec7d245a6243b1c385a1d39fe996915c2a1c7c88d806e214f833e11c07af3f7425334cb5a34f9bac6cc2d8e2d50b49d5e36cbe9ba36b81f14a1b31dbaab7d16cc237385b42aac532e0ea4f866fd719a2abab19c61ccc726b9348ea9a786cf1174340d763d972beadc1cecaea873e3864710e1c80ba8f4c4cd9a01c784eca930db95239d1b778777a5e831da9d0b6ce55b5eececaae9b197063359235dd85eef0b4f8b24f2b851a01553f546347b5bc84ec6d6442edc0594b8f8f5d6f7c8b2704d748d1332a42df759f90541ccd334ad4dc5b938cd24f903950dfab3e524b680519da848d713e5751d906bf66655cbe8f4ff871a7cc09c59dd193dafc2cf6a9d07a860d96013b4796ad347ae48d157ca978fd4ec36d987feeaa9ac59f53e878fcb17b4726cdc79f19920da439a0f078582661e4e5679f51a7dce5f4c4d81a3eb8d3ea46c6b8f16152c9f86984dc96491346dbb54c70f845bc7ecc59f195eb52e8a6fc3ee916e4b5926431a6369b0d3f2bab331f63031a1a0014aa74fd2869403633ed570d7db42ec78bc5f8e1c65fd8a215f05a06f8f68b71083c1b14a44a378427336add34b7b458bf444c9c664d1b9aeed4b93f55e9474ecf7300f6bcd85d7dc2c158bfc40c06c98ecc80390a8b63c96c3e8e5396c0092aab21bdd5fe5c228aa9c887958f5a2e4caa73504b28f653a487ba8ee8e89451000843923e12d228152626d1b467b4123857410a687a59bfa785d3b471ec1c2e7dd278a5ee07c05d334c8c322ecbb98b1d44c16fb2c439a47094260ec13ae82d8bd14e86bbba692179ee41ddca22e82122901ef36693c942777280c0dce3f34864fc94f784175a813c4a148425e531ca100da1c9dd10f082f772948467f24da8d21b054993b9515e6ad4210e7701c281900153b228d2afc97520c88d82f2f0ecbc11208534774e2bd4890194c24a29099601444d03248e2d3086fee9ce6f2874371506484aab4d4e4c2a08c695e4abc540a1a426f64f4c7fd140e86baed13027d8f201e6d0b9a4c6ff0049fb80f28051b3d325240dc55fb079fa2c8e93925e1a4f82b58c90160af8510434668da389438289bc256c80764483f29a0c6552e4441cd215bcb2173083c2ab9801bb948d0519fd4b1ced757c2e6d9eedd99235c6e8aea9a90fe8b8fd1723cc713a8e481e1eb0663e2ba2da7fb1223200014b885a89047756a7c51d785ca6ce8c4f3dfe9b775f6f0a9b527c9964b3710d2adb25840f940870bd576e70a4b1934c135d0ba3e880b01ba15cda94d9e2c290b5a058f2bd3651c48b6b41048eeb35919923a6344924a191636b43d30d9b7c5d6d8e52bfc5e8705a173f8f5596135e994acd7f2dd9023f44ecf959e0e5a2f7146f59ade482487348fb5a0646af30177cfda9654ea843b9bfe5327d6bda5a09053f26c1c516999ad4d6419295366ebd231a6e471fdd54676a45eefd4a932f2cbafdc557386c65145ecbd659589b84720734f70ee41555a9f50e06a71ff5711b04a3fba3e2d51cef716900f0a1f93f2aeaa95a2b6896591bc975fd92b6101a5c0281bcb49a349edd4a4841693b8115cad0a0fe8adf43d92ec94d1ecb41a4e607bc35637f30e739ce07cab0d3f5074520707525bf1f944d10b16b475ed5f5fc6ccd230b022c264326381ea39a3f51557116df7e16770b5632f2f3cf7b539b9b7cb5cb897d526cd756bd97ad8da4714541d63418f50c4924d9fd560b093175060e1c54f6ea2c360b86d239596be754d325d5a9ad1cbe17b5990e86668203aa8a7eb72c51c71c5135801f80aeba97a79a26397866d84ee70f8594cfde1cd6bfc2f578f384e3b5ece0591709e9fa3a27e174a0c52b09b2ba3b2a9727fc387be174aee402385d371f25ce16579df296baeed1d4c38a947658b5a292100029913f73794e253572538ecbc8b930b651ee54f9f8bc7b05ab99de7b5288f16b3db143d72d328863f144270c62dadaad4e20ab287b434d50207958e5271ecd1cbb0fa4630130256eb4d818e6804714b23a60b95a02d962b84708e173f8bb2ddb25966904ca3bc88bc37b29ba169d2e14b3bcbbfa728b68f87287081364b7cabf1fd30037f75dbc3c28cec5330db6bd0e9492c51dec69fd414873b851a7786b6cfd97a58c74616fb3ce646f69e05d2ad93143ac6d14559358d2cfad28a1ae326d0926b636f44731bb0f19ef8838c8453694fc0c576361b448edceadce254dc681a23f778557ad4b2418d236306f6f1491c15517216525ec951163c3cb5e09ff002875a14bec7036b9574ceab998faf458e325c5cf93dedb3d9748d43300702d3c2cd4e5f34d094cb9fd120813ef63ec870a403811e2c45919757d5468b51693df9533d71333da78f2ac8ce2fa65dc746535567a32927b1592eb8d79dd37a39ccdad2e71f631c7bfecad3ae7aaf1b440e748e064174d5c1fa8b59ceea4ce33e44b21603ec613c33ecaca31b9cb72f4073d1b6c0eabc7d7a132340648cfd4d71e02cf753751fa51bb1f1cf2ef20aca5cd8c1db0b9b7de8f751247b9eeb7d93f55ba1870e7c90af21eb435ee748e25e493f2537d34f6b0da7edf0b7f2d7465dec0fa7c24f4d481192bde99f85391007a691d19aeca57a45288cf6439853d10c466bb22c7113c5292d889f08d143ee03b28ac26b66f7f0b9951bc1be0f85b8d6300cc2175b9a59cd0ec5643f0d1a1a656fd472ba16545be1a1e5782f2b6b8e53676f156e1a32ee716d2998d934df283978c6226ca8f03f6bac7755e94e3b35c5e8d769dab4a60fcbc8edd097025aefa27eb110c19e37c66e3986f07e3e8a970720eff0071a57c7106a3a748e63899a26db584f7589c127d8da4fb2469baa96b368239565164332f891e1847658ec29cc6eda4516f7fa1573064173acaaa52956fa11a4d16995a9331fda4ee03801456e67aaf6b9dc16f0140d62574a5b20377dd7b4f91bb9a1e78beff0008dd2e7db1a0922dbf3bbadaebb1dad120739f437505e9b1e3ce7030d35cc6f8fee4c8584001e29c0f6589d4a32e8126594596e923303dd61bd965b59c777aee7342b7dce8a5b6a06a25af8da2c6fddcae9d766e3c6452ccc32e2946ef9f289ac3a3c8859b5a039a3929fa932985e3c2af0e12b79572ebd00ad7070e085073216924f956b3b833db56abe7e41242d5549ef62c97d950e6904a8d971ba589cd69a729f23039d498e841eeba309e9a636db5d18bc88a58672d2f26cabad3b0cec0493c852e4d0e39e60fab57189a596b0003b2d77e5c54524535d4d4b6c898d83c83cab8c68446451368b140d6b76d82422b19b4dd2e3db7722fe435f3d5b492923c87c4fb053e7843bdca3ccc2d8ec1e5549261f959a5d1b39cf900dc41bf9573ade181b3500f2e2e34ff00a2e7d879efc794381aa5abc3d6dd958c6071b0ef954d95b82697a0edc9ecdf7464f239e0092ac764cd5a5747a96483c1f50ffba87d28246cad3575ca16ab9c727509e5f9752c54dce14b5fec471dcc9714c64e0952227026ad524594e6a9716635aeb2696cab2a2dfe4338765cef21bc28d2e598cf2a4e1ffe3006c62ca8d9f812ee22b90ba4e2e50e5033ed45e9811a8464904a8b365d496d79a5166824638f05472e209057072acb7b5246987168bbc1d52485e0b5e795b2d1f5b7cac25c7750ecb9ac52969be2968341d57f2f2f2b5f8accf8ecd329c9a54a274a8278b29bc3bb7844aaecb31a6ea03f30769a17e168d93073411e57b6a6e562d9c99d7a095b9216584e69e3ba70ec6d5fd4ba2a447f4fbaa6ea8c4195a5ccd3dc0576f35d953f506486614a3c91d95334b8b09c8b2b10179e15664e1d2d264c437950a4c612023e161e298e9991c884b49a50dcda0569b334fe0d0545958e58e2da53d0c9f44206b852a06fab206816a3161be55d681866595a6ad5d8e9ca42ce5a46cfa634f2c68706803e8b6d8a0358152e838ed821a015dc7c2f434c348e659324d8485c08a0865c36a66f571541ed8ae29b691cee0a66efaa85829a0865c91ee0985e004022b8a1121217df9437bc00a10f12102471b345217ddf284e7050239c78407794e2fe392845f76a00149e54693f494790df9512690fe908364d11a6342d54654c012a7e63dcc6d13dd67b3273b880555290f189d2986dbcf75eaa4c8cee6820a2d5a74f6268672bc2d3b6af6da4c88336a13986d1dc78285c82a0019423dca396a1b9bc9510418098e08955699dca2231a9094e3548642814781b5e202f06d794b480c0c8b4d20945dbc2611f081093a6e57a2fa71e2d6df0676cd8e1c3bd2e76db63ed69fa7f50b1b2f8508684c8026fadde9449a4e783691b2017689090ec9f936a34d2870286e7f279417c957ca0023e79b8c81f0b9465b40d5f2583bfa9ff0065d4f21f6c3cae51a892cea2ca24d0df7fe8b0e5fa2ea7fb93a18cdd7856109631b6f2a0c13b6ad44cace2f716b4f0b8af7b3a5b2d9f2c6e76d06d48836d785518ad21bbacdab5c36ef612eb03e55f08ad6d8adefa21eb34d6b7e5675f18f5777c2b5d5320cd396b5d6d6f0aba7140f3d964ba5b7d17c3a438b9a7b816bc4b76f617f2a2be60de0f7423945a0f2843d0ebfd84c8943395599592093453b2a72f6955b23aed3244d829f241245f2a0c8f2494f980e4df2a3f9e51489b0729280515eeddc521f002ba3d039027b770e108c408af2a5340bfa24746158a5a071d90a1c40640de689e696934fe8bccd4486e9f13b21eee035bcbafe6bc05550c45af0e6f041e1759e82eadcbd15a27c58e16cdb7697960b23ea9be4dfb2b9c5af473d9b49cfd2a431e541246e1e1cdab4e8e72c1dd752eabea03d50e6c9950421e055b1a02c2e674d9a2e87b1e6be161b526cd3549eb4ca67e7381b16027c3ad90e0d2544ccc4961716f3c2ac9237c6eddca31a6125d97f23650e73668cb490411d8acc6aa638f2e8c60b6d331b35ece092144cf7486779ddbc109f1a87097fa39d9fae26c7a4336077b18369016ef0b28102d70fd2355934fcb0fb205f2174bd175b66431ae078a5ccf2f8b272e659e3ec4e3c4ddb1fed1dd21791cdaaec3d4048ca05496ce246123bac58f3eb46e94747a59493e135bcf74c20da7b4505638f262ae82165b2bba8a61f7a98c7d0a2131d1ef248592fa9eba2e8c89da5421b205a37ba9805acce048627f27cab88e774b2b5a173a9da969fd92cf45d696c2f9012b430b0d72aa74e84c4d04856a24dacecbd4f8eada5b39d6c857b7851a76db6abcda91bf704278dc0aeb7d14688b24bb7c8082250c901b43cdb69efe144127376b1d96e9e8b631ebb3470e6b0474aa75bc9696177d2945194583bdaaed5330ba3a42776e1c43c51478f1e2e3673b2591344bbaf729d99a812cddb892a9c190e41e0f2a4cc07a7cac50eb690ea2a2ba15ba8126c70aeb4bd4008dc4927da566059f0972753fc961481a1c6478d8d03ea9129296d0cd2d1c93aeb50975aea2c97927d36bb6b478a55b81a66f78dcad754d2e48f539839aef73ac58ee0a9987862302da6fe174e57b8c3a3338f644d73a660fc8b72201ee22c8585c9c7d8f2daaa2bab3e5062f4c8b15d961b58d3cbb25ce680012adc5c9dbd32b9c3f46740ae3e12d7956074d7341287f9270e785d1e68ab4471c04d73c37ca94ec5700a34f87211c029e324c0d31bf9860ee4270c88bc1503f292975514e3832b39a2ace09fd95f26593266523b39e682ad86094782558e3b1e05105513fc7d32e8b6cda741e67a39661ba0e36ba9e31123769f85c73a64fa3aa40fba04d15d6e09c358d703dc2f19e6abd5ca47531a4d2d1eccd2db33491f0b3f241e83f69f0b5b0ced3f047c2a3d6b19c1ee7b5a2895cfa2cff00b4d727bf4416481a782adb4fd45d8e43838feea81aea241e084764e2be295d654a488a7ae992b2271f9a7bdbc071be15b4392d18e5de6967cdbcd83c292cc8fe9965d2aacaf69222922cd991eb87079edd948c58c8e7b854b8d2ec7f27b957704ed733859ee8b8ae874cb8c099c26606fca993c2f1239ce69166d55e048d2eee6d5c899f2b2de6e85218f4f24c49cbb229689013f0a9f2e702737e0ad1478ed7309f959cd6b19f0bcb9a16ab28d47a2b8cbbecaece7fa9190deca9dd26d3b54b92573410a03cee92d356ba2de814e5c5d61479a325a6d5839a0b544c87b688042d107de80f5a28326631bfbaf4196d783bb951f5676c24aa5c6d47fadb39e4f75d9ae8e70d99fe6507a66c715ed7381569248d8600f15442a081d26c650e5583b7e4e3988920d52c1643f25b2ff9138f4563f587ff008a32085c1ce2795aa6b2da2f834b3ba6e831e9d29c975be426ecad0e338e4337104525cb70eb87d19eae5dec1c81c2d01cd3c82a7bd9629a120c42e1cac8a6916e8a47404395be92e0d7b5a8c34cddfdaa4e3e9863208085b7a71d17c0e87d22fdb6681007fd95566c2ef5a470047b8ab1e9377a2d1b870a766e1b642edad593129f96128a1652e33ecc91796921162797775365d2cb5c4a8de91612034f09a5e3a715b194d6cd174b65b59386b8f2785b61a3c7911fabc12e5cb70e4931e763c5f05747d0359f5e1113ddcf80bd0787bb51f8e68c19507ee243d43a65d6e2d6d8fa059ccfe9f962b26323f65d4a2a919de9367c38e6690e6870fa85d8bbc7d372db463864ca1ece2f3603e27763f647c38ded36785d2b3ba671271bbd3a3f459cd4ba7a4c4371b096ae2d9e09572e71354731496887a6e53a070df4b61a5e6b660078f0b0d66320fc2b8d1f500d91ad2ea5a71e5f13d4fa04929235d90f7c66da8b8b91eb467e8a14d9ac31825c10f132407535dc12b62c88fc9a8be8ccebe8b270e79597ea87fb9ad07ee169aec2c6f5439c32c8be2ad68b5ad74271465b21b72943310aec8ef1eeb4a002d5917b26884ec76bac52a4d4b4c712e7d701698c63ba879acdd191f29b8ec061cc04c9405f2b67d31a61686b8b55769da41972c9dbedb5bfd1b0844d682d02974b0e9fb33dd3fa26636388d83c292052f39b5c04974175d7473e5db149e0a1dd774e2ee10dc88d18e847c805a67a82935e9878080e39c494291c404d73c84c73f70508217d8433c83ca54dba50830f942714471ab4071eea1047b811c210e0a473e8217ae6f84087a671ec1459380794590b9cebb50b2e4d809255739690f14cafcf985104aa12df566201b46d432dd2ca5adb01134dd3e474809f2b1ced45f18337b8d36c6eda5358770b55b03a39bdd1bb7294c9c47dc2b69b54bbd954a3c431eebc12b646b858483bad69a6b68aff00d8d29a427f94854003a485a89b578b542002d4178525cd422db501a235729c1bf2886349b54d9120749a7b944da90b5008c4dec9cbc45a8400f0482931329f8725dd045210678c3c50eea10d661e60ca8afca73e42d356b3fa6e6184d5f0adfd7190cdc3c2840dea20c92a1894f286f92aca003d249ed2b95ebcd31f514e4f626ff65d2a49bb80b9bf5b3cc7acb5ed15b9ab2644771d96d2ff002d911fa8c713b682930ddebcfb872d555131d3bb7bc51579a6420385f0172d476cded973044e2435a38b53355ca6e162fa4d700e70aa4ec40d898657d6d6ace6a739cdca749b8ed068046d9715d06b5b6003887137769b2bb78296b8e130ae7b8ecd7a21cdeee5bc8519e4d2972b2bb203da29345340f442949da54378b6953a66f05442de0ab12015cf61b284452992b792a2bdbc14da032316f2531cce11c378b2832cad6856456c46c46339468f11f3bb6b1a4a8832371a1dd5b69f966167239f94d2834b618cc958ba4060fea1b3f0adb0a56e37b5a555fe65efb76eee97d675707958a729265b16997edce73dc2cd856116434c7c57eeb2d8f2bf77255be1c85e034f65572659d12b234a8b29ae7860ba59f9f42b7382d6c4f642c166da7baaccd99bea38b3801326dfa19330fa960bf12c91c2cfbdef6cc7dc482b79a944dc9a0ee4772165353859065bbd9b58eecba1896a7f8fd9cecddb5a2118dcf8cbc0b21693a4f538197048e224278faace479670e473b68781c57d11b23f2ae0dccc490b1e392d5a6dc7f96b6998a9b3e397475bc39c307eaeeae719c1ccb69eeb9df4c750333636c52f0f6f0b6fa7e4823682bcacf1a554deceed5729a2c1d2383abc22b25047751c926ca48c17bb9e12a45ba2734ee4e02af9426bb6a7ee525afb0a1ed259cf7575a317cd23496914a8d925ba8ad774fc34d6b880b25308d96f44b24d23450349636948710194532007670139fc0e57a4aa3a8e91cf9f628750e506691b1826fba290833c5bda9ded017657645ca0951442ef82ac86382bde996d52a255efb1be8ac74248b503363696d93c2b8cb89cd68dad24da893e9b348dfd07e556e0c4d99f0f89af24d028599207b0068f2aeffe189a5365a42930f48122dd6955527f4373466591070b0149834ff59c00659f92b4cde9f6c4da213e3c16c07da395646ad7b27c9d19bcae92d3b21e1f9711df55b8059fd6f42c2c29c470c6e9227762d0b759b14b29202a29e196192e8b9adef68d9e855fb311a9f4f1c78fd68c1208e563355c22d713f0baa6ab983696868daee02c9ea7a77a8d77a91f7eca9ae4b9743c96ce6d98c79b0d50a1f5438d82b5999a400e71029543f0dcd2452ead73ebb28716886d6177254a6e3fb7f4a518ea5b180340eea737f4151d95df916071349cdc2613cb559189a84f66e14d48ee6bec7f8e2466e346d1ed685e3181c6d08fe8168ee9ae1452fc8e43a8a43b11c6195af6ff0069b5d3313508ff00c2db90e203765b89f0172f6b883c72b57a1e64336953e16438b811b40b5cef254ab34d96d4f4fa34fa6ead06637d48a4dcdf90ac2773648cee16b19d2303f0e39711c0ec6bf746e3e42d546fb6513cae165511aecd41f46cae5b5d947a987c21ef8db6072b3b2754e3e33ea676df0b619b187b4b4f95cffac3458d9b666c64d9e69753c72aed7c2c335f2697e28d4e9fac63e4c4d9239039a7eaa53729ae71e452e46dcdcac02042e735a39e569340ea474e1c321e03c765b323c538a728768cf5e6adf191d049268b79563a7c6f2dde5c4578541a5eb18ce21b23a8abe8b5083696b5e395c0be125d347423627da2ff004e7c608df7c9eeaefd9d984eda592c596c0a7795a1c69bfa40772a8a3a7a237b25c73ec716da1ea2c6cf8c6c72abe7c87366a08bf9a2e868ad15dca52e02b87463f2806cae67c2ad94161dd6aef52c70277bc795579316f6f1e1347f17a1d3e81c73ee1b485033dbb2dc124d318b8baa5132335af69693cad75d4f7b40e6506b13c9b5c1a2d54e063cf2bbdcda36afa46b5eee4036a5634110229a2d75e37fc75f1d18aca39bd86d3fd46c01b2f2e0a762ca1a689a50a595b082e3c34795131352872331b0b1e0b9c680b589d6ecdbd17d738d6b4cd763ec959cd394c89ad683b780a1c5a6e4c113cca0b69b63eaab8eaae60ae7e173e58f37da2e56c5fa3451b583ca3b76d714b3f8faa1701ca9716693e5669d125ec74d176c003b85361687d0a5518b921e79b57fa5c0fc8786b584ac765727f8c57615251f66a7a7309d2901be072af24c07b6c16d853ba5747762e209246d39eaea4c6638516af4be27c74abab94bdb30df909cba31526036cdf7421a231efb03bad54da5b5ceb012374f0d21741d3a627cada2a61e8cf5e30f6b871e1586274fbb0dd64723c85758b2fa4ddb4a687078ec16ec7c5ad2daf665b2e9fa21e1e3b9adee7eb6a65eda0579e768b417b8b870b6ed2f467db6fb1ef9a36fea41798261cb4387c520c8d73cf74e863a69b55f36fa688968cd6a7a0c53cf700db7e13b17a44fea2fda7c2d4c70c60dedb28ed1f60aa78709bdc8b95cd140ce9f91a007485c1488b4a309bf015d0eddd0dfdb94cf0eb8bda17e66c8a23a6d9f0b15d595f9ce3c356da63b5a4ac27504beae749f455db0490632281c395e0384473794d2282c490dc868e524b007b2ab929f034b9e7e14ec6c6f54d91c05bb1a972f6512b74268fa60602e70a255ec71ec15d826e3c41ac142a91eb85d7ae1a5a31cecdb184fca42529ec9a55a50b63537baf39c5a384d0e34a16a6c6c9486483c27f742770540b90c7841361c53dee4c3dad4227b1a50dc6939ce4173d008ae720170a3caf4b271c21175fb54d9340a505dc0430036eca3792a34f2160e7b25df4440e79dac612567f53d4bdc58de51f51ccbb6b39503130ce44e1f20e166b65b45f581d3f1ff00352973afbadbe89a2be72c8a2617b9de4784cd0f446e410c8e3164ae8fa3e99069300da0195c3dc7e173dc5c99ae2ba386f4feaf26346ddf2d8f366d6db072e1ce8c398f6924785c0b46ea4731cd63bb15d0b45d643230f8de5b63b05c6a276e2cf4ded1b2dc78cd7e274489cd6b88ae6d490dbf0b3fa4eb11651a9080ef9f957f8f2878f771f0bd2e365c6d3956d0e035cce5210a516308b08662ae56fdefd19806d487b2318d30c6a6c80bc20b9b4a496526380aeca108d5ca1b872a490130b020423f2bd48c5a131cd50804f2900a44db49a428406534b6d3c8494542026462376e0a5e3671c7241160a0169a480578509a2df735ecf559cfd144965da2afca063e53e17d03c7908b930fab19960e7e4040804c967bac9f5be2093162ca02dd19f72be339160f7f2a0ea85993852c6f1c39bd95562fc74347a660a0a2377cabdd2da3680b2ecc9f465744e70a6b95be9fab4518346d735e9236c1ecbbd5335d1c2d822ec47b9550fd26ff0064efccfaee2ebee88035cdec173ad9ed9b6a5a00d3bc1b5e733845746076a09cc84bcf755458ed90258cbb951e588d1576fc4262243402abe584c6df7a707b29e569682a19079537372238c7240f0a1ee6b8583613a011663e1462a648c0e2546f48b6ecda642b644941ecdeca0cd01b255c084521498f60f0b457a452caac78addcfcab160737802d3a2c400ded0a4c6ce6a934e4048488bb6a332ca7320aee149871ec701639a45d0e8f422c5a931e63a13c2090d8b8b423333936b249176cb376a25cdeea1c999b89e54433b4d8053370bbb0a24d1361df26f50752c38f221f70b47df410e49770ab4f5b717b457349fb33f91a1d349692556c98133090d6b8d77e16c585a281a5371c44db1b1bcfd1752abe463b295f46134dc89b4fca0f6eeefee5d2b40d5dd90c6bd54bb48c79667388147e8ad34ac18f1e9b1f02d519d056c76bd8f8e9c59ae872b736cf952a27870551013b401ca3b721d12f3eff0007a674a33d960f7f3769f1c9bc7d957fe643c7c23624a3755d8596c9edf45c91651464c8daf2b73a147b616df7a58fc1731d20e2d6db486b7d3051c2e2a7d096fa2e6035dd1b607287ea861eea441283c95e82b9746198af63e31c0b4dc7864ca25a186d5963b58f0372b187d08c7f4da2fe8b542be4552935e8a4c5d1672f265a0d529ba44515f95687dde126db15d968542450ec6560c581a796f648e9b1a370062054d931dbc950323185d84271515d21549b1ecca8b700216d292f2d70f6b000ab99151054af518d6f27b2cea65d15fb23e4c20f75146135c8d2e631e481cd2645965a0f03eea74c7481bb4d69e79551abc58918702288ee15c65654e5b5179f2aab2346972ddea4845fd55764131d1cf75cc312c65d00e41b029524faa643a218b9186d7d701c07b97586e8505dce4100795ce3f11fab22e99c98b0f4c82232b8173a622cb7e96b1fc2e2fa0596705b29d9d359da992dc7c394b9dc801a4dfeeaa733a1b5e82731ff0085e4bcfd232429ba17e27e7e3e364c195289a4907b642796fd9019f8b5ae699309592096263c5c6efef1f0af8c9a7a39d3cc9efa47b0bf0eb31ee2fd45afc78873b4369dfeabdaef4232087d7d266966a1ee866a0f3f6a02ff0065d87a3fa9b4feb5d1c6740c0c78a64d17f91ea667749e1ea91b98fb8dc7fbdbdd3595cd7698bf2dc9f2df47cc8d12176d90399f3b9b4568742e9966a6cf5a59847083cb47ea72ea1aa7461c4bff0011d3d9a9e30e3d603fa8dfaa8d8dd2d82c81dfe11961a3bfa13fb48fa5f958e57a5fdfa36d5969ad4fa321aef4b4197a7b59a5c0d6cf1761e5ff0075cfb3209b0a57453c4e8a4fee6b87fb2ebb98dcad2afd788b41f2058fe5516a6cc0d7d8e6e633facd14c92b90aeaae83f4cd0a4a5fd4e6f1bb7baad5b634671dcd70e4151b51d28e9f926263b781c828d833db3649e15d62da1ebdecd3e99902c0b57cc70db7e4ac2e3e6181e0d95a0d3b588e66ed73f9faae0e563c9be48db5bd16938dcabf2b159302d7b4381f95264c8045875a8f36403de9515a944b25fe8a1d4340c4c889f1fa4d69ae1c076598774b64c33917ec1d9c16edef638f708123e316072bab4e65b05a30db8d093dfd99f669f3c0c69de5c477577819351b46ee426c859b4d2aa0e923c9f6bfda4f64d2ddcbb161ca0f4cdae9f9ee0402568713552cab3c2e7f8da86c76d577899ae7d004d2e45f8ee2f68d6a5d1aa9f35af76f0bc3381a14aaf0e39b29e1b1b4b95d43d33a9bc07b603b7eea8a71ac72e50433b168a6ce958efbaad91ed6b5c0dd9563a968d998f90ef51ae6d782a2c98ae6b0ee61b3f2ba91c7937b68add8b464b59c9d9fa6f80b3a75273a5dbe56c73f497481c6aed6766d024fcc5b59fe8ba5446315d943968f63b4c86fe56c7a5fa523d75f246eca6e3968f6926966f1f4f96170b1c05a4d33125790e6170596db3be8d09ea2546afa4e769beb61cf0b658df64123b8f90b25a3e852e26a8cc86ef258fdc1770cfc56ea7063faac01d0c7e9f0151ff00854514ded8eb9a3c2d71ba70ada8fd9924b6fb2fba74e475142e8f3a0686318367d541d63a1b6bcbe114df015ee84ffcb6c11b76807c2d7c714790c06bc7654d29cd7e406f89c4a7e9ec9c778a06beca661e93358dc0ff000baecda2634a2df1b5bf5a49ff000d42cb2437e94134b122c2ad660b0346702ddcdb37cae99d25a7c38ae6b844d248f21031f438daeba1fc2bfd3b1446001e13538d1ae5c921a56371d1a163995eda4d7d734a2b1ee6704a20713e575fe56d1878b3c7b1403fa91494c005f6556b65d17a1a1db4d852a098bc775136f289102decad84b895ca3b25925fc5a42ca1dd0d8f169e640af524d15f1136f29cda082e99a0a56bc3bb148a4b64d076bd3b7a035dc250efaa6f909a41dafa0535efb05077fd52170a3651e6f418c502ce936c27ecb0b9af12e4bdddecad76b52edc5751ae162c03dcf7ef6a8b3b23e819603e10cc377c297b53d91170469c772f6572b1240f171011db853a188305009d0474da1c2306d05d6aa0a31d18253e4c7378096d36d7b70567644b67894c079295ee14805e7e510a1ef37e532f83ca639e0a1971f0540ec717d5a0c929f85e24fca63cf1ca804842531ce149a64427924937c28148f39c0f0105efda129348521b250181b9e495e07e52db436d479e70c1c520fa0877c9b5a5c48b5499d9c7dcdbee9b95a83ecb47940830a4c8dd23dc037c5acb65a9745d18ec0418ae99fb9ede2fcf6575a469726a32fa70b37570768ec14ad23a7b335591b0323a8c9e5f7c05d074fd3f1ba7e01063005f54e78f2566e5b65d1af40b47d231f43c6d8d3be670b738f8fa239c9db64be828f99922305ef77d567b2750c8d467f431ac01e548afa1a53e27cae70e5c478dc08a573a66b736390ddd6d0afb55e9d3217b837b0593cbc1970c905aebec164c8a76b5a36d17afb3a0e91aec73b402ed8ef9056c747d6cb5bb5d26f6f8b2b856167cd03f6b8915defc2d4e91d51b06d2eecb96e3650f712f928588ee787a863e48a6ca03be0a9c5aedbcfec42e57a6ebb0ca5aef576b96cf4aea4f6063cef1f24aea61f95dfe3339f761f5b89a0319a432c3ca36366e3e50a8de09f8b46f4da47dd77236466b699ce7171e995f450dcd2a7bf1ef90806220f64c291033e57b67d1487447e137d3fa2242316266d525ccaf0865a6fb2040058130b0291b6c2616a842316242da08e5a9ae6fd14220239eebd49c0525a50200c42ec709f1c8f847b5dfb7ca26c090b028401958eccc697454c94783e5663519e4c3718e76b878e7cad7085a79f23ca1e669f8fa845e8e5337fc3ffb8259ada22384f55bbf2f96658410cbbe15769fac0e69e6cf85baeb3e84cac6dd2425d918c45ee1e3f65c9f2e1974ec93b5a400573ecab68d5091d1349cfdee16e5771c8c278770b9ae93ab8247b8836b4b8fa8388fd4572a78ed366eaace8d5b9cc144393e295b7fa82ce333cd72e28accc3df724e259bdf66a3f371eceeaaf51904b5b7855e738f6dc98fc92ee2d3240d91f3b0a3ca66d73a8fd10a3c4642d0d06c2239f4e286e758e3ba6d742ec438cd75f1c26bf11a0700274d9ccc5d8d734b8b8d70a56e0e6d800f1682e8856182b8a437c546a95938350a46026c764d196bd834426c47b016a463e0ba57fe9247cabde97d1e0d472c9c9bf49bf1c5957ba8694cc7bfcbc6037c5049659fa0a899b8b4c2d16fa00214cd8a1079e7e8ac321938f6904aac9f1e4176d5994dbf65b14b4564ceb91c41511c2c1e795612e31a200a2abe489cd7114514d0180732400969e123049dc94e76e1c594ad610d26d5abb404cf1948e10cbcda56538d5f29ce8241cd71f28a4911f60c486f92a4b27713dd447b4824246b8b7b95645e8acb3196e68eea6e2e6915cf9546d96fca3c73969096736149236781982807153037d593d8eb056471b3f60b255a6266ba51ed710b35b04d6f4589973344e6b4906fe17b19ef68e01b09904e363771ba5618ad1272d6f75cd9d317d17466c9da6653c3858e6d6f349c8dd101cac8e9fa7191eda6adc68fa7d31b60a98b86e13da16cb36b44e8f1df30b00f2a44713a214e56f838ed6b43690f271f7b8802976e356a2649322c1965bc6e054dc5cb1bb92aadd88e8dfedf9a281939acc288cb2bb6b47fba12b9d4b9304b496d9ad6cd196feb169c1ed23836b9fe075a6365670c56ee0eedcad863ca5cdb4f8fe415dd44ceb8cbd327ba88a0a2ceda5e74a68d28ef9ac7256ab27d0635ad83988aa0a1bedce20928f24a391614695e00dd7d9634f7ecd2bd0d3155d2732200725477e5df63c28afcdab3bbb774cb447e8b5df1b0528d91a9470c64d835e162f3bf1034f8e6742c7ba57835ed52f0278f57c633c2e791f5eeab57a72e31f653f22de883d45d4b952c6e6c2c70ee2c2e2fd4534d9f94f74e497035caee39ba397b0ede6d73fea1e8d903cc8c88f7bb4b28c9763f4d699cdf4ad364ccd49b1904301b3f65b4cce948f2e264262222f91dd7b4fd18e2cdb9e2abe8b5d87307c00576e14727b5b24688b25743e1c3d3501871da5b1bcdb87c9f95d170f3e39180836560b10b6c35bdd5e604ce8f9ba4f1b5c5ec67523758bb678e9c1a7e7eaabb56e8fd3f53638b41c79c769586b951b4ecf776b579065077eb22be0ab1aaad5f9a33ce84fa6732d6348d6f41b6e4e31d470fbef0390166b274ed235824c131c594ff0063f8e577b74b1bd8439a0b6bff00b0b9c759e8ba3e4e16567cb80301f1b49f543eaff60b9193e2f83e5548c73aaca7f28338e6bdd359985917216ba23c6f69b0adb44e85d27538083a848c948eed6823f8b0a3699a862cd97e9e7646fc369a0c2e237ffe8b5b0f4d74b6a6d1268ba964e9f940700c9b997fbaad4ace3c5b32bf2162e8e7bd55d259bd2f90193912e3bf98e768e1df02bc2a464f244096ddaea9d4191aae0e933e95afe1b72b1e4170e547c96bfc1fa0585768f0089ae7cdb2fc9ecb4c6d4d6a475f1bc8a947537a657c3ae4adade3808f36a62468734f7574ce80972f1599111f56370e0b50cf463e13b76915e08567c5092e48d90c9e4ba33336a320342d2c1913487cab9ccc0d370a511e4b831df2558e8b89a266cf1e3c326f7bfb51562ae3c7a0fcbd9511432c83b593f08f1e8524bcec209f95bcc6e90631e3646695ee2e810c4c2e95ad6868bbaec3e550eb6bd0b2b968e61174be4bb96b4ad2e8dd312865bdaeb012e575e69f8b952410e1b656c6e2ddf7dd58607e23e981803f19cd27c02b3b8b7fdbd19d790afd365b697a43f19d6d1455db3232583687914a1e8bd55a6eaf951e2e3b1feabc5f23b2d1bf4db04ede56ea23d7fc63ab14d6d333f9700ccb7cdcb8f955597a631ed2005b2934cbf02be8a264698e6b0d33b7856baa4c6e660e5d145f26931ba046f37409fa2d79d35d3021f1108b8fa3b23fec2ab7530eff00662ddd3edb34c0a6e1e85e9800023f65af1a5301dd48e3000aa0ab8e2aded855bf467e1c0a0011549e3436ca6c00afdb80d2ee42990e1b631ed0b4286ba1548ce41a5ba170da15fe2c063daa433141364052a3c63c14d08e8927b1d1b039b4f68211fd36b8760bcc8cd523b23354add91016420378526166d1c5af363e2911829328f61dad0f1f524a346e15486d0084bdbb2b968adae821fa269f6a4debd61c3947408a7f6781052eeae1089d96a3c99f03646c4e9007bbb041e89b44e67929c0a1c2e059769c5ca20686bdb7cf943daef9a46ee121b1e104bb074399fa52729bbdc3c5a7b0978248a4fd83420b4bba8127c25af2a266e5331e27389fb2b229b0f249151ade539c760e01e152edf1dcd52364ccfcf90d1e01ee9d0e33583b92ae850dbdb334ac40e08b93bfb292c601d92ede293c3785ba3048c73dc8468a28942b84cec937729c118e85e68da6924764d7bf8e0a1ef3d9123e8f17929a470bc48086e7bb9f850236f934bdbabba139c6f9298643d8150811cf1e1064794c73dc102498da81413d4b2531d20a402e209f2bdddbcf0a20a15cff0084199dc2f3a4da0a859796d6d39cf000e524e5c7b63463b1d364063792aa3332e49096c7dd0a5c89331ce310706f6ba567a2f4e646758635dcf92b996e4b6f5135d7411b0b09d2b9b6d7171f0b57a5f48bf318c7caf74310eed2af745e9fc7d2f1dae946f947cf85613e635b19e4709229bf65fd23d8f041a763fa18e28577f2541cccf642c2e73bb78b5172b56680edaff752a03f99d5272da76db57463fa335b7a5e893265e4eab2ec8c532eac2bcd2f4f8f0a2ed6f3dc943c1c3661c3b03403e4a94c751bb5aeaad2ed9ce9dce47cf1d33d630ea71360cfa8b25bc179ecf57197a36366b298e63b9b057240fa3b8122bb10ae745eabcbd3e40c95ee7c7f53d93ce94cd71b1a2db54e9990492968a77d167323172b06eae8772ba7e91ace26ab10712c713fe89d9dd358da846f0d205fc0eeb0d98d166aaf21a39b69faccf07b77027e6fb2d6e8dd5221004af0e0aab55e91762b09863eddd6725c6cbc779058435737230e2fa8fb37d5909f4cedba5f52c4230627069f91e56b74aea26cf1d3c59af2be76c1d63271435ad71e3e56a345eb2a9adf2106b90b3572bb1fd3d8f3a6167a3bfe3cad9e2dedec95ccde3b2e71a47581739bb67ae3f495acc0eaa64a2a6e0fc85d2a7cc573fc65ece6db8325da2d9f150284623e14bc69e1c96d820da33b0f8dcc5d585d09ada66475b8fb2a9ed23c2116fd159498ee27dc1479318b49aecad10845a7e130b029258ebec986375a8423fa4134c3f5527d37795ed8a1082e888f09be99539cc287e9950888c1b4bdb6d1cb2bba686d201074bd5c22571d926d50801f138b5c2853851bf2b17d57f86d81aec4e9314371f27c50f6b8ade387086581cdf81f23e50714c89b47cd1aa7496a9d339a5b9b8ef600787816d3fb85618d2dc601a5df72b020cc8dd0e540d9a3ae43c2c46b3f863038be6d225f4c9e7d17f63f658eec7fb469aaefa6603d6ae11d9370a26b1839ba34fe966633e320fdc7f2bd8d94c7b412b9aea69f66f8cd4913db2d9450f54f9babc58607952307386643ea01e52b86bb17977a25bc926ed3775029ee23b520969b55efb26c7771eea348f14db851082ca3c23c7186f28319095cd5a3e2e14b9320631a48253b1b19d3bf681dcad869182cc3876800bdddcfc246c7d07d3b121c0c66c741ae3dcfd54a95d6dafd48e30a2c86ec793f744fca3216ec02c0f2a87bd84a49f1c3ff00b55764606e2b50e859f0a1cd861e785348299949b4da2785579181b4f2d5b09b10b5c541970b793c2291366327c1f77010bf2ce68a2385ac934bdce26945934c37d93a623334ec50d36029f06317462c5ab3ff000773cf656583a56d145b6aab67a4344c8e469a5afb683483fe1b23b8017476f4eb7223b0d5e6f499e486f6fa210b9b2747371a4cc2e814e669790e3fa4d2eaf8bd24d7b412c1fc29b8fd10d2e0ef4ec27736039563e8d2b87636ad707469c57b1dcfd1755c6e87809bf4c82a637a770b0f6b6534ff000023397414ce7da774ee64ce680c2415b3d27a2f2806ff004cadae8f8b810b76c6c6f1e55f44d6ed1b7b7d0215529f6072d199d2fa5dd0105cd17f55a0c4d2fd0e4d053a3681e11abe16e8d4922895804445bd8f0a3cedf4cf24292f0fbe157e7095c090d28cfa404f642d4758c4d3a092599ed6b63697125711d7ff0011f2b51cc95864ac72ff006b1693f1262d5e4c47c7043348d91d54d17c2e5f8fd33abe54fbbf213f8145b54b917bf97fbfa3064ca4df1474ee8610e63865b612e7df2efaaea987217440f6e164ff000df49934fe9f6c53c3b25de49b5ae0034707f65ab031a35c3947ec6a2b71f6137d83ca8f21abe79439a42d770682873e69646f21bb8adb2f46d42c933b71fa28197904023777f095d95b99b9dc5acb6b7d55838f3be0f587a8c1c8f859a52d0e5dbb398d04170b0a0cfad62321903cf25a471f5580d43abe377027ab3428a439524b1ee2e277048e7d1349f4c58723031b21e238038171dc4f90569745cd8f4bcb6e5427761ca3dcd26cb3f6587a736471a2a6e8b879da8648c481ccfea9e3d42400b88dca36724ce65b8f6d72e71ed1de313062cc843e36b5cd70b047651f33a6999ac78d95b57ba0344d4345c47459b3b243e18d24d2d908daeaf8edd97afc683b2a4da2d8c9fb386751f4fbb04b8b5b415360bdd88eb7b3783e3e1757eb1d3d930906d1c765cdf2b19b0b8b4fcd2c991534f66ba6cd8b89916f7387167b2b4123a810e5510c41a6fc2961e1a79269609368bcb9c3cd7336f2afa3d51ae03de3b2c609b81b4a5198f8e402c552784da42346f1ba97b016bfb2c37e214997aae9aec760b176e211a1d5dec35dc7d1487e4372587c93e15aa7c909286d68e11a960491ca76820f9dddd46c5d5b50c1937324750f0bb367f4ce366b5ce113771eeb27aa7e1dca039f8f609f09541fe8e3df83ded10f43fc4acac7062caa960770e6b85f65a3861e95ea381b2b1a713209b2587b7ecb9b67e8995a7cbb6680807fb804d89f2e0bd86191ddb92b3d94adf473a555953db3e83e92d030b4fd3a48629fd7613bb738f2a66574ee3e41e582be42e37a1f5ae76316c66571be00b5d97a13539b58d3257e48f735d41594b8c9fc67470f294bf131dd4ff84f8baf4676c9e93c767287d1ff00867a5f4b653b232357c49726a9cc320e1751d471670c2221c91c734b97ea7f84dd43a8674993167e345ea9b11b9c49ff006564e138fe1066d9c251fc91bac4930657fa516563bde7b34482cfed6aa7adf2f33074d7e2e0e1c92cd302d2e03b3573c8b45d7fa6f5264a30724e4c2eb8dfb6da5dfca9f9ff0088fd58e27f3b088db756e8e85a4f95c63c648a2591b5a91cf66d135c6b9f27e56668b3fda970749d6b26511331252e240b2d229755d075dccd51823c9c78a573c5b8b4552d16263418dcec0d7774d07192f40a70a13fcb607f0eba04e92e19f952974c581a05f65d19d16dba1640543a4ea6cfd16afa3789ae9de16bc78c78bd1b557182d189d6baab50d2b5986238bba127696b4775adc5106a18cd7b410edb6e691c850754d3f119911cd92d25d7ed34b1ff0089dd5f374d6146dc797d274aed85ccefb7ff00ba51572ad36cae56382d9b87458ac9fd1f5a2df7fa770b08c3099c70171ae9dd565d4c8c86e43f9a2e7b8f24aec3d3fabe3ead86c69706e4b053dbf3f558b1b2559371653467a9cb8b0a70d845785efcab41aae14d30904a4f4c2d9c4e8f4c87f946f701386383d94a6b7c2511808e82900663522c71d13e511a0a7823e1375a0e865009ec35e53880578310d04468b4f01780a295322a6c5040094105352b7bab6285e478b4a7166d1ca471a5e73f7504fa27206f6eeb55f2698d93244ee68dcd140ab25ef295c42e3b070073181bf08809f08ad008ec95ad03b22a02f63039df088d048e4254e20016481f74f181069da130bdac05ce70007ca85a86b5063b0c6c3eabfc01e1506465cf944ef7b837c056c296ca67668b5cfd7dad063c7e5dfe6f854ce966c825d2b8bad35b1edf08808016caea48cb2b1b3cc8dadfd2293c50481d7c2f2d0d2150b696d35217521a08e250cbc3531eedde504fdd42682ba5041086094336bd66bba82b15ee2d1c143f52c774d2f15c94073bbd140839cebbe50f7edb4c2eda0a1971784c41ce96ed0f92528157693d40106f4b614b62386c1bbc20c930aefc28f9bab4305b43b7bcf66b55648ccfd480ff00a117c0e49596cca4bd17c31dbf648cdd52385bb63707bcf81ca850e0e56aefed67c3568347e927baa4786b195fa9ddcad2e0e9f87a6ffcb1bdde5e56194e56766b8c140a7d13a45cc0d7e649b5839d83cad4c4cc5c468663b036bfb8a81266b5c497bf6805536a9d48c86d919dc4290afed8676a4ba2fb3351686925d67e567b3b5c69dcd8812e2a91ba86567e41a2e24f002bfd33a73de25cbfd67dc02ba153662b7256ba2169b8193a849bde5cc65f72b5589871e380c68175dfe51044d8c0f4d81a1198455f95b210491cf7372ed886303c7286681aa4593e530b6c5ab92403e2d2eb297b775e005a5d85eeaf84c69d9334eccc8c2937c0f737cd782b69a1f5c88c06649d87b73e5629ad0d681c26c915bafc7d12b8a6153d1d820cfc4d4985ccdaeb1f2a2e574ee36444e263683dc10b9be9dab6469ee1b5c4b3cd95b5d2baba39d818e7b5aeff00cfd9512a51757722bb3fa4dec739ed236f8a0a8e5d1f231a52e2d24782ba6419d8f90c0c14967d261c869076d9f21629d09fb365592d1cdf1751c8c6790e73a876bf0b55a275708e3d933f71fbaf6a5d26d7b5de8fea59b9b41c9c171dcc2572f27c7a7da3a1565c5f4ceb1a3f52c523ae19cb0d76b5a8c6eaa73001337737fccd2b806366cd86eb6b8b4857d81d59951336ca41fa95897cf8ff00d479555d877fc4cfc7cd66e64ad75fcf7520e3b4f85c734ceaf6bb6ef25a7fcc0ad9e99d5f286b76c8266d793657431fcc2df1b0c37607dc4d5498e1bd9b6a33a137fa53f4eea3c1ce708e43e8c9ff00987055b9823905b29edf96f2bb95645762dc59ceb299c7d944633da909f191d85aba970db46bf821467e3380e02b93d88556df909bb3e8ac1f8a7c843fcad0b510084e8c1087e98038538c1f2130c200448422c4dd854b3150e13361f8408457466d2ec528b47c24d808ec142111cc41923da2f9fd829ae890dd09e7ca8145665e9d8f9d11872f1e396370ff00aad04ac36bbf85b04a1f269533b1e4efe93f907ecba398e8f6437024aaecad490d19b8be8f9a7aa7a5359d21dff8cc494477c48d04b558f4fc662c20d7b483f514be827c0246398f68735dc3811c15439fd07a56731fe943f9579e43e21ed1f70b2594be3a4591b372d9cb68934127a441256bb23f0eb54c6deec731e4b47228f27f659ccac4c8c294c5950c90b87f6bda42e6cea941f66f538c9110368da9986cf51db0a8ce01bdf8fb9571a3e3b1d521e6d5122c4d163818cdc6161bc9f2ae30ddb39726331e3d82911b19695536322d219c347b6919d2ee16554b1e5a8ac99de523ec3b27fb0a190d7027e1004a95a7702d07ba4e3a06c73a2638700151e5c26b871ed28f146583824a3323dfcb8045488567e409ec2c261d32ff00b6d5f470068e390515900e6c29b2140cd2f81ec53b1f03d26dec0ad598d67e8a4470340a2a7b26c8d898a285056516290db6b0583cfd93a08dad68a6d296c7359cdd211868524438719a21a0292d11c5e070a0fe758c69e7b0543ab7513b19ae0081c2937a5d0c91a0d435d870b16473482f038fbac63b5a9f2321d23dc4b89fe02ad3ac3b324daeec51238e9c48ee5569ca658b48d2e9bad3e3e09a5aad275f040123ec2e6cd9cb246b769fbab1c4ce745dad32e50ed11a523ac459f03c0f7f254c64ad70bdeda5ce3035890d7202b9c6d58bf87557d16baf25fd944a94cd8b2437fda5a9c435fdda152636731cc1b5ff00eaada1918f0da20adb4db19f4ca655e8cef50f53e95a43dd14b199661d9ad14b1b93d72dca2445a6b40bfd4490ba3eb1d3da76b6d3f9b81ae7ff00f11a7dc3f754d89f873a2e2cdbddeb4c2ef6caf2e0b064635f39fe325a39d746fdea2fa29ba53a9f2752ccfcbbf18362aadcd6f00ad6cf09603ffdda9f8da763e1b3663c51c6d1e1a137263dcd20d1207165742aa9d70d32fa14e3fd8a298fb4fcaa5cccd6c1609ecacb3336063e485af0656f25ab2dabcdc48e3c7dd67b77a364501cdd7a304b4ff2b896bb3cf3eb79b2ee753dc68adceb59fb77869583ca9cface7bb9b2b2a7df634a3d194cacac9c57904b8f3c5ad9747f540cb846364bbdedec552e6e96332dccae79fb2a58e39f4ac8b6d8a37616ed4670d7d9839ca12efd1d8998ed90df70b4dd32c8f1a564858d2e1f2173ee94ea36e65432bfde00efe5744d3246fb5c28af399757096ceb5125389d5743ce6cd1076eb7f95a38ded31d82b9be859ae8a4b69eeb658534d236cf017a1f1d94e51d332df524c85d410fa9bbe0ae65ade2132bdc3c15d673b1ccd13aceef8a583d734d7c123a37b39772b4e42e68ae1d7464239483b4f8442f247238563fe0ff00dde538696eaa2db1f45cc7034a65239ef6fe9757d1304d21b055d3f4668e768bfb211d29c68d20ab26ca8873dc67f44b0b768ddbbe7e8aca3ce7340e3ba7ff00853f72f3b4e9011fd32efd91e0d7681b25419c050713cab6836646ddb57f5542ec4742dbe41faa19d65b83ee95fb43472ace4e2b6c5e8becce938356696ba36db877a586d6ff000da4c590ba26ee00f14b6fd27d71899f97f9674b7bbf4bad69b3c46e66e2057956ae1647667718cfa38968ff00879939da8889f2fa1443b711e1779d2f47c7d174d863c700b5c01de3fbbeaaa74bc58df9224637907835d96a7538c438d10f808d15a8eda1238b1adeca8ccd5a3c704494aba0ea285b2176f691f0aa75db96422cd2cf64c471a325a560b6cb14f68e9414789b6d53aae1742f60d8411c16f70b9075d677e7616b192b9c586c827ca97a86a1335a4348592d4c4f3485c6cdf7596574e72d48cf9142947a07a5759e668fa8e34e08259fd378f0f6df6fbaeaced5f1353c1873709ce6878a7309bda57163a3cb9d30818dda5e6974be9fd3a4d3f4f8b19c3f40a5b21a51d44c98d0941e8d0e9d9ae8dc4ee36b4da76b05c45bea963c34c241014982479ec4857c1b46f71da3a8410c5abe286bb6d8e42c4fe247410ea1c17376dc918b15f2a6f4e6af2e2c8192d907b15b08666657ea160f7b5b63256474cc9657d68f98b4a767f4d653f1b2217461a6b96f07eab51a7754b99331e1ee6381e0b785daf52e8ed2b5785ec971d8e2efeedbd9733ea8fc25cbd30bb274d2668072583bae3e5e0d90fceb389918724f944d6f4ef5bc59db21cb0038fb5b28feefa15ab311ab1e4582b85f4d7ad8dabc58f346fd8e786b995cb57d00dc72d8236f7dad1f702969f1d29d917cd7a37615f635a910b6524a0a43e3da86e039e16d51d2d1d44c6015d97920f29db5c54d0bb147096d252428764d8e5eb49baa85279684f144d88083c14468ae13590d9b4403c2b1214616a6908bc514d22d3e880f6a50291191d8f71afba164e46362b4992660fa795156fe88e697b08c3eea4e9246c6d2e7b9a1a3926d50e4f51016dc767ffbc554cf953e538fa8f711f17c2d10a37eca279115e8bfc9ea2c7682dc7b95c3e3b2accad472b307b9ce637e015098d0d088d2b4c2948c93be4cf0603ca726b5d49dc116ad0296c50bc5301faaf388aee88743810178bd0efea92ca81d0ff52891685b882794c7ddd84c2e3ca841ce783e504b8df74d24594d73a82841e5c7e53649435bc1e547331ba4391c4a80d04f52d28f721463ca77acd6d8b68f9b34ab738c7d8d18b97a15ee038284e9035a4d81f755b91ac37d531c74eaf2a2819d9f36d60716fc059accb8aea25f5e337dc89d36a71303b9dc47c2af12e567c858db6b4fc2b7c0e9794bae7f603dc2bbc7d2f17005b1a0bbe56295f659d335469840cee9dd30e73bd49477ee4f75a1c0d331705a4b1a2423e53e7cd82385ef73c023e552657523638dc22a3f54d0ab449dc9745fc99c1839700df85579baf45092d61b2b2795d40f71e5e7ed6820cfa81058c71bf85746adf464b2f5a26e76b52e4484337514ba669793a9cd4c61e7fb9dd95a68bd244812e4f0dff2795a88b1a3c58c4713031be568855a30cee6fd01d3345c7d3a21b407c9fe63dd58edbf77c21c4f12388ba2112377b9c1fc05744a8412379bee941039e536a3f0795e6be8907b2620a6573b80384f69245211701c847c73bda6fba6443e2a69b2a4c200049431186a7b41a3f54ccd3a3dea12511efa6f9436b39e111e3ec94560ec923e1183eaa90c59057870d22d4fa11745c69baf6461b836cbdbf5eeb4d81d5d16f6ee90b5df0e58384b893e004505aeb3cd854cabd962b1a3ace1eb90e41b76d00f90a7490e2e734fe97023b85c8b0f52c8c3e637dfd0ad0e9bd52f8c8b7edf96955caae8b236f66872ba62295c4c6d0a8f52e999d9cb2fec168b4cea38e73ee228ab88e68261b9bb5cb2cf1d4bda3657735f673131e6611dbc903e8ac34fd7b2318b6dc5856de6d2b1726c9637f65499fd2cc90ee6378fa2e7dfe314bd1ba9cbfd93b4feb404b5b36d35e40e56cb49eb28dad696ce5b7c55ae4191a1e5c3292c6bb8eca38cccec1986edc36ac4b1eea1ee26a72aed5ad1f49e0f52c791183373f50ae60c8c6c968f4e46b8fc134be76d1fadb22134f79a07cad8e9bd651ce07bf69f90b455e5a75be33465b3013ee275e9311b2b7db4a1c983b0f9a592d23adb6bb6b7203fff002b96a707a9b1b22c4ecd9f5f0bad4f92a2ceb7a39d662d9118ec71cf0505d8b7d82bb6cb8992018e46392bb11b57556b7c6519afc59438c97b4679d8a7e10ff2e6fb2be76206dd8bfb72a3bf1c7240474d0ba653181df093d034acdd111e10cc44f850256ba029a213e5593b1c9423011e142100c1f44c38e0f853cc4ef849e89f85084038c3e134c03e14f3195e1092102100444f04d843c8d2b1b35bb72208e51ff982b110f3c8456c4003c77492ae32fec829b5e8c26b1f863a66a037623df872f9ae5a7f65507a035ad21a3d16479918edb0d3bf85d51b10b3c9a458e3002c93c284bd1646f92f672011e56238372219233f0e1d94a6c839b37f6e57519f0e1c96164d132469ee1cd0553cfd11a5ce4989b2e393e18ee3fd573edf1f28ff0057b34c729187bb5e692e0b4999d0b95082716564c3fca782aae5d0351c2e65c692be4058e54db1f68b164419085dd2340d3bad34b39a20823c1e0a938e2c722beeb3b2d8c93fea3c32cf752228fda5303477a2a4c6285244bb1f63e365017d91c47c70851b817510a4800764c43d1b481ca2865f64d06f85eb23b14d100f8dc40a253a49b8a511d2fbbbd263e7dc08ba4c1079791b6e9cb21afe4973c826d5f6612d6b9c4f0b23ab97b897b6e92b5b1a2c8f066fa728b202d0e9d9ed9bb91c2c33b73a5dc0956da7e4b9a08160fca78474493366e20f2179b6aaf1f50f600e3ca9d164b5e011c5ab38813d12d9348c783bb801498b59745206fcf0abdcfe2ed0c3c6ebee42a5d7fa2723658faa811d924156da675002e01ceecb011e6bdbddd612c7a9963ced752916e1e80f4fd9d83135764aeda5c0fd5588f70dc08fa52e4989d4324236fc9eeb4fa7f561d8039fc2d9566fd315d1bf46c9dbc8bb2a3c8f70043943c3d7e1987bdc02b08e48f2ef672b5c6e52454eb71e99419f8ed73dd20600e2289a590d6b0df207f1dd748cbc401a416f759cd470fdae0402151622459c675dd2a5dcf0dbe7bac66661be171dc2d76ad6f4bf6b9cd65958ad534073c93b3fd165687e473f6b9f1b89b038a507538c4d8ce3b6dc3e168f3b47918e236915e69573f18b4ed736d34134f624ab535a2afa671a766609790175bd07224746d0561b4c8369ed5f0b73a1b4d343472a8c88ab5f63d5ff0012d1bdd186f2caf056ef0dff00d16b4df6586d0a718c0198b42d3e3ea6c2380adc551afa0d9b917465a6968eca0e5e14599cca2c8ec52c591ea73d91c51a0b7b9ed7452a3a29dda0445c6805e768f186d6d015e7a64f6082f8483cacf28adf6589a294e8b151f2a3bf4768ec15c49ea8fd038f9437bffcddd0505f41da298e907b8011e2d3db41a40fe14f19318f6d836bcd9a3278239e13a490ad1066e9f8f263240e56635ae857e445200d26c52e93a4e2b5a09bdd7f3e1599c38dc0db41bfa2d2b1d58bb33cdb47cc9ffe9feaf8595bb132fd300f7ae5745d164cd874c662e63fd69582b78f2ba1e574d413025ad0dbfa28cde940c04b6bf8557f09c7d095f4c8bd31825f207387015f6af135d8cfbeed1c27e99a78c38b914bda8006121c7f65a2142ae3d96b96df6735d531dcd99e6fda55165c0f91842dde7e10909f68a5533696d208002c528a7b34a7d184c8d15ce04d1fe1458f410e26da6becb78ec03d883499fe1c1b65ac2efb2cd2a5223665b0ba7626bc3846377cd2b9661fa60376934aee1d3c001db48fba2bb12c7e856571515e85452bb0c3da98cc67467b0a568fc47b1dfa6d08c32824bc34b3eca37fa1f60719ee8e40e2380b55a56a21eda24d85983192380422e3643f1ddde804633d01c76748c1ca6bc0f9214ee1cc20d1078a58fd2f5663f6b4ba9cb4f83297b792ba18f6a974ccd64340874f696324650c2884c0dee014e790394f1e546c871ec16a75c63fd4a5475e804a43cf0690cc6036c95e2ddc7e13d9083c136b1f1fc8b79e81888023cda2fa22b85259131ad1c247814695ca9eb6453d901f6d299dfb292e6b1df742dbb4935c2adc7436c468053ab94d6b40e7c7ca6bb261603720e1150647ff00b0ec23e53890155c9ac451dec1ba9439b579e5bdbed0ad8c188ec48b9972a28af7bc05026d698c69f49bb9c1533def949321252035c057c69fb6552bff0044ac8d572721a7dfb3ecaae42e91e7d4717fdd1c8be7ca171b8dad308a4659ca4fec6b5a7b771f54f0da5e0697b77d53f42a88e00af03491aefaa427eaa6c7d217c5af35d628a66fe2bba469a44892411c6933783e535ee039bb519f21bb0a04926403ca63a6007751bd4e131d25a9d803fe6091410ccc7e533b0b099bac94bb689dfe82875f64c909a429278e01b9cf002aec9ea2c58c96b5c5c7e8a9964d71f6cb6354a45839c1be5025cf862fd4e05548cdcdd46d90b0b41f34a56174e4f2bb7643dcb34b31bfe869862fec1cbacbe51b71a324fd42f45a5676a4cdf292093dbe16970f45c6c76eed9b8fd54a764438f19bdad01676e52f6cba308c0a9c0e9686121d3baf857b047878000631b75dd54e5750e143139ce905fc5ac9eabd65ea3cb61776e3829a15027725d1b8cfd6618add6166755eac0c8ddb1c37058c9f59c9c92417bb9faa03629722edc49f857460cc53bcb1ccea09b24704d1438e59f218436ecf808fa66813e54803a321bf256c34ad1b1b4f6071607bbea15f1acc73b4a2d1ba6a5cd734ccdf4c7c9f2b69a76918d86cdb1005c3c94d61738db7dad054af50300783f457462914f2d878c98dc1aeae7ca738d13b394c244ac0e1dd7b1416b8b4f9f28842086f90eda7e53f613defee832fb89167dbf08f8d3890165ded50234ed69149db79b23841cb76d16c3f74e8e57984d8f0a2006db7fa45a58f7477c20c5904308be53d921be4d94e887c70ce7ba2003c2181488c47668d88780900247ca57a58cd204e8560e3948e23e12b9e01a0bdb78b43426840ed8085e67252393a22005046856b7dc8c4504c0f00f6b5e0fb27844689331b2e48802c7114ad707aa5d8d201297382cf825bd8a13c1bb48e099746674dd3fa9a39c7b24055cc1a94333083409ee6d723c3df19b6b8b4ab7c3d772318ed7d3dbfeaaa75b1e333a447e91f21df750b51d222c8b76c1655269fadc1355485a7e1cae71f522472e042ae50fd97c2cff650e674c3da098f8b554fd1f52c5b70dc07fe52b7833192020d7eea4c7161cf1d4848fb2c93c28cd1a219528bf673cc6d77370e42eb7020f95aad1ff10e68789dd63eaa76674a60ce3fa641b5479bd1bb7888fecb9b678b6bb89ba19916b5236781d738d927746e2d70e2ed6970bad25005646e1f0e3c2e1b95a3e6e093b378fb2145ab67e1b86e7bf8f959dc322a7d31f55587d2b8bd5ad9583d48c1fab15841ab61e4b806ca1a4f87f1feebe73d3baeb2f15c0b8923e169b0ff0011607169969c7e0abebf256d7fd9154bc7a97f56771d8c9058a23e86d31d8e070b99e9dd7313e84790e67d2d69b4eeb077e973db283e2f90b7d5e62a9bd3e8c73c19af48d1fe589b4238fca8b1754e139d520319f953e1ccc7c86ee8a66381fd8ae8d79354fd48cb2a271f6801c7a286fc6beca7feaed447d17b68f857269fa2bd15c310f94a7178e14c2d5e0c41ec5d904e2f2bc31e8f2a788978c451090c42178440295e97093d353488004602f6d47f4c2f7a610688000a4689a0820f212fa61398292349fb21072f40d3b36fd5c68c93e6a8aad7f446073e84b2c4ef83c85a209d542d513c6ae5f414e4bd33132f46e7c6efe9ba291bf7a507234dcec225b263bf8f2d161745ae17806b85385fdd659f8d84bfa97c72271f67346b8b79702dff00e6e1488e40efee1cfd56f67d370a71fd4c78dffb28195d2981334fa44c2ef858a7e3ac8f68d11ca8bf665da529edf2ae1dd273463fa53b4fdd574fa46a3117030581fdcd2a9fe3cd7d16aba05764ba87b45955f90e7836090aca48278c90e85e2bcd280ff7170702aa7092f63a9a7e990a790bd85a7954d9f8a68d763e15d643401c28af66e09574c632cfc116455258e031fe9571918f44d040fcb38309a4fcd11f64561793cf75320c87b1957d90c406aa912281ccbbe55a9a629650646e60b44ded1c82146c6659f7045fcab8b89078475d03639f2ef3c7b535b216d9eebc719d49e22b1dbb24e20e434e43813de9171f3a467fd470f84d763a6362a34a99d468aec2eb035b9a23cb895b0e9fea395f331bc51e0ae78194adb4599f1e446413c14916e322cb24a4b476b3b32a006c72150ea38fb49695ed3f523e937ddd826e4cc7249702bb1c94a299cee3a65165e1b5fc13c2a5cbd3051f6f2b5133005072180f60a97141307aa686c730f02d64f2f43def3eda2ba8e6400df01526461c66c96aada1933130e9cdc768e2c8eea6c1a80c4702d700a76a189b012d591cf91ed9b69e39592ddfd16c7b35ecea495e5a1a781e55ee9fd4f2b4005e173cc36bf6836ad607b9a38bb55f1d77b2d4933a8617533ddb6dc2969703598650dbab2b8fe3654acab242bbc1d4df180771255b0c86bd81d699d7e2963756d3dd19b8fea8b0573cd335f9411ba4e3e16cf0b5d67a2d735bb8d2e85175763d3289d6d2da273b0081407eca8356c79a27921d4df8f85aec59db9518752a9d6b1490481c2d76511d6d19d4fbd18b3b859b29b06e7bc02e239e291f2896178aecabe1c93ea7c52e73da6688f68da688c7442cbdcef9b5a185e0b792b15a76a4e8e8ee5738fabdf0e700b7d1724b4ccf383d97c4d0ef68724a58d509ba84640dafe511cf6ca2f7f27c2bddd17e8ab8b124cd3448be070b2facf5449859cdc793126746f1ff00340b17f0b4663db7e6c765073315af1cb2c0f90a8b26da1922b5b37aecdedec79e4728621ee7e5496421850e59433b2c45f16c8e611441169ac81ac248be54a3548648418e2c71071a2784538d5c345a6463776e14c88d0a46288429700b85f6419316856ce3e15c3781ca6b802ace047b33cf8371a0d01027d3dcea215f498cd6bca60841e384aead823b29f1319f13c572015afd3e4261058e01c079507170984d9014e63044383c2b69871609bdf458b721db79a2531cf2e2a28c9637bb804c7ea3033f54a16c49b28e9139ad0e1ca5644d6b8927955675cc767e96b9c852751ba8864407d4ab235ecae5245e58682a26466471836ffe167a7d5b326b05e18df80a29924703ba42537062f348bb9f5885828592a2cbacbded2236d2acbe3ba4ba4634efd83e52449a8e4ca28bf68416db8f2e24a4b095a2cab54122b7263ebe8bdd928e121509b13e784949c9a5420c3486fa1cd2797043782eec9e2c5711a68a4e29296ed1ca1b9d49bd817428e3ca0cae3e1c95cee105ce04a0f4bd9361630436ed37d4367942de1a3975263b2e18812e95bf64aec4bec78c5bfa0ee9370f84323c2872eb38a3b6e7fd828f26a7953718d8df671f0a99e64225b1c79b2cbc1409f26181b7248c68fa9553f94d56504e4e5b4027b338a4e7616144e6ba790ca7cee2b2cb3dbf468af0f7ec90ed7f19bb9b1873cf8e142933351ca1fd08cb01ec519f9fa6c63d9e98aee50b23abf4bc480dccdb1e02c92c89cfad9aa38d188e8346cd99d7953583e0a95174fe1b64dcfa2564f2bf1371d8088c170f92a9b2ff00133225696c000bfaa4516df658a31475bc638b88299e98a51333aa30305c4c92b4d78b5c5b2fabf52c9e3d770f90103d6cbc9f739e5d7fe62af841fd145938afb3a86a1f8911104630eff559cd4babf2271ed91dcf7a2b2b063bf7d136158c5a797ba9ad2e14b5d7030dd7c7e8f7f88646612373abeea56262bf82fe4953f4ee9e948b003479b5a3c4d271e0746e03d4f041f0b4282304ae4ca6c1d1659ddbb690df92b4389a343865af94071ab564c6b1f6c680ca1d821c4c76407464f20709e3133b9364e8da0b37b29a3e02390760da540c17be2b8e43614b63dcd341588014bcc60b89f6a387b7d2dc4f0428fbc104387053d9b7d3aefe141932562d0a76eb08ac70b076f36a330800340e5488818e5739dd9c38fa29a0ec902a47ed02be5460d9609dc583da4f253dce2c909f94f865749206ede00534308f76f61e1360dc46d27ba97e8b76b8f14a2c8cd843c7845200f6c6027586b933d4208757b7ca20b74a29a28a203e3d0003f29ee70dbc2133848f73afb252e1dcdf2bdba8a560f924a4228df84429308d65b49a098d77042417dc2f06dfd140a3c05a781491bc709c8938fd9e278be179bf26935c0b86d09ec8eb82a0388e04570903413caf387bb84e6379e42814904b3e0a56927bf74de1be526fbeca315b1deac9bac3aa948c6d6b3719fed90b9a3c1514005dca7b982b84bc42a4d1a7c0ea88a4a6cc369f95a0c3d431f21971ca2fea57366807852a192585c0c4f701f00a5e08b236b474e8679da6dafb0a53339d4448dfdd73ec3ea9ccc6e1dfd41f557983d558f90d026f63bff37641c745eac4fd9a90dc5c800101c4f7b5559ba14334cfa8db4961cb86401f1c83f653192995bbc3c71e1552ad32c566bd33359bd2a371d83c78554fe98c98892d2e23e16dbf3320792e6923e51064c6eeed03ee166963c25ed17c3266bd1cf8fe7309db8ee6a9b89d57918a3dee777eeb6195a763648e5ad2abf37a520961dc23007d961bbc6d6fd1aabf212dea445c6eb79775bdfb85f95a2d3bae60341d23997f0563b23a4c34ee61fa70a0656939588fa6ee2173e7e32517cab669fe5427d33b36075786377c79763ea55d61f5a870f77a722f9e59959f8c080e70fa5a3e3f51e646034970415997575b15d14d87d25175461cc06f0e61fb29b0ea7893ff00cb9da49f0ee17cf583d693424032389fa95a4c1eb686707d6a0e1e7cad15f96b63fd914cfc7475b89dad85aeec6ff74fe1dd972ed33ad58f3b5997247f0371a2afb17abe68c8b7b246ff00aadd5f9baa5fdfa31cf06c5e8d9d5242db54789d531ca4999a1a3c52b0c7d6b0a63466dbf70b7559d459ea46795162fa256c49b4a56cf1c87db231df629fdfb2bd4e32f4ca9a6bd83da7ca42cb45b1692c5a3a7fa020605275a7709ae6fc289a191eb4893695ea214da26da16eb94df5121250ca2857d85dde57bd441df5e5217f087bf688879f49e29cc6b87d428993a4e06477858d27e0226efa94d2ee7b9492c784be829ebd14f93d21832d964ae61f82abf27a19ee1704cd715a62e27ca4b2b2cf06b63aba4ba30597d21a8c367d2dc07c280ed1b2a3690fc77b4fcd2e9c1eea4e0fb14e008faacefc6afa1e37cbece4b2e298f831d7ded30626e008e1758960c59bfe640c3f76a8efd0f4c9b9762b39f80127f024bd32d592bed1cdd9150a229496462bb2dc3fa534b78f6c6e61fa209e8dc623fa73969faa578b34591be2cc93236bbc2236167f95689fd1f3b2cc533081f299ff000be631a4db0fd8aadd135f459f2c0a13033fca9a70839a4b42ba768b9ac16e809fb217f87650ed03c7ecaa75cdfd1158bf6538c5a1ee08d8e442f0e014e7e064d7ba17ff00081362cb136cb1dfc24f824fe878d88bcd3b573b769ecadf1f398f0eeeb29821c1d5b5c3f65718b2967078478ca3d036996467dc8521dd7f64173afb584ac750e5c4ab63bfb15e88390ca695499ec70693bbb785a0c8db46fb2c77566b2fd1f15d9231dd2c60d10def4958bb456e6be60f7171b691c0596ca8ccf926c7957d2ea5166411cecb6b5edbdaeee1022c563e4de4820aa26b6cba1247b1f06a216a7438cd0001e1123680daa4f602d2a99c18ca43dace294885c5b4109a79ec8ac367b2afe3d8ca4c9d8f353b85a5d173e405a09e16523047215ae9b92e32002c5290dd72da2d4f6b475bd1de1b0b6cfea527380746ebfd35cac9e9d9b9d34518888a05699bba4c7064e1d5cf0bd2d36a9d7a672ec8f19990d5303d39a52d75871baf8542f88b1e4d1e16bf518c171356b3d9cc037552e6e5269f45d5c80c3991423dee0dfdd584192266ee61b0b9e6a58ba9cfaa4462bf443b9fb2d8e9f91e8421b546b90b1634a739768d128ad6cb86195a6c3958e26a0e60dafe553c7980f829e7303780d71fd96f94649748a38a35f8b5332da4143ca8dc2d40d0f5289b196ca7679e423e567c567d393770b4453705d153d27ec8a5b6e24a8af88581f2579d96e7137c7d90c4cedd6555f14dfa41538852cda2900b0877644764028666e532c79bfa0fc887b1488dea1199de127a8f3f2ad8e249fb15de916824681c9098678c1e5ffc2ac73dc6f94c57c71115bc8274b90c26c219c904528d4908e15d1c682f654ee91259972834d7709b2e6645feb2a3b3da6d13772ac54c57a13e463bd491ff00a9c50a46b8f94fdd69af7784ea090bc98d6d84fb1e537f75e047368bd217b14d24213377252172883a1e1abc682687a6b8da80d0fb0385e069081e794fddf1feca37a0aec797250414307ee3f648e7358092e02bea9392fb1b8bfd06b0997caafc9d774fc417266424ff0095a6caae9facf059ff002daf93ec1532c8847db2c8d337f4681d4426114d2070b1f91d6792ff006e3e38603e5c1439b55d4e7bdd9c5a0ff6b78a59e5e4211f45eb0a4fd9b69b321632a4918daf24aaf975bd3e3716fac1e7e8b1724b1dde4ccf93e77148750d371c17324634d7954bf23297f52e8e0c57b35327508b2d8e073be0a8e750cec971dac8e16fc9eeb2ceeaec387974ecb1f0555e6fe216346e21b25fd8aa5e45922e8e3417b363244f64979198f78ff2b4f088e769d10dcf96cd762572accfc4697d43b390556e475ce5cc2980fd526a6fd96a8c6275693a8b4ec58de46ddc0f0a0e47e216363c5c48c69af05721cad6f2b20f2e751faa82ff0052736f7129e35480ed8a3a7e67e25c6e236ca49fbaa1cbfc42c8983837f658d6e2bcf0015271f05fb4f16ac8d0d95bc98a44c9fab3509f7d38b01f851466e565021d238a970e84f97bb4856b89a1b611cad31c6464b33522a31f1e570a367eea6e2e972076e0dfbabc874e68a34a5c500ee38fa2be342473eccd7f45745a66eab6ab0c3d3def04358403c5ab3c18616b7dd5bbeaa7e23c42ff004dc3d87c8f0ad8c12324b225221e068ed73bfa8451f14afb1e1c78e0708d8d0f61a26901bed8eea9c390a460481d2b8bff00ea724274915726c98c91ae88070aaf845691403130cb1b9ae6d0b1c04f8da5911dfc3aafee9c9a098523bf336fec0f3f552dec21cf747c5a84d710d0d6feaef6a5c4f2e1ee26fca890521f6c60120ec51d8f0e77d1027840c70e8f96fc2480dc449e6910935ce691ed369d19e3b2034863438701de119cf2d70205b4a840dbdc24e14b61f52376e3ee5163a7737dfe5143490ee6bea140ec398c865bddfc2485cf06dbc8a4366e76e6fc27c1eab5a5d5c2840a267b86d08ac0248e9ddfca89eb163ac0e2f952d8e61f68ee794504708c32322f75765e8a469e0f09cd68008432d00f1451d10f8ec923809cc23ca4af3e12b4020bbb205fa1ef7b5acaf282c05de5238ee34027edd8d1b9108e8c869209b5eb04fd108baafea8b15116a10f1b1c84bbf94a1c36ed3dd31b57ca8336118eee9e38050f8f0bdbbba826c23059b45dbb5063750289ea0279501b3d56bdb2fb2736be5136f161100c89845829e450253e269be528166a902038e3ae5488e3da0928ad89bb523bbf6e102022cdce23c2698f83fe8a43280bab27c2f166e1c2885de81636764e33bd923abc05718bd513c203666d8fa2a86b29c490936971bab51a5a1e3336b89d4b8d9000de1a7e0ab1873219393b6be57357465af2458fb2243a965e25964a48f82abe25cadd7b3a50f4dfcb1fcfdd48f5e5f44b2ec05cff000babdec3fd58f8fa2b887ab316460a796b8f8295c364f956cd0891a41dcd217bfa328f70b5071f566ca4025a4108edca8aec510aa957a2f8da99e934cc59493b47d94093a771a426fdbf0acbd563acb5d5f51e101ee981dd1bb70f82aa704fd96c6cd7a28b2ba61ec79744e04286fd372b1daef6b89edc2d4c734b54e6152592c047bda07dd512c68bfa35c726497462da73b1980d915d94ac4ea2c984d3dcea5a7960c59cd6d6a8591d3f8f335de9d6e592df1b4cbad17433a4bd80c5eb49a27f321a1d82b9c4ebbb1ee37fbacb6574d968e1a6c2ae7e89951ba8034b0cfc46bfa3d172cc8cbda3a961f5de3ba9bbcb0fd0abdc0eb11d9b957f4715c39d85990b6c17123ca0ff00896663f2e73814b1c7c9a9fe2c1aa67ecfa460eacbe5db1e3e4152e2ea9c771a9237347c836be6fc7eafcf8a87a84857987d7991130099b6079b5a16764d7ed6cae58553f4cfa0a2d630a534260d3f0e52a39992fe97b5dfbae1785d7f04c46f9767d0abcc3eb58dee063c8000ff00ccad8f9992fed129978ddf71675c3c770525122e973683afbdf5f9afe4ab38baedc768dec703f55a63e5ea7eca5e0cd1b220f3c266def7caa18bac1af6d96b0a950f53e3c9dd95f65aebf234cbeccf2c69afa2c1cce5308416eab8935fbe8a2b66c778b6cadfe5698df07e99538497b426dee9945103d84fb5c0da77a240be159ce3fb174c0d5774c71472c149be983e54da141b4f0bd6510c27c521969068a9bd10f0369c0d780981a47829d46905b08f0e4e0f3f2547b369e0a3a6441dafbee96c2137b775e2e210d7ec3b417d435569bb8fca6a4278538c7f40db16c1ee91d144e1ee634fec99652ee4bc22329b3c6084ff00d368fd935d8d0b856dfe13b71a4ddc52bae0fda0ab1a10e3c5f54dfcb45f25248ff8481dc7948a98bfa0ab588fc285e28d9fdd459343c09ac4d1ef69f04f0a5ee3e134923ba3fc787e85f91eca99ba2f479aff00a4e67ff2950fff00d3fc10fdccc895a3e085a36bc9fb27b5ff0064162c37e86f9648cf0e88c61fff003127f0bc7a2a0fff00a87fff004ad17a9cd6dff54a4822c053f8d5fe89f2cbf6675bd19003ce4bff00fa515bd1f8c39390ff00e15c7a845af0792a7f16afd115b3fd9583a5b15a2bd793f84e8345c6c63c3e43fb2b0b48459f083c4abf437cd35f64dc1cc3843d8db1f5535fd4190f6ed1b5a3eca998eaf09e09215b1a6296b456ec93f649972e596f73ad45735ae26c04b74137b9b4df0c1fb40e723cd862efe9b7f84e6c3157e86a6eea4e0f08c68af4d681ce6fec50c60ec025b00a66f5edc0df2a2ae2907e46158ea1dd3cb8528dbbe13b71a4aa2903b7d86dc1217142dc94393f44ff00e8b66d2eea4960f64d7772a2d937a1e1c9775a1070f29c1c023b22dec524a4b5eeebdc7ca2984506d2a6835c94a1c0824200d8a426a42e27b70bd7c1436417b764c37769c0868b75041765c0d27748c1f7291d915f632837e82f085238dd05132759c3c706e569fb2ab9faa6004fa63710a99e5571f6c78d337f468197fdc29388fa8591c8eab9deda63437f75026d7f3a735eb0681f0b24bca417a2f8e1c9fb3725cc6f26468af92a3cfaa6241fae51fb72b06fd4677f2f99c47dd0e4ce8c36dd27faacf2f28dfa4688607fb3633751c0c1ec6971fba852f55ce01f4e36b7ee562e6ea18636383dcc691f0553e4759e2b383203fbaa5e6d93f45f1c2844dd4fd4ba83eee66b6fe154e6ea19129b7e43dc2fb5ac5e47e206231d4d17f65519dd7334cf3e8b7685549df3f45d1aeb89bf3998f1def22d479f5dc581a6e46ae6b2ebd9f91c871a28065c99da4b8bad058963f6cb1db5c5746fe7eb0c78af6bed566575c08dbba23b8fd4ac93716475eeb4f6696e9070c2568878fdfb324f3228b3c9eb4ca94ba9b56a9a6d5332726defa3f55319a34a4f11903eaa7c3a03cb796adb0c551334b3d233f7952772efe528c298f364ad641a06d14e23f852e3d16268a21688d0bf4649e737e8c63305ee1fa6d49c7d2e4712030f2b5f1e99031dc342388238c70d09d548a5e5c8cb45a1c8eee14c8f41dbc9a57ed603e2910404b6c0b4caa4532c86fbd94f06911807700694c870218f86b38faa9cdc62d697526b680af253a8246695ecf43825e2850451834ea28b1b5d7c77aff54ef55c246b5c0927ba6457f2362451363b6d5a77a6d7484761e510b36bca6387240ee7b28d083e388024a98f204713ebbf750e23668771dc23b67b67a64723b2042689c358001dfb5a342c6ecf5d8ffa528d0bd95e9c9fa9c2d87e51b0256b8fe5f8ef77f08a1913a001dee52c874d1820f2d3fe8a05fa79046ea6b85feea740ee360346bb271c7e347f988c86b80734f0a546c7471ec791bc7ea50a263a2976c77760da94d2e79249e7ca2441f75b832e9850dcc7b5d4d3cb7c7c84acdbb7dc793d91439aca73bf56eafd9408e8a46381dc080a4c7ff2b69f278299b19ea1068b0f628cd6b48a69b6fca841d74031a45a2c1336273d8f166b85119ea191ccae7fb4a3c0edce3eab69cdf3f28909b1963a4d8d3ca57b9d1ca62fed3e7e1318d0c70781eef9466bbd573cc956470a0413a20d3dec27b5a1ae041e027c518763d13c8481a28d8e54410ed7837cf7428dc3d5209e7ea865e212db3ca2491096a46f053019f1eb0ee1b7e179cf76da092f6da66eb40d415bed6fc94846f75972f35e03520e5ca0878b41e3c2207860e0267049a4d7ba82834474846e14795ebb25003e8728ac6db03be542487b5e1a08b4a0da10692e52580552821e65d14f8dc01a22d7b6704a746cf2a1090d60237527b42634d001486b37347d0a841c19b53a38c1fba7d6e75299063822d42036c0401c709ad8f7bdc2b80a53c50ab5e645b232eae4a04220836c975c14f7441a4d29f0801a6c77417c4492052056c80d6924f1c5a52d0d0476531f080dfaa13e1b6fd541a1ec852b7807e50248f73081dd4b7c7b783e10decda2d44072dbd104c5b1b5ca092e143c0535edb43f4c35ae24201070e5e442e2592387eea76375165c0d2d790f6fd557edff00548e6534a1a4345b3478dd591581202d3fe8ada0ea1c6900a7b6bef4b9ebc91c2f1dc1b6d75153e34cb94da3a845a8c5272c907f28e2764829c1a7eab97e3e6e4c237091d63c2b4c5ea89e2ff9a2d2ba87571b87c43bb1fb50bf2d3def6cc6c7d55143d538f2b01276953a1d72291a3fa802afe30fca59327c98cfbadc3ca38c963b97b28fd9408b516bc82d78e1486e5c6ffd45a50f8c75684bc7949fd27e88391a5e14c29cd6d9f09ec7630362814af63241bef9fa25706fe8b23625f654cdd3b1077b00a429741b8ced72ba6c67fb6435f54290bda280279f0ab74afb45aaf92f4ccd1e9f9d8491e105fa76743fa03bf65af6ca01dae69e4774e13c4ef6800fecaa962d72f68ba19724bb31ae390c680e6bf7a243aae5c4e007a82beab54e8b15e48205a8eed3f1dc780166978f832d8e768ae83a9b26204be5938faa9b8bd753b05091dc7ca0e56891cdc34d28d274eb434969f0b2bf19f69972cd8b5a68ba87f116663a9ceb0ad31baf4c91fa9c50f82b02741981771612374bc8858407168295e0d8974c1f3552fa3a641f8891970bde2beaace2fc4488b4112bebeb6b8ec38796cb22d3bd4cd8c1001297f8d910feac3ca997d1dce0eba85ecbf59bfba951f5bc1d9cf67f2b813350cafd3cb484f76ab94ce371513cc881d5433e8187ac31a5e36dfd4294dea6c2f25c0fd8af9d22ea9cbc67f0f77f3c29a7adf2da05bca7597971fa2bfe2552f47d06cd770dfcfabc23b354c49381335700c4eba9c70ff1f553a3ebe02aec7ee9bfc9e42fed1d83f811fa3b989a1f1233f94e12c47fbdbfcae28cebf61ffaa5bfba95175f0bb191fea9979a9af7015f8e3b331cc3d9c3f94ae69f85c9b1bf10984f3914aca1fc406b99ff00edad015f0f2f17ed153c068e8c0129d5c2c0c7d7e00f6e4c6e468fafcb8d1963fb2b23e52b62bc2b0dbed5edab243ae9a1bcec3f629f1f5c447bb01fdd5b1f21532bfe2d8bad1a9dbf4485b5f2a859d6784e6fb8381446756603bfccad59b57fe5a15e3d9fa2d25601d90f9501dd4982f3fa8ff0bccea0c1b20bca9fcda9ff00ddb13e1b17d13f9485436eb386e3ff0036bee88355c3efea856472aa7f656eb9fe89b086df278457359e29577f89e251feb048354c53ff005826fe455fb07c722738b40faa1ef50cea38c7feb05e6ea18a3bcc14f9eafd8783fd129789a51bfc4317ff008e127f88e20ffaed53e6abf64f8e449bfaaf5a8a753c303fe7b50ddace1b3fea8295e4d4bec655cfe91600d2707d2ac3ae619ed226ff008de20eef293f9b4af72d0ca99bfa2d3758480aaa7750e1b7fb90ddd4d84077287f3a8ffc83fc7b3f45c1e52059c93abb198ea0091f7437759631e0023f748fc950bec758d67e8d36efa84ac772b2dff174357407ee867abda7b3c048fc8d418e1d8cd81e39a4a48016227eb17b07b266a88feb095f7538dcaa9795aa23af1f36741b1f292cae747aae62de66ff0054377564a3ff00e648fdd52fcf56ba48b578c91d243c37c85e3333cbdbfcae5d27579f390e3fba01eaeaff00aa6bee82f3abe913fc648ea8e9e20d277b7f943fcfc154e91a2972c3d607b097fd5066eade7fe6ff00aa47e6dbf48b23e2dfdb3ab9d5f0d80eec867f2847a83039db2b4ae4bff1444e34e73493f55265d7191421deac401faaadf98b1fa43ff8e82f674f7f5361b40b77751e5eacc46f0d05723c8eb38db6d2e06be0a87275a47e0a6ff277cbd224706b5eceb13f58b184ed1c7d5409fad272d218fab5caa6eb38dd6392a1cdd5ee75868fb247919132c58d5c4e9f3754e43812e9dffca812eb6f7b4bcc80fdcae6b3755654cd0da22bca8d2eb19b2f0d71e556ebbe6ff263a8d48e94fd7e26f2e91a4fdd419faba08df45c2be6d7393f9c95c492fafba5ff000ec899b7eefdd3c7c7ce5ed892c8aa3e8d965759b038ec7f0a39eb78a31c825cb31168939364123eaa4c3a0c8e7d39a7f8572f1897b2b79b045a64f5de4cf198d8c27e080a9a7d7b52c871a73c05718da14ad69a60e78eca6c3d32f78b200fd969ab0208a259ebe8c9ba4cc9f892479253a0d29f9160ddad9b3a500e5cff00f453b1b42820af27e56b8d097d19a79edfa30b174f383bddfeca5b3a7f78ad8495bb6e9f133b34220c48c76681fb2be34c7d99a5972663f1fa65fb00d9c2991f4d86f70b4c5a1bc0a4c771f08fc71334b226feca666831b4dd02a645a7431b78672a61735a3b8407e435bddc004c924512b26fec19c48c0ba5ef49a050e1324cd8f6ee6bafc204b9659c147a0727aec924b1a7ba6ee69341c2d579ca3777c5a9133e48640c0d6fa6e17b877437a0720fc52639ae0f1f5428e73ea107b04e7b8bf79ed54976194b6ba17f30592966de2ead4fc6717801a78f2aa7d6dccaaf75936a46048f118b3f74c99477a2fe28593090068a6b7baa89e3f4e4b6b6c5f856588f0e6ed61e4f7bf2a1c84b663448a3c8a44505bdec9011f37fba93935b83dbcb88b4c01b7bd3dad2f207ee8a1858dce95809eebc7f571dc2751f543870135ce1bcdd83e1120e638b01703ee28b2c664804805b9bde94632b8329adb703ca9314a5d8ad9070497023ec9483a399ae8da0f3233962990435b72a36f3e45aafd9b86e6f1b558603ceda3fa64e0228744ddccc88f737f503b9486bc4b1b258ff005b1c091f2a2c2e64192013519e4a9113c4323f610e8dc6c527439612b492d7b4d122ebe0a58a46bf97b4db7fd536090b9fcf6ae1235c5ec2f1c73c84c425c6e6c80f1cf8456401d1dbb934a244d96161734ee00f95318f716d8e2fba0440a10fbda4170ba53e0606c476f950dd1ef14c0edc0d9a52a07513cfb1dc8fa2811f13c82770b23b14432ee0eda86621cb83bf64580b7672c4c40b1484b435de11196f941e41ec876e127b470a45fabd853daa04308dc1c7943342fe5783dc580ff71ee8e1ad0de47850207d264ac3bbbfca58b76c22fb764d2ea27e13e2203a82813e3979be0ffa26b450a4f6817d923d42f6782737ca6b5789ae54021d44035dd05e6cd5f2bcfc9d9c79426ee91d7480e3dbee3b51da68526066c7703f7440dbe3b2600ad4f60739ddd206d14f00772485001438b5a4577448413e10daebedc852e22057010119e8d94549613d9318c2515b43dbe7e5406c7c0d717a9f0b83782a3c036f214a8dac22cf7536442ec2e929dfa7c290184d7c05e840735dbbb8ec8954da1c20197a075f1d9208c171476b00b1dd79ac01db436c908156885334b49a1686db20d8539d0baa88419232df0a136409197654690717f0a7491900bbc2008b782a00af76d71dbcda473886ec2dfdd1db1bbd5ba4f919b9dcfc283ec8223e40ec80e16dbb52a6040b69e7e5410e7722b8446409dee72f552473b9a094590896263f7b5cddb547fcc8190eb140f644236823c7c28e0ee7f2a1144563cb5b44f2963c999afaf55db7c26bec1a086c690ebb280dbfa27c5ace4c0787d8faa9d8bd50f69feaaa193dcea4c20b4f0810d9e3f54412d0163eead22d598e0089073f55ce791e4a236599a2db23bb7ca5d0e74b8b5104fea521b9e2eb85cd61d5f2a16076e26beaa4c5d5728203c1fba571223a38cb611cd27b1d08f7002d61f13a9e39ddb43aabe55a45ac5b2ec11f455f01b6d1a23140f36473f74efcb83fa091fbaa066a566c3d1d9a8484fb5ca700f27f65bc986edb61fcfc217a5922c707850ffc41e07b8a7b354703dca9c09cc331b3440ee086e9ecec315a79d503bb809466c3b81e2d2fc61f950d6c9edaf48001780883482d1ca27e661783549cc7461b7c20eb1a369024c4879341479b023907015d491c32b076b4138ed3fa557f10df368cee468cd70e105bd3ee3cb9d7f016a7f2408ee1064c4730dda9f06c6593a3332688f69e0a1bf4a99a169bf2ee48fc73b7b12556f1e3f658b2da326ed3a6faa04b8792d1eddcb5bf967341b1dd27a03fb9a97f8f0fd0eb32463dc32e26ff007258e4cb3fe65a79a1612418c11f64d8f1a23ff492bc687e86fe6b33c350c888f0e7270d67203bfea7dd5c49a646e770d28acd2212d16155fc383fa1bf9cca576bd942e9d20fdd359d459cd3c4b28fdd5d1d1e2e781fc261d163f0028b063f40fe732b4754e7c67fe7c9fca7b3ad3399fa677da967428dc7902921e9e83c003f641e0c3ec9fce02cebbd47ff00ea1c8b1f5d6a20dfaf69bff0e460f85e3d38cf1c21fc0813f9d1fb24b7aef39dfaa428cceb8caffe3b8fd141ff00875a07ca70d0a30da2c0ab978d8b27f361fa2cdbd7b3b45190a51f8832b39f54aa67f4d87bb8750f8437f4b0228149fe310eb3abfd17aefc4799dd9c137ffd41c870fd6553c1d31b47b8128bff000cb0ddda65e3913f9d5fe8b21d7d37ff0010a6bbafe6e4fa96ab4f4b349e2d364e9668f051ff001e81fce87e8b33d77311bb7f74dff8fe46f827f755cde981da8a7b3a65a78dbfe891f8e8fd93f9f0fd1307e204e47909aeebacb77690a0ff00c34caa2d5e6f4bf3619c28bc743f415e421fa1ff00f196538d995c91dd63943fea393d9d341a7962f4dd34d2da0ce7ec9d78d87d8bfe496fa4447f57e438f049437754e4bbe54c8ba5f68362938f4f06f847fc6d7fa0ff00912bbfe27c91e5c9a7a9f289b0e770ad59d38d7725a8cce9a8ff00f849bf815fe81fe4599f93a8f35d74e7774dff001ccf79bb2b48ee9b8c0e22a48ce9e6b7b4613ac2aff42bf22cce3b59d448a05c86354d45c68ef5ad66861bfd817868a37590029fc3ad7d0afc8b325f9ccf70b25c99f9ccce45b96c59a2339b4cff0004603fa78fb22b12bfd09fe46463db919ae3c1794f71cc70e4bd6ce2d2216f6681fb237f8545f456ac4aff00423f2337d18318f9af3c39cbc71f35dfae57903c5addff0084c377c219d0e07389a2ac8d15afa11e759fb3151e95349de428834776ed9b892b66cd1a169ae51d9a440d75816537c71fd0bfcc918b87a7dce71b695361e9b0392c2b6516231bd9a3f8468f1c7c265522b965c99918fa7e303fe59254987a785dec016a0e38f80bcd8429f1e8a9e448a38b418eb9afe14d668708001f0acc3000bc3ee55918e8a5db26458b4a81bfdb7f75219850b3b31a13f751ee9aec9632f73804da176c788d9e004f631aa049ac62c20dcace140c8eacc487f43b71fa260e9b2f5cd35e10800d3c90b17a87e2036024376b40fdcacf4fd779b985ed8b706d5875f7450541fd9d4e6cb82016f7b47eea0c9d47851837237f95cd864e66a8f179120b6f60e3c25931cb4f23c84761f8d1b5cbeadc6611b5a5d7f09936bf20e0c65a1c2c12b350c4c73dbbdbec3c2b0983f29ae8de4138e2aaf92cf9418ae2890fd532259765903ee9439d2c8497b8d0b20950a089a012e2eddf552e0881883eac3ad84fc155b656d242b1aff4b2b198e20822504f752df90e93185b2a4656fe39fa940c03bcc326e21fff00ecefb3fb025370f2659735ccca7fea7189eff9bf94548a9c49103daf8e689ecdbb80d8efa8effeea4441d41ae26c79439f1e510c911fd704dfff006d7fec3f946c593d76b497017c20fb07a1ad0d32bc934d3c9a4d73dcf63b6124f7af9a5e99a610e6b413b8af63b4c3389240486771f20a80d898ef6160dd7b8916df1451a2ddeab9a29a1cee6bc5204b8c5ec97d1268821aef3cf6fe111af2e7c1256d8b2051238a7522885b472377b0b2dae8fb8fbf95232a8ff563e491ca84c649eb3a40e17b36917dc29706e18c1d62de6a936c9a04ca70e084e849b780c758e2fe533d1d8090ce48bfe14a86c16d7622c028ec1a06e0e732bf491e13d87700e22e91046c7e46e73881547ee9b88e63b7467923e8a106b5b4f24d00e4b15c6c7b483efee3e0a73e1fd47c8e404f129dac900b0e34f50280ec79140edb3dfe14994c91c41f18ff00cd5f07ca2cb8a375583c5d04fe5d21611408ba502892d8db9b8cd91aef76dddf7fa2573fd1c23335a4ba320b983b91e47f169b8c4633e18f6800fbb8f1ca972348943a314776e6d7164729d7a1c971c81f09732b6001c0f9fb23e331b2476496d77ffcca06316e33803fa643645f6b53b13d46ca18e16c068df81f2990507c575ee2eec7b5a3580cda073defe13258c805ad1c785e64840696feaec5bf4502498232f05c2fb27c313a26169046e360a16f79a6b7f483e3c8466fad37b47706daa105631ee98c228102eca92d9446df4dec20b7e3ca8fbf71f50d83fa48faa99eb367a6c5c3da393e540e87421cf6b9d40109d16e6c9556ef3f65e85c69f5cb8f76a2e33f82e2d167ca830cddb1d23bc0ec149f544b132851ae505c00783dc14685be9baaac14428686020d849180c9369ec511c7bd7ca4317a9cb4fb93264d1f1b91b47c9427b88ee9cdfba64adaee6d434b47bf5b785eae3baf07863504bf73bba056325b73800394785aeaaae53231ee2a530ed1f0a0db1cd6d04879481c6936eca885d8760b6944672d210e3e07ca2b057d6d106c263b39e14a20020143846d6d94e6fbdf6800971bc1168d1343ceeae028c053a8716a735bb2100704f7501a0f8ad697f3d8a95e953c3681f2a14163927b2b0841209dd64a0c88742d0e240ee90c9448ab29cd8f63cbafb84b134592794a4977d0b15971f94767f4dcedddd208581bbecee29dc13b89b500968f5f651e71bc12881f6e22b84c7d906bb2640e246923262dbf2a2fa1562d4d99a7800f6412db347ba80688cc65ee7116107825ce23eca5b22f499b4936579f0b58da3dfba02a452e4c64a8cf88861a16a7cedb71f82505c7638b7bd8507f4553196faf28db6b829463b848f7ff009795e7027ca28b57a22c84b242d3d90da00b71525ed05a1ce1ca1cac1400f3dd10b6088e370ec50dd414875066cafdd4593ee80a7b6f17e537773ca20702daa48d60279403b1af23b79446b69be1239a0fdd2927b283a632561adaded484d806df7147ee0da67820283911f13984961a4e66a193882c3891f0a4d58aa084f634df083414c9989aec8fe1ce015963eaee6f3baff759b6c4d70f6803ea87fd56ded90a46865d9b36eb763ddca930eab011ee75158766a92434d7b4bbeaa643a9c32f0e3b4a02b86cd83752809fd68a3258fe778595dc1c016bbf84e124807eb214d89f11ae8a5dbfdd69ff009aa356b26dcb9987879288cd465ff3260f166b199859fdc52b7503756b32dd4e4aa29e3566460efb2a6859266a4679e087277f88070f72cab3546bbdc1c47dd199a9870fd48a8eca9292348dcd0e3d91465b4705671b983b8714ff00cfdf01fd90702e8b2fdd92c293d78eb9a545fe2053db9a1e2b721c09cda2e8be322e82567a47b016a9bf3742b7256e786f6297888ed65d16b09aae52889a3c854ecd40df7e519ba81be4a1c03f293cc6c0d26c5da40d69e143fcdda20cc087127224ba368ec137d31f084fca0470524795f2e53889c83fa20f64a22010fd6f82bdbcf7dc1071072683fa2d3f097f2cdfb20b2620f348debf03b29c42a63bf2edafaa566380983280b4a32dbf50a686d851083c1ee97d0a4d8f29be4144194c3f2a681b15b8e3b2593145a56e4c67bda576430f3748689d8c188027fa0005e190c03baf7e6995e14d13b13f2d67f484ff004b6f0004832d8385efcd30a9c4011918aba5ef4fbf091b94c1c5a77e6e3ec795341d8df4782693443679684ffcdb129ca6529a0ec41081e12edf80130e4b4774e665c75f2a7127214c408ec9be8525fceb07002f7e6c90780a6856c4f401fbaf7a3b7ba61cdaf0127e6cbfc29a22615b00e6c84d308f94c39476f7084739a3bb9bfca890c1bf2e094e1028bfe23137fbc21bb5ac78f93284da013db036f909ff009769ec55249d5188c27fa80d289375be2c3f27ec8685d1a8f480f0126c039e162723af1f20fe8c6a0bfabb3a5be76a21e2ce8624636edc178e4c2070f6ff002b99bfa87507d8f5a87d14776a39725933c9fca9a1940e972ead8b1def9d81447f536046684a095cddef964366479fdd2c609ec79faa8903e366f24eb2c30768ddf750b27ac451f401fa02b28360144d0f92a34da963c0db748d71f809921d57fb3453f536a12ff735a3e8a1cdaee439a44931afa9a596c9d79ee6910b287c9558eca9f21c37c8e26fb26d05411a4caea0863716b24748eff45553ea997924ed76c1e001ca8f1635bcfcab2c6c7d8cdce682eba06900e9222e3e2be57ed92dc5fe4fca99898a03f61ed4a7cb8a585ae60e4f238ecbd8ec0f949a340594bcc594f648c76b1928f4f820ed35e3852a384cd19239d9c1faa1988634beab5a7d2976927eeac72433035110b0831ca5a1943bd8b51b29721b8d0ee8f616723907e549923607c723083249153c7d6c8afe2937d392298d1a0cf7347c9442f6c913a6d9c3807d0eede791fb209956d91f6b99449dd7ded4bc5753658bb0710f6fd2bba8c087b2fddc0b20a917e9456daed5fca523f42b18d31d8796b9c28003bfc1527f27ea64cf07b5ae9236cc3ff9c775167bc6763c960b4b2a91e790b1f1650734fb8b49fba22af44a91ef75927991bee3f6436b7d38cb982c816118474c0fab07b0ff0074c07749b1be00b505d06783335af8c82dda0a6301735c5c6bebff0064e8e4d8c6c4d686d34df082417ca23e5ad27de3e0fca857a0d13bd289ed6db8bbdc3e8478fdd2e3c7eb41246ea0d61f518ef1de88fdad174e91916646e9796f2d7023b7d5321c6a88b771a0491fca21416096d911e0104c6f3f5538b28b0b4d80a03630cbe0538eefdd58e318df1904969f1f550239f1b8b58e1c39aeddff006462c2c2370e0b6db4984b8b192444ee6b9a5b63f537c83fb23e707b18d7b79008fd9a6d140198e43c8b3cdff2904420c9dc5dc1e084d73831923a2ffa7ee03e4270febb37fee4fd3c2200d90cb67a83b1287090c6bac5b0f70a4821d0ecb175c20be30e000e2fba2061212f7d8ff2f013cb1ed7b6f927943c38ded900374f1b9a7e42972fb1ed78e48f0822c88476c2e05f774a4878971078732893f63ffa28f101911170e0bc71f43f08b8d26e0d6d539c39691d8fc2b116123220687c12b0db1e79fa5a9cd8e66b1ce8cb4869afb81dff0085131834e3c8d939638fff004fc27e9392e11ba393f5071147e07fea8a602dd8f06205dde9308642fbab07ca71d9fd397fb0db4a63e00ec875baa26b41099fa26c951425ed76caaf052b1a58e0d0e36ee07dd7a1dd8b106b9d4d91d43e9f09dff2a68dce1c0e528c8595a3d56b07f70e7e852420c1b9f7cbc6d1f4725cd90c120ca8dbb9845387c7d519b1366a24d893dd438a50615ec95b3453b080d70f70470da7d6ea0e4dc7dd2bdd013cb058faa5c2609e49048ea23b28409131fbdde405258e716efa1438430c23b1446fb19b6f8f28851e2370dc3b22c20526471bdd44105a52c640268835dc7c28147c597caf1b3f64dde17b7f149cbdb11dca6500ee072bdb88052b4f72540243e0f7137c22876d3479420eda38f29cef054d01a0c48aee9ac010db44da2b689b501a0ace01b458f93c9a436f7e111aeda4fd94268925c0003770890d12a1b0979eca540f0dee80ba26346fa7fc76467c8e73dbfea811bfd31f20a3339367e14213a060db6e3dfb2958ee0d6f05410ea634da9303876081344bdc49a446346d2a335feea52623ef03c2508579da0b89491f039ee9b25125a9e39f6f8010225b605e5cefd2691082d65129ae01b547b7292494cc413c10a261d0392e43ed1429318dfea51ee9fba9c02f16f05c136c46816406ed15c9409bdcdb27c2915bde47c28b94dba6b4f3dca80d1080b8c9aba2a36434101cdee558c9c34f80541630c921b3ed054203d9b6124f95088db64ab390378693c03ca8d263fa91b8b458f0a263a7d1024b7041d8ea25d7f4537d1d83948f88101d75f4441b2010434a8cf1e3ca9b2507577b4291ad06e94200634d27b015e751095a0c62c20118e277d05e7d0a4d61dd2129e0592541d7423e8b6c248c5b4909cd65924f65e1ed69a506d89d82149b4823ca21bda86e6f9450508c6358384c8da05df944fed4805b50681b02e8412483c203e0beca6ede0da635bc94ba1949916333c009638d7d5119aa4ad14e61723b9bed3c2196b761a1ca1c475225335386460bb8ddf08b16446f276bc12aaa4c60e04f7514e3bc3ad85c3ec887668fd4783f448e96f8e15237272e0672eb3f54b16a8f06e48c92803a2f4363737f51b4cda580d3bfd55537528dc4f0e6a911e5b1c3870fe50db269139b23c9e2477d92ba795a3dae2a109cb7c8288dc8691ca289a44a8f2a6da4efb369a3509c3b9e107d7634775edec70b47426912c6ab2014412bcdd5c83ca8839e004df49a4fb829a27144f6eb2d6f2515badb5dfdcaab636ea93c4318f0895712e5bad81c5a33759691dc2cfba368290c7c775341d1a1ff0018abe47f29edd59a47ea1fcacd36327c949e9bc920128689a352dd581ffa83f94566a77fdcb205b237c94ac7ccdfef214d052d9b2ff121f29f1eb2d61e7958bf5e71fde69219e5f0f24a9a27046d5dad3013c7295bac35df2b13f9b9be4a519b38f252f127046e06aedf04a737566ff99617f3f38f2bc351c9ff00305388381be6eaecedbd2b75488f777faac0ff008865787048dd4727cbd4e23289d086ad08f23f94e1aac47b52e78351c8f2e4a7509fc38a9c49c4e82fd52207f584cff178ff00ce1603f3d91e5e93f393fc95388389d07fc623f2eff54d76b110e43bfd560464ce47ea2134cf39fee254e24e06fbfc6d9e2417f5290f503471ea33f9580b95c797949b1f7cb8ff002a71070376eea48da482f693f7483aa6068e5ed58511bbf50b4a62e7ea87127036afeab87b87ff000a349d58083b6425653671e12b18394389381a41d5a6fc9419bab6617e9eefe55018c03c148595e54e232896eeea9cd3c026934eb39afbb7503f555ac6822d137523a1b41dd9d96f26e570fb14232cafb0f91cefdd35e688fa84d22c1e69148838027824d0faa791ede05fdd059235a0ee700bdf9c89bc178fe531344903ea9a4105439357c58472e24fd146975f041746c06be52681a2d8db425123003bc803ea56665d6279ddc5b6fc0419659a4eee7108e83a3412eab8d11203ac8f8509faebc877a4c03eeaa1b17b4d024a918b03bd406810a682859b3b2b23717486be02132179734ed241fe558458cd3b9e05047821692e900e0147d0391161c73658f079eca463e2b18c7b4b2c9ecea53440d1b6568e4bbb1525b07f40bc0e0f6fba8d8ae7a21e2e2f059b49b1dd5960b5a5c6223fbbca262463d0049e4b7f8448a11ea82db3eee4fec9365129ec951c60e4c11bcd7aa5a391e37007fdd3b51c0820ccc88a173898df56055348ff00d548fcbfa9a76364c808763873038793dc7fb2f656130bc6407bcbcb03a43fe604ff00ec945d8f94332f478e1aa7c25ad75725c2e9332b7e4e9b1cc4b7d4c76d1a1fa8b781fbf95ed35ae826cd0f36d8dc367d41ff00ded15f1c71cd362b77189f21939f04823feea0ac3c32c33603276d86975807cd8e7fdbfd51a385a4365602d63cd8691c1a3dbf7ec55761441fa73f15e761825e493e0fff008521b03dad7b1a4b8c5cb79404611d13617edb143b1ff3029909fd4c7f2d278b0a76531b1c6d2035f4ded7da90e088e7ed8c16b448d25aebf22bff005403b1d8b0c7918d94d949f4d8d2da02c8247082e617e9e6326ea803f00f03fd484b1b8c39d0036ddded95be1c5bd82265c231f786dec91e2bff0029bedfcd280f44bd2e4fcd31b1bc105836b8fc387ff6130b1b1cd232e8924025370e4317a9b8fba6f73abc3bb7fe888e8e37c9ee25deeeca364d922089b2485c5c1ae11baec7d38ff5a43ef94c99ecf739b4efa91ffb1446b8b9ee738017ede3ff00bf95e80365638804bdbc8fd94d8878c61992f26f69e3ff0074b36e6b1bb7ddb246922fc5f293d2f5017091c5f7bb9ff645686b5e5e7f43cd1ffe64764d0f30b4472c5f037b1d7dfe9fee9e21f4e2de492f8c0340df8b4af66c6b4f96bb91f44f83ff00133513e932676d71f81ffdf2a6c1a2c31bd3dad76ee3bb9bf23e57b699b23bee6b6ec1f2da50cb1f03a5848a7c3cb79bb6f96a34b21630491d13f3f21301a05841ed9b6175b082c27e9ffaa3c2d384f7e382246b00078e081c041c6262ca109163208900fa852a08981ce612e32b1db483f078443a0d8f1005dc069737701f23e895ecf498ea1dc584c99e5a2290348309a67d5a54c6e38200367e510688db5dba3daee63f7b3ff994923d489b935c8e0b7ea93d165ed889efe526139dfe27958ce61f49ed01b7f2a0d11ef7337fb3d80343be05a24b333d76e4b472ee687ca73b103e07c64804761f28b8c193c11c61ad6cac3dbe811d9620d07f5e3f536ec1fa48fafca8ee99d165c6ff004c96fea7103b81dd4bc3732733c57b4b681093231e766df4e8b9ac2e17e41eff00e8a6c858979ccc16e4c4458a750fef6fff008529e227e236369fd436d790a9f0268f4f95f16fbc77ed733ff283dc7ecadfd2db1b8914ebb1f64e84f635af19389263b81f5601c1f923b7fa52740e32e330c8eb6bc5825234ec7b246104c8687d484e9a38e378c78f70121b04f61cff00e8a3197411b905b0b5ae6fb4bb69bf8536369f5810386900d28797b4c9e911c501c7cfff007488c99f0ce192dfa658391f2a21d3246d2f9cec710ebee13e2687ceeae1c073f54a4fa6f89d18e6e9c538637bb76fa737907fcc140e85a94901bc83c9fa23491915cf042f3242e6ee02afba0366709887124764424985ae8d8e7075b7e1246c6c8e3332c5fea09ecb0d4b1d30ee6ff0a051f13b4822d35ce3e125802979b7543ca6d97fb1c05f08a0343506f67376943af9536148203b9c3e1138229047b4121381a168a068783f4441c8002087df646848e49449a082c506f74f7467701e0f74c85c0bc93e117d51468a00d0f8da0127c76448da5cefa20b0973432fca950b7694a02516ec976dd80a5474e069406bc9773dfe54c846c6f2795362683c2eeed2a446f07b785118fda09f28b011e4f743642744438127ba94c2d6d51361416f269a6beaa435e41008b2ee100aec34726e79257a698b4f1e531ed319e0a6b8eeae1019208012d27e89a08de00ec022b788c93f6416814e17448e1420e700d160dd94e91c238eeb77d1363007007298e90ba42d6f84508d1e6348697d104fd541989748437eca6cef2383c0ae3eaa001231cd240e7923e11d9388b3c9ed0df9085b5be838f64621aee48fd905dcd8ae3e10d8013d8df4cc84724d52633fa6d27bd78459eced686703942b05ce63792544c2980716b83ac7d94795ddc29590d01cd6b7b0eea248d2e05ffe88a6022b9a0bafca13c59aef68b135dbfdc11248c178a1488484f60671495def89cdec7c23cb575dc8407ff712500a030c5c9252d9161be4a787530a63012a0e2bec7091bc117dad7b92fee9250437eca111e99fbdfedec1307eae52421ce367b2f480828a630e27e890101259aee90b771ab51810a690c025e6bb27b8781e17ab90471f28683f427d0f64d7b38e384fddefb5e90f16a68310554481d932ab84768e135d1ee143b94ba188ef60714c7422bb5da3b5b4d17caf58f28e89b20bf14f3487f9678ec5583c0a348747eaa683bd1059248c3409fdd10e64ace08b477420928621ee294d07620cfb1ee61b4f8f38125b7486e8e81e10c63926e94013199c41203bb27ff88bc780556985c0f1c2f7a720f250d80b61a8d9b737f84e3a94342eed53ef7347943f59c0f2d251068bf6ea10bc5dff00297f3711fef0a844c3c8a49eb463c21b268d1b321a470f09cc95af0435c2d6704edf0e213d99263fd2ff00f5451345f17879abec9db81e3e167bf372b79f50ff0029cdce9c7690a81d17fb81f84dec6f8546350981e4da2b7527b01b00a889a2e289238094b7eca9c6acee2c27bb5622a980a20d165b45af7a6095591eb4d713b99b53ff00c6183c5a3a0961e973e17bd3a77855dfe32cbe5a9c35884734495344d13c821de12d7d1563b598eec30a5ff1c8c0fd0e409a2cb6af2ac76bac0d3b6224a1b75c793cc5c281d16f74941b552ed65c47b63084359905fb405085d7ee96c52a23ac4bf01786ad39ff002a84d17cd7b6978b9bded67ffc5a5db5c5a19d4e73ddd48689c4d10919e5c178e4c6d1cb82cc9cc91dcfa8e3fba67e65e472e27f75340d1a43950df323421c99f8cd772ffe1677d4376bdeab8f953442f5dabc2dfd36531fad308f6b0daa46b9c7b2f10f26b95025abb5a907f681f7407ead3c80fb80fb28822711549f1e3723850235d933c976f299be4f9254bfcb0ee47012c78e6f8ec89088d85ceadd68e31b834a5fe5c870f211628c176d77f77c2501161c600f3dd498b101b27f647740233c0edc2930456caf250d836427628ba22bea14cc3c46091b629bf2a44988e8630e70e1dc708be95b03688097606c8d8f8fb9ae03827c1458f1363cc6478b3f452863db85773c29021b32bc73c25654d8011b1f149429cd036fd7852302371fe9380d81bb81f85e86077a0653c347b7f952b67a1153393b767dc5a5454d91f1370716b802038b1c2bfb4f63feea7e446dc4c8744d3b9a1ac27ebed17fea8d0e1b0b7d41c3f2418aabb16d11fee52319eae446d7804b5a5aeaef765111e82398e8f4f918039d143607d5c0d7fa8b4af64b0e3e9f201b9b231ec75f6a041e7f952def63b48cdc61b5b2c604eda06e88affba1e43449811800535ed776e0870a3fc50fe5406c8b9663933e66e3b76b29b54782368e7f7458636b4ef792f91dc57d3e51f2e06e0cb1b252db85ae68a1dda7dcdff7a4fc389a64b7d971618c37e107d036477e242d84b038b64918e0f73bb770410a4c1330c585272d99d1ba39801fdc1c68fee2948cec68b274092626a5c097d323cba370bbfd8b7fd50b180386dca3c9908e40ece1c0ff00607f7408d8ec7c6f5593444801a5b2444ff737cffa2132138f09796d35928dbf4befff006524eefc8e3915ba277a04f9aa23fd948cbad8d6776ba26b5c00eceb3cff00086caf641cc808c9843c34bde41ddf4bff00d14dfcb812bf19fb0b6335273c81e1dfcd26362230220ee6689c6ac770790111d0bf29b14ed7b43e4608e5e3f537c15362f22348d6c3aab4b59fd0750e7c5f04ff00a29b26098242e058ef4de63241fdc1fe0841fcbb9f2331a4a32baecfc06f6e7f71fca95866499afb207ad6d703fdaf1c281078c44e67341a47147b21738eedbbbbf0d3f3f45331f11b287467870e4822931cf8e40c32376bd9251bed7e08fe0a9b26c30c81201ec6b29b4001fb26bb1ccb05822b70a35d8829ee8f7edd8d21c6cf22ac59478da1d8fe937da5e777d9d6a0414024ca889e0171d8eb1c82924f64a23028b06d70f9f947c87085cd68fefadc7e3ea8b318a37c7992ed34373b8efe0ffb23a0a1040639c3dd1b9feac679279e4523698c87d27077bbd31bb6bbef5498f73a4647b5e4869d81c3820784f898fc7c9943c911cb44577f3fefff0064c8879d06d98ba531b4c67fa6ef807b2246d749962769692053dbf3f5fe50752926663ba10d6db59bf7117fa458468b27f39810e5e3535f2814d3dc03d857d93a423277a51e4e3bcd72d16d498ad95976e143f4fd026e3b1d8f8d44f0d702e3ff00646922f40b761dcd73b8e3fb0a3a0a3d92f6c5cc7d88dd7e4a4dce6ec96b9701c9fa76ff0072a4c9146e89bb85b987fd3e136605d8c69b6eb268a9a0a0ec6b1d2c8410592805a7fca6b90a3cd1ba0d40ba203dadf77d7e5170b6cb0bb6340d94e7b6bb293e90791ee009f6935c9078ff00d14d0e888f6096664919f49c4fbc8f3f0ae6583ff0ee31f2e68e01f27ff4555130c0f91ce229bec23c5f852f44cf6498ef86425b26fd8e0791b6e91442065e3130c0dfd2e63c399f6ae415a389cd9208dc010e00036ab32e239189910485bebb1fba370feea3c1fdc27e899ae9d9be70e60b0db3d811dc1ff44c9681a254b8a5d8be98753848648dc3fb68a9186c6e7460f692cb6be1c3bffb21e4cd26c706168da7751e0103929d8c0c1299dbc36421ff00fef11ffe5108511fe62567203fb127ca6094ba518ee16f613cf83c945cc6b590faac1c8e4a6bdad9b1993c669edaba1dbe52b1912a098092473cd9878af945c67ba58e48c9a7bb907e07c2ad8b2e37b649dfb5ae0edae15f5e0ab0c421cc9242e164f1ff00b2232616192da631ff0031be119af6b9bee68b010b1e56b5e496ed77977928919dc4d8b05108ad9497b5fddbd88450e0e36dfe14763831a5be41b08f1461c7d469a23c2043e23365118e0d1642678b48c2e37f098d48716ee75f82958d17c949bb8aa495b5b64df2a04799036c7848c76e77d10f7583c22475b2bcfca3b0048cfb8fc2707218e7b145888ae54012617348361231c093c7098d900b4ad207b7e5101218e17c29314ad68b7025428c869f9447c94da09588c930bccd258e029cd2776d07b2afc576c6f3dca930fa9b1cf1f2a00921ee0fe071d94981e012482694364bc579460f3b78fdd0013e3797b8168ab524c94f2efa2811c8d6464dd108b1484c60b90197448326e22c9b52232d5098e05f6e472f0da03b2010d24a6abc26c0f0242e70be3840925a01bdc92bd7b38be502052e7386eec49f0990b8ba491b4416f72900a05ce252c8ff0046073ddc17a642e847ccd3bac5edec84de18e71ee5098e323a87944ca218c0d1dd417b04c7973c368d7ca1319ba57879e01448650d1448ef6a3bddfd526fda4a0320847bcd5d04389bef739b42fe511ae009af28137b646069e07745001c9bbd33639511ed3c0f852e73ea3c6dba0a33bf5100a8880cb9a5ddbee98c3bdc4ff09cf698ec91fab8a487fa4cbf8448479594f25026a77b403ca965c1c6c73f4406b0ba427c0506431e360a5e8d94491d884b20df213e12876c69e2fc281d82681b894c95c1c2915bb76d79286e6520140f70650f086497b8d58088f1f09a38450c2ba9a1344801b0394e22c91491ccda010a0343a325c5c53482d052b3dad26d2eeb6a81fa07f1c223a880d432491c784eee2d41531a096a73082fbe7809b5c72bc1dc70a6871807149036c9b44da08b253680268a9a0a187809a0df1c279ee991725cef8442780f714c3c388466d3775f84223dca111e31d8b4bb4359f54f23692df84c1cdda9a0827c64725201edba447592015e68f69434023cb183e105b11bf0a5117c26eca251d1017e5c11cd209c617d94e0c045af300b368688577e5ec90523b1c34d72a73da2c901336871eca682888f849ecbcc89cde2d4b7b07848d6f36a244223e379faa610f1fa9a429af1cdaf166e60b1e51d01be8816e1e178b89fed534c4d03b260659eca684d91688f94a2d4a7442ad358c1f088e4722928525f08a5e6c203795344d914923b2f1eca53a36df02d37d203c20123571749a78e54d3136bb21fa0dae420423d1217bd33e54a10803b256c5bafc528421b984a56464770a6b614be97154a07642f4acf09df9735dd4bf4bc8094c44714a0bb20fa27b27371ed4b30b814b1c649aa5360d915b8c7cf65e306df014ef40d27b31b8e45a04d90a282bc238887c2931c148a21e3b284d90e38ecf64ff4487f6e14b8a0b770a4880510421b07220b60dcedade6d1463ed7160e7ea14c18ed8df6d34293e38ae576d1c21b2722118ea87946871c583b79456447f3147b29514637f651b03911ce2975df84ec766e7702a94f78205b07245d26066d697d006e92ecaf91e782f81a1c0d35dc273585b396bd8eb0d047eea40877402cfb81b46ce91b9196c9a30583d3634b7ea001ff00648c0e40a0687c9eb570df1fea8b8f01617170203b952cc6d73c06b434559012c81e71a407f535c037ec815f222085cc900ffa2f07f95221c57085dbc90f8a811f3f54ae68708da1c41dc0907c294d8247341e4974365c7c96917fe968a176479e47c6d89ad25db9c24b03b782a4bdad9a73342360baff004e579db76978aa6f2d169fb1cf8d81942c0e3eb6a315925a6076764bcd98fd21157d146f4e6fcb35b47690d20fc8b3ff00a25888db29165db3755feaf9565b07e4ce1bbdb263dfbfe770040ff43fca84644cea984199102e11bbd091afeff00ffb23b4fa8d8a4e2bd3ab02b9ee0ffa28acc670c513ba47b9923c3656ff0094f61fe94ac70626fa3344e3ef89d5b7e9d8ff00a2596c5078117ab16546f76e06dafaf20f94cd1a1737d4d3247537692c71ff00303d94dd2dac8c39d234ef068969af6fdbf71fc204984ec5d5e62c79700e0e6927fbbbff00a8a409f47b0cc6e1938cf1ee7b6da7e0b79ffb52930e23a685a64246d3448f8ffee933230dd1e73dcc61d8f3baefe7dd5fe94ad3158c7cc607369af686937e68d14a27d14b9ce74b8cf7c05db9ad2396f623ff00bb52318b67c694e393fd17539a4776da2e263804c326ea9da63773d9c3dbff00644c4c39f07589d9b3fa7344e9083dbddffe5309c58cc985b064c33b8ed25bb79162c73ff70bdf97feb96badad9c92eda7f493e47ee8f8ce3a86016be3739ec706d93dcf3ffaff00a273438c592dab7e3802bc87035fec0281083d4c890ced02db18611fee8670e27be4865351c8cdcd7917b1e3b0fdeca769c431eeb2776ea70fa3b807fd55940e30be4c39a16bda597647717fef6a0da29f20e4fa164ee31516d0fd23e14b858d7cd230b482f1eab7f8bff64fc78e46c391ea3797bc802fea546dcf26190170f45fee3ff97c7fe8a209206319a363dc4891960803b84af2ec8d3dadb20c6e2faaeed3c257cd23321ed17b5c7734fcb7ff00b29ce63239211212d64836120a621ec261f4e46c86cd50ff00e608d8afbcc77a80d46db1f5bffd2bfd5446c9247913c728171124907e54e2c8dd1c6e8ddefdd4dfbfc1fe5144058726e64f03819771f6b88e76dfff0063f642c1c67343e265170689231544b3effc2939130c431c9e9ed3bf91dbf6fe6d183063653e5e4b62adbcf707b8fd93a158faf5a2f4c02390a4430b8db77590287ec1798c6340a23693baefbda7b22742db7385d9ff007b4e18b3d1c2e78dcf758ae6979e4bde03780ebae11cb809b6b4101de3e130b07a8e3755fa79eca04f421b8f90256126399a1927dd118e96298b8301e6a9c579ec6371cfc3b827e0fca56bdcf23d414f68a2141d7a1c03659dd1bbb1e5c3c1409a06e364b72632434fb5cdf83f2549c661dae2f6fbddc820a47b04d04ddf73ddb0ff00ea810991b1a65738825a5b43e8501e5d0de3914647dd570efa2589ef7c6c02f73053a8f744998d740f2edc646d39a7e9ff00a8e7f94e8848c890c510a24d000388ee2919cc2c80303dc58e37eee793f0830ccccd83f55f91f5521b28f23da39af8508992620c7636c79e081ca8d88efcacd3b24e6390edfb1296067ab13e50f26370e3e88b044d9c863bfb802ffa1083430c8f0dbeb105a3691cdf945819bc7a83dbb0f6f09cc9009bd277f684b1491c52989e68bbb281448739af63e5dbf74f6cacf4d8d61e4f751f19c592490ca46c70e0fc2761c40b5ec3fadbfa4fca81d92402eb639b4e02c14489c08da0f23b90a34394e918e2e14f8cd11f21198e607ee60a69ee8a09f128b5e2481c2407848db71eea1a4734fca4f738903b277e9f3695ae02d4088c6502bdc8ecbd7dca5634b8a2883e3b69369c3baf6e09375a200cc03b7945adb6506369a253e8baf950848046c06bba4bb3c8e132ea3abec9f13a872a01a253086537bfd53c4c5adf4c1ffdd456c81fca733dce167b28564b8e53bff4d053239377d9426bc386d1e3ca2c0edc4fc0081096260e77a75c0ee8beb97be80a68eca3424592539b20b28009cc36e03e51859750e694389c7d4b1fa4052a17eeb70ec529623db5c65048ec88e6fbb7148d7538a47cbc57d540e857c864218077437bcccedaee5ad4ad203b72631cdf50fd5100f8c88e42eedb470a3c97236cb93e525ded081249b46d505d1e6b4b471c93d923eac32b81c94e6ba9a094165bdc4fc9a50019a3871e3817dd4604be4f94599e36d0bbec822dad79bda435420ddc5c6eb6a135a1fbc83ca7ba53b5c0727c21c436b4df05400d79b0371b2123cff48f16e3d8271aba4391e010294211b696927b129d1b3634fc148fe5c484f80b8037d9408373435c6bb21705d47b2348773c80805b4e2a106901a4d721309e2ef84f22814303d94a0e86975a1b1c1c4a791c14d8db565419046b85142dd6483e12c839a05235b4a1073bf414c1fa7eabce0485e02984a841187b829c7b52637b03f29fe42289a13c265d2571e530f26930c3c9b6a613ee440db6142abe5409e3c77480100d273807102d398d0dedca84197bb713e578b7869f90bc47bb84feee03e1420d71a7127b26353a4fd7498ff6b82843ce1c15e8cf14949b04a56d06da8404fa06bcaf32acdaf7724a6ed376a102570690eaf809f1f63691a395083003c84a1a022160052520404f09197653de1798de54441ae6f3caf3451a4f2de52eca368908f2d83d92b5a36da2b99b9231b6d211068096926884a23a46d86d78b54203db6bdb7c225534948d3bbc2240463dbdb9484123b293b2c24f4cfc2426c035b7e12fa67e11983e89c7ec81360fd315d92363a3d9480dddd93db150ba401b22b99495915a921967b27b595e1426c8a60363e13fd1dce15e149f4cfc27b233de90d8bc88c71ec22478962d486c64fd13c35cdb01420038bb45af322f952666bbd36fd524511f2a206c8fe851e0708ed83dbd917d2224701fa514b7da38e0f6509b03042288ae6eff00644744ddc47ca23627c6d278a4f6b43c007cd20004c8438f624764ac849de1a28a9d0467686570d279448618db290e3dd28bb2a842ea63c852e18c1791f011990ed639dddbfec9f891d135e477500d8b100c96273da76f22e909b091210e1603bfeead666472c5146c04d379e3cda1c388e74cd89dfa8707ee944d8d8f1a4918f25a1ad02c1449e18dce63da002e68b1f04294d7fa6d3111e08b5e7e3163f92375b437e288080ad808e23eb902c92db77d13a36fa990e04d31a3ff00c7fdd1a295ac84babfa85b47f94d716b9ae8da2ac807ff00bfdd289b19344c9b15ce029f4435c149c6c82cc5877b43844fa7fd9dc1ff0074fc2883e56e35ed6075591df945d2f1c6447971b88da6032075791ee1fec99136026c1dd7b2835a7691febff75270c31990cdcc04336823e52c530659e09da0bac783dbfd8a3319b230f0e6ed91a4b4fc104a8121b0e3cf2c9340ca6ef918076f69ba2a418e4943f21f644b1b5c6bff0029a3ff00fd04e180d606c6d1521fd5f039536093d47cb8c06d1102e67d77771fff0068508c88c617c936302003196177d89a3f7aa46c190c596c9cb5bba5696c80ff009be511b13b19e5ee8ffe7012338ee1cd168de8b76c85d4d7c2f6b88aefced3fee95b1448d8c66a31cad2c2c76e85c078f23feffc2f4f188b2435d45c406177dbb1ff00b7ec8ae60c79777a6031cd0e757dcd23c908f58bdc2e3ab27e5840bafb1b4a88286131920071616d8bec1003d90e537d777b1bb4d817e51e38df14731bb700e611f3f07f9a4fdad7c910701b6402ec28c43ce859f9ec9c799ad21d29313c1edbbff7b28b91bd8e8e6747b9acf6b8ff00e571aff4349b96c7e36b91e24d422ca8416bfe08e2ff006ab56d991099b1ef3b5aefd400ec0ffef45143b2b31f1a4f51e0900c2f00edf2d3e53463628ea49a290b9bf9860a7df1da89ff004b52db9022ca12105b1c9503b8f8edfee5072a46c19513e56177a2e73cd0e48f8fe00445d10f19be866e33a614d25d03cf82472d3fc80ac678a47b3d56b47a91b8f7eee02adbff00741ce8d8d05f5bf1dc036ffca4b8107f95618f90d9a37ed04c91bb6b8d76701c1fdc7fb2844417614534a2a5f49ce3b811d8da23e30d65c8d1b5fec3f63c03fb146cb64445b186ab757c7c8ffbfee8d2ff00e2e0dc00f60dae1f1e14d10811c5be263c1fea476ca3e3e9fe812654427c56b9836bab736fb31c0a3b1c3d6bec5c06ee3fb8763fc7fb23ba312472454d6fa8d21bf17e532414882ef498d8257b06c27d291a7b9f165120c42d63db60b1840a1e0df07fd579d8ceca832711f200e05a03abfb8720ff000149c62676805c1af23d391a3c387ff94744d039f665447790e6476de7b83ffe6d0f01c7274cd8e683938efdce6df2e68e08fe149c17318d92099cd1d9aee3922fbfec6d26339907e5f29b5b88a2e03b9ffdd1035d0c91a6090c7570bc07c6ef969eff00c1ff00752fd40f0d04805aedae41caddeac78fb86c702f61aec4f768fbd7fa2930169883dc36d3769e3c7ca3b2241890447bb92de094e95bebc2e731a06de693201b1af89dc907ba362c83788ddfdc384e41b100e85b1386df57b8faa06e77ab244e353444127fcc2e918c9f979883c6d3f1d8a5c988978c96510451fb7cff000a0c83120bc036370147e02f3656b266b6b81c1e3ba747b64c561fee8dd4efa83dbfd8a664b5b17a72b090dfeee140a1d1c8713224279638037f72ac3d26cb1063858ee5a3b90ab243ea4459234ed7ff0077fa05631cce6c71bec0781b49ff00b2286447c08862c9f961b4b24713192791f4561145ea0bb163bd7ca8b2630c99c3a3786b81ff00e9777014bd2b2ce4452fa8cd9335db1cdaef5c0288ba0d8f1c50bdb1b470eee9db463e66edc0090dd135f45ef5236ff58d8ae3b7643d43124cc11ba07fa7931bb7c6e77e923ffbe14084ca88326f55bc8939b436b06530c86bda7da54d91ad7b446f706b88e0287035ec6ba1aa6ddfeea043c05ae1b5f41d549d28746c2e69a7307f2131be94a39b0f6f07eea5db03436417628fd900a00f958f8a29da00bf61faa3e13bd725ae6edaf1f2ab31c9c67cd88f1b9ae3ba3564d9016364678e08537a18f8aa87a65359d979791349e3d92af2f2841a4f2115bdc2f2f2282843fad39abcbc89037f62733c2f2f280164441fa17979401e8bba333b95e5e50464987b14783f43979790004613bc8fa2f45ff30af2f25212e2ff009327d9498f86b7ecbcbc80d11413eee547909beebcbca0c1acfb7ecbd17ea5e5e44079de0a8cfe5e17979420c94d35df643c3712793e179794107bff00ee8737fca2bcbca001b7b85e907b42f2f28412200d92a23892f70257979420d8c0dc53dbfa5cbcbca048f2774c778fbaf2f2243c3f4a11fd45797900a047caf31797902c430fea29ccfd25797906142bfb04c3fa0af2f250821fdbf747f217979588560fe530775e5e5622051fa50dbfa4af2f22c640d9fad399e579792a0b15bff33f64dbe7f75e5e5008f4bfa93323f52f2f2813c3bfec9cefd2bcbca1063393ca700373979792906f929cd5e5e44835ddd2b7f4b57979120c72f33b2f2f28882ff7271ec5797912031e139bdd7979120be535fdd79790033de123179790020cd4abcbc908347744a1b7b2f2f2028918a251c2f2f28414774b417979400560149ebcbca10f0fd41380f715e5e5083dc3f4a2ed1bfb785e5e4ac562c2d040e3ca34ed0208287877fd97979440040ee8df7cf08b8e2fbaf2f2621618e3897f64277eb5e5e557d88c3c6d1e9660ae013fee878fff0031bf65e5e44564bc72464d7fe60a4bb8d4091dfd5ffb15e5e418bf42c807a24f9dc7fdd4e91a1d8d0970b21bff0072bcbc8214aa909d909f345486b47aac35cef0bcbca08898cf69639bc1f51fcfff00bc546d1a57b6791ad7101cd7348f91457979144448900dff0078c829f19bc5a3dac7fb2f2f20bd909f89fd4cb983fdc0c2f26fe50f0c910e2befdce69b3f347ff75e5e4c15e89f98f77e4607dfb9991b1a7e05f64ed44012170144b7baf2f2adfb110e8497c6cddcdc6f07fd14a7b88c0d3abfb9ae69fa8b2bcbc81621b96e2d818e068968b3fb84fef8ec2793cffb85e5e4bf655ff712f5b68741812385b9ac9803f005a3365796b6dc4d937fb2f2f276332367fb717288e0b65047d3b22663899b1df7ee25967ee02f2f22880a517a4658f0d9c003e06e46d31c46bb93183ec763c6e23e4f3caf2f222bf616427f34d6f87d970f94982486d0eceeff0055e5e43ec61d92d00c040a25d47ebdd3b2fd905378a22979793108b1c8e77e59e4dba488179ff372025c4277ccebe77037fbaf2f261fe8579fff00cd403e64e7f953b05a0e9f96080446e3b38fd3caf2f245ec5437249ff06864fef648034fc729cd27f31337c5914bcbc9c641f149762b1c7926ecfee87944b276969a21dc2f2f27092271bb713c92db29627121ad2782da23e8bcbca1095180d7b9a050d9dbf74d94dc5383da8af2f28443f140934d697fbbda3ba261924869e47a64d7d6d797900a193b8b3dcd344d1255842489da477268fd42f2f2742b2464001b92df04378fe52e348f2f602e34035797941a21753f6b0387041ee871b8faa395e5e500fd9ec81b44e470691227176334b8d95e5e407446d438971c8efb94c770e734703834bcbca119ffd9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tagspreelaboraciones_ingredients`
--

CREATE TABLE `tagspreelaboraciones_ingredients` (
  `id` int(11) NOT NULL,
  `preelaboracion_id` int(11) DEFAULT NULL,
  `ingrediente` varchar(100) DEFAULT NULL,
  `cantidad` float DEFAULT NULL,
  `unidad` varchar(50) DEFAULT NULL,
  `alergeno` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tagspreelaboraciones_ingredients`
--

INSERT INTO `tagspreelaboraciones_ingredients` (`id`, `preelaboracion_id`, `ingrediente`, `cantidad`, `unidad`, `alergeno`) VALUES
(1, 37, NULL, NULL, NULL, NULL),
(2, 37, NULL, NULL, NULL, NULL),
(3, 39, 'azucar', 2, 'cup', NULL),
(4, 39, 'sal', 2, 'cup', NULL),
(5, 39, 'sal', 2, 'cup', NULL),
(6, 40, 'azucar', 5, 'cup', NULL),
(7, 40, 'sal', 5, 'cup', NULL),
(8, 40, 'sal', 5, 'cup', NULL),
(9, 47, 'sal', 2, 'cup', 'Crustáceos'),
(10, 47, 'sal', 2, 'cup', 'Crustáceos'),
(11, 47, 'sal', 2, 'cup', 'Crustáceos'),
(12, 48, 'azucar', 2, 'cup', 'Huevos'),
(13, 49, 'azucar', 2, 'cup', 'Huevos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traduccionesingredientes`
--

CREATE TABLE `traduccionesingredientes` (
  `id` int(11) NOT NULL,
  `nombre_ingles` varchar(255) DEFAULT NULL,
  `nombre_espanol` varchar(255) DEFAULT NULL,
  `nombre_frances` varchar(255) DEFAULT NULL,
  `nombre_aleman` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unit`
--

CREATE TABLE `unit` (
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `unit`
--

INSERT INTO `unit` (`unit`) VALUES
('cup'),
('gram'),
('litre'),
('millilitre'),
('none'),
('pound'),
('tablespoon'),
('teaspoon'),
('units');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `grupo_id`, `name`, `phone`, `image`, `surname`, `address`, `city`, `cp`, `country`, `province`) VALUES
(48, 'gonzalezbellver@gmail.com', '$2y$10$7PqVf63UgMFWSBs8dorfOOR5XNi7RloUiTm0/6OZkplGqaA/aLnje', 1, 'raul', '606176815', NULL, 'gonzalez', 'Campaners', 'Valencia', '4550', 'España', 'Valencia'),
(50, 'cookcode@hotmail.com', '$2y$10$Nl3EOyLU4SJuWFZ.ae3mdOgS44tc39OgvoTJhhBjWam2Dvvcfrlfe', 64, 'CookCode', '963825284', NULL, 'System', 'dfg', 'Valencia', '46014', 'España', 'Valencia'),
(51, 'user1@gmail.com', '$2y$10$VrymxKin3QwWacUnJSFr7uU1Avk1L214RNmektYFgvb18Go3PXnw6', 64, 'User1', '652111111', NULL, 'Surname1', 'sinnombre', 'Madrid', '42030', 'Spain', 'Madrid'),
(52, 'user2@gmail.com', '$2y$10$CKJ407QtQFGUPjIu/vlqSOJD231gsWCJue8pkj/f/VH9UZmASm8c6', 64, 'User2', '633222222', NULL, 'Surname2', 'sinnombre', 'Valencia', '41235', 'Spain', 'Valencia');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alergenos`
--
ALTER TABLE `alergenos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `almacenelaboraciones`
--
ALTER TABLE `almacenelaboraciones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_receta` (`receta_id`);

--
-- Indices de la tabla `almaceningredientes`
--
ALTER TABLE `almaceningredientes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_ingrediente_id` (`ingrediente_id`);

--
-- Indices de la tabla `autoconsumo`
--
ALTER TABLE `autoconsumo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `elaboraciones`
--
ALTER TABLE `elaboraciones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_id_receta` (`receta`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grupos_permisos`
--
ALTER TABLE `grupos_permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupo_id` (`grupo_id`),
  ADD KEY `permiso_id` (`permiso_id`);

--
-- Indices de la tabla `ingredientesalergenos`
--
ALTER TABLE `ingredientesalergenos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ingrediente` (`id_ingrediente`),
  ADD KEY `id_alergeno` (`id_alergeno`);

--
-- Indices de la tabla `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit` (`unit`);

--
-- Indices de la tabla `pedidos_ecommerce`
--
ALTER TABLE `pedidos_ecommerce`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `platos_ingrediente`
--
ALTER TABLE `platos_ingrediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_plato` (`id_plato`);

--
-- Indices de la tabla `platos_preelaborados`
--
ALTER TABLE `platos_preelaborados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_plato` (`id_plato`);

--
-- Indices de la tabla `precios_producto`
--
ALTER TABLE `precios_producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos_pedido`
--
ALTER TABLE `productos_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receta` (`receta`),
  ADD KEY `produce` (`produce`);

--
-- Indices de la tabla `receta_elaborado`
--
ALTER TABLE `receta_elaborado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_receta_elaborado_receta` (`receta`),
  ADD KEY `fk_receta_elaborado_elaborado` (`elaborado`);

--
-- Indices de la tabla `receta_ingrediente`
--
ALTER TABLE `receta_ingrediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_id_tipo_cantidad` (`tipo_cantidad`),
  ADD KEY `FK_id_ingredients` (`ingrediente`),
  ADD KEY `receta` (`receta`),
  ADD KEY `FK_id_elaborado` (`elaborado`);

--
-- Indices de la tabla `recuperacion_password`
--
ALTER TABLE `recuperacion_password`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `stock_elab_kitchen`
--
ALTER TABLE `stock_elab_kitchen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- Indices de la tabla `stock_ing_kitchen`
--
ALTER TABLE `stock_ing_kitchen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ingredient_id` (`ingredient_id`);

--
-- Indices de la tabla `stock_lotes_elab`
--
ALTER TABLE `stock_lotes_elab`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receta_id` (`receta_id`);

--
-- Indices de la tabla `stock_lotes_ing`
--
ALTER TABLE `stock_lotes_ing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingrediente_id` (`ingrediente_id`);

--
-- Indices de la tabla `tagscreados`
--
ALTER TABLE `tagscreados`
  ADD PRIMARY KEY (`IDTag`);

--
-- Indices de la tabla `tagselaboraciones`
--
ALTER TABLE `tagselaboraciones`
  ADD PRIMARY KEY (`IDTag`),
  ADD KEY `fk_receta_tag` (`receta_id`);

--
-- Indices de la tabla `tagselaboraciones_ingredients`
--
ALTER TABLE `tagselaboraciones_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tag_elaboracion_id` (`tag_elaboracion_id`);

--
-- Indices de la tabla `tagsingredientes`
--
ALTER TABLE `tagsingredientes`
  ADD PRIMARY KEY (`IDTag`),
  ADD KEY `fk_ingrediente_id_tags` (`ingrediente_id`);

--
-- Indices de la tabla `tagspreelaboraciones`
--
ALTER TABLE `tagspreelaboraciones`
  ADD PRIMARY KEY (`IDTag`);

--
-- Indices de la tabla `tagspreelaboraciones_ingredients`
--
ALTER TABLE `tagspreelaboraciones_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `preelaboracion_id` (`preelaboracion_id`);

--
-- Indices de la tabla `traduccionesingredientes`
--
ALTER TABLE `traduccionesingredientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unit`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grupo_id` (`grupo_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alergenos`
--
ALTER TABLE `alergenos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `almacenelaboraciones`
--
ALTER TABLE `almacenelaboraciones`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;

--
-- AUTO_INCREMENT de la tabla `almaceningredientes`
--
ALTER TABLE `almaceningredientes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;

--
-- AUTO_INCREMENT de la tabla `autoconsumo`
--
ALTER TABLE `autoconsumo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT de la tabla `elaboraciones`
--
ALTER TABLE `elaboraciones`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `grupos_permisos`
--
ALTER TABLE `grupos_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `ingredientesalergenos`
--
ALTER TABLE `ingredientesalergenos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT de la tabla `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT de la tabla `node`
--
ALTER TABLE `node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `pedidos_ecommerce`
--
ALTER TABLE `pedidos_ecommerce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `pedido_producto`
--
ALTER TABLE `pedido_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `platos_ingrediente`
--
ALTER TABLE `platos_ingrediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `platos_preelaborados`
--
ALTER TABLE `platos_preelaborados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `precios_producto`
--
ALTER TABLE `precios_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `productos_pedido`
--
ALTER TABLE `productos_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT de la tabla `receta_elaborado`
--
ALTER TABLE `receta_elaborado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `receta_ingrediente`
--
ALTER TABLE `receta_ingrediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT de la tabla `recuperacion_password`
--
ALTER TABLE `recuperacion_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `stock_elab_kitchen`
--
ALTER TABLE `stock_elab_kitchen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `stock_ing_kitchen`
--
ALTER TABLE `stock_ing_kitchen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=991;

--
-- AUTO_INCREMENT de la tabla `stock_lotes_elab`
--
ALTER TABLE `stock_lotes_elab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `stock_lotes_ing`
--
ALTER TABLE `stock_lotes_ing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `tagscreados`
--
ALTER TABLE `tagscreados`
  MODIFY `IDTag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tagselaboraciones`
--
ALTER TABLE `tagselaboraciones`
  MODIFY `IDTag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT de la tabla `tagselaboraciones_ingredients`
--
ALTER TABLE `tagselaboraciones_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT de la tabla `tagsingredientes`
--
ALTER TABLE `tagsingredientes`
  MODIFY `IDTag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT de la tabla `tagspreelaboraciones`
--
ALTER TABLE `tagspreelaboraciones`
  MODIFY `IDTag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `tagspreelaboraciones_ingredients`
--
ALTER TABLE `tagspreelaboraciones_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `traduccionesingredientes`
--
ALTER TABLE `traduccionesingredientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `almacenelaboraciones`
--
ALTER TABLE `almacenelaboraciones`
  ADD CONSTRAINT `fk_receta` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`);

--
-- Filtros para la tabla `almaceningredientes`
--
ALTER TABLE `almaceningredientes`
  ADD CONSTRAINT `fk_ingrediente_id` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredients` (`ID`);

--
-- Filtros para la tabla `ingredientesalergenos`
--
ALTER TABLE `ingredientesalergenos`
  ADD CONSTRAINT `ingredientesalergenos_ibfk_1` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingredients` (`ID`),
  ADD CONSTRAINT `ingredientesalergenos_ibfk_2` FOREIGN KEY (`id_alergeno`) REFERENCES `alergenos` (`id`);

--
-- Filtros para la tabla `node`
--
ALTER TABLE `node`
  ADD CONSTRAINT `node_ibfk_1` FOREIGN KEY (`unit`) REFERENCES `unit` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`produce`) REFERENCES `elaboraciones` (`ID`);

--
-- Filtros para la tabla `receta_elaborado`
--
ALTER TABLE `receta_elaborado`
  ADD CONSTRAINT `fk_receta_elaborado_elaborado` FOREIGN KEY (`elaborado`) REFERENCES `recetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_receta_elaborado_receta` FOREIGN KEY (`receta`) REFERENCES `recetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `receta_ingrediente`
--
ALTER TABLE `receta_ingrediente`
  ADD CONSTRAINT `receta_ingrediente_ibfk_1` FOREIGN KEY (`ingrediente`) REFERENCES `ingredients` (`ID`),
  ADD CONSTRAINT `receta_ingrediente_ibfk_2` FOREIGN KEY (`receta`) REFERENCES `recetas` (`id`),
  ADD CONSTRAINT `receta_ingrediente_ibfk_3` FOREIGN KEY (`elaborado`) REFERENCES `elaboraciones` (`ID`);

--
-- Filtros para la tabla `stock_elab_kitchen`
--
ALTER TABLE `stock_elab_kitchen`
  ADD CONSTRAINT `stock_elab_kitchen_ibfk_1` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `stock_ing_kitchen`
--
ALTER TABLE `stock_ing_kitchen`
  ADD CONSTRAINT `fk_ingredient_id` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ID`);

--
-- Filtros para la tabla `stock_lotes_elab`
--
ALTER TABLE `stock_lotes_elab`
  ADD CONSTRAINT `stock_lotes_elab_ibfk_1` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`);

--
-- Filtros para la tabla `stock_lotes_ing`
--
ALTER TABLE `stock_lotes_ing`
  ADD CONSTRAINT `stock_lotes_ing_ibfk_1` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredients` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tagselaboraciones`
--
ALTER TABLE `tagselaboraciones`
  ADD CONSTRAINT `fk_receta_tag` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`);

--
-- Filtros para la tabla `tagsingredientes`
--
ALTER TABLE `tagsingredientes`
  ADD CONSTRAINT `fk_ingrediente_id_tags` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredients` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
