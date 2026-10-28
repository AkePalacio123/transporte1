-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-08-2026 a las 03:59:26
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
-- Base de datos: `transporte`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operador_id` bigint(20) UNSIGNED NOT NULL,
  `tracto_id` bigint(20) UNSIGNED NOT NULL,
  `equipo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 1,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargas_combustible`
--

CREATE TABLE `cargas_combustible` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `viaje_id` bigint(20) UNSIGNED NOT NULL,
  `estacion` varchar(120) NOT NULL,
  `fecha` date NOT NULL,
  `litros` decimal(8,2) NOT NULL,
  `precio_litro` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `kilometraje` int(10) UNSIGNED DEFAULT NULL,
  `folio_factura` varchar(50) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `casetas`
--

CREATE TABLE `casetas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `viaje_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha` date DEFAULT NULL,
  `folio_ticket` varchar(50) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_equipo`
--

CREATE TABLE `documentos_equipo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipo_id` bigint(20) UNSIGNED NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_operador`
--

CREATE TABLE `documentos_operador` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operador_id` bigint(20) UNSIGNED NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_tracto`
--

CREATE TABLE `documentos_tracto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tracto_id` bigint(20) UNSIGNED NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `estatus` enum('Activa','Inactiva') NOT NULL DEFAULT 'Activa',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serie` varchar(50) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` year(4) NOT NULL,
  `tarjeta_circulacion` varchar(255) DEFAULT NULL,
  `tarjeta_pdf` varchar(255) DEFAULT NULL,
  `estatus` enum('Activo','Mantenimiento','Fuera de servicio') NOT NULL DEFAULT 'Activo',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guias`
--

CREATE TABLE `guias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tracto_id` bigint(20) UNSIGNED DEFAULT NULL,
  `equipo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo` enum('Preventivo','Correctivo') NOT NULL DEFAULT 'Preventivo',
  `estatus` enum('En taller','Finalizado','Cancelado') NOT NULL DEFAULT 'En taller',
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date DEFAULT NULL,
  `kilometraje` int(10) UNSIGNED DEFAULT NULL,
  `taller` varchar(120) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `costo_mano_obra` decimal(10,2) NOT NULL DEFAULT 0.00,
  `folio_factura` varchar(50) DEFAULT NULL,
  `factura_pdf` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_06_11_015406_create_unidads_table', 1),
(6, '2026_06_16_015951_create_operadors_table', 1),
(7, '2026_06_16_021420_create_viajes_table', 1),
(8, '2026_07_12_223843_create_tractos_table', 2),
(9, '2026_07_12_223904_create_equipos_table', 2),
(10, '2026_07_14_022644_create_asignacions_table', 2),
(11, '2026_07_19_223737_add_estatus_to_viajes_table', 3),
(12, '2026_07_19_231118_add_cataporte_to_viajes_table', 4),
(13, '2026_07_20_172557_create_empresas_table', 5),
(19, '2026_07_21_155143_update_viajes_estructura_empresa_tracto_equipo', 6),
(20, '2026_07_21_161037_update_viajes_estructura_final', 7),
(21, '2026_07_21_161120_drop_unidades_table', 7),
(22, '2026_07_21_203019_2026_07_21_180000_create_casetas_table', 8),
(23, '2026_07_21_190000_create_cargas_combustible_table', 9),
(24, '2026_07_22_180000_rename_numero_economico_and_add_tarjeta_pdf', 10),
(25, '2026_07_22_200000_create_mantenimientos_table', 11),
(26, '2026_07_23_100000_fix_asignaciones_table', 12),
(27, '2026_07_23_120000_add_rol_to_users_table', 13),
(28, '2026_08_02_112019_agregar_fecha_limite_pago_a_viajes', 14),
(29, '2026_08_02_124634_crear_documentos_operador', 15),
(30, '2026_08_02_171704_crear_documentos_tracto', 16),
(31, '2026_08_02_171727_crear_documentos_equipo', 16),
(32, '2026_08_02_174810_crear_guias', 17),
(33, '2026_08_11_173003_agregar_kilometros_a_viajes', 18),
(34, '2026_08_11_175905_agregar_modulos_permitidos_a_users', 19),
(35, '2026_08_15_194104_crear_reparaciones', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadores`
--

