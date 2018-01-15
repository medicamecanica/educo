-- 
-- Copyright (C) 2018 ander
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see <http://www.gnu.org/licenses/>.
--/
--
-- Author:  ander
-- Created: 15/01/2018
--/

--
-- RELACIONES PARA LA TABLA `llx_educo_horario`:
--   `fk_group`
--       `llx_educo_group` -> `rowid`
--   `fk_teach_sub`
--       `llx_educo_teacher_subject` -> `rowid`
--

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `llx_educo_horario`
--
ALTER TABLE `llx_educo_horario`
  ADD PRIMARY KEY (`rowid`),
  ADD KEY `fk_llx_educo_horario_llx_educo_teacher_subject1_idx` (`fk_teach_sub`),
  ADD KEY `fk_llx_educo_horario_llx_educo_group1_idx1` (`fk_group`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `llx_educo_horario`
--
ALTER TABLE `llx_educo_horario`
  MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `llx_educo_horario`
--
ALTER TABLE `llx_educo_horario`
  ADD CONSTRAINT `fk_llx_educo_horario_llx_educo_group1` FOREIGN KEY (`fk_group`) REFERENCES `llx_educo_group` (`rowid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_llx_educo_horario_llx_educo_teacher_subject1` FOREIGN KEY (`fk_teach_sub`) REFERENCES `llx_educo_teacher_subject` (`rowid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

