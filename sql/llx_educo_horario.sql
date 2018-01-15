
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `llx_educo_horario`
--

CREATE TABLE `llx_educo_horario` (
  `rowid` int(11) NOT NULL,
  `ref` varchar(45) DEFAULT NULL,
  `label` varchar(450) DEFAULT NULL,
  `datep` datetime DEFAULT NULL,
  `datef` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `note_private` text,
  `grado_code` varchar(45) NOT NULL,
  `subject_code` varchar(45) NOT NULL,
  `datec` datetime DEFAULT NULL,
  `tms` datetime DEFAULT NULL,
  `entity` int(11) DEFAULT NULL,
  `fk_group` int(11) NOT NULL,
  `fk_teach_sub` int(11) NOT NULL
);
