-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026 at 05:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laboratory`
--
CREATE DATABASE IF NOT EXISTS `laboratory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `laboratory`;

-- --------------------------------------------------------

--
-- Table structure for table `categorias_equipo`
--

CREATE TABLE `categorias_equipo` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL,
  `requiere_mantenimiento_mensual` tinyint(1) DEFAULT 0,
  `cantidad` int(11) DEFAULT 0,
  `observacion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias_equipo`
--

INSERT INTO `categorias_equipo` (`id_categoria`, `nombre_categoria`, `requiere_mantenimiento_mensual`, `cantidad`, `observacion`) VALUES
(1, 'Computadora', 1, 0, ''),
(2, 'Proyector', 0, 0, ''),
(3, 'Impresora', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `estados_reserva`
--

CREATE TABLE `estados_reserva` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estados_reserva`
--

INSERT INTO `estados_reserva` (`id_estado`, `nombre_estado`) VALUES
(1, 'Pendiente'),
(2, 'Confirmada'),
(3, 'Cancelada');

-- --------------------------------------------------------

--
-- Table structure for table `incidencias`
--

CREATE TABLE `incidencias` (
  `id_incidencia` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `id_usuario_reporta` int(11) NOT NULL,
  `descripcion_problema` text NOT NULL,
  `fecha_reporte` datetime DEFAULT current_timestamp(),
  `resuelto` tinyint(1) DEFAULT 0,
  `nivel_gravedad` enum('Baja','Media','Alta') NOT NULL DEFAULT 'Media'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidencias`
--

INSERT INTO `incidencias` (`id_incidencia`, `id_equipo`, `id_usuario_reporta`, `descripcion_problema`, `fecha_reporte`, `resuelto`, `nivel_gravedad`) VALUES
(1, 1, 5, 'asdasasd', '2026-02-06 06:53:50', 0, 'Media'),
(7, 1, 5, 'asd', '2026-02-06 17:32:13', 0, 'Media'),
(8, 2, 5, 'asdsad', '2026-02-06 17:32:19', 1, 'Media');

-- --------------------------------------------------------

--
-- Table structure for table `inventario`
--

CREATE TABLE `inventario` (
  `id_equipo` int(11) NOT NULL,
  `codigo_serial` varchar(50) NOT NULL,
  `id_laboratorio` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `marca_modelo` varchar(100) DEFAULT NULL,
  `estado_operativo` enum('Operativo','En Reparación','Baja') DEFAULT 'Operativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventario`
--

INSERT INTO `inventario` (`id_equipo`, `codigo_serial`, `id_laboratorio`, `id_categoria`, `marca_modelo`, `estado_operativo`) VALUES
(1, 'asd', 1, 1, 'asdsad', 'Operativo'),
(2, '12124124', 1, 1, 'laptop', 'Operativo');

-- --------------------------------------------------------

--
-- Table structure for table `laboratorios`
--

CREATE TABLE `laboratorios` (
  `id_laboratorio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `capacidad_personas` int(11) NOT NULL,
  `esta_activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laboratorios`
--

INSERT INTO `laboratorios` (`id_laboratorio`, `nombre`, `ubicacion`, `capacidad_personas`, `esta_activo`) VALUES
(1, 'Sala Prueba', 'Edificio X', 10, 1),
(2, 'asd', 'lugarcito', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_laboratorio` int(11) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `id_estado` int(11) NOT NULL,
  `motivo_uso` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_usuario`, `id_laboratorio`, `fecha_inicio`, `fecha_fin`, `id_estado`, `motivo_uso`) VALUES
(25, 5, 1, '2026-02-06 19:32:00', '2026-02-08 20:33:00', 2, 'hola'),
(26, 5, 1, '2026-02-11 06:41:00', '2026-02-19 06:41:00', 3, 'Reparacion'),
(27, 5, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 2, 'sad');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(1, 'Cliente', 'Usuario estándar con permisos de reserva'),
(2, 'Administrador', 'Personal administrativo con control total del sistema');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_completo`, `email`, `password_hash`, `id_rol`, `reset_token`, `reset_expires`, `fecha_registro`) VALUES
(5, 'Jose Lopez', 'Jalgxone@outlook.com', '$2y$10$WgI95ICRMuvwCHtxXE8yNeQ6nVolD4xIri5HpGRruvKMC7h5EkY2G', 1, '150bda9b6f59213cb591e3e9fbe9cb414a7b70d6d3c402b07cbcc4c001253f9a', '2026-02-07 06:06:18', '2026-02-04 00:23:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias_equipo`
--
ALTER TABLE `categorias_equipo`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `estados_reserva`
--
ALTER TABLE `estados_reserva`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id_incidencia`),
  ADD KEY `id_equipo` (`id_equipo`),
  ADD KEY `id_usuario_reporta` (`id_usuario_reporta`);

--
-- Indexes for table `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_equipo`),
  ADD UNIQUE KEY `codigo_serial` (`codigo_serial`),
  ADD KEY `id_laboratorio` (`id_laboratorio`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id_laboratorio`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_laboratorio` (`id_laboratorio`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias_equipo`
--
ALTER TABLE `categorias_equipo`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `estados_reserva`
--
ALTER TABLE `estados_reserva`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id_incidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laboratorios`
--
ALTER TABLE `laboratorios`
  MODIFY `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `incidencias_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `inventario` (`id_equipo`),
  ADD CONSTRAINT `incidencias_ibfk_2` FOREIGN KEY (`id_usuario_reporta`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`id_laboratorio`) REFERENCES `laboratorios` (`id_laboratorio`),
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias_equipo` (`id_categoria`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_laboratorio`) REFERENCES `laboratorios` (`id_laboratorio`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estados_reserva` (`id_estado`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
