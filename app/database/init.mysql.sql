-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-03-2024 a las 15:10:52
-- Versión del servidor: 10.1.29-MariaDB
-- Versión de PHP: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sacadn1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion`
--

CREATE TABLE `asignacion` (
  `ID_asig` int(11) NOT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `ID_materias` int(11) NOT NULL,
  `ID_seccion_a#o` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `ci_est` int(11) NOT NULL,
  `nombre_completo` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int(3) NOT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `lugar` varchar(20) DEFAULT NULL,
  `genero` varchar(12) DEFAULT NULL,
  `fech_est` datetime DEFAULT CURRENT_TIMESTAMP,
  `ci_repr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`ci_est`, `nombre_completo`, `apellido`, `fecha_nac`, `edad`, `estado`, `lugar`, `genero`, `fech_est`, `ci_repr`) VALUES
(30734944, 'Elaine Yusenidis', 'Rndón Angulo', '2003-01-04', 0, 'Mérida', 'Tucaní', 'femenino', '2024-03-19 04:48:10', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `ID_mater` int(11) NOT NULL,
  `codigo` int(11) DEFAULT NULL,
  `nombre` varchar(40) DEFAULT NULL,
  `fech_mater` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `ID_notas` int(11) NOT NULL,
  `momento_notas` varchar(45) DEFAULT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `ID_materias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `ID_per` int(11) NOT NULL,
  `nombre` varchar(12) DEFAULT NULL,
  `fech_per` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `ci_prof` int(11) NOT NULL,
  `nombre_completo` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int(3) NOT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `lugar` varchar(20) DEFAULT NULL,
  `genero` varchar(12) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fech_prof` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`ci_prof`, `nombre_completo`, `apellido`, `fecha_nac`, `edad`, `estado`, `lugar`, `genero`, `telefono`, `direccion`, `fech_prof`) VALUES
(66666, 'yyyyy', 'gggggg', '2003-06-08', 0, 'uyyiu', 'hghgvh', 'masculino', '0909', 'bjgvlhb', '2019-03-24 00:00:00'),
(77777, 'ooooooo', 'uuuuuu', '1999-08-09', 0, 'ggggg', 'uhiuhiuh', 'femenino', '98y98y', 'ypi7tpiy', '2019-03-24 00:00:00'),
(13567890, 'Lupe Elena', 'Torres Albornoz', '1990-12-04', 0, 'Zulia', 'Desconocido', 'femenino', '0426835678', 'La pueblita', '2019-03-24 00:00:00'),
(32786878, 'Juan Martines', 'Ramirez Contreras', '1983-05-04', 0, 'Merida', 'Tucaní', 'masculino', '05425678943', 'Caja Seca', '2019-03-24 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `ci_repr` int(11) NOT NULL,
  `nombre_completo` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `edad` int(3) NOT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `lugar` varchar(20) DEFAULT NULL,
  `genero` varchar(12) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fech_repr` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`ci_repr`, `nombre_completo`, `apellido`, `fecha_nac`, `edad`, `estado`, `lugar`, `genero`, `telefono`, `direccion`, `fech_repr`) VALUES
(28072391, 'Franyer', 'Sánchez', '2001-10-06', 15, 'Amarrado', 'No sé', 'masculino', '055555555555', 'No me acuerdo', '2018-03-24 00:00:00'),
(30735099, 'Yender', 'Sánchez', '2004-04-30', 19, 'Apendejado', 'En su kokoro', 'masculino', '12345', 'Su casa', '2018-03-24 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones_a#os`
--

CREATE TABLE `secciones_a#os` (
  `ID_seccion_a#o` int(11) NOT NULL,
  `a#o` varchar(5) DEFAULT NULL,
  `seccion` varchar(5) DEFAULT NULL,
  `fech_seccion_a#o` datetime DEFAULT CURRENT_TIMESTAMP,
  `ID_per` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `nombre_completo` varchar(50) DEFAULT NULL,
  `Cedula` varchar(11) DEFAULT NULL,
  `Usuario` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `nombre_completo`, `Cedula`, `Usuario`, `contrasena`, `rol`) VALUES
(1, 'eliana martines', '31331136', 'eliana', '$2y$10$9jujd5R/Y2Ubv6flQXlpfuRu1zrO8xkz3vQ1TG72QzfB9Z1cQTPia', 'A'),
(2, 'gregoy albornoz', '30734944', 'parche', '$2y$10$uaeSG4EOuFsw7Xhlo3s26eTmiY/ixbi3gWRYvPvSiJUYa8.djWAjm', 'A'),
(3, 'Elaine RondÃ³n ', '27890456', 'ela2', '$2y$10$jWAI9MRgYGfoaWzGA7RATekyAIou7tky0fJCERwztjqBWrmIhSg9.', 'A'),
(4, 'wwww', '222222222', '11111111', '22222222', 'A'),
(5, 'tttttt', '00009989878', '546546yy', 'tttttt', 'A'),
(6, 'Elisel Rocet', '456768990', 'Elisel', '$2y$10$xmeCi47RF.CKZKGAXMpuaeKI2srfHvgkAhFZep8SyUooYMBsSpDDu', 'U');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`ID_asig`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`ci_est`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`ID_mater`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`ID_notas`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`ID_per`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`ci_prof`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`ci_repr`);

--
-- Indices de la tabla `secciones_a#os`
--
ALTER TABLE `secciones_a#os`
  ADD PRIMARY KEY (`ID_seccion_a#o`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
