/* Database export results for db cutiesys_system */

/* Preserve session variables */
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS=0;

/* Export data */

/* Table structure for leave */
CREATE TABLE `leave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apply_user_id` int(10) unsigned NOT NULL,
  `approve_user_id` int(10) unsigned DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `no_days` smallint(5) unsigned DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* data for Table leave */

/* Table structure for user */
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `is_approver` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `full_name` (`full_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* data for Table user */
INSERT INTO `user` VALUES (NULL,"admin","$2y$10$nBZ20A0V/y1gA2.tFA84n.GU99FyalKUU7Uo5kblm.5y5InGvmhhS","Administrator",1);

/* Restore session variables to original values */
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
