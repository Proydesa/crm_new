ALTER TABLE `h_comprobantes` CHANGE `numero` `numero` VARCHAR(127) NOT NULL;

ALTER TABLE `h_course_periods` ADD `period_number` TINYINT NOT NULL AFTER `month_max`;
UPDATE `h_course_periods` SET `period_number` = '1' WHERE `h_course_periods`.`id` = 1;
UPDATE `h_course_periods` SET `period_number` = '2' WHERE `h_course_periods`.`id` = 4;
UPDATE `h_course_periods` SET `period_number` = '2' WHERE `h_course_periods`.`id` = 3;
UPDATE `h_course_periods` SET `period_number` = '2' WHERE `h_course_periods`.`id` = 2;
UPDATE `h_course_periods` SET `period_number` = '3' WHERE `h_course_periods`.`id` = 5;
UPDATE `h_course_periods` SET `period_number` = '3' WHERE `h_course_periods`.`id` = 6;
UPDATE `h_course_periods` SET `period_number` = '3' WHERE `h_course_periods`.`id` = 7;