ALTER TABLE `crm`.`h_comprobantes` 
ADD COLUMN `tarjetaid` INT(11) NULL AFTER `pendiente`,
ADD COLUMN `bancoid` INT(11) NULL AFTER `tarjetaid`;