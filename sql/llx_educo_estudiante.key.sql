
--
-- RELACIONES PARA LA TABLA `llx_educo_student`:
--   `fk_contact`
--       `llx_socpeople` -> `rowid`
--   `fk_soc`
--       `llx_societe` -> `rowid`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `llx_educo_student`
--
ALTER TABLE `llx_educo_student`
  ADD PRIMARY KEY (`rowid`),
  ADD UNIQUE KEY `document_UNIQUE` (`document`),
  ADD UNIQUE KEY `ref_UNIQUE` (`ref`),
  ADD KEY `fk_estudiante_llx_socpeople1_idx` (`fk_contact`),
  ADD KEY `fk_llx_educo_estudiante_llx_societe1_idx` (`fk_soc`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `llx_educo_student`
--
ALTER TABLE `llx_educo_student`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `llx_educo_student`
--
ALTER TABLE `llx_educo_student`
  ADD CONSTRAINT `fk_estudiante_llx_socpeople1` FOREIGN KEY (`fk_contact`) REFERENCES `llx_socpeople` (`rowid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_llx_educo_estudiante_llx_societe1` FOREIGN KEY (`fk_soc`) REFERENCES `llx_societe` (`rowid`) ON DELETE NO ACTION ON UPDATE NO ACTION;
