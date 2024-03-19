DROP TABLE IF EXISTS estudiantes;
CREATE TABLE `estudiantes` (
  `cedula` int(11) NOT NULL,
  `nombres` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `f/n` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `lugar` varchar(20) DEFAULT NULL,
  `gen_est` varchar(12) DEFAULT NULL,
  `fech_est` varchar(45) DEFAULT NULL,
  `ci_repr` int(11) NOT NULL,
  PRIMARY KEY (`cedula`),
  KEY `ci_repr` (`ci_repr`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`ci_repr`) REFERENCES `representantes` (`cedula`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
DROP TABLE IF EXISTS profesores;
CREATE TABLE `profesores` (
  `cedula` int(11) NOT NULL,
  `nombres` varchar(50) DEFAULT NULL,
  `apellidos` varchar(50) DEFAULT NULL,
  `f_n` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `lugar` varchar(20) DEFAULT NULL,
  `genero` varchar(12) DEFAULT NULL CHECK (`genero` in ('masculino','femenino')),
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fech_prof` datetime DEFAULT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO profesores VALUES ('30975656', 'jose', 'acevedo', '2003-08-06', 'zulia', 'caja seca', 'masculino', '04128286313', 'los proceres', '2024-03-16 06:58:15');
DROP TABLE IF EXISTS representantes;
CREATE TABLE `representantes` (
  `cedula` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `f/n` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `genero` varchar(12) DEFAULT NULL,
  `lugar` varchar(20) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `fech_repr` datetime DEFAULT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO representantes VALUES ('7903751', 'Ibeth', 'Flores', '0000-00-00', 'Zulia', 'Femenino', 'Maracaibo', '0412-1663245', 'Los Proceres', '');
DROP TABLE IF EXISTS usuarios;
CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(50) NOT NULL,
  `Cedula` int(15) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `contrasena` varchar(65) NOT NULL,
  `rol` enum('A','U') NOT NULL DEFAULT 'U',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
INSERT INTO usuarios VALUES ('1', 'Jose Acevedo', '30975656', 'nachoacev', '1234', 'A');
INSERT INTO usuarios VALUES ('2', 'Jose Acevedo', '30975658', 'lilianap1988', '$2y$10$HvMTK4H/WUojm9Ws8bXRr.8rf9jO9P4hRitWfMB3OYk', 'U');
INSERT INTO usuarios VALUES ('3', 'Jose Acevedog', '79037512', 'nachoace0', '$2y$10$11xHlP5oo1iF/jbxuA7GCOci.zR7uAfLNh5GbDUsD9gn6I2nu4.4G', 'A');
