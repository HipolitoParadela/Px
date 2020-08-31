-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 03-03-2019 a las 19:21:03
-- Versión del servidor: 5.6.42-log
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
-- Base de datos: `c1481017_erp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_clientes`
--

CREATE TABLE `tbl_clientes` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre_cliente` varchar(200) NOT NULL,
  `CUIT_CUIL` varchar(30) DEFAULT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Producto_servicio` text NOT NULL,
  `Direccion` varchar(200) NOT NULL,
  `Localidad` varchar(20) NOT NULL,
  `Provincia` varchar(20) NOT NULL,
  `Pais` varchar(20) NOT NULL,
  `Telefono` varchar(50) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Web` varchar(50) DEFAULT NULL,
  `Nombre_persona_contacto` varchar(200) DEFAULT NULL,
  `Datos_persona_contacto` text,
  `Mas_datos_cliente` text,
  `Visible` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_clientes`
--

INSERT INTO `tbl_clientes` (`Id`, `Nombre_cliente`, `CUIT_CUIL`, `Imagen`, `Producto_servicio`, `Direccion`, `Localidad`, `Provincia`, `Pais`, `Telefono`, `Email`, `Web`, `Nombre_persona_contacto`, `Datos_persona_contacto`, `Mas_datos_cliente`, `Visible`) VALUES
(1, 'Cliente de prueba', '123456789', NULL, 'ñsdalfkj', 'Rio segundo', 'rio segundo', 'cordoba', 'Argentina', '3572408000', NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_clientes_seguimiento`
--

CREATE TABLE `tbl_clientes_seguimiento` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Id_cliente` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Descripcion` text NOT NULL,
  `Usuarios_id` int(11) NOT NULL,
  `Visible` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_compras`
--

CREATE TABLE `tbl_compras` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Proveedor_id` int(11) NOT NULL,
  `Fecha_compra` date NOT NULL,
  `Factura_identificador` varchar(50) DEFAULT NULL,
  `Valor` varchar(10) DEFAULT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Usuario_id` int(11) NOT NULL,
  `Descripcion` text,
  `Ultima_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Visible` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_empresas`
--

CREATE TABLE `tbl_empresas` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nombre_empresa` varchar(100) NOT NULL,
  `Descripcion` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_empresas`
--

INSERT INTO `tbl_empresas` (`Id`, `Nombre_empresa`, `Descripcion`) VALUES
(1, 'Ingeniería en mobiliario urbano', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_fabricacion`
--

CREATE TABLE `tbl_fabricacion` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Codigo_interno` varchar(10) DEFAULT NULL,
  `Categoria_fabricacion_id` int(11) NOT NULL,
  `Nombre_producto` varchar(100) NOT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Descripcion_publica_corta` text,
  `Descripcion_publica_larga` text,
  `Descripcion_tecnica_privada` text,
  `Visible` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_fabricacion`
--

INSERT INTO `tbl_fabricacion` (`Id`, `Codigo_interno`, `Categoria_fabricacion_id`, `Nombre_producto`, `Imagen`, `Descripcion_publica_corta`, `Descripcion_publica_larga`, `Descripcion_tecnica_privada`, `Visible`) VALUES
(1, '123', 1, 'Producto de prueba', 'bd3e8a5ce691e78ffc0451f3e59bbc15.png', 'Pasd asdñlkasf ñlkjsfd', 'ñadslkf as3e  {ñlaksfd', 'ñsaldkfj asdfowealskjdf ', 1),
(2, '123654', 1, 'Segundo producto de prueba', NULL, 'añlksdjf', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_fabricacion_archivos`
--

CREATE TABLE `tbl_fabricacion_archivos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Producto_id` int(11) NOT NULL,
  `Nombre_archivo` varchar(200) NOT NULL,
  `Url_archivo` varchar(200) NOT NULL,
  `Descripcion` text,
  `Fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Usuario_id` int(11) NOT NULL,
  `Visible` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_fabricacion_archivos`
--

INSERT INTO `tbl_fabricacion_archivos` (`Id`, `Producto_id`, `Nombre_archivo`, `Url_archivo`, `Descripcion`, `Fecha_hora`, `Usuario_id`, `Visible`) VALUES
(1, 1, 'Archivo de prueba', 'a018e4e474bd974996771e153e00bb42.pdf', NULL, '2019-02-05 17:25:29', 1, 0),
(2, 1, 'prueba 2', '', NULL, '2019-02-05 17:28:12', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_fabricacion_categorias`
--

CREATE TABLE `tbl_fabricacion_categorias` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre_categoria` varchar(200) NOT NULL,
  `Descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_fabricacion_categorias`
--

INSERT INTO `tbl_fabricacion_categorias` (`Id`, `Nombre_categoria`, `Descripcion`) VALUES
(1, 'Cat. de Prueba', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_fabricacion_insumos_producto`
--

CREATE TABLE `tbl_fabricacion_insumos_producto` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Fabricacion_id` int(11) NOT NULL,
  `Stock_id` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Observaciones` text,
  `Ultima_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Visible` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_orden_trabajos`
--

CREATE TABLE `tbl_orden_trabajos` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Producto_id` int(11) NOT NULL,
  `Subproducto_de_id` int(11) DEFAULT NULL,
  `Usuario_respondable_id` int(11) NOT NULL,
  `Cliente_id` int(11) NOT NULL,
  `Numero_pieza` varchar(100) NOT NULL,
  `Fecha_inicio` date NOT NULL,
  `Fecha_estimada_finalizacion` date DEFAULT NULL,
  `Fecha_finalizado` date DEFAULT NULL,
  `Fecha_despacho_cliente` date DEFAULT NULL,
  `Observaciones` text,
  `Usuario_id` int(11) NOT NULL,
  `Fecha_publicacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Ultima_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Estado` int(1) NOT NULL DEFAULT '0',
  `Visible` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_orden_trabajos_seguimiento`
--

CREATE TABLE `tbl_orden_trabajos_seguimiento` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Orden_id` int(11) NOT NULL,
  `Fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Usuario_id` int(11) NOT NULL,
  `Descripcion` text NOT NULL,
  `Visible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_proveedores`
--

CREATE TABLE `tbl_proveedores` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Nombre_proveedor` varchar(200) NOT NULL,
  `CUIT_CUIL` varchar(30) DEFAULT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Producto_servicio` text NOT NULL,
  `Direccion` varchar(200) NOT NULL,
  `Localidad` varchar(20) NOT NULL,
  `Provincia` varchar(20) NOT NULL,
  `Pais` varchar(20) NOT NULL,
  `Telefono` varchar(50) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Web` varchar(50) DEFAULT NULL,
  `URL_facebook` varchar(200) DEFAULT NULL,
  `Nombre_persona_contacto` varchar(200) DEFAULT NULL,
  `Datos_persona_contacto` text,
  `Mas_datos_proveedor` text,
  `Visible` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_proveedores`
--

INSERT INTO `tbl_proveedores` (`Id`, `Nombre_proveedor`, `CUIT_CUIL`, `Imagen`, `Producto_servicio`, `Direccion`, `Localidad`, `Provincia`, `Pais`, `Telefono`, `Email`, `Web`, `URL_facebook`, `Nombre_persona_contacto`, `Datos_persona_contacto`, `Mas_datos_proveedor`, `Visible`) VALUES
(1, 'ELECTROCENTRO - SERGIO GONZALEZ', '2025178620', NULL, 'materiales electricos e iluminacion', '9 de julio 940', 'PILAR', 'CORDOBA', 'ARGENTINA', '03572472222', 'electro_centro@hotmail.com', '', NULL, '', '', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_proveedores_seguimiento`
--

CREATE TABLE `tbl_proveedores_seguimiento` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Id_proveedor` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Descripcion` text NOT NULL,
  `Url_archivo` varchar(250) DEFAULT NULL,
  `Usuarios_id` int(11) NOT NULL,
  `Visible` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_proveedor_vinculo_categorias_producto`
--

CREATE TABLE `tbl_proveedor_vinculo_categorias_producto` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Proveedor_id` int(11) NOT NULL,
  `Categoria_id` int(11) NOT NULL,
  `Descripcion` text,
  `Visible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_puestos`
--

CREATE TABLE `tbl_puestos` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nombre_puesto` varchar(100) NOT NULL,
  `Descripcion` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
(1, 'Superadmin', 5, 'Usuario con acceso total, incluso configuraciones importantes del sistema. '),
(2, 'Administrador', 4, 'Usuario con acceso a todo, menos configuraciones importantes del sistema');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_stock`
--

CREATE TABLE `tbl_stock` (
  `Id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `Nombre_item` varchar(200) NOT NULL,
  `Categoria_id` int(2) NOT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Descripcion` text,
  `Cant_actual` int(5) DEFAULT NULL,
  `Cant_ideal` int(5) DEFAULT NULL,
  `Ult_modificacion_id` int(11) DEFAULT NULL,
  `Visible` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_stock`
--

INSERT INTO `tbl_stock` (`Id`, `Nombre_item`, `Categoria_id`, `Imagen`, `Descripcion`, `Cant_actual`, `Cant_ideal`, `Ult_modificacion_id`, `Visible`) VALUES
(0001, 'Producto de prueba', 1, NULL, 'Editar o eliminar', -4, 10, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_stock_categorias`
--

CREATE TABLE `tbl_stock_categorias` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nombre_categoria` varchar(100) NOT NULL,
  `Descripcion` text,
  `Imagen_categoria` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_stock_categorias`
--

INSERT INTO `tbl_stock_categorias` (`Id`, `Nombre_categoria`, `Descripcion`, `Imagen_categoria`) VALUES
(1, 'Categoría de prueba', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_stock_movimientos`
--

CREATE TABLE `tbl_stock_movimientos` (
  `Id` int(5) UNSIGNED NOT NULL,
  `Stock_id` int(5) NOT NULL,
  `Fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Cantidad` int(5) NOT NULL,
  `Tipo_movimiento` int(1) DEFAULT NULL,
  `Descripcion` text,
  `Usuario_id` int(2) NOT NULL,
  `Proceso_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_stock_movimientos`
--

INSERT INTO `tbl_stock_movimientos` (`Id`, `Stock_id`, `Fecha_hora`, `Cantidad`, `Tipo_movimiento`, `Descripcion`, `Usuario_id`, `Proceso_id`) VALUES
(1, 1, '2019-01-31 00:57:50', 4, 2, 'asadfasf', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_stock_vinculo_proveedor`
--

CREATE TABLE `tbl_stock_vinculo_proveedor` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Stock_id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `Proveedor_id` int(11) NOT NULL,
  `Descripcion` text,
  `Visible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `DNI` int(7) NOT NULL,
  `CUIT_CUIL` varchar(30) DEFAULT NULL,
  `Pass` varchar(8) NOT NULL,
  `Rol_acceso` int(1) NOT NULL,
  `Empresa_id` int(11) DEFAULT NULL,
  `Puesto_Id` int(11) DEFAULT NULL,
  `Imagen` varchar(200) DEFAULT NULL,
  `Telefono` varchar(30) DEFAULT NULL,
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
  `Activo` int(1) NOT NULL DEFAULT '1',
  `Ultima_actualizacion` date NOT NULL,
  `Ultimo_editor_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`Id`, `Nombre`, `DNI`, `CUIT_CUIL`, `Pass`, `Rol_acceso`, `Empresa_id`, `Puesto_Id`, `Imagen`, `Telefono`, `Fecha_nacimiento`, `Domicilio`, `Nacionalidad`, `Genero`, `Email`, `Obra_social`, `Numero_obra_social`, `Hijos`, `Estado_civil`, `Datos_persona_contacto`, `Datos_bancarios`, `Periodo_liquidacion_sueldo`, `Horario_laboral`, `Lider`, `Superior_inmediato`, `Observaciones`, `Presencia`, `Fecha_alta`, `Fecha_baja`, `Activo`, `Ultima_actualizacion`, `Ultimo_editor_id`) VALUES
(1, 'Administrador', 98765432, NULL, '123456', 5, NULL, NULL, 'f1f46815d68d38585cd7208c6eb3d800.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '2019-01-29', NULL),
(2, 'Adriana', 88888888, NULL, 'flamini', 5, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '2019-01-30', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ventas`
--

CREATE TABLE `tbl_ventas` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Identificador_venta` varchar(30) DEFAULT NULL,
  `Empresa_id` int(11) NOT NULL,
  `Cliente_id` int(5) NOT NULL,
  `Vendedor_id` int(5) NOT NULL,
  `Fecha_venta` date NOT NULL,
  `Fecha_estimada_entrega` date NOT NULL,
  `Observaciones_venta` text,
  `Descuento` int(11) DEFAULT NULL,
  `Recargo` int(11) DEFAULT NULL,
  `Monto_cobrado` int(11) DEFAULT NULL,
  `Responsable_id_planif_inicial` int(11) NOT NULL,
  `Eval_planif_inicial` int(11) DEFAULT NULL,
  `Responsable_id_planif_final` int(11) NOT NULL,
  `Eval_planif_final` int(11) DEFAULT NULL,
  `Responsable_id_logistica` int(11) DEFAULT NULL,
  `Info_logistica` text,
  `Responsable_id_instalacion` int(11) DEFAULT NULL,
  `Info_instalaciones` text,
  `Responsable_id_cobranza` int(11) DEFAULT NULL,
  `Info_cobranza` text,
  `Fecha_finalizada` date DEFAULT NULL,
  `Observaciones_entrega` int(11) DEFAULT NULL,
  `Usuario_id` int(11) NOT NULL,
  `Fecha_ultima_edicion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Estado` int(11) DEFAULT '1',
  `Prioritario` int(1) DEFAULT '0',
  `Lote_abastecido` int(1) DEFAULT '0',
  `Visible` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_ventas`
--

INSERT INTO `tbl_ventas` (`Id`, `Identificador_venta`, `Empresa_id`, `Cliente_id`, `Vendedor_id`, `Fecha_venta`, `Fecha_estimada_entrega`, `Observaciones_venta`, `Descuento`, `Recargo`, `Monto_cobrado`, `Responsable_id_planif_inicial`, `Eval_planif_inicial`, `Responsable_id_planif_final`, `Eval_planif_final`, `Responsable_id_logistica`, `Info_logistica`, `Responsable_id_instalacion`, `Info_instalaciones`, `Responsable_id_cobranza`, `Info_cobranza`, `Fecha_finalizada`, `Observaciones_entrega`, `Usuario_id`, `Fecha_ultima_edicion`, `Estado`, `Prioritario`, `Lote_abastecido`, `Visible`) VALUES
(1, '20190227-01', 1, 1, 2, '2019-01-30', '2019-02-03', 'Sin editar', NULL, NULL, NULL, 2, NULL, 1, NULL, 2, 'Es necesario contactarse con Juan Perez, encargado de Obras. Teléfono 357245875478', 1, 'No hay que instalar este lote', 2, 'El cobro se realiza con cheque', '2019-03-03', NULL, 1, '2019-03-03 21:14:45', 10, 1, 0, 1),
(2, '20190228-01', 1, 1, 1, '2019-01-31', '2019-03-13', NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 1, NULL, 2, NULL, 2, NULL, NULL, NULL, 1, '2019-03-03 14:24:24', 7, 0, 0, 1),
(3, '20190227-02', 1, 1, 1, '2019-02-04', '2019-02-28', 'ñalskdjfcn añsldkjf', NULL, NULL, NULL, 1, NULL, 2, NULL, 1, 'ñalsdkfj, ', 2, 'Sin colocación', 1, 'fd gsdñflgksjdfg ', NULL, NULL, 1, '2019-03-01 15:32:15', 10, 0, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ventas_productos`
--

CREATE TABLE `tbl_ventas_productos` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Venta_id` int(11) NOT NULL,
  `Producto_id` int(11) NOT NULL,
  `Observaciones` text,
  `Estado` int(11) NOT NULL DEFAULT '1',
  `S_1_Requerimientos` text,
  `S_1_Fecha_finalizado` date DEFAULT NULL,
  `S_1_Observaciones` text,
  `S_2_Requerimientos` text,
  `S_2_Fecha_finalizado` date DEFAULT NULL,
  `S_2_Observaciones` text,
  `S_3_Requerimientos` text,
  `S_3_Fecha_finalizado` date DEFAULT NULL,
  `S_3_Observaciones` text,
  `S_4_Requerimientos` text,
  `S_4_Fecha_finalizado` date DEFAULT NULL,
  `S_4_Observaciones` text,
  `S_5_Requerimientos` text,
  `S_5_Fecha_finalizado` date DEFAULT NULL,
  `S_5_Observaciones` text,
  `S_6_Requerimientos` text,
  `S_6_Fecha_finalizado` date DEFAULT NULL,
  `S_6_Observaciones` text,
  `S_7_Requerimientos` text,
  `S_7_Fecha_finalizado` date DEFAULT NULL,
  `S_7_Observaciones` text,
  `Usuario_id` int(11) NOT NULL,
  `Visible` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_ventas_productos`
--

INSERT INTO `tbl_ventas_productos` (`Id`, `Venta_id`, `Producto_id`, `Observaciones`, `Estado`, `S_1_Requerimientos`, `S_1_Fecha_finalizado`, `S_1_Observaciones`, `S_2_Requerimientos`, `S_2_Fecha_finalizado`, `S_2_Observaciones`, `S_3_Requerimientos`, `S_3_Fecha_finalizado`, `S_3_Observaciones`, `S_4_Requerimientos`, `S_4_Fecha_finalizado`, `S_4_Observaciones`, `S_5_Requerimientos`, `S_5_Fecha_finalizado`, `S_5_Observaciones`, `S_6_Requerimientos`, `S_6_Fecha_finalizado`, `S_6_Observaciones`, `S_7_Requerimientos`, `S_7_Fecha_finalizado`, `S_7_Observaciones`, `Usuario_id`, `Visible`) VALUES
(6, 1, 1, 'asdfasdf', 7, 'asdfasdf', '2019-02-27', 'asdf', 'asdfasdf', '2019-02-27', 'asdf', 'asdf', '2019-02-28', 'Soldaduira lista', 'asdf', '2019-02-28', 'Soldaduira lista', 'asdf', '2019-02-28', 'Soldaduira lista', 'asdf', '2019-02-28', 'Soldaduira lista', 'asdf', NULL, NULL, 1, 1),
(7, 1, 1, 'asdfasdf', 4, 'asdfasdf', '2019-02-27', 'asdf', 'asdfasdf', '2019-02-27', 'asdf', 'asdf', '2019-02-27', 'asdf', 'asdf', NULL, NULL, 'asdf', NULL, NULL, 'asdf', NULL, NULL, 'asdf', NULL, NULL, 1, 1),
(8, 1, 1, 'asdf', 1, 'asdf', NULL, NULL, 'asdf', NULL, NULL, 'asdf', NULL, NULL, 'asdf', NULL, NULL, 'asdf', NULL, NULL, 'asdf', NULL, NULL, 'sadfasfd', NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ventas_seguimiento`
--

CREATE TABLE `tbl_ventas_seguimiento` (
  `Id` int(11) UNSIGNED NOT NULL,
  `Categoria_seguimiento` int(11) NOT NULL,
  `Venta_id` int(11) NOT NULL,
  `Fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Descripcion` text NOT NULL,
  `Url_archivo` varchar(250) DEFAULT NULL,
  `Usuario_id` int(11) NOT NULL,
  `Visible` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_ventas_seguimiento`
--

INSERT INTO `tbl_ventas_seguimiento` (`Id`, `Categoria_seguimiento`, `Venta_id`, `Fecha`, `Descripcion`, `Url_archivo`, `Usuario_id`, `Visible`) VALUES
(1, 0, 2, '0000-00-00 00:00:00', 'Inicio de fabricación', NULL, 1, 1),
(2, 0, 1, '2019-01-31 02:48:54', 'Se actualizó la ficha de la venta, editada', NULL, 1, 1),
(3, 0, 1, '2019-01-31 01:38:28', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(4, 0, 1, '2019-01-31 01:38:38', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(5, 0, 1, '2019-01-31 01:40:28', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(6, 0, 1, '2019-01-31 01:41:10', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(7, 0, 1, '2019-01-31 01:42:27', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(8, 0, 1, '2019-01-31 02:56:19', 'Se actualizó la ficha de la venta', '19e184e980ab3fddbcb7b6889acf8799.jpg', 1, 1),
(9, 0, 1, '2019-01-31 01:45:15', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(10, 0, 1, '2019-01-31 02:57:56', 'Probando seguimiento con carga de archivo', 'c6200a05946d771f4286e09b82299414.jpg', 1, 1),
(11, 0, 1, '2019-01-31 04:19:28', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(12, 0, 1, '2019-01-31 04:27:59', 'CADA VEZ QUE SE MARCA UN PRODUCTO COMO PASADO A LA SIGUIENTE ETAPA, DEBE GENERAR UN REPORTE. Y TAMBIEN, \n\nDEBE GENERAR REPORTE CUANDO SE AUTORICE A PASAR AL SIGUIENTE PUESTO TODO EL LOTE.', NULL, 1, 1),
(13, 0, 1, '2019-02-05 17:14:00', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(14, 0, 3, '2019-02-05 19:28:19', 'Inicio de fabricación', NULL, 1, 1),
(15, 0, 1, '2019-02-05 19:29:28', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(16, 0, 1, '2019-02-05 19:37:39', 'Recibi factura tanto, por el monto 321654 ', 'af512615b7080bcbb594d107835d25eb.jpg', 1, 1),
(17, 0, 1, '2019-02-06 00:20:17', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(18, 0, 1, '2019-02-27 00:35:27', 'Se actualizó la ficha de la venta', NULL, 1, 1),
(19, 0, 3, '2019-02-27 17:33:10', 'Se actualizaron los datos en la ficha de la venta', NULL, 1, 1),
(20, 2, 4, '2019-02-28 02:36:42', 'Avanzó el lote a la estación de Proceso de Materiales', NULL, 1, 1),
(21, 2, 5, '2019-02-28 02:36:54', 'Avanzó el lote a la estación de Proceso de Materiales', NULL, 1, 1),
(22, 2, 6, '2019-02-28 02:37:07', 'Avanzó el lote a la estación de Proceso de Materiales', NULL, 1, 1),
(23, 2, 7, '2019-02-28 02:38:20', 'Avanzó el lote a la estación de Proceso de Materiales', NULL, 1, 1),
(24, 0, 1, '2019-02-28 02:38:41', 'Se actualizaron los datos en la ficha de la venta', NULL, 1, 1),
(25, 2, 8, '2019-02-28 02:38:52', 'Avanzó el lote a la estación de Proceso de Materiales', NULL, 1, 1),
(26, 2, 1, '2019-02-28 02:40:07', 'Avanzó el lote a la estación de Soldadura', NULL, 1, 1),
(27, 2, 1, '2019-02-28 02:40:22', 'Avanzó el lote a la estación de Pintura', NULL, 1, 1),
(28, 2, 1, '2019-02-28 02:41:10', 'Avanzó el lote a la estación de Rotulación', NULL, 1, 1),
(29, 0, 1, '2019-02-28 03:08:00', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(30, 0, 1, '2019-02-28 03:10:05', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(31, 2, 1, '2019-02-28 03:10:34', 'Avanzó el lote a la estación de Empaque', NULL, 1, 1),
(32, 3, 1, '2019-02-28 03:40:49', 'Probado ver solo logistica', NULL, 1, 1),
(33, 3, 1, '2019-03-01 01:05:00', ' añsldkfjañ sldkfjasdf adsf', NULL, 1, 1),
(34, 3, 1, '2019-03-01 01:09:36', 'sdfgsdfgsdf', NULL, 1, 1),
(35, 1, 1, '2019-03-01 01:14:25', 'compras', NULL, 1, 1),
(36, 3, 1, '2019-03-01 01:16:24', 'problemas con la entrega', NULL, 1, 1),
(37, 2, 1, '2019-03-01 01:16:50', 'Demora por corte elèctrico', NULL, 1, 1),
(38, 3, 1, '2019-03-01 01:17:50', 'logistica\n', NULL, 1, 1),
(39, 2, 1, '2019-03-01 01:18:10', 'produccion', NULL, 1, 1),
(40, 5, 1, '2019-03-01 01:19:17', 'cobranza', NULL, 1, 1),
(41, 0, 1, '2019-03-01 01:28:33', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(42, 0, 1, '2019-03-01 01:33:21', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(43, 0, 1, '2019-03-01 01:53:40', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(44, 5, 1, '2019-03-01 15:22:37', 'Primero cobro realizado', NULL, 1, 1),
(45, 0, 2, '2019-03-01 15:31:55', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(46, 0, 3, '2019-03-01 15:32:15', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(47, 2, 1, '2019-03-02 23:26:14', 'Finalizó el proceso de producción de este lote.', NULL, 1, 1),
(48, 2, 2, '2019-03-02 23:28:16', 'Avanzó el lote a la estación de Proceso de Materiales', NULL, 1, 1),
(49, 2, 2, '2019-03-02 23:28:35', 'Avanzó el lote a la estación de Soldadura', NULL, 1, 1),
(50, 2, 2, '2019-03-02 23:29:04', 'Avanzó el lote a la estación de Pintura', NULL, 1, 1),
(51, 2, 2, '2019-03-02 23:30:13', 'Avanzó el lote a la estación de Rotulación', NULL, 1, 1),
(52, 2, 2, '2019-03-02 23:32:40', 'Avanzó el lote a la estación de Empaque', NULL, 1, 1),
(53, 2, 2, '2019-03-02 23:32:44', 'Finalizó el proceso de producción de este lote. Continúa en Logística.', NULL, 1, 1),
(54, 0, 2, '2019-03-03 14:24:24', 'Se actualizaron los datos en la ficha de esta venta.', NULL, 1, 1),
(55, 2, 1, '2019-03-03 21:12:20', 'Finalizó el proceso de producción de este lote. Continúa en Logística.', NULL, 1, 1),
(56, 2, 1, '2019-03-03 21:12:27', 'Logística finalizada.', NULL, 1, 1),
(57, 2, 1, '2019-03-03 21:13:59', 'Instalación finalizada.', NULL, 1, 1),
(58, 2, 1, '2019-03-03 21:14:45', 'Cobranza finalizada.', NULL, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_clientes`
--
ALTER TABLE `tbl_clientes`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_clientes_seguimiento`
--
ALTER TABLE `tbl_clientes_seguimiento`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_compras`
--
ALTER TABLE `tbl_compras`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_empresas`
--
ALTER TABLE `tbl_empresas`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_fabricacion`
--
ALTER TABLE `tbl_fabricacion`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_fabricacion_archivos`
--
ALTER TABLE `tbl_fabricacion_archivos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_fabricacion_categorias`
--
ALTER TABLE `tbl_fabricacion_categorias`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_fabricacion_insumos_producto`
--
ALTER TABLE `tbl_fabricacion_insumos_producto`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_orden_trabajos`
--
ALTER TABLE `tbl_orden_trabajos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_orden_trabajos_seguimiento`
--
ALTER TABLE `tbl_orden_trabajos_seguimiento`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_proveedores`
--
ALTER TABLE `tbl_proveedores`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_proveedores_seguimiento`
--
ALTER TABLE `tbl_proveedores_seguimiento`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_proveedor_vinculo_categorias_producto`
--
ALTER TABLE `tbl_proveedor_vinculo_categorias_producto`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_puestos`
--
ALTER TABLE `tbl_puestos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_stock_categorias`
--
ALTER TABLE `tbl_stock_categorias`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_stock_movimientos`
--
ALTER TABLE `tbl_stock_movimientos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_stock_vinculo_proveedor`
--
ALTER TABLE `tbl_stock_vinculo_proveedor`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id` (`Id`);

--
-- Indices de la tabla `tbl_ventas`
--
ALTER TABLE `tbl_ventas`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_ventas_productos`
--
ALTER TABLE `tbl_ventas_productos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `tbl_ventas_seguimiento`
--
ALTER TABLE `tbl_ventas_seguimiento`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_clientes`
--
ALTER TABLE `tbl_clientes`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_clientes_seguimiento`
--
ALTER TABLE `tbl_clientes_seguimiento`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_compras`
--
ALTER TABLE `tbl_compras`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_empresas`
--
ALTER TABLE `tbl_empresas`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_fabricacion`
--
ALTER TABLE `tbl_fabricacion`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_fabricacion_archivos`
--
ALTER TABLE `tbl_fabricacion_archivos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_fabricacion_categorias`
--
ALTER TABLE `tbl_fabricacion_categorias`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_fabricacion_insumos_producto`
--
ALTER TABLE `tbl_fabricacion_insumos_producto`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_orden_trabajos`
--
ALTER TABLE `tbl_orden_trabajos`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_orden_trabajos_seguimiento`
--
ALTER TABLE `tbl_orden_trabajos_seguimiento`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_proveedores`
--
ALTER TABLE `tbl_proveedores`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_proveedores_seguimiento`
--
ALTER TABLE `tbl_proveedores_seguimiento`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_proveedor_vinculo_categorias_producto`
--
ALTER TABLE `tbl_proveedor_vinculo_categorias_producto`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_puestos`
--
ALTER TABLE `tbl_puestos`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_stock`
--
ALTER TABLE `tbl_stock`
  MODIFY `Id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_stock_categorias`
--
ALTER TABLE `tbl_stock_categorias`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_stock_movimientos`
--
ALTER TABLE `tbl_stock_movimientos`
  MODIFY `Id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_stock_vinculo_proveedor`
--
ALTER TABLE `tbl_stock_vinculo_proveedor`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_ventas`
--
ALTER TABLE `tbl_ventas`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tbl_ventas_productos`
--
ALTER TABLE `tbl_ventas_productos`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tbl_ventas_seguimiento`
--
ALTER TABLE `tbl_ventas_seguimiento`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
