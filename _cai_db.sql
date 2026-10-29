-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 09-07-2026 a las 01:54:20
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `_cai_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

DROP TABLE IF EXISTS `clases`;
CREATE TABLE IF NOT EXISTS `clases` (
  `id_clase` int NOT NULL AUTO_INCREMENT,
  `clase_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_profesor` int DEFAULT NULL,
  `id_grado` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_clase`),
  KEY `id_profesor` (`id_profesor`),
  KEY `id_grado` (`id_grado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id_clase`, `clase_nombre`, `id_profesor`, `id_grado`, `created_at`) VALUES
(1, 'sasdasd', 2, 1, '2026-05-08 18:24:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_sistema`
--

DROP TABLE IF EXISTS `configuracion_sistema`;
CREATE TABLE IF NOT EXISTS `configuracion_sistema` (
  `clave` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_sistema`
--

INSERT INTO `configuracion_sistema` (`clave`, `valor`) VALUES
('tema_default', 'nord-light');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencias`
--

DROP TABLE IF EXISTS `dependencias`;
CREATE TABLE IF NOT EXISTS `dependencias` (
  `id_dependencia` int NOT NULL AUTO_INCREMENT,
  `nombre_dependencia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_dependencia`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dependencias`
--

INSERT INTO `dependencias` (`id_dependencia`, `nombre_dependencia`) VALUES
(1, 'MPPE'),
(2, 'FUNDASALUD'),
(3, 'GOBERNACION TRUJILLO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id_estudiante` int NOT NULL AUTO_INCREMENT,
  `apellido` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `edad` int NOT NULL,
  `id_sexo` int DEFAULT NULL,
  `tz` int NOT NULL,
  `tc` int NOT NULL,
  `tp` int NOT NULL,
  `id_municipio` int DEFAULT NULL,
  `id_institucion` int DEFAULT NULL,
  `tipo_estudiante` enum('universitario','liceista','foraneo') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_estudiante`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `id_sexo` (`id_sexo`),
  KEY `id_municipio` (`id_municipio`),
  KEY `id_institucion` (`id_institucion`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `apellido`, `nombre`, `cedula`, `fecha_nacimiento`, `edad`, `id_sexo`, `tz`, `tc`, `tp`, `id_municipio`, `id_institucion`, `tipo_estudiante`, `created_at`) VALUES
(1, 'ale', 'Andru', '11111111', '2003-06-07', 23, 2, 33, 12, 44, 1, 1, 'liceista', '2026-05-08 18:24:30'),
(2, 'auu', 'piña', NULL, '2018-12-30', 7, 1, 32, 41, 22, 1, 1, 'universitario', '2026-06-04 03:40:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_clases`
--

DROP TABLE IF EXISTS `estudiantes_clases`;
CREATE TABLE IF NOT EXISTS `estudiantes_clases` (
  `id_estudiante_clase` int NOT NULL AUTO_INCREMENT,
  `id_estudiante` int NOT NULL,
  `id_clase` int NOT NULL,
  PRIMARY KEY (`id_estudiante_clase`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_clase` (`id_clase`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes_clases`
--

INSERT INTO `estudiantes_clases` (`id_estudiante_clase`, `id_estudiante`, `id_clase`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

DROP TABLE IF EXISTS `grados`;
CREATE TABLE IF NOT EXISTS `grados` (
  `id_grado` int NOT NULL AUTO_INCREMENT,
  `nombre_grado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_grado`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id_grado`, `nombre_grado`) VALUES
(1, '1er Grado'),
(2, '2do Grado'),
(3, '3er Grado'),
(4, '4to Grado'),
(5, '5to Grado'),
(6, '6to Grado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

DROP TABLE IF EXISTS `historial`;
CREATE TABLE IF NOT EXISTS `historial` (
  `id_historial` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `accion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instituciones`
--

DROP TABLE IF EXISTS `instituciones`;
CREATE TABLE IF NOT EXISTS `instituciones` (
  `id_institucion` int NOT NULL AUTO_INCREMENT,
  `nombre_institucion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_institucion`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `instituciones`
--

INSERT INTO `instituciones` (`id_institucion`, `nombre_institucion`) VALUES
(1, 'MPPE'),
(2, 'GOBERNACION TRUJILLO'),
(3, 'FUNDASALUD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `madres`
--

DROP TABLE IF EXISTS `madres`;
CREATE TABLE IF NOT EXISTS `madres` (
  `id_madre` int NOT NULL AUTO_INCREMENT,
  `apellidos_nombres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edad` int NOT NULL,
  `estado_civil` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocupacion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_estudiante` int DEFAULT NULL,
  PRIMARY KEY (`id_madre`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `id_estudiante` (`id_estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamentos`
--

DROP TABLE IF EXISTS `medicamentos`;
CREATE TABLE IF NOT EXISTS `medicamentos` (
  `id_medicamento` int NOT NULL AUTO_INCREMENT,
  `nombre_medicamento` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_estudiante` int DEFAULT NULL,
  PRIMARY KEY (`id_medicamento`),
  KEY `id_estudiante` (`id_estudiante`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medicamentos`
--

INSERT INTO `medicamentos` (`id_medicamento`, `nombre_medicamento`, `id_estudiante`) VALUES
(1, 'tal pin pom', 1),
(2, 'ajua', 1),
(3, 'none', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

DROP TABLE IF EXISTS `municipios`;
CREATE TABLE IF NOT EXISTS `municipios` (
  `id_municipio` int NOT NULL AUTO_INCREMENT,
  `nombre_municipio` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_municipio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `nombre_municipio`) VALUES
(1, 'Valera'),
(2, 'San Rafael de Carvajal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padres`
--

DROP TABLE IF EXISTS `padres`;
CREATE TABLE IF NOT EXISTS `padres` (
  `id_padre` int NOT NULL AUTO_INCREMENT,
  `apellidos_nombres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edad` int NOT NULL,
  `estado_civil` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocupacion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_estudiante` int DEFAULT NULL,
  PRIMARY KEY (`id_padre`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `id_estudiante` (`id_estudiante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

DROP TABLE IF EXISTS `personal`;
CREATE TABLE IF NOT EXISTS `personal` (
  `id_personal` int NOT NULL AUTO_INCREMENT,
  `nombres_apellidos` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `lugar_nacimiento` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_habitacion` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_celular` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_familiar` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_sexo` int NOT NULL,
  `edad` int NOT NULL,
  `titulo_pregrado` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postgrado` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estudia_actualmente` tinyint(1) NOT NULL DEFAULT '0',
  `estudios_actuales` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_personal` enum('Docente','Administrativo','Obrero') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_ingreso_mppe` date DEFAULT NULL,
  `codigo_rac` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carga_horaria` int DEFAULT NULL,
  `codigo_dependencia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_dependencia` int NOT NULL,
  `institucion_pertenece` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cuenta_nomina` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_cuenta_nomina` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricula_docente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `situacion_trabajador` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otros_datos` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_personal`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `id_sexo` (`id_sexo`),
  KEY `id_dependencia` (`id_dependencia`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id_personal`, `nombres_apellidos`, `cedula`, `fecha_nacimiento`, `lugar_nacimiento`, `direccion`, `telefono_habitacion`, `telefono_celular`, `telefono_familiar`, `correo`, `id_sexo`, `edad`, `titulo_pregrado`, `postgrado`, `estudia_actualmente`, `estudios_actuales`, `tipo_personal`, `fecha_ingreso_mppe`, `codigo_rac`, `cargo`, `carga_horaria`, `codigo_dependencia`, `id_dependencia`, `institucion_pertenece`, `tipo_cuenta_nomina`, `numero_cuenta_nomina`, `matricula_docente`, `situacion_trabajador`, `otros_datos`, `created_at`) VALUES
(2, 'asdasdas', '33333333', '2004-04-27', 'asdasd', 'jasdjjasdjs', '2334452', '23123123', '234552', 'jasdjas@gmail.com', 1, 22, 'ingenieron en pan', 'no', 1, 'pan 2', 'Docente', '2003-05-31', '223', 'ESTUDIANTE', 22, NULL, 1, 'MPPE', '22323', '123123123123', '224414', 'sdasd', '', '2026-05-08 18:24:30'),
(3, 'asdasdasss', '2344522', '2004-04-12', 'asdasdas', 'wdsadsdwwwww', '233445222', '231231232', '', 'jasdjasss@gmail.com', 2, 22, 'ingenieron en pan 2', 'no', 0, NULL, 'Administrativo', '2004-03-22', '224', '2323', 2234, NULL, 1, 'MPPE', '2211134', '112223444', NULL, 'sdasd', 'asdasdasdsa', '2026-05-08 20:24:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma_tp`
--

DROP TABLE IF EXISTS `pma_tp`;
CREATE TABLE IF NOT EXISTS `pma_tp` (
  `id_pma_tp` int NOT NULL AUTO_INCREMENT,
  `pma_principal` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pma_primaria` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_estudiante` int DEFAULT NULL,
  PRIMARY KEY (`id_pma_tp`),
  UNIQUE KEY `id_estudiante` (`id_estudiante`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pma_tp`
--

INSERT INTO `pma_tp` (`id_pma_tp`, `pma_principal`, `pma_primaria`, `id_estudiante`) VALUES
(1, '221321', '13223', 1),
(2, '2231', '23223', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

DROP TABLE IF EXISTS `profesores`;
CREATE TABLE IF NOT EXISTS `profesores` (
  `id_profesor` int NOT NULL AUTO_INCREMENT,
  `id_turno` int DEFAULT NULL,
  `id_personal` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_profesor`),
  KEY `id_turno` (`id_turno`),
  KEY `fk_prof_personal` (`id_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesor`, `id_turno`, `id_personal`, `created_at`) VALUES
(2, 1, 2, '2026-05-08 18:24:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

DROP TABLE IF EXISTS `representantes`;
CREATE TABLE IF NOT EXISTS `representantes` (
  `id_representante` int NOT NULL AUTO_INCREMENT,
  `apellidos_nombres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cedula` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar_nacimiento` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `estado_civil` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_sexo` int NOT NULL,
  `nacionalidad` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_estudiante` int DEFAULT NULL,
  PRIMARY KEY (`id_representante`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_sexo` (`id_sexo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`id_representante`, `apellidos_nombres`, `cedula`, `lugar_nacimiento`, `fecha_nacimiento`, `estado_civil`, `id_sexo`, `nacionalidad`, `telefono`, `id_estudiante`) VALUES
(1, 'aaa', '2232', 'dasdasd', '2004-06-27', 'soltero', 2, 'veneko', '87747416247', 1),
(2, 'asdasd', '23123123', 'asdasdasdadas', '2011-01-31', 'soltero', 2, 'veneko', '2312321312', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `descripcion_rol` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `descripcion_rol` (`descripcion_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `descripcion_rol`) VALUES
(1, 'Administrador'),
(2, 'Invitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sexo`
--

DROP TABLE IF EXISTS `sexo`;
CREATE TABLE IF NOT EXISTS `sexo` (
  `id_sexo` int NOT NULL AUTO_INCREMENT,
  `descripcion_sexo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_sexo`),
  UNIQUE KEY `descripcion_sexo` (`descripcion_sexo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sexo`
--

INSERT INTO `sexo` (`id_sexo`, `descripcion_sexo`) VALUES
(2, 'Femenino'),
(1, 'Masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

DROP TABLE IF EXISTS `turno`;
CREATE TABLE IF NOT EXISTS `turno` (
  `id_turno` int NOT NULL AUTO_INCREMENT,
  `descripcion_turno` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_turno`),
  UNIQUE KEY `descripcion_turno` (`descripcion_turno`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `descripcion_turno`) VALUES
(1, 'Mañana'),
(2, 'Tarde');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_rol` int DEFAULT NULL,
  `clave` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tema_preferido` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'nord-light',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  KEY `id_rol` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `id_rol`, `clave`, `tema_preferido`) VALUES
(1, 'ADMIN', 1, '$2y$10$2bPcqfsjTOb3MkkS89maaeiTn.gesYTAfKv0J5ZtbJNYlQxr37pja', 'light'),
(3, 'ale', 2, '$2y$10$9V2rMkwsZ303z9JJyd2Yy.bQvrzqpX8EEKlPEBV9yYA.8pOm3e2Rq', 'light');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clases`
--
ALTER TABLE `clases`
  ADD CONSTRAINT `clases_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`),
  ADD CONSTRAINT `clases_ibfk_3` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id_sexo`),
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`),
  ADD CONSTRAINT `estudiantes_ibfk_3` FOREIGN KEY (`id_institucion`) REFERENCES `instituciones` (`id_institucion`);

--
-- Filtros para la tabla `estudiantes_clases`
--
ALTER TABLE `estudiantes_clases`
  ADD CONSTRAINT `estudiantes_clases_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `estudiantes_clases_ibfk_2` FOREIGN KEY (`id_clase`) REFERENCES `clases` (`id_clase`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `historial_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `madres`
--
ALTER TABLE `madres`
  ADD CONSTRAINT `madres_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE;

--
-- Filtros para la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD CONSTRAINT `medicamentos_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE;

--
-- Filtros para la tabla `padres`
--
ALTER TABLE `padres`
  ADD CONSTRAINT `padres_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE;

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id_sexo`),
  ADD CONSTRAINT `personal_ibfk_2` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencias` (`id_dependencia`);

--
-- Filtros para la tabla `pma_tp`
--
ALTER TABLE `pma_tp`
  ADD CONSTRAINT `pma_tp_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD CONSTRAINT `fk_prof_personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`),
  ADD CONSTRAINT `profesores_ibfk_1` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`);

--
-- Filtros para la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD CONSTRAINT `representantes_new_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE,
  ADD CONSTRAINT `representantes_new_ibfk_2` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id_sexo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
