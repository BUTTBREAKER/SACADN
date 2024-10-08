SET foreign_key_checks = 0;

-- Limpiar tablas si existen
DROP TABLE IF EXISTS asignaciones;
DROP TABLE IF EXISTS secciones;
DROP TABLE IF EXISTS materias;
DROP TABLE IF EXISTS calificaciones;
DROP TABLE IF EXISTS boletines;
DROP TABLE IF EXISTS estudiantes;
DROP TABLE IF EXISTS momentos;
DROP TABLE IF EXISTS profesores;
DROP TABLE IF EXISTS periodos;
DROP TABLE IF EXISTS inscripciones;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS representantes;
DROP TABLE IF EXISTS niveles_estudio;
DROP TABLE IF EXISTS asignaciones_estudiantes;

-- Crear tablas
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(20) NOT NULL,
  apellido VARCHAR(20) NOT NULL,
  cedula INT NOT NULL UNIQUE,
  usuario VARCHAR(20) NOT NULL UNIQUE,
  clave TEXT NOT NULL,
  rol ENUM('A', 'U'),
  estado ENUM('activo', 'inactivo') DEFAULT 'activo' NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE representantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  cedula INT NOT NULL UNIQUE,
  nombre VARCHAR(20) NOT NULL,
  apellido VARCHAR(20) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  lugar_nacimiento TEXT NOT NULL,
  genero ENUM('Femenino', 'Masculino') NOT NULL,
  telefono VARCHAR(16) NOT NULL UNIQUE,
  direccion TEXT NOT NULL,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo' NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE profesores (
  id INT PRIMARY KEY AUTO_INCREMENT,
  cedula INT NOT NULL UNIQUE,
  nombre VARCHAR(20) NOT NULL,
  apellido VARCHAR(20) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  estado_nacimiento VARCHAR(20) NOT NULL,
  lugar_nacimiento TEXT NOT NULL,
  genero ENUM('Femenino', 'Masculino') NOT NULL,
  telefono VARCHAR(16) NOT NULL UNIQUE,
  direccion TEXT NOT NULL,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo' NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE niveles_estudio (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE periodos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  anio_inicio INT NOT NULL UNIQUE,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo' NOT NULL
);

CREATE TABLE estudiantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  cedula INT NOT NULL UNIQUE,
  nombres VARCHAR(255) NOT NULL,
  apellidos VARCHAR(255) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  estado_nacimiento VARCHAR(20) NOT NULL,
  lugar_nacimiento TEXT NOT NULL,
  genero ENUM('Femenino', 'Masculino') NOT NULL,
  estado ENUM('activo', 'inactivo') DEFAULT 'activo' NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  id_representante INT NOT NULL,

  FOREIGN KEY (id_representante) REFERENCES representantes (id)
);

CREATE TABLE secciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(20) NOT NULL,
  id_nivel_estudio INT NOT NULL,
  numero_matriculas INT NOT NULL,
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_nivel_estudio) REFERENCES niveles_estudio(id),
  FOREIGN KEY (id_periodo) REFERENCES periodos(id),
  UNIQUE(nombre, id_nivel_estudio, id_periodo)
);

CREATE TABLE momentos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  numero_momento INT NOT NULL,
  mes_inicio INT NOT NULL CHECK (mes_inicio >= 1 AND mes_inicio <= 12),
  dia_inicio INT NOT NULL CHECK (dia_inicio >= 1 AND dia_inicio <= 31),
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE inscripciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_periodo INT NOT NULL,
  id_estudiante INT NOT NULL,
  id_seccion INT NOT NULL,
  fecha_registro DATETIME NOT NULL,

  FOREIGN KEY (id_periodo) REFERENCES periodos(id),
  FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id),
  FOREIGN KEY (id_seccion) REFERENCES secciones(id)
);


CREATE TABLE boletines (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_momento INT NOT NULL,
  id_estudiante INT NOT NULL,
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_momento) REFERENCES momentos (id),
  FOREIGN KEY (id_estudiante) REFERENCES estudiantes (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE materias (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  id_periodo INT,
  FOREIGN KEY (id_periodo) REFERENCES periodos(id),
  UNIQUE KEY unique_nombre_periodo (nombre, id_periodo)
);


CREATE TABLE calificaciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_materia INT NOT NULL,
  id_boletin INT NOT NULL,
  id_usuario INT NOT NULL,
  calificacion INT NOT NULL CHECK (calificacion >= 0 AND calificacion <= 20),
  id_periodo INT NOT NULL,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (id_materia) REFERENCES materias (id),
  FOREIGN KEY (id_boletin) REFERENCES boletines (id),
  FOREIGN KEY (id_usuario) REFERENCES usuarios (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id)
);

CREATE TABLE asignaciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_profesor INT NOT NULL,
  id_materia INT NOT NULL,
  id_nivel_estudio INT NOT NULL,
  id_seccion INT NOT NULL,
  id_periodo INT NOT NULL,

  FOREIGN KEY (id_profesor) REFERENCES profesores (id),
  FOREIGN KEY (id_materia) REFERENCES materias (id),
  FOREIGN KEY (id_nivel_estudio) REFERENCES niveles_estudio (id),
  FOREIGN KEY (id_seccion) REFERENCES secciones (id),
  FOREIGN KEY (id_periodo) REFERENCES periodos (id),
  UNIQUE(id_profesor, id_materia, id_nivel_estudio, id_seccion, id_periodo)
);

INSERT INTO niveles_estudio (nombre) VALUES ('Primer Año'),
('Segundo Año'), ('Tercer Año'), ('Cuarto Año'), ('Quinto Año');

SET foreign_key_checks = 1;
