CREATE TABLE `h_academy_aulas` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `academyid` bigint(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `capacity` tinyint(1) unsigned NOT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8;
SELECT * FROM crm.h_academy_aulas;
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (18,1,'.INT',24,3);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (17,1,'.NET',24,1);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (19,1,'.MIL',24,8);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (16,1,'.BIZ',24,2);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (15,1,'.COM',24,10);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (20,1,'.TV',24,7);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (21,1,'.EDU',24,9);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (22,1,'.ORG',22,5);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (23,1,'.INFO',20,4);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (38,1,'.GOV',20,6);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (26,1,'.TECH',18,11);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (27,2,'.BIZ',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (28,2,'.COM',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (29,2,'.INT',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (30,2,'.NET',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (31,2,'.MIL',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (32,2,'.TV',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (33,2,'.GOV',20,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (34,2,'.TECH',18,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (35,2,'.INFO',20,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (36,2,'.EDU',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (37,2,'.ORG',22,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (39,76,'Aula10',10,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (40,75,'.GOB',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (41,75,'.ESI',12,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (42,75,'.FISU',24,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (43,75,'.SHE',12,NULL);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (1000,1,'.WEBEX',99,12);
INSERT INTO `h_academy_aulas` (`id`,`academyid`,`name`,`capacity`,`orden`) VALUES (1001,201,'.WEBEX',99,NULL);
