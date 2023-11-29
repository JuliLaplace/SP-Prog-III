-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2023 a las 17:26:55
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `segundo_parcial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `id` int(11) NOT NULL,
  `numeroDeCuenta` int(11) NOT NULL,
  `montoAjustado` decimal(10,0) NOT NULL,
  `idMovimiento` int(11) NOT NULL,
  `motivo` varchar(50) NOT NULL,
  `fechaAlta` datetime NOT NULL,
  `fechaBaja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ajustes`
--

INSERT INTO `ajustes` (`id`, `numeroDeCuenta`, `montoAjustado`, `idMovimiento`, `motivo`, `fechaAlta`, `fechaBaja`) VALUES
(1, 100000, 10, 1, 'esto es una prueba', '2023-11-27 01:47:48', NULL),
(2, 100010, 1, 10, 'devolver el retiro', '2023-11-27 01:50:03', NULL),
(3, 100003, 100, 9, 'sacar el deposito', '2023-11-27 01:52:54', NULL),
(4, 100003, 100, 9, 'sacar el deposito', '2023-11-27 01:54:09', NULL),
(5, 100010, 1, 10, 'sumar retiro', '2023-11-27 15:29:02', NULL),
(6, 100010, 1, 10, 'sumar retiro', '2023-11-27 15:30:19', NULL),
(7, 100010, 1, 10, 'sumar retiro', '2023-11-27 15:30:35', NULL),
(8, 100000, 5, 6, 'cancelar deposito', '2023-11-27 15:39:26', NULL),
(9, 100017, 10, 12, 'cancelar retiro', '2023-11-27 22:13:31', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `tipoDocumento` varchar(10) NOT NULL,
  `documento` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `tipoDeCuenta` varchar(10) NOT NULL,
  `moneda` varchar(5) NOT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `numeroDeCuenta` int(11) NOT NULL,
  `nombreFoto` varchar(20) DEFAULT NULL,
  `fechaAlta` datetime NOT NULL,
  `fechaBaja` datetime DEFAULT NULL,
  `fechaModificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`nombre`, `apellido`, `tipoDocumento`, `documento`, `email`, `tipoDeCuenta`, `moneda`, `saldo`, `numeroDeCuenta`, `nombreFoto`, `fechaAlta`, `fechaBaja`, `fechaModificacion`) VALUES
