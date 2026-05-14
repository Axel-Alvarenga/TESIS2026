-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-05-2026 a las 21:49:23
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
  `comentario_bloque8` text DEFAULT NULL COMMENT 'Comentario bloque VIII - Esperanza'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id`, `fecha`, `ip`, `p1_anio`, `p2_parroquia`, `p3_pertenencia`, `p4_atraccion`, `p5_espiritualidad`, `p6_familia`, `p7_proyecto`, `p8_vocacion`, `p9_critica`, `p10_esperanza`, `campo_libre`, `permiso_padres`, `comentario_bloque2`, `comentario_bloque3`, `comentario_bloque4`, `comentario_bloque5`, `comentario_bloque6`, `comentario_bloque7`, `comentario_bloque8`) VALUES
(1, '2026-05-13 23:44:49', '::1', '2010', 'Nuestra Señora de la Santísima Encarnación', 'A', 'D', 'A', 'B', 'B', 'B', 'C', 4, 'a', 'si', 'a', 'a', 'a', 'a', 'a', 'a', 'a');

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
(5, 'admin@vocesdelsur.com', '$2y$12$ifb2esB1LQDNjHY2FMYoHe0DHNolhV.cSwnSEp7sUExUy2aiBTEbi', 'Administrador Principal', 'admin', 1, '2026-05-13 21:59:21', '2026-05-13 22:46:11'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
