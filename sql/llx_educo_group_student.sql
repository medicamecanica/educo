--
-- Estructura de tabla para la tabla `llx_educo_group_student`
--

CREATE TABLE `llx_educo_group_student` (
  `rowid` int(11) NOT NULL,
  `ref` varchar(45) DEFAULT NULL,
  `datec` datetime DEFAULT NULL,
  `tms` timestamp NULL DEFAULT NULL,
  `statut` int(3) DEFAULT NULL,
  `fk_estudiante` int(11) NOT NULL,
  `fk_grupo` int(11) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `fk_academicyear` int(11) NOT NULL,
  `entity` int(11) DEFAULT NULL
);

