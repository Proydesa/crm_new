CREATE TABLE `h_contactos_de_la_red` (
  `id_contactos_de_la_red` bigint(10) NOT NULL AUTO_INCREMENT,
  `id_academia` bigint(10) NOT NULL,
  `cod_tipo_contacto` int(11) NOT NULL,
  `descripcion` varchar(4000) NOT NULL,
  `pendientes` varchar(1) NOT NULL DEFAULT 'N',
  `descripcion_pendientes` varchar(4000) DEFAULT NULL,
  `cod_estado` varchar(45) NOT NULL DEFAULT 'A',
  `usu_alta` bigint(10) NOT NULL,
  `fec_alta` bigint(10) NOT NULL,
  `usu_mod` bigint(10) DEFAULT NULL,
  `fec_mod` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id_contactos_de_la_red`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

CREATE TABLE `h_contactos_de_la_red_usuarios` (
  `id_contactos_de_la_red` bigint(10) NOT NULL,
  `id_usuario` bigint(10) NOT NULL,
  `cod_estado` varchar(45) NOT NULL DEFAULT 'A',
  `usu_alta` bigint(10) NOT NULL,
  `fec_alta` bigint(10) NOT NULL,
  `usu_mod` bigint(10) DEFAULT NULL,
  `fec_mod` bigint(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `h_contactos_help_desk` (
  `Nro` int(11) DEFAULT NULL,
  `id_academia` bigint(10) DEFAULT NULL,
  `Academia` varchar(100) DEFAULT NULL,
  `Tema` varchar(100) DEFAULT NULL,
  `Detalle` varchar(100) DEFAULT NULL,
  `Fecha_de_inicio` varchar(100) DEFAULT NULL,
  `Formula_Fecha_de_Inicio` varchar(100) DEFAULT NULL,
  `Fecha_de_cierre` varchar(100) DEFAULT NULL,
  `Formula_Fecha_de_Fin` varchar(100) DEFAULT NULL,
  `Tiempo_de_realizacion_en_dias` decimal(10,2) DEFAULT NULL,
  `Tiempo_de_realizacion_en_horas` decimal(10,2) DEFAULT NULL,
  `Responsable_de_Solucion` varchar(100) DEFAULT NULL,
  `Via_Help_Desk` varchar(100) DEFAULT NULL,
  `Via_Mail` varchar(100) DEFAULT NULL,
  `Via_Llamado` varchar(100) DEFAULT NULL,
  `Observaciones` varchar(100) DEFAULT NULL,
  `periodo` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