('julieta', 'laplace', 'dni', 35411575, 'juli.laplace@gmail.com', 'CAU$S', 'u$s', 25.00, 100000, 'CA100000.jpg', '2023-11-26 06:22:27', NULL, NULL),
('esteban', 'camejo', 'dni', 33462718, 'esteban@gmail.com', 'CA$', '$', 10020.00, 100001, NULL, '2023-11-26 06:27:42', '2023-11-27 15:38:35', NULL),
('candela', 'bogado', 'dni', 27384293, 'cande@gmail.com', 'CC$', '$', 50000.00, 100002, NULL, '2023-11-26 06:59:40', NULL, NULL),
('andrea', 'brisa', 'dni', 3777263, 'andrea@gmail.com', 'CCU$S', 'u$s', 22629.00, 100003, NULL, '2023-11-26 07:01:10', NULL, '2023-11-26 23:46:54'),
('andrea', 'papas', 'dni', 40273821, 'andrea@gmail.com', 'CC$', '$', 23029.00, 100004, NULL, '2023-11-26 07:02:33', NULL, NULL),
('andrea', 'papas', 'dni', 40273821, 'andrea@gmail.com', 'CC$', '$', 23029.00, 100005, NULL, '2023-11-26 17:59:08', NULL, NULL),
('andrea', 'andressssssss', 'dni', 3777263, 'andreitaa@gmail.com', 'CC$', '$', 23029.00, 100006, NULL, '2023-11-26 18:02:14', NULL, '2023-11-27 22:12:09'),
('andrea', 'papas', 'dni', 40273821, 'andrea@gmail.com', 'CC$', '$', 23029.00, 100007, NULL, '2023-11-26 18:04:38', '2023-11-27 01:57:35', NULL),
('andrea', 'papas', 'dni', 40273821, 'andrea@gmail.com', 'CA$', '$', 46058.00, 100008, '40273821CA.jpg', '2023-11-26 18:05:31', '2023-11-27 15:52:52', NULL),
('andrea', 'papas', 'dni', 40273821, 'andrea@gmail.com', 'CA$', '$', 23029.00, 100009, NULL, '2023-11-26 18:08:54', NULL, NULL),
('gonza', 'montero', 'dni', 37635243, 'gonza@gmail.com', 'CC$', '$', 15.00, 100010, '37635243CC.jpg', '2023-11-26 19:19:48', NULL, NULL),
('pepe', 'montero', 'dni', 37635242, 'gonza@gmail.com', 'CC$', '$', 1.00, 100011, './ImagenesDeCuentas/', '2023-11-26 19:23:34', NULL, NULL),
('pepe', 'pepito', 'dni', 37635222, 'gonza@gmail.com', 'CC$', '$', 1.00, 100012, './ImagenesDeCuentas/', '2023-11-26 19:24:24', NULL, NULL),
('pepe', 'pepito', 'dni', 37635222, 'gonza@gmail.com', 'CA$', '$', 3.00, 100013, NULL, '2023-11-26 19:25:19', NULL, NULL),
('pepe', 'peee', 'dni', 11111111, 'gonza@gmail.com', 'CA$', '$', 1.00, 100014, '11111111CA.jpg', '2023-11-26 19:32:43', NULL, NULL),
('Matias', 'Reinoso', 'dni', 33321, 'mati@gmail.com', 'CA$', '$', 20.00, 100015, NULL, '2023-11-26 21:38:33', NULL, NULL),
('mortyman', 'mortic', 'dni', 20235261, 'morty@gmail.com', 'CA$', '$', 20.00, 100016, '20235261ca$.jpg', '2023-11-27 16:08:40', NULL, NULL),
('anya', 'pila', 'dni', 25142374, 'anya@gmail.com', 'CA$', '$', 160.00, 100017, '25142374CA$.jpg', '2023-11-27 21:18:58', '2023-11-27 22:14:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
  `numeroDeCuenta` int(11) NOT NULL,
  `monto` int(11) NOT NULL,
  `tipoDeMovimiento` varchar(10) NOT NULL,
  `fechaAlta` datetime NOT NULL,
  `fechaBaja` datetime DEFAULT NULL,
  `nombreFoto` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `numeroDeCuenta`, `monto`, `tipoDeMovimiento`, `fechaAlta`, `fechaBaja`, `nombreFoto`) VALUES
(1, 100000, 10, 'deposito', '2023-11-26 21:24:58', NULL, 'CA100000.jpg'),
(2, 100000, 10, 'deposito', '2023-11-26 21:50:15', NULL, 'CA100000.jpg'),
(3, 100000, 10, 'deposito', '2023-11-26 21:52:57', NULL, 'CA100000.jpg'),
(4, 100000, 10, 'deposito', '2023-11-26 21:54:50', NULL, 'CA100000.jpg'),
(5, 100000, 10, 'deposito', '2023-11-26 21:56:39', NULL, 'CA100000.jpg'),
(6, 100000, 5, 'deposito', '2023-11-26 21:58:53', NULL, 'CA100000.jpg'),
(7, 100001, 10, 'deposito', '2023-11-26 21:59:07', NULL, 'CA100001.jpg'),
(8, 100001, 10, 'deposito', '2023-11-26 22:01:12', NULL, 'CA100001.jpg'),
(9, 100003, 100, 'deposito', '2023-11-26 22:59:40', NULL, 'CC100003.jpg'),
(10, 100010, 0, 'retiro', '2023-11-27 00:10:38', NULL, NULL),
(11, 100017, 100, 'deposito', '2023-11-27 21:22:25', NULL, 'CA$100017.jpg'),
(12, 100017, 40, 'retiro', '2023-11-27 22:12:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `rol` varchar(15) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaBaja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `rol`, `clave`, `fechaCreacion`, `fechaBaja`) VALUES
(1, 'julix404', 'supervisor', '12345', '2023-11-27 19:14:10', NULL),
(2, 'morty', 'cajero', 'mortyman', '2023-11-27 19:14:18', NULL),
(3, 'effy', 'operador', 'effyciana', '2023-11-27 19:14:23', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`numeroDeCuenta`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `numeroDeCuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100018;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
