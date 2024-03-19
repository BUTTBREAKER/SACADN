DROP TABLE IF EXISTS asignacion;
CREATE TABLE `asignacion` (
  `id_asig` int(11) NOT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `id_materias` int(11) NOT NULL,
  `id_secc_año` int(11) NOT NULL,
  PRIMARY KEY (`id_asig`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS datos_de_nacimiento;
CREATE TABLE `datos_de_nacimiento` (
  `id_nac` int(11) NOT NULL,
  `f/n_nac` date DEFAULT NULL,
  `edo_nac` varchar(20) DEFAULT NULL,
  `lug_nac` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_nac`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS estudiantes;
CREATE TABLE `estudiantes` (
  `ci_est` int(11) NOT NULL,
  `nom_est` varchar(50) DEFAULT NULL,
  `apell_est` varchar(50) DEFAULT NULL,
  `id_nac` int(11) NOT NULL,
  `gen_est` varchar(12) DEFAULT NULL,
  `f/n_est` date DEFAULT NULL,
  `fech_est` varchar(45) DEFAULT NULL,
  `ci_repr` int(11) NOT NULL,
  PRIMARY KEY (`ci_est`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS materias;
CREATE TABLE `materias` (
  `id_materias` int(11) NOT NULL,
  `cod_mater` int(11) DEFAULT NULL,
  `nom_mater` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id_materias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS notas;
CREATE TABLE `notas` (
  `id_notas` int(11) NOT NULL,
  `momento_notas` varchar(45) DEFAULT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `id_materias` int(11) NOT NULL,
  PRIMARY KEY (`id_notas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS periodos;
CREATE TABLE `periodos` (
  `id_per` int(11) NOT NULL,
  `nom_per` varchar(12) DEFAULT NULL,
  `fech_per` datetime DEFAULT NULL,
  PRIMARY KEY (`id_per`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS profesores;
CREATE TABLE `profesores` (
  `ci_prof` int(11) NOT NULL,
  `nom_prof` varchar(50) DEFAULT NULL,
  `apell_prof` varchar(50) DEFAULT NULL,
  `id_nac` int(11) NOT NULL,
  `gen_prof` varchar(12) DEFAULT NULL,
  `telf_prof` varchar(15) DEFAULT NULL,
  `direcc_prof` varchar(50) DEFAULT NULL,
  `fech_prof` datetime DEFAULT NULL,
  PRIMARY KEY (`ci_prof`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS representantes;
CREATE TABLE `representantes` (
  `ci_repr` int(11) NOT NULL,
  `nom_repr` varchar(50) DEFAULT NULL,
  `apell_repr` varchar(50) DEFAULT NULL,
  `id_nac` int(11) NOT NULL,
  `gen_repr` varchar(12) DEFAULT NULL,
  `f/n_repr` date DEFAULT NULL,
  `telf_repr` varchar(15) DEFAULT NULL,
  `direcc_repr` varchar(50) DEFAULT NULL,
  `fech_repr` datetime DEFAULT NULL,
  PRIMARY KEY (`ci_repr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS secciones_anios;
CREATE TABLE `secciones_anios` (
  `id_secc_anio` int(11) NOT NULL,
  `anio` varchar(5) DEFAULT NULL,
  `seccion` varchar(5) DEFAULT NULL,
  `fech_secc_anio` datetime DEFAULT NULL,
  `id_per` int(11) NOT NULL,
  PRIMARY KEY (`id_secc_anio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS usuarios;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(255) NOT NULL,
  `cedula` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('A','U') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_completo` (`nombre_completo`),
  UNIQUE KEY `cedula` (`cedula`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO usuarios VALUES ('1', 'Franyer SÃ¡nchez', '28072391', 'fadrian06', '$2y$10$GgFeyHv0tO139.CC43iMyutcpBVP3lx1jZn.HJiLS7NFB.1B3uUlW', 'A');
