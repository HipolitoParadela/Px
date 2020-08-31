-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-08-2019 a las 13:18:37
-- Versión del servidor: 5.6.40-log
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `c1260237_001`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_caja`
--

CREATE TABLE `tbl_caja` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Jornada_id` int(11) NOT NULL,
  `Valor_ingreso` int(11) NOT NULL,
  `Valor_egreso` int(11) NOT NULL,
  `Observaciones` text NOT NULL,
  `Usuario_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_categorias`
--

CREATE TABLE `tbl_categorias` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nombre_categoria` varchar(50) NOT NULL,
  `Descripcion` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_categorias`
--

INSERT INTO `tbl_categorias` (`Id`, `Nombre_categoria`, `Descripcion`) VALUES
(1, 'Bebidas', 'En este grupo se ubicarán todas las bebidas ofrecidas en la carta'),
(2, 'Pizzas', 'En este grupo se ubicarán todos las variedades de pizzas'),
(3, 'Baguette', 'variedad de sandwiches'),
(4, 'Sandwiches', 'En este grupo se ubicarán todas los sandwiches'),
(5, 'Menú', 'Medio dia  de lunes a viernes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_comandas`
--

CREATE TABLE `tbl_comandas` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Jornada_id` int(11) NOT NULL,
  `Mesa_id` int(5) NOT NULL,
  `Cant_personas` int(3) NOT NULL,
  `Cliente_referente` varchar(100) NOT NULL,
  `Fecha` date NOT NULL,
  `Hora_llegada` time NOT NULL,
  `Hora_entrada_en_mesa` time DEFAULT NULL,
  `Hora_menu_en_mesa` time DEFAULT NULL,
  `Hora_cierre_comanda` time DEFAULT NULL,
  `Moso_id` int(5) NOT NULL,
  `Valor_cuenta` int(5) NOT NULL DEFAULT '0',
  `Valor_descuento` int(5) NOT NULL DEFAULT '0',
  `Valor_adicional` int(5) NOT NULL,
  `Estado` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_comandas_timeline`
--

CREATE TABLE `tbl_comandas_timeline` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Comanda_id` int(11) NOT NULL,
  `Accion` int(1) NOT NULL,
  `Hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_delibery`
--

CREATE TABLE `tbl_delibery` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Jornada_id` int(11) NOT NULL,
  `Repartidor_id` int(11) NOT NULL,
  `FechaHora_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaHora_salidarepartidor` datetime DEFAULT NULL,
  `Nombre_cliente` varchar(200) NOT NULL,
  `Direccion` varchar(200) DEFAULT NULL,
  `Telefono` int(15) DEFAULT NULL,
  `Valor_cuenta` int(10) DEFAULT NULL,
  `Valor_descuento` int(10) DEFAULT NULL,
  `Estado` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_delibery_items`
--

CREATE TABLE `tbl_delibery_items` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Delibery_id` int(11) NOT NULL,
  `Item_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_delibery_timeline`
--

CREATE TABLE `tbl_delibery_timeline` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Delivery_id` int(11) NOT NULL,
  `Accion` int(11) NOT NULL,
  `Fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_items_carta`
--

CREATE TABLE `tbl_items_carta` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Categoria_id` int(11) NOT NULL,
  `Apto_delivery` int(1) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Imagen` varchar(300) DEFAULT NULL,
  `Descripcion` text NOT NULL,
  `Precio` int(5) NOT NULL,
  `Tiempo_estimado_entrega` int(3) NOT NULL,
  `Fecha_actualizado` date NOT NULL,
  `Activo` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_items_carta`
--

INSERT INTO `tbl_items_carta` (`Id`, `Categoria_id`, `Apto_delivery`, `Nombre`, `Imagen`, `Descripcion`, `Precio`, `Tiempo_estimado_entrega`, `Fecha_actualizado`, `Activo`) VALUES
(1, 2, 1, 'Piza Mozarella', 'e65aa044413de0ee5e9a527d2a3f269f.jpg', '', 150, 15, '2018-11-06', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_items_comanda`
--

CREATE TABLE `tbl_items_comanda` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Comanda_id` int(11) NOT NULL,
  `Item_carga_id` int(11) NOT NULL,
  `Hora_entregado` varchar(20) NOT NULL,
  `Estado` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_jornadas`
--

CREATE TABLE `tbl_jornadas` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Descripcion` text,
  `Fecha_inicio` datetime DEFAULT NULL,
  `Fecha_final` datetime DEFAULT NULL,
  `Efectivo_caja_inicio` int(11) DEFAULT NULL,
  `Efectivo_caja_final` int(11) DEFAULT NULL,
  `Valor_ventas` int(11) DEFAULT NULL,
  `Valor_movimientos_caja` int(11) DEFAULT NULL,
  `Usuario_id` int(11) DEFAULT NULL,
  `Estado` int(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_log_usuarios`
--

CREATE TABLE `tbl_log_usuarios` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Jornada_id` int(11) NOT NULL,
  `Colaborador_id` int(11) NOT NULL,
  `Fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Accion` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_mesas`
--

CREATE TABLE `tbl_mesas` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Identificador` varchar(20) NOT NULL,
  `Descripcion` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_mesas`
--

INSERT INTO `tbl_mesas` (`Id`, `Identificador`, `Descripcion`) VALUES
(1, '1', ''),
(2, '2', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_planificaciones`
--

CREATE TABLE `tbl_planificaciones` (
  `Id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `start` varchar(150) NOT NULL,
  `end` varchar(150) NOT NULL,
  `className` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_planificaciones`
--

INSERT INTO `tbl_planificaciones` (`Id`, `title`, `start`, `end`, `className`) VALUES
(1, 'Prueba ', 'Wed Nov 07 2018 03:00:00 GMT-0300 (hora estándar de Argentina)', 'Thu Nov 08 2018 03:00:00 GMT-0300 (hora estándar de Argentina)', 'bg-success');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nombre_rol` varchar(20) NOT NULL,
  `Acceso` int(1) NOT NULL,
  `Descripcion` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_roles`
--

INSERT INTO `tbl_roles` (`Id`, `Nombre_rol`, `Acceso`, `Descripcion`) VALUES
(1, 'Colaborador', 1, 'Acceso a todo el sistema'),
(2, 'Mozo, Camarero', 2, 'Acceso al control de pedidos'),
(3, 'Cheff', 3, 'Acceso a sus propios pedidos'),
(4, 'Administrador', 4, 'Algunos accesos '),
(5, 'Delivery', 1, 'Personal de repartos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `DNI` int(7) NOT NULL,
  `Pass` varchar(8) NOT NULL,
  `Rol_id` int(1) NOT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Telefono` varchar(30) NOT NULL,
  `Fecha_nacimiento` date DEFAULT NULL,
  `Domicilio` varchar(250) DEFAULT NULL,
  `Nacionalidad` varchar(50) DEFAULT NULL,
  `Genero` int(11) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Obra_social` varchar(200) DEFAULT NULL,
  `Numero_obra_social` varchar(200) DEFAULT NULL,
  `Hijos` int(2) DEFAULT NULL,
  `Estado_civil` varchar(10) DEFAULT NULL,
  `Datos_persona_contacto` text,
  `Datos_bancarios` text,
  `Periodo_liquidacion_sueldo` varchar(10) DEFAULT NULL,
  `Horario_laboral` text,
  `Lider` int(1) DEFAULT NULL,
  `Superior_inmediato` int(11) DEFAULT NULL,
  `Observaciones` text,
  `Presencia` int(1) NOT NULL DEFAULT '0',
  `Fecha_alta` date DEFAULT NULL,
  `Fecha_baja` date DEFAULT NULL,
  `Activo` int(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`Id`, `Nombre`, `DNI`, `Pass`, `Rol_id`, `Imagen`, `Telefono`, `Fecha_nacimiento`, `Domicilio`, `Nacionalidad`, `Genero`, `Email`, `Obra_social`, `Numero_obra_social`, `Hijos`, `Estado_civil`, `Datos_persona_contacto`, `Datos_bancarios`, `Periodo_liquidacion_sueldo`, `Horario_laboral`, `Lider`, `Superior_inmediato`, `Observaciones`, `Presencia`, `Fecha_alta`, `Fecha_baja`, `Activo`) VALUES
(1, 'Rodrigo Medina', 35994086, '123456', 4, NULL, '1121928852', '2018-09-24', 'Cordero 728 - Adrogué - Buenos Aires', 'Argentino', 2, 'rodramedina@hotmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '', 0, '2019-08-14', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios_formacion`
--

CREATE TABLE `tbl_usuarios_formacion` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Usuario_Id` int(11) NOT NULL,
  `Titulo` varchar(200) NOT NULL,
  `Establecimiento` varchar(200) NOT NULL,
  `Anio_inicio` date NOT NULL,
  `Anio_finalizado` date DEFAULT NULL,
  `Descripcion_titulo` text,
  `Imagen` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios_sueldos`
--

CREATE TABLE `tbl_usuarios_sueldos` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Sueldo_pactado` int(6) DEFAULT NULL,
  `Sueldo_abonado` int(6) DEFAULT NULL,
  `Bonificacion` int(6) DEFAULT NULL,
  `Descuento` int(6) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Costes_impositivos_adicionales` int(6) DEFAULT NULL,
  `Observaciones` text,
  `Usuario_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_caja`
--
ALTER TABLE `tbl_caja`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_categorias`
--
ALTER TABLE `tbl_categorias`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_comandas`
--
ALTER TABLE `tbl_comandas`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_comandas_timeline`
--
ALTER TABLE `tbl_comandas_timeline`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_delibery`
--
ALTER TABLE `tbl_delibery`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_delibery_items`
--
ALTER TABLE `tbl_delibery_items`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_delibery_timeline`
--
ALTER TABLE `tbl_delibery_timeline`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_items_carta`
--
ALTER TABLE `tbl_items_carta`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_items_comanda`
--
ALTER TABLE `tbl_items_comanda`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_jornadas`
--
ALTER TABLE `tbl_jornadas`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_log_usuarios`
--
ALTER TABLE `tbl_log_usuarios`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_mesas`
--
ALTER TABLE `tbl_mesas`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_planificaciones`
--
ALTER TABLE `tbl_planificaciones`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_usuarios_formacion`
--
ALTER TABLE `tbl_usuarios_formacion`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_usuarios_sueldos`
--
ALTER TABLE `tbl_usuarios_sueldos`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_caja`
--
ALTER TABLE `tbl_caja`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_categorias`
--
ALTER TABLE `tbl_categorias`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_comandas`
--
ALTER TABLE `tbl_comandas`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_comandas_timeline`
--
ALTER TABLE `tbl_comandas_timeline`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_delibery`
--
ALTER TABLE `tbl_delibery`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_delibery_items`
--
ALTER TABLE `tbl_delibery_items`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_delibery_timeline`
--
ALTER TABLE `tbl_delibery_timeline`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_items_carta`
--
ALTER TABLE `tbl_items_carta`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_items_comanda`
--
ALTER TABLE `tbl_items_comanda`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_jornadas`
--
ALTER TABLE `tbl_jornadas`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_log_usuarios`
--
ALTER TABLE `tbl_log_usuarios`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_mesas`
--
ALTER TABLE `tbl_mesas`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_planificaciones`
--
ALTER TABLE `tbl_planificaciones`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios_formacion`
--
ALTER TABLE `tbl_usuarios_formacion`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios_sueldos`
--
ALTER TABLE `tbl_usuarios_sueldos`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
