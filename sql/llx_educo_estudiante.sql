--
-- Estructura de tabla para la tabla `llx_educo_student`
--

CREATE TABLE `llx_educo_student` (
  `rowid` int(11) NOT NULL,
  `ref` varchar(64) DEFAULT NULL,
  `name` varchar(450) DEFAULT NULL,
  `firstname` varchar(150) DEFAULT NULL,
  `lastname` varchar(150) DEFAULT NULL,
  `doc_type` varchar(2) DEFAULT NULL,
  `document` varchar(15) NOT NULL,
  `entity` int(11) DEFAULT NULL,
  `fk_contact` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `fk_soc` int(11) NOT NULL,
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT NULL,
  `import_key` varchar(14) DEFAULT NULL
)
