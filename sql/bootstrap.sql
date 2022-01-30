CREATE TABLE `received_data` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`dated` DATETIME(6) NOT NULL,
	`method` VARCHAR(20) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`ipaddr` VARCHAR(100) NOT NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`headers` MEDIUMTEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`query_string` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`post_data` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`input_data` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB;
