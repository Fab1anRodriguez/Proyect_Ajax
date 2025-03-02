-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-03-2025 a las 00:05:18
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
-- Base de datos: `juegoff`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `armas`
--

CREATE TABLE `armas` (
  `ID_arma` int(11) NOT NULL,
  `danio` int(11) NOT NULL,
  `municion_max` int(11) NOT NULL,
  `imagen_armas` varchar(500) NOT NULL,
  `ID_tipo` int(11) DEFAULT NULL,
  `nivel_ar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avatar`
--

CREATE TABLE `avatar` (
  `ID_avatar` int(11) NOT NULL,
  `avatar` varchar(500) NOT NULL,
  `imagen` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avatar`
--

INSERT INTO `avatar` (`ID_avatar`, `avatar`, `imagen`) VALUES
(1, 'Alvaro', 'alvaroff.png'),
(2, 'Moco', 'mocoff.png'),
(3, 'Alok', 'alokff.png'),
(4, 'Chrono', 'chronoff.png'),
(5, 'Kapella', 'kapellaff.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_historial`
--

CREATE TABLE `detalle_historial` (
  `ID_det_partida` int(11) NOT NULL,
  `eliminaciones` int(11) DEFAULT 0,
  `Dano_infligido` int(11) DEFAULT 0,
  `Fecha` datetime NOT NULL,
  `ID_partida` int(11) DEFAULT NULL,
  `ID_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `ID_estado` int(11) NOT NULL,
  `estado` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`ID_estado`, `estado`) VALUES
(1, 'Desbloqueado'),
(2, 'Bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapas`
--

CREATE TABLE `mapas` (
  `ID_mapas` int(11) NOT NULL,
  `mapas` varchar(100) NOT NULL,
  `imagen_mapas` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mapas`
--

INSERT INTO `mapas` (`ID_mapas`, `mapas`, `imagen_mapas`) VALUES
(1, 'BR-CLASIFICATORIA', 'BR-CLASIFICATORIA.png'),
(2, 'DE-CLASIFICATORIA', 'DE-CLASIFICATORIA.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `ID_partida` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `ID_usuario` int(11) DEFAULT NULL,
  `ID_sala` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partidas`
--

INSERT INTO `partidas` (`ID_partida`, `fecha_inicio`, `fecha_fin`, `ID_usuario`, `ID_sala`) VALUES
(1, '2025-03-01 04:33:04', '2025-03-01 04:33:04', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `ID_rol` int(11) NOT NULL,
  `rol` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`ID_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Jugador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `ID_sala` int(11) NOT NULL,
  `nombre_sala` varchar(100) NOT NULL,
  `jugadores_max` int(11) NOT NULL,
  `nivel_requerido` int(11) NOT NULL,
  `ID_mapas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`ID_sala`, `nombre_sala`, `jugadores_max`, `nivel_requerido`, `ID_mapas`) VALUES
(1, 'Prueba-Sala-123', 2, 0, 2),
(2, 'Prueba-Sala2-456', 5, 0, 1);

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
-- Indices de la tabla `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`ID_avatar`);

--
-- Indices de la tabla `detalle_historial`
--
ALTER TABLE `detalle_historial`
  ADD PRIMARY KEY (`ID_det_partida`),
  ADD KEY `ID_partida` (`ID_partida`),
  ADD KEY `ID_usuario` (`ID_usuario`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`ID_estado`);

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
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ID_rol`);

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
-- AUTO_INCREMENT de la tabla `detalle_historial`
--
ALTER TABLE `detalle_historial`
  MODIFY `ID_det_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `ID_partida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `ID_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `ID_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `partidas_ibfk_1` FOREIGN KEY (`ID_sala`) REFERENCES `salas` (`ID_sala`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `partidas_ibfk_2` FOREIGN KEY (`ID_usuario`) REFERENCES `usuario` (`ID_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `salas_ibfk_1` FOREIGN KEY (`ID_mapas`) REFERENCES `mapas` (`ID_mapas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
