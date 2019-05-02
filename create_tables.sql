DROP DATABASE IF EXISTS `ajax_crud`;
CREATE DATABASE `ajax_crud`;

use `ajax_crud`;

DROP TABLE IF EXISTS `to_do` ;

CREATE TABLE `to_do` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB, auto_increment=1;