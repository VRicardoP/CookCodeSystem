-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-03-2025 a las 16:00:11
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
-- Base de datos: `empresa_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `codigo_postal` int(11) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  `imagen_tag` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `nombre`, `direccion`, `codigo_postal`, `ciudad`, `pais`, `imagen`, `imagen_tag`) VALUES
(1, 'COOK SYSTEM.S.L', 'Calle Torrent 24', 46002, 'Valencia', 'España', 'https://lh3.googleusercontent.com/pw/AP1GczNoUlfKx2PqfvOCwi5_KbpsPlnvfRBvXTfuDnP8kXAxRu9B1UdY7zKAxwn1Kg7UKpEhMfO2MXdi78qqiwSZp_srLqPNpobkP-4G2mkrWfV2JpPUlIXvBByTq5OatF_baWCjl8M2siy0PvXO2JlRDOjedtpoH0mjbCFLwRLKbV3_G4_nkGGz8dKZp39RnVJejHnj3vIhNC2ms1RuewiMkJPUnh4GA-XFBWcPCX4ZT8-tXEntF9--nKj2rtMBvm6OZub9SH4PvkSQAOr-Jscj10uQLqjl5c6gX94stvgWYXMILvyPJTxwMVKBeiESkIsOUkpXJDKw-VRMurjT0jBmz9m_Qyq9HbAsJqrUtL4oZRmETjZyMKBnHozbjEnTlONIKlRe398PV8PvfydQ6jWEJZsJW7fRv0FLR7WZ8WUqEj5UIeRMcgRYijHge4sxySnTLl1-4AUR6oduCOBvwRCeoBFH-NwsGuNQ8cW_GuKZf-tGdKbFizK6xLJryi2g6oFI2-JCWTAfsGbEmjt_MuNXmicr-iejfUp6CPzKWCw9HxlYzmKG5fg78jsE5du7YKC43PflD7nQbI1_OrLkK2oR1AvLLMFrj77XiByAJVRFYq7T7P86whhtSBM42l2pd68tznX2198LhiyRQk2riALysPwz01ULiqNt6sGWVhCrMGQAHIUJOcoRQrRK_lyeJcX5qbnxrHiDDV0uJETkf3KDgTHYpZ7TecFvdCcxBbyA-CV_4oPG3qkICqsuqK34RGMXLTyICioCUbIrhPV7gwQwqxMoRFlHm-nfZJvtSmvKM8RvJyVc8SGr5oUH9a9R_w0CqCZsLmVvBnq53ZPoeEIzMj6bl7vJHw3ml6pM9XMNy6B49eFswm5eGwaDCuWSYzRaFFcrwBRTqMU03UV5iLrYSQ=w1500-h900-s-no-gm?authuser=0\r\n', 'https://lh3.googleusercontent.com/pw/AP1GczNoUlfKx2PqfvOCwi5_KbpsPlnvfRBvXTfuDnP8kXAxRu9B1UdY7zKAxwn1Kg7UKpEhMfO2MXdi78qqiwSZp_srLqPNpobkP-4G2mkrWfV2JpPUlIXvBByTq5OatF_baWCjl8M2siy0PvXO2JlRDOjedtpoH0mjbCFLwRLKbV3_G4_nkGGz8dKZp39RnVJejHnj3vIhNC2ms1RuewiMkJPUnh4GA-XFBWcPCX4ZT8-tXEntF9--nKj2rtMBvm6OZub9SH4PvkSQAOr-Jscj10uQLqjl5c6gX94stvgWYXMILvyPJTxwMVKBeiESkIsOUkpXJDKw-VRMurjT0jBmz9m_Qyq9HbAsJqrUtL4oZRmETjZyMKBnHozbjEnTlONIKlRe398PV8PvfydQ6jWEJZsJW7fRv0FLR7WZ8WUqEj5UIeRMcgRYijHge4sxySnTLl1-4AUR6oduCOBvwRCeoBFH-NwsGuNQ8cW_GuKZf-tGdKbFizK6xLJryi2g6oFI2-JCWTAfsGbEmjt_MuNXmicr-iejfUp6CPzKWCw9HxlYzmKG5fg78jsE5du7YKC43PflD7nQbI1_OrLkK2oR1AvLLMFrj77XiByAJVRFYq7T7P86whhtSBM42l2pd68tznX2198LhiyRQk2riALysPwz01ULiqNt6sGWVhCrMGQAHIUJOcoRQrRK_lyeJcX5qbnxrHiDDV0uJETkf3KDgTHYpZ7TecFvdCcxBbyA-CV_4oPG3qkICqsuqK34RGMXLTyICioCUbIrhPV7gwQwqxMoRFlHm-nfZJvtSmvKM8RvJyVc8SGr5oUH9a9R_w0CqCZsLmVvBnq53ZPoeEIzMj6bl7vJHw3ml6pM9XMNy6B49eFswm5eGwaDCuWSYzRaFFcrwBRTqMU03UV5iLrYSQ=w1500-h900-s-no-gm?authuser=0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
