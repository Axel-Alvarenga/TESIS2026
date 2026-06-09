-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2026 a las 00:45:27
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `analisis_texto`
--

INSERT INTO `analisis_texto` (`id`, `respuesta_id`, `texto_original`, `sentimiento`, `palabras_clave`, `tema_principal`) VALUES
(1, 1, 'a', 'neutral', '', 'general');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL,
  `p1_anio` varchar(20) DEFAULT NULL,
  `p2_parroquia` varchar(100) DEFAULT NULL,
  `p3_pertenencia` varchar(5) DEFAULT NULL,
  `p4_atraccion` varchar(5) DEFAULT NULL,
  `p5_espiritualidad` varchar(5) DEFAULT NULL,
  `p6_familia` varchar(5) DEFAULT NULL,
  `p7_proyecto` varchar(5) DEFAULT NULL,
  `p8_vocacion` varchar(5) DEFAULT NULL,
  `p9_critica` text DEFAULT NULL,
  `p10_esperanza` int(11) DEFAULT NULL,
  `campo_libre` text DEFAULT NULL,
  `permiso_padres` varchar(5) DEFAULT NULL,
  `comentario_bloque2` text DEFAULT NULL COMMENT 'Comentario bloque II - Vínculos',
  `comentario_bloque3` text DEFAULT NULL COMMENT 'Comentario bloque III - Espiritualidad',
  `comentario_bloque4` text DEFAULT NULL COMMENT 'Comentario bloque IV - Familia',
  `comentario_bloque5` text DEFAULT NULL COMMENT 'Comentario bloque V - Proyecto de vida',
  `comentario_bloque6` text DEFAULT NULL COMMENT 'Comentario bloque VI - Vocación',
  `comentario_bloque7` text DEFAULT NULL COMMENT 'Comentario bloque VII - Crítica',
  `comentario_bloque8` text DEFAULT NULL COMMENT 'Comentario bloque VIII - Esperanza',
  `comentario_bloque9` text DEFAULT NULL COMMENT 'Comentario bloque VIII - Esperanza social',
  `p4b_situacion` varchar(5) DEFAULT NULL COMMENT 'P4b-1: Situación principal (estudio/trabajo/busco/etc)',
  `p4b_area` varchar(5) DEFAULT NULL COMMENT 'P4b-2: Área de interés formativo/laboral',
  `p4b_movilidad` varchar(5) DEFAULT NULL COMMENT 'P4b-3: Disposición a movilidad territorial',
  `comentario_p4b1` text DEFAULT NULL COMMENT 'Comentario P4b-1 - Situación principal',
  `comentario_p4b2` text DEFAULT NULL COMMENT 'Comentario P4b-2 - Área de interés',
  `comentario_p4b3` text DEFAULT NULL COMMENT 'Comentario P4b-3 - Movilidad territorial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id`, `fecha`, `ip`, `p1_anio`, `p2_parroquia`, `p3_pertenencia`, `p4_atraccion`, `p5_espiritualidad`, `p6_familia`, `p7_proyecto`, `p8_vocacion`, `p9_critica`, `p10_esperanza`, `campo_libre`, `permiso_padres`, `comentario_bloque2`, `comentario_bloque3`, `comentario_bloque4`, `comentario_bloque5`, `comentario_bloque6`, `comentario_bloque7`, `comentario_bloque8`, `comentario_bloque9`, `p4b_situacion`, `p4b_area`, `p4b_movilidad`, `comentario_p4b1`, `comentario_p4b2`, `comentario_p4b3`) VALUES
