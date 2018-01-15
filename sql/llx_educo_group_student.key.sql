
--
-- RELACIONES PARA LA TABLA `llx_educo_group_student`:
--   `fk_grupo`
--       `llx_educo_group` -> `rowid`
--   `fk_estudiante`
--       `llx_educo_student` -> `rowid`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `llx_educo_group_student`
--
ALTER TABLE `llx_educo_group_student`
  ADD PRIMARY KEY (`rowid`),
  ADD KEY `fk_llx_grupo_estudiante_llx_user1_idx` (`fk_user`),
  ADD KEY `fk_llx_educo_group_student_llx_educo_student1_idx` (`fk_estudiante`),
  ADD KEY `fk_llx_educo_group_student_llx_educo_group1_idx` (`fk_grupo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `llx_educo_group_student`
--
ALTER TABLE `llx_educo_group_student`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `llx_educo_group_student`
--
ALTER TABLE `llx_educo_group_student`
  ADD CONSTRAINT `fk_llx_educo_group_student_llx_educo_group1` FOREIGN KEY (`fk_grupo`) REFERENCES `llx_educo_group` (`rowid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_llx_educo_group_student_llx_educo_student1` FOREIGN KEY (`fk_estudiante`) REFERENCES `llx_educo_student` (`rowid`) ON DELETE NO ACTION ON UPDATE NO ACTION;
