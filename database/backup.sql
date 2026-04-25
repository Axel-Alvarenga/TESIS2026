-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2026 a las 15:54:50
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
-- Base de datos: `voces_del_sur`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `analisis_texto`
--

CREATE TABLE `analisis_texto` (
  `id` int(11) NOT NULL,
  `respuesta_id` int(11) DEFAULT NULL,
  `texto_original` text DEFAULT NULL,
  `sentimiento` varchar(20) DEFAULT NULL,
  `palabras_clave` text DEFAULT NULL,
  `tema_principal` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `analisis_texto`
--

INSERT INTO `analisis_texto` (`id`, `respuesta_id`, `texto_original`, `sentimiento`, `palabras_clave`, `tema_principal`) VALUES
(1, 1, 'ay', 'neutral', '', 'general'),
(2, 1, 'ay', 'neutral', '', 'general'),
(3, 2, 'Me gusta', 'positivo', '', 'general'),
(4, 1, 'ay', 'neutral', '', 'general'),
(5, 2, 'Me gusta', 'positivo', '', 'general'),
(6, 3, 'ayuda', 'positivo', '', 'general'),
(7, 4, 'as', 'neutral', '', 'general'),
(8, 5, 'asda', 'neutral', '', 'general'),
(9, 6, 'fgg', 'neutral', '', 'general'),
(10, 7, 'Me permitio expresarme', 'neutral', '', 'general'),
(11, 8, 'Me permitio expresarme', 'neutral', '', 'general'),
(12, 9, 'asda', 'neutral', '', 'general'),
(13, 10, 'me permitio decir todo', 'neutral', '', 'general'),
(14, 1, 'ay', 'neutral', '', 'general'),
(15, 2, 'Me gusta', 'positivo', '', 'general'),
(16, 3, 'ayuda', 'positivo', '', 'general'),
(17, 4, 'as', 'neutral', '', 'general'),
(18, 5, 'asda', 'neutral', '', 'general'),
(19, 6, 'fgg', 'neutral', '', 'general'),
(20, 7, 'Me permitio expresarme', 'neutral', '', 'general'),
(21, 8, 'Me permitio expresarme', 'neutral', '', 'general'),
(22, 9, 'asda', 'neutral', '', 'general'),
(23, 10, 'me permitio decir todo', 'neutral', '', 'general'),
(24, 1, 'ay', 'neutral', '', 'general'),
(25, 2, 'Me gusta', 'positivo', '', 'general'),
(26, 3, 'ayuda', 'positivo', '', 'general'),
(27, 4, 'as', 'neutral', '', 'general'),
(28, 5, 'asda', 'neutral', '', 'general'),
(29, 6, 'fgg', 'neutral', '', 'general'),
(30, 7, 'Me permitio expresarme', 'neutral', '', 'general'),
(31, 8, 'Me permitio expresarme', 'neutral', '', 'general'),
(32, 9, 'asda', 'neutral', '', 'general'),
(33, 10, 'me permitio decir todo', 'neutral', '', 'general'),
(34, 11, 'asdf', 'neutral', '', 'general'),
(35, 12, 'hola', 'neutral', '', 'general'),
(36, 13, 'sd', 'neutral', '', 'general'),
(37, 14, 'hola', 'neutral', '', 'general'),
(38, 15, 'Hola', 'neutral', '', 'general'),
(39, 16, 'hola', 'neutral', '', 'general'),
(40, 17, 'Hola', 'neutral', '', 'general'),
(41, 1, 'ay', 'neutral', '', 'general'),
(42, 2, 'Me gusta', 'positivo', '', 'general'),
(43, 3, 'ayuda', 'positivo', '', 'general'),
(44, 4, 'as', 'neutral', '', 'general'),
(45, 5, 'asda', 'neutral', '', 'general'),
(46, 6, 'fgg', 'neutral', '', 'general'),
(47, 7, 'Me permitio expresarme', 'neutral', '', 'general'),
(48, 8, 'Me permitio expresarme', 'neutral', '', 'general'),
(49, 9, 'asda', 'neutral', '', 'general'),
(50, 10, 'me permitio decir todo', 'neutral', '', 'general'),
(51, 11, 'asdf', 'neutral', '', 'general'),
(52, 12, 'hola', 'neutral', '', 'general'),
(53, 13, 'sd', 'neutral', '', 'general'),
(54, 14, 'hola', 'neutral', '', 'general'),
(55, 15, 'Hola', 'neutral', '', 'general'),
(56, 16, 'hola', 'neutral', '', 'general'),
(57, 17, 'Hola', 'neutral', '', 'general'),
(58, 18, 'Me permitio decir todo', 'neutral', '', 'general'),
(59, 19, 'Me permitió decir todo', 'neutral', '', 'general');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL,
  `p1_anio` int(11) DEFAULT NULL,
  `p2_parroquia` varchar(100) DEFAULT NULL,
  `p3_pertenencia` char(1) DEFAULT NULL,
  `p4_atraccion` char(1) DEFAULT NULL,
  `p5_espiritualidad` char(1) DEFAULT NULL,
  `p6_familia` char(1) DEFAULT NULL,
  `p7_proyecto` char(1) DEFAULT NULL,
  `p8_vocacion` char(1) DEFAULT NULL,
  `p9_critica` text DEFAULT NULL,
  `p10_esperanza` int(11) DEFAULT NULL,
  `campo_libre` text DEFAULT NULL,
  `permiso_padres` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id`, `fecha`, `ip`, `p1_anio`, `p2_parroquia`, `p3_pertenencia`, `p4_atraccion`, `p5_espiritualidad`, `p6_familia`, `p7_proyecto`, `p8_vocacion`, `p9_critica`, `p10_esperanza`, `campo_libre`, `permiso_padres`) VALUES
(1, '2026-04-08 18:26:16', '::1', 1996, 'encarnacion_centro', 'B', 'E', 'B', 'C', 'B', 'C', 'C,E', 2, 'ay', NULL),
(2, '2026-04-08 18:34:48', '::1', 1995, 'trinidad', 'E', 'C', 'B', 'C', 'A', 'A', 'C', 2, 'Me gusta', NULL),
(3, '2026-04-18 16:09:16', '::1', 1995, 'santuario', 'A', 'C', 'D', 'E', 'C', 'D', 'F', 3, 'ayuda', NULL),
(4, '2026-04-18 16:12:31', '::1', 1996, 'trinidad', 'D', 'A', 'A', 'D', 'D', 'D', 'D', 2, 'as', NULL),
(5, '2026-04-18 16:24:34', '::1', 1993, 'fram', 'C', 'B', 'D', 'C', 'D', 'D', 'C', 2, 'asda', NULL),
(6, '2026-04-18 17:01:01', '::1', 1993, 'santuario', 'E', 'D', 'C', 'B', 'C', 'C', 'F', 2, 'fgg', NULL),
(7, '2026-04-18 17:07:18', '::1', 1996, 'fram', 'A', 'A', 'A', 'A', 'A', 'A', 'F,G', 4, 'Me permitio expresarme', NULL),
(8, '2026-04-18 17:11:14', '::1', 2000, 'catedral', 'A', 'A', 'A', 'A', 'A', 'A', 'G', 5, 'Me permitio expresarme', NULL),
(9, '2026-04-18 17:16:15', '::1', 2000, 'fram', 'B', 'C', 'C', 'B', 'D', 'C', 'E', 5, 'asda', NULL),
(10, '2026-04-18 17:30:45', '::1', 2000, 'catedral', 'A', 'A', 'A', 'A', 'A', 'A', 'G', 5, 'me permitio decir todo', NULL),
(11, '2026-04-20 19:12:51', '::1', 2009, 'Nuestra Señora de la Santísima Encarnación', 'C', 'F', 'C', 'C', 'D', 'B', 'B,E', 4, 'asdf', 'si'),
(12, '2026-04-20 19:16:31', '::1', 2009, 'San Roque González de Santa Cruz', 'B', 'A', 'A', 'A', 'A', 'A', 'A,F', 2, 'hola', 'si'),
(13, '2026-04-20 19:24:16', '::1', 2009, 'Nuestra Señora de la Santísima Encarnación', 'C', 'D', 'B', 'C', 'C', 'B', 'B,D', 4, 'sd', 'si'),
(14, '2026-04-20 19:25:30', '::1', 2009, 'San José Obrero - Cap. Miranda', 'B', 'B', 'A', 'A', 'A', 'A', 'A,D', 5, 'hola', 'si'),
(15, '2026-04-20 20:10:32', '192.168.100.5', 2009, 'San Roque González de Santa Cruz', 'A', 'A', 'A', 'A', 'A', 'A', 'A,E', 5, 'Hola', 'si'),
(16, '2026-04-20 20:13:42', '::1', 2009, 'San Roque González de Santa Cruz', 'A', 'A', 'A', 'A', 'A', 'A', 'A,E', 5, 'hola', 'si'),
(17, '2026-04-20 20:18:44', '192.168.100.5', 2009, 'San Roque González de Santa Cruz', 'A', 'A', 'A', 'A', 'A', 'A', 'A,E', 5, 'Hola', 'si'),
(18, '2026-04-22 14:00:47', '192.168.100.7', 2009, 'San Roque González de Santa Cruz', 'A', 'G', 'A', 'A', 'A', 'A', 'E,G', 5, 'Me permitio decir todo', 'si'),
(19, '2026-04-22 14:10:40', '192.168.100.5', 2009, 'San Roque González de Santa Cruz', 'A', 'A', 'A', 'A', 'A', 'A', 'G', 5, 'Me permitió decir todo', 'si'),
(20, '2026-04-25 10:28:10', '192.168.100.7', 2009, 'San Roque González de Santa Cruz', 'A', 'A', 'A', 'A', 'A', 'A', 'A,D', 5, 'ayuida', 'si'),
(21, '2026-04-25 10:29:30', '192.168.100.5', 2009, 'San Pedro Apóstol - Encarnación', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 3, 'Prueba celu', 'si');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `analisis_texto`
--
ALTER TABLE `analisis_texto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `respuesta_id` (`respuesta_id`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fecha` (`fecha`),
  ADD KEY `idx_parroquia` (`p2_parroquia`),
  ADD KEY `idx_anio` (`p1_anio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `analisis_texto`
--
ALTER TABLE `analisis_texto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `analisis_texto`
--
ALTER TABLE `analisis_texto`
  ADD CONSTRAINT `analisis_texto_ibfk_1` FOREIGN KEY (`respuesta_id`) REFERENCES `respuestas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
