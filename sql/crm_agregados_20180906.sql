SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;


CREATE TABLE `h_calendario` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `daynum` smallint(6) NOT NULL,
  `daycode` varchar(1) NOT NULL,
  `type` enum('holiday','tech','course') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `h_calendario_courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `idperiod` smallint(5) UNSIGNED NOT NULL,
  `start` date NOT NULL,
  `amount` tinyint(3) UNSIGNED NOT NULL,
  `days` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `h_course_periods` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `period` varchar(40) NOT NULL,
  `mode` varchar(20) NOT NULL,
  `month_min` tinyint(3) UNSIGNED NOT NULL,
  `month_max` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `h_course_periods` (`id`, `period`, `mode`, `month_min`, `month_max`) VALUES
(1, 'Verano', 'Intensivo', 1, 3),
(2, 'Marzo / Julio', 'Regular', 3, 7),
(3, 'Marzo / Mayo', 'Intensivo', 3, 5),
(4, 'Mayo / Julio', 'Intensivo', 5, 7),
(5, 'Agosto / Diciembre', 'Regular', 8, 12),
(6, 'Agosto / Octubre', 'Intensivo', 8, 10),
(7, 'Octubre / Diciembre', 'Intensivo', 10, 12);


ALTER TABLE `h_calendario`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `h_calendario_courses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `h_course_periods`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `h_calendario`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=745;
ALTER TABLE `h_calendario_courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
ALTER TABLE `h_course_periods`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
