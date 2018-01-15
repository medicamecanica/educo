
--
-- Estructura de tabla para la tabla `llx_educo_group`
--

CREATE TABLE `llx_educo_group` (
  `rowid` int(11) NOT NULL,
  `ref` varchar(250) DEFAULT NULL,
  `sufix` varchar(45) DEFAULT NULL,
  `label` varchar(450) DEFAULT NULL,
  `fk_academicyear` int(11) NOT NULL,
  `grado_code` varchar(20) NOT NULL,
  `tms` timestamp NULL DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `statut` int(3) DEFAULT NULL,
  `import_key` varchar(14) DEFAULT NULL,
  `entity` int(11) DEFAULT NULL
);

