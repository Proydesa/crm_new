CREATE TABLE `h_asistencia_instructor` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `idAcademia` bigint(10) DEFAULT NULL,
  `idComision` bigint(10) DEFAULT NULL,
  `fecha` bigint(10) DEFAULT NULL,
  `idInstructor` bigint(10) DEFAULT NULL,
  `idInstructorReemplazo` bigint(10) DEFAULT NULL,
  `Inicio` bigint(10) DEFAULT NULL,
  `Fin` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
SELECT * FROM crm.h_asistencia_instructor;