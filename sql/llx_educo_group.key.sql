
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `llx_educo_group`
--
ALTER TABLE `llx_educo_group`
  ADD PRIMARY KEY (`rowid`),
  ADD UNIQUE KEY `ref_UNIQUE` (`ref`),
  ADD KEY `fk_llx_educo_grupo_llx_educo_acad_year1_idx` (`fk_academicyear`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `llx_educo_group`
--
ALTER TABLE `llx_educo_group`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `llx_educo_group`
--
ALTER TABLE `llx_educo_group`
  ADD CONSTRAINT `fk_llx_educo_grupo_llx_educo_acad_year1` FOREIGN KEY (`fk_academicyear`) REFERENCES `llx_educo_acad_year` (`rowid`) ON DELETE NO ACTION ON UPDATE NO ACTION;
