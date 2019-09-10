ALTER TABLE `mdl_proy_convenios` 
	ADD `logo` VARCHAR(255) NULL DEFAULT NULL AFTER `link`;

ALTER TABLE `mdl_proy_convenios` 
	CHANGE `link` `link` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;