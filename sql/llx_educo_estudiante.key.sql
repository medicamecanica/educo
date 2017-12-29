
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