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
INSERT INTO estudiantes VALUES ('0', '', 'bbbb', '1978-05-16', '0', 'hhhh', 'llll', 'masculino', '2001-05-24 00:00:00', '0');
INSERT INTO estudiantes VALUES ('999999', '', 'jha{ñoiujñvs', '2015-09-16', '0', 'hhh', 'uypñ', 'masculino', '2001-05-24 00:00:00', '0');
INSERT INTO estudiantes VALUES ('30734567', 'joseee', 'acevedo', '2003-08-06', '0', 'zulia', 'no se', 'masculino', '2001-05-24 00:00:00', '0');
INSERT INTO estudiantes VALUES ('30734789', 'jose', 'acevedo', '2003-08-06', '0', 'zulia', 'maracaibo', 'masculino', '2001-05-24 00:00:00', '0');
INSERT INTO estudiantes VALUES ('31245678', '', 'ramires', '2003-05-06', '0', 'merida', 'palmarito', 'masculino', '2001-05-24 00:00:00', '0');
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
INSERT INTO profesores VALUES ('0', 'Elaine Yusneidis', 'Rondón Angulo', '2004-01-04', '0', 'Mérida 4', 'Tucaní', 'femenino', '04269759809', 'San Pedro', '2006-05-11 00:00:00');
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
INSERT INTO representantes VALUES ('123456789', 'John', 'Doe', '1988-01-01', '0', 'Mérida', 'Tovar', 'femenino', '0555-555-5555', 'Tovar,  Carretera Panamericana', '2006-05-11 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
INSERT INTO usuarios VALUES ('1', 'eliana martines', '31331136', 'eliana', '$2y$10$9jujd5R/Y2Ubv6flQXlpfuRu1zrO8xkz3vQ1TG72QzfB9Z1cQTPia', 'A');
INSERT INTO usuarios VALUES ('2', 'gregoy albornoz', '30734944', 'parche', '$2y$10$uaeSG4EOuFsw7Xhlo3s26eTmiY/ixbi3gWRYvPvSiJUYa8.djWAjm', 'A');
INSERT INTO usuarios VALUES ('3', 'Elaine RondÃ³n ', '27890456', 'ela2', '$2y$10$jWAI9MRgYGfoaWzGA7RATekyAIou7tky0fJCERwztjqBWrmIhSg9.', 'A');
INSERT INTO usuarios VALUES ('4', 'wwww', '222222222', '11111111', '22222222', 'A');
INSERT INTO usuarios VALUES ('5', 'tttttt', '00009989878', '546546yy', 'tttttt', 'A');
INSERT INTO usuarios VALUES ('6', 'Elisel Rocet', '456768990', 'Elisel', '$2y$10$xmeCi47RF.CKZKGAXMpuaeKI2srfHvgkAhFZep8SyUooYMBsSpDDu', 'U');
INSERT INTO usuarios VALUES ('7', 'Elaine Rondón ', '30567890', 'elaine', '$2y$10$oLpQYKJSc.F85jh/Qa2u8ewxiKGUd8Ta2YwCEleLNpeAanVfpdIVC', 'U');
INSERT INTO usuarios VALUES ('8', 'julia ramirez', '27890456', 'ela3', '$2y$10$i8nmmYmEgMNr18seDHeu2u8XEpuG3lXIBioecSDIiHJRBBD5vrlju', 'U');
INSERT INTO usuarios VALUES ('9', 'usuario', '123456789', 'usuario', '$2y$10$K4R.MWzpNww03kgTYXeD5.513yO3myu8B6FR81hICAt/1s2mNLp2G', 'U');
