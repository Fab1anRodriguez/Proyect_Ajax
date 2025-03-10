-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-03-2025 a las 02:33:00
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
-- Base de datos: `juego`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `armas`
--

CREATE TABLE `armas` (
  `ID_arma` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `danio` int(11) NOT NULL,
  `municion_max` int(11) DEFAULT NULL,
  `imagen_armas` varchar(500) NOT NULL,
  `ID_tipo` int(11) DEFAULT NULL,
  `nivel_ar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `armas`
--

INSERT INTO `armas` (`ID_arma`, `nombre`, `danio`, `municion_max`, `imagen_armas`, `ID_tipo`, `nivel_ar`) VALUES
(1, 'Puño', 1, NULL, 'puño.jpg', 1, 1),
(2, 'Pistola', 2, 12, 'pistola.jpg', 2, 1),
(3, 'Ametralladora', 10, 30, 'ametralladora.jpg', 4, 2),
(4, 'francotirador.jpg', 20, 4, 'francotirador.jpg', 3, 2),
(5, 'Usp', 2, 10, 'usp.jpg', 2, 1),
(6, 'Revolver', 2, 6, 'revolver.jpg', 2, 1),
(7, 'Xm8', 11, 30, 'xm8.jpg', 4, 2),
(8, 'Mp 40', 10, 30, 'mp40.jpg', 4, 2),
(11, 'SVD', 20, 6, 'svd.jpg', 3, 2),
(12, 'M82B', 20, 2, 'm82b.jpg', 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapas`
--

CREATE TABLE `mapas` (
  `ID_mapas` int(11) NOT NULL,
  `mapas` varchar(100) NOT NULL,
  `nivel_requerido` int(1) NOT NULL,
  `imagen_mapas` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mapas`
--

INSERT INTO `mapas` (`ID_mapas`, `mapas`, `nivel_requerido`, `imagen_mapas`) VALUES
(1, 'BR-CLASIFICATORIA', 1, 'BR-CLASIFICATORIA.png'),
(2, 'DE-CLASIFICATORIA', 2, 'DE-CLASIFICATORIA.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `ID_partida` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `ID_usuario` int(11) DEFAULT NULL,
  `ID_sala` int(11) DEFAULT NULL,
  `puntos_partida` int(11) DEFAULT 0,
  `dano_total` int(11) DEFAULT 0,
  `headshots` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partidas`
--

INSERT INTO `partidas` (`ID_partida`, `fecha_inicio`, `fecha_fin`, `ID_usuario`, `ID_sala`, `puntos_partida`, `dano_total`, `headshots`) VALUES
(94, '2025-03-08 21:47:27', '0000-00-00 00:00:00', 4, 26, 3, 3, 0),
(95, '2025-03-08 21:47:28', '0000-00-00 00:00:00', 9, 26, 22, 22, 0),
(96, '2025-03-08 21:47:30', '0000-00-00 00:00:00', 9, 24, 22, 22, 0),
(97, '2025-03-08 21:47:31', '0000-00-00 00:00:00', 4, 25, 3, 3, 0),
(98, '2025-03-08 21:47:33', '0000-00-00 00:00:00', 9, 25, 22, 22, 0),
(105, '2025-03-09 23:42:53', '2025-03-09 23:42:53', 8, 25, 0, 0, 0),
(111, '2025-03-10 01:37:03', '0000-00-00 00:00:00', 10, 25, 312, 54, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `ID_sala` int(11) NOT NULL,
  `nombre_sala` varchar(100) NOT NULL,
  `jugadores` int(11) NOT NULL,
  `nivel_requerido` int(11) NOT NULL,
  `ID_mapas` int(11) DEFAULT NULL,
  `ID_estado` int(11) NOT NULL,
  `tiempo_inicio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`ID_sala`, `nombre_sala`, `jugadores`, `nivel_requerido`, `ID_mapas`, `ID_estado`, `tiempo_inicio`) VALUES
(2, 'sala_prueba', 5, 0, 2, 0, '2025-03-08 00:00:56'),
(3, 'PRUEBA_SALA_F', 5, 1, 1, 0, '2025-03-08 00:00:56'),
(4, 'PRUEBA_SALA_F', 5, 1, 1, 0, '2025-03-08 00:00:56'),
(9, 'PRUEBA_SALA_F', 5, 1, 1, 0, '2025-03-08 00:00:56'),
(18, 'Sala 2', 5, 0, 2, 0, '2025-03-08 03:48:59'),
(19, 'Sala 3', 5, 0, 2, 0, '2025-03-08 04:24:33'),
(20, 'Sala 4', 5, 0, 2, 0, '2025-03-08 04:25:10'),
(21, 'Sala 5', 5, 0, 2, 0, '2025-03-08 18:53:00'),
(22, 'Sala 6', 5, 0, 2, 0, '2025-03-08 20:16:32'),
(23, 'Sala 7', 5, 0, 2, 0, '2025-03-08 20:27:27'),
(24, 'Sala 8', 5, 0, 2, 0, '2025-03-08 20:45:41'),
(25, 'Sala 9', 4, 0, 1, 0, '2025-03-08 23:06:15'),
(26, 'Sala 4', 5, 0, 1, 0, '2025-03-09 20:22:52'),
(28, 'Sala 6', 0, 0, 1, 0, '2025-03-10 01:03:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `armas`
--
ALTER TABLE `armas`
  ADD PRIMARY KEY (`ID_arma`),
  ADD KEY `ID_tipo` (`ID_tipo`);

--
-- Indices de la tabla `mapas`
--
ALTER TABLE `mapas`
  ADD PRIMARY KEY (`ID_mapas`);

--
-- Indices de la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`ID_partida`),
  ADD KEY `ID_usuario` (`ID_usuario`),
  ADD KEY `ID_sala` (`ID_sala`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`ID_sala`),
  ADD KEY `ID_mapas` (`ID_mapas`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `armas`
--
ALTER TABLE `armas`
  MODIFY `ID_arma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `mapas`
--
ALTER TABLE `mapas`
  MODIFY `ID_mapas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `ID_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `ID_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `armas`
--
ALTER TABLE `armas`
  ADD CONSTRAINT `armas_ibfk_1` FOREIGN KEY (`ID_tipo`) REFERENCES `tipo` (`ID_tipo`);

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`),
  ADD CONSTRAINT `partidas_ibfk_2` FOREIGN KEY (`ID_sala`) REFERENCES `salas` (`ID_sala`);

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `salas_ibfk_1` FOREIGN KEY (`ID_mapas`) REFERENCES `mapas` (`ID_mapas`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
