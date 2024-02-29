DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre_completo VARCHAR(255) NOT NULL UNIQUE,
  cedula INTEGER NOT NULL UNIQUE,
  usuario VARCHAR(255) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  rol VARCHAR(1) NOT NULL CHECK (rol IN ('A', 'U'))
);

CREATE TABLE `asignacion` (
  `id_asig` int(11) NOT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `id_materias` int(11) NOT NULL,
  `id_secc_año` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_de_nacimiento`
--

CREATE TABLE `datos_de_nacimiento` (
  `id_nac` int(11) NOT NULL,
  `f/n_nac` date DEFAULT NULL,
  `edo_nac` varchar(20) DEFAULT NULL,
  `lug_nac` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `ci_est` int(11) NOT NULL,
  `nom_est` varchar(50) DEFAULT NULL,
  `apell_est` varchar(50) DEFAULT NULL,
  `id_nac` int(11) NOT NULL,
  `gen_est` varchar(12) DEFAULT NULL,
  `f/n_est` date DEFAULT NULL,
  `fech_est` varchar(45) DEFAULT NULL,
  `ci_repr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id_materias` int(11) NOT NULL,
  `cod_mater` int(11) DEFAULT NULL,
  `nom_mater` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id_notas` int(11) NOT NULL,
  `momento_notas` varchar(45) DEFAULT NULL,
  `ci_est` int(11) NOT NULL,
  `ci_prof` int(11) NOT NULL,
  `id_materias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `id_per` int(11) NOT NULL,
  `nom_per` varchar(12) DEFAULT NULL,
  `fech_per` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `ci_prof` int(11) NOT NULL,
  `nom_prof` varchar(50) DEFAULT NULL,
  `apell_prof` varchar(50) DEFAULT NULL,
  `id_nac` int(11) NOT NULL,
  `gen_prof` varchar(12) DEFAULT NULL,
  `telf_prof` varchar(15) DEFAULT NULL,
  `direcc_prof` varchar(50) DEFAULT NULL,
  `fech_prof` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `ci_repr` int(11) NOT NULL,
  `nom_repr` varchar(50) DEFAULT NULL,
  `apell_repr` varchar(50) DEFAULT NULL,
  `id_nac` int(11) NOT NULL,
  `gen_repr` varchar(12) DEFAULT NULL,
  `f/n_repr` date DEFAULT NULL,
  `telf_repr` varchar(15) DEFAULT NULL,
  `direcc_repr` varchar(50) DEFAULT NULL,
  `fech_repr` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones_a#os`
--

CREATE TABLE `secciones_a#os` (
  `id_secc_a#o` int(11) NOT NULL,
  `a#o` varchar(5) DEFAULT NULL,
  `seccion` varchar(5) DEFAULT NULL,
  `fech_secc_a#o` datetime DEFAULT NULL,
  `id_per` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Indices de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`id_asig`);

--
-- Indices de la tabla `datos_de_nacimiento`
--
ALTER TABLE `datos_de_nacimiento`
  ADD PRIMARY KEY (`id_nac`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`ci_est`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_materias`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id_notas`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`id_per`);

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
  ADD PRIMARY KEY (`id_secc_a#o`);

COMMIT;
