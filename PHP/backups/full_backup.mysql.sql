DROP TABLE IF EXISTS asignacion;
CREATE TABLE `asignacion` (
  `ID_asig` int(11) NOT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `ID_materias` int(11) NOT NULL,
  `ID_seccion_anio` int(11) NOT NULL,
  PRIMARY KEY (`ID_asig`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS estudiantes;
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
  `ci_repr` int(11) NOT NULL,
  PRIMARY KEY (`ci_est`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO estudiantes VALUES ('30734944', 'Elaine Yusenidis', 'Rndón Angulo', '2003-01-04', '0', 'Mérida', 'Tucaní', 'femenino', '2024-03-19 04:48:10', '0');
DROP TABLE IF EXISTS materias;
CREATE TABLE `materias` (
  `ID_mater` int(11) NOT NULL,
  `codigo` int(11) DEFAULT NULL,
  `nombre` varchar(40) DEFAULT NULL,
  `fech_mater` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_mater`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS notas;
CREATE TABLE `notas` (
  `ID_notas` int(11) NOT NULL,
  `momento_notas` varchar(45) DEFAULT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `ID_materias` int(11) NOT NULL,
  PRIMARY KEY (`ID_notas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS periodos;
CREATE TABLE `periodos` (
  `ID_per` int(11) NOT NULL,
  `nombre` varchar(12) DEFAULT NULL,
  `fech_per` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_per`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS profesores;
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
  `fech_prof` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ci_prof`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO profesores VALUES ('66666', 'yyyyy', 'gggggg', '2003-06-08', '0', 'uyyiu', 'hghgvh', 'masculino', '0909', 'bjgvlhb', '2019-03-24 00:00:00');
INSERT INTO profesores VALUES ('77777', 'ooooooo', 'uuuuuu', '1999-08-09', '0', 'ggggg', 'uhiuhiuh', 'femenino', '98y98y', 'ypi7tpiy', '2019-03-24 00:00:00');
INSERT INTO profesores VALUES ('13567890', 'Lupe Elena', 'Torres Albornoz', '1990-12-04', '0', 'Zulia', 'Desconocido', 'femenino', '0426835678', 'La pueblita', '2019-03-24 00:00:00');
INSERT INTO profesores VALUES ('32786878', 'Juan Martines', 'Ramirez Contreras', '1983-05-04', '0', 'Merida', 'Tucaní', 'masculino', '05425678943', 'Caja Seca', '2019-03-24 00:00:00');
DROP TABLE IF EXISTS representantes;
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
  `fech_repr` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ci_repr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO representantes VALUES ('28072391', 'Franyer', 'Sánchez', '2001-10-06', '15', 'Amarrado', 'No sé', 'masculino', '055555555555', 'No me acuerdo', '2018-03-24 00:00:00');
INSERT INTO representantes VALUES ('30735099', 'Yender', 'Sánchez', '2004-04-30', '19', 'Apendejado', 'En su kokoro', 'masculino', '12345', 'Su casa', '2018-03-24 00:00:00');
DROP TABLE IF EXISTS secciones_anios;
CREATE TABLE `secciones_anios` (
  `ID_seccion_anio` int(11) NOT NULL,
  `anio` varchar(5) DEFAULT NULL,
  `seccion` varchar(5) DEFAULT NULL,
  `fech_seccion_anio` datetime DEFAULT CURRENT_TIMESTAMP,
  `ID_per` int(11) NOT NULL,
  PRIMARY KEY (`ID_seccion_anio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS usuarios;
CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(50) DEFAULT NULL,
  `Cedula` varchar(11) DEFAULT NULL,
  `Usuario` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
INSERT INTO usuarios VALUES ('1', 'eliana martines', '31331136', 'eliana', '$2y$10$9jujd5R/Y2Ubv6flQXlpfuRu1zrO8xkz3vQ1TG72QzfB9Z1cQTPia', 'A');
INSERT INTO usuarios VALUES ('2', 'gregoy albornoz', '30734944', 'parche', '$2y$10$uaeSG4EOuFsw7Xhlo3s26eTmiY/ixbi3gWRYvPvSiJUYa8.djWAjm', 'A');
INSERT INTO usuarios VALUES ('3', 'Elaine RondÃ³n ', '27890456', 'ela2', '$2y$10$jWAI9MRgYGfoaWzGA7RATekyAIou7tky0fJCERwztjqBWrmIhSg9.', 'A');
INSERT INTO usuarios VALUES ('4', 'wwww', '222222222', '11111111', '22222222', 'A');
INSERT INTO usuarios VALUES ('5', 'tttttt', '00009989878', '546546yy', 'tttttt', 'A');
INSERT INTO usuarios VALUES ('6', 'Elisel Rocet', '456768990', 'Elisel', '$2y$10$xmeCi47RF.CKZKGAXMpuaeKI2srfHvgkAhFZep8SyUooYMBsSpDDu', 'U');
