-- MySQL 8.0 +
CREATE TABLE IF NOT EXISTS `notice_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(120) NULL ,
  `email_sent` BOOLEAN NOT NULL DEFAULT 0,
  `phone` VARCHAR(20) NULL ,
  `phone_sent` BOOLEAN NOT NULL DEFAULT 0,
  `version` VARCHAR(20) NULL ,
  `create_time` TIMESTAMP NOT NULL,
  `update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- or

-- MySQL 5.6 5.7

CREATE TABLE `notice_user` (
	`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
	`email` varchar(120) NULL,
	`email_sent` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`phone` varchar(20) NULL,
	`phone_sent` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
	`version` varchar(20) NOT NULL,
	`create_time` datetime NOT NULL,
	`update_time` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;