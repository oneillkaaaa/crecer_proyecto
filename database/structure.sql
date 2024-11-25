-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-11-2024 a las 00:30:55
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
-- Base de datos: `bd_crecer`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `Id` int(11) NOT NULL,
  `Nombre_paciente` varchar(100) NOT NULL,
  `Edad_paciente` int(11) NOT NULL,
  `Sexo_paciente` varchar(20) NOT NULL,
  `Fecha_nacimiento` date NOT NULL,
  `Diagnostico` varchar(200) NOT NULL,
  `Nombre_apoderado` varchar(100) NOT NULL,
  `Edad_apoderado` int(11) NOT NULL,
  `Dni_apoderado` int(11) NOT NULL,
  `Correo_apoderado` varchar(50) NOT NULL,
  `Celular_apoderado` int(11) NOT NULL,
  `Relacion_apoderado` varchar(50) NOT NULL,
  `Id_Psicologo` int(11) DEFAULT NULL,
  `Psicologo_Asignado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`Id`, `Nombre_paciente`, `Edad_paciente`, `Sexo_paciente`, `Fecha_nacimiento`, `Diagnostico`, `Nombre_apoderado`, `Edad_apoderado`, `Dni_apoderado`, `Correo_apoderado`, `Celular_apoderado`, `Relacion_apoderado`, `Id_Psicologo`, `Psicologo_Asignado`) VALUES
(1, 'Sabrina Jiron Martinez', 12, 'Femenino', '2008-05-12', 'Tartamudez y timidez al hablar en público.', 'Maria Martinez', 42, 7203598, 'mariama@gmail.com', 906885796, 'Madre', 2, 'Max Armando Figueroa Duran'),
(2, 'Fabricio Carlos Zurita Cordova', 8, 'Masculino', '2012-12-24', 'Problemas de ira.', 'Romina Cordova Atoche', 36, 687589, 'romi@gmail.com', 906778549, 'Madre', NULL, NULL),
(3, 'Lupita Villalobos', 11, 'Femenino', '2009-05-01', 'Pesadillas y temor. gwege', 'Hilda Villalobos Ramirez', 27, 778278, 'hilda@gmail.com', 906997854, 'Tutor legal', 1, 'Gabriela Trelles Rivas'),
(4, 'Juan Daniel Lopez Hurtado', 10, 'Masculino', '2010-12-25', 'Adicción a juegos en linea.', 'Daniel Lopez Farfan', 41, 7205412, 'daniel@gmail.com', 901447859, 'Padre', 2, 'Max Armando Figueroa Duran'),
(5, 'Georgina Gonzales Socola', 12, 'Femenino', '2009-06-12', 'Problemas en el hogar, depresión y ansiedad.', 'Catalina Socola', 36, 3687548, 'cata12@gmail.com', 906778542, 'Madre', 1, 'Gabriela Trelles Rivas'),
(6, 'Angel Zeas Iglesias', 10, 'Masculino', '2010-11-02', 'Problemas en el habla.', 'Yomar Dairon', 28, 7548521, 'yomar@gmail.com', 978558412, 'Tutor legal', 1, 'Gabriela Trelles Rivas'),
(7, 'Sebastian Ruiz Mejias', 15, 'Masculino', '2008-04-12', 'Discute con los compañeros del colegio y problemas de ira.', 'Julieta Ruiz', 51, 368574, 'julieta@gmail.com', 906774585, 'Madre', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `psicologos`
--

CREATE TABLE `psicologos` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Sexo` varchar(30) NOT NULL,
  `Edad` int(11) NOT NULL,
  `Especialidad` varchar(200) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Telefono` int(11) NOT NULL,
  `Dni` int(11) NOT NULL,
  `Activo` int(11) NOT NULL DEFAULT 1,
  `Fecha_Registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `psicologos`
--

INSERT INTO `psicologos` (`Id`, `Nombre`, `Apellido`, `Sexo`, `Edad`, `Especialidad`, `Email`, `Telefono`, `Dni`, `Activo`, `Fecha_Registro`) VALUES
(1, 'Gabriela', 'Trelles Rivas', 'Femenino', 53, 'Psicóloga', 'gabriela123@gmail.com', 957885492, 123, 1, '2024-11-11 10:28:59'),
(2, 'Max Armando', 'Figueroa Duran', 'Masculino', 29, 'Terapeuta', 'max@gmail.com', 906990457, 72085102, 1, '2024-11-11 11:36:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Id` int(11) NOT NULL,
  `Usuario` varchar(30) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Password` varchar(120) NOT NULL,
  `Activacion` int(11) NOT NULL DEFAULT 1,
  `Id_Psicologo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id`, `Usuario`, `Nombre`, `Password`, `Activacion`, `Id_Psicologo`) VALUES
(1, 'oneillka18@gmail.com', 'Cinthia Kcomt Atoche', '$2y$10$IAc9DuFdhQ9ZEq.PhPtwjOB0yXLywh0LjFPT.kTeYvzqn/hKPLyPy', 1, 0),
(2, 'gabriela123@gmail.com', 'Gabriela Trelles Rivas', '$2y$10$OXgHc0FCN.7WMtGsXElrAuTJ.98tgG6ofOil.jtE0uqSrPa/z0hkq', 1, 1),
(3, 'max@gmail.com', 'Max Armando Figueroa Duran', '$2y$10$RGumJdsTZIpdevkyF0Yzje8cSRSIFelqi/6xIo8M0ljDlv8TVBG5O', 2, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_Psicologo` (`Id_Psicologo`);

--
-- Indices de la tabla `psicologos`
--
ALTER TABLE `psicologos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_Psicologo` (`Id_Psicologo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `psicologos`
--
ALTER TABLE `psicologos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