CREATE TABLE `operadores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `licencia` varchar(50) NOT NULL,
  `vencimiento_licencia` date NOT NULL,
  `estatus` enum('Activo','Inactivo','Suspendido') NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piezas_mantenimiento`
--

CREATE TABLE `piezas_mantenimiento` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mantenimiento_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `numero_parte` varchar(60) DEFAULT NULL,
  `proveedor` varchar(120) DEFAULT NULL,
  `cantidad` decimal(8,2) NOT NULL DEFAULT 1.00,
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparaciones`
--

CREATE TABLE `reparaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `viaje_id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `costo` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fecha` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tractos`
--

CREATE TABLE `tractos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serie` varchar(50) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` year(4) NOT NULL,
  `tarjeta_circulacion` varchar(255) DEFAULT NULL,
  `tarjeta_pdf` varchar(255) DEFAULT NULL,
  `estatus` enum('Activo','Mantenimiento','Fuera de servicio') NOT NULL DEFAULT 'Activo',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rol` enum('Administrador','Capturista','Consulta') NOT NULL DEFAULT 'Capturista',
  `modulos_permitidos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`modulos_permitidos`)),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `rol`, `modulos_permitidos`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(5, 'Tere', 'teresitapoot40@gmail.com', 'Administrador', NULL, 1, NULL, '$2y$10$FVqA57S0LqYPNE3fR83V0eToTb710vIELdLHwdIjS7JZP4rEVW5da', NULL, '2026-08-03 00:08:07', '2026-08-04 00:22:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `viajes`
--

CREATE TABLE `viajes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `folio` varchar(30) DEFAULT NULL,
  `cataporte` varchar(30) NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `tracto_id` bigint(20) UNSIGNED NOT NULL,
  `equipo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_viaje` enum('Normal','Retorno') NOT NULL DEFAULT 'Normal',
  `fecha_viaje` date NOT NULL,
  `fecha_captura` date NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `fecha_limite_pago` date DEFAULT NULL,
  `origen` varchar(150) NOT NULL,
  `destino` varchar(150) NOT NULL,
  `kilometros` int(10) UNSIGNED DEFAULT NULL,
  `pago` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estatus` enum('Pendiente','En ruta','Finalizado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `estatus_pago` enum('Pendiente','Pagado') NOT NULL DEFAULT 'Pendiente',
  `estatus_bitacora` enum('No entregada','Entregada') NOT NULL DEFAULT 'No entregada',
  `entregado_a` varchar(100) DEFAULT NULL,
  `fecha_entrega_bitacora` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `archivo_bitacora` varchar(255) DEFAULT NULL,
  `operador_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asignaciones_equipo_id_foreign` (`equipo_id`),
  ADD KEY `asignaciones_operador_id_activa_index` (`operador_id`,`activa`),
  ADD KEY `asignaciones_tracto_id_activa_index` (`tracto_id`,`activa`);

--
-- Indices de la tabla `cargas_combustible`
--
ALTER TABLE `cargas_combustible`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cargas_combustible_viaje_id_fecha_index` (`viaje_id`,`fecha`);

--
-- Indices de la tabla `casetas`
--
ALTER TABLE `casetas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `casetas_viaje_id_fecha_index` (`viaje_id`,`fecha`);

--
-- Indices de la tabla `documentos_equipo`
--
ALTER TABLE `documentos_equipo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documentos_equipo_equipo_id_foreign` (`equipo_id`);

--
-- Indices de la tabla `documentos_operador`
--
ALTER TABLE `documentos_operador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documentos_operador_operador_id_foreign` (`operador_id`);

--
-- Indices de la tabla `documentos_tracto`
--
ALTER TABLE `documentos_tracto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documentos_tracto_tracto_id_foreign` (`tracto_id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empresas_nombre_unique` (`nombre`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `equipos_numero_economico_unique` (`serie`),
  ADD UNIQUE KEY `equipos_placa_unique` (`placa`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `guias`
--
ALTER TABLE `guias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mantenimientos_tracto_id_fecha_entrada_index` (`tracto_id`,`fecha_entrada`),
  ADD KEY `mantenimientos_equipo_id_fecha_entrada_index` (`equipo_id`,`fecha_entrada`),
  ADD KEY `mantenimientos_estatus_index` (`estatus`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `operadores`
--
ALTER TABLE `operadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `operadores_licencia_unique` (`licencia`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `piezas_mantenimiento`
--
ALTER TABLE `piezas_mantenimiento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `piezas_mantenimiento_mantenimiento_id_index` (`mantenimiento_id`);

--
-- Indices de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reparaciones_viaje_id_foreign` (`viaje_id`);

--
-- Indices de la tabla `tractos`
--
ALTER TABLE `tractos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tractos_numero_economico_unique` (`serie`),
  ADD UNIQUE KEY `tractos_placa_unique` (`placa`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `viajes_folio_unique` (`folio`),
  ADD KEY `viajes_empresa_id_foreign` (`empresa_id`),
  ADD KEY `viajes_tracto_id_foreign` (`tracto_id`),
  ADD KEY `viajes_equipo_id_foreign` (`equipo_id`),
  ADD KEY `viajes_operador_id_foreign` (`operador_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargas_combustible`
--
ALTER TABLE `cargas_combustible`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `casetas`
--
ALTER TABLE `casetas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_equipo`
--
ALTER TABLE `documentos_equipo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_operador`
--
ALTER TABLE `documentos_operador`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_tracto`
--
ALTER TABLE `documentos_tracto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guias`
--
ALTER TABLE `guias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `operadores`
--
ALTER TABLE `operadores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `piezas_mantenimiento`
--
ALTER TABLE `piezas_mantenimiento`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tractos`
--
ALTER TABLE `tractos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `viajes`
--
ALTER TABLE `viajes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `asignaciones_operador_id_foreign` FOREIGN KEY (`operador_id`) REFERENCES `operadores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `asignaciones_tracto_id_foreign` FOREIGN KEY (`tracto_id`) REFERENCES `tractos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `cargas_combustible`
--
ALTER TABLE `cargas_combustible`
  ADD CONSTRAINT `cargas_combustible_viaje_id_foreign` FOREIGN KEY (`viaje_id`) REFERENCES `viajes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `casetas`
--
ALTER TABLE `casetas`
  ADD CONSTRAINT `casetas_viaje_id_foreign` FOREIGN KEY (`viaje_id`) REFERENCES `viajes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `documentos_equipo`
--
ALTER TABLE `documentos_equipo`
  ADD CONSTRAINT `documentos_equipo_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `documentos_operador`
--
ALTER TABLE `documentos_operador`
  ADD CONSTRAINT `documentos_operador_operador_id_foreign` FOREIGN KEY (`operador_id`) REFERENCES `operadores` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `documentos_tracto`
--
ALTER TABLE `documentos_tracto`
  ADD CONSTRAINT `documentos_tracto_tracto_id_foreign` FOREIGN KEY (`tracto_id`) REFERENCES `tractos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `mantenimientos_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mantenimientos_tracto_id_foreign` FOREIGN KEY (`tracto_id`) REFERENCES `tractos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `piezas_mantenimiento`
--
ALTER TABLE `piezas_mantenimiento`
  ADD CONSTRAINT `piezas_mantenimiento_mantenimiento_id_foreign` FOREIGN KEY (`mantenimiento_id`) REFERENCES `mantenimientos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD CONSTRAINT `reparaciones_viaje_id_foreign` FOREIGN KEY (`viaje_id`) REFERENCES `viajes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `viajes`
--
ALTER TABLE `viajes`
  ADD CONSTRAINT `viajes_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `viajes_equipo_id_foreign` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `viajes_operador_id_foreign` FOREIGN KEY (`operador_id`) REFERENCES `operadores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `viajes_tracto_id_foreign` FOREIGN KEY (`tracto_id`) REFERENCES `tractos` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