(1, '2026-05-13 23:44:49', '::1', '2010', 'Nuestra Señora de la Santísima Encarnación', 'A', 'D', 'A', 'B', 'B', 'B', 'C', 4, 'a', 'si', 'a', 'a', 'a', 'a', 'a', 'a', 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2026-05-31 01:25:04', '::1', '2009', 'San Roque González de Santa Cruz', 'B', 'C', 'B', 'C', 'C', 'D', 'B,D', 5, 'sdfaf', 'si', 'ad', 'dad', 'dad', 'fa', 'faf', 'faf', 'adad', 'adad', NULL, NULL, NULL, NULL, NULL, NULL),
(3, '2026-05-31 01:48:04', '::1', '2009', 'San Pedro Apóstol - Encarnación', 'D', 'C', 'B', 'C', 'D', 'D', 'D', 5, 'dad', 'si', 'dad', 'dada', 'dad', 'dad', 'dad', 'adad', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(4, '2026-05-31 02:11:22', '::1', '2006', 'San Pedro Apóstol - Encarnación', 'B', 'C', 'C', 'D', 'C', 'C', 'C,E', 5, 'adas', 'no', 'adfa', 'fafas', 'ad', 'ad', 'dada', 'dad', 'das', '', '', '', '', NULL, NULL, NULL),
(5, '2026-05-31 02:38:11', '::1', '2009', 'San Pedro Apóstol - Encarnación', 'B', 'D', 'C', 'C', 'C', 'C', 'D,F', 2, 'sdasf', 'si', 'asdas', '', 'asdas', 'dasd', 'adas', 'asdasd', 'asda', 'sdasd', 'C', 'D', 'C', 'asdad', 'asdas', 'asds'),
(6, '2026-05-31 02:38:50', '::1', '2010', 'San Roque González de Santa Cruz', 'D', 'C', 'C', 'B', 'B', 'B', 'C,E', 2, 'asfasf', 'si', 'dasdasf', 'fsfassdas', 'fsfsaf', 'sfasfsafas', 'safsfs', 'safafsa', 'safasfs', 'sfsf', 'D', 'B', 'B', 'sfasfs', 'saffsas', 'asfasf'),
(7, '2026-05-31 02:39:42', '::1', '2002', 'Inmaculada Concepción de María', 'E', 'E', 'D', 'D', 'D', 'C', 'D,G', 2, 'fsafasf', 'no', 'fsfas', 'sfasf', 'sfasfs', 'sfas', 'sfasfa', 'safasfs', 'sfsf', 'sfafs', 'D', 'E', 'D', 'fasfas', 'sfafs', 'sffs'),
(8, '2026-05-31 02:45:08', '::1', '2010', 'San Pedro Apóstol - Encarnación', 'C', 'E', 'C', 'E', 'D', 'C', 'B,D', 2, 'fasfawf', 'si', 'asffs', 'sfasf', 'fasfafw', 'sfawf', 'fsafasf', 'fsafawf', 'sfafa', 'sfafw', 'B', 'F', 'B', 'fasf', 'fasfas', 'fasfaf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `rol` enum('admin','lector') DEFAULT 'lector',
  `activo` tinyint(4) DEFAULT 1,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password`, `nombre`, `rol`, `activo`, `fecha_creacion`, `ultimo_acceso`) VALUES
(5, 'admin@vocesdelsur.com', '$2y$12$ifb2esB1LQDNjHY2FMYoHe0DHNolhV.cSwnSEp7sUExUy2aiBTEbi', 'Administrador Principal', 'admin', 1, '2026-05-13 21:59:21', '2026-05-15 19:35:59'),
(7, 'lector@vocesdelsur.com', '$2y$12$GRqSYs5QrpRhLfJb5fJrdeX.aVDJXrJnjdspkXy4rwTCr8YAB/OCa', 'Yonathan Gale', 'lector', 1, '2026-05-13 22:03:53', '2026-05-13 22:14:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `analisis_texto`
--
ALTER TABLE `analisis_texto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_respuesta` (`respuesta_id`),
  ADD KEY `idx_sentimiento` (`sentimiento`),
  ADD KEY `idx_tema` (`tema_principal`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fecha` (`fecha`),
  ADD KEY `idx_parroquia` (`p2_parroquia`),
  ADD KEY `idx_anio` (`p1_anio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `analisis_texto`
--
ALTER TABLE `analisis_texto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
