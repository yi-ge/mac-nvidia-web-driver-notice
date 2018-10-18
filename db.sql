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

CREATE TABLE `notice_order` (
	`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
	`out_trade_no` varchar(32) NOT NULL UNIQUE,
	`payjs_order_id` varchar(32) NULL,
	`transaction_id` varchar(32) NULL,
	`total_fee` int(16) NULL,
	`openid` varchar(32) NULL,
	`mchid` varchar(16) NULL,
	`time_end` varchar(32) NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `notice_data` (
	`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
	`content` longtext NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


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


CREATE TABLE `notice_order` (
	`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
	`out_trade_no` varchar(32) NOT NULL UNIQUE,
	`payjs_order_id` varchar(32) NULL,
	`transaction_id` varchar(32) NULL,
	`total_fee` int(16) NULL,
	`openid` varchar(32) NULL,
	`mchid` varchar(16) NULL,
	`time_end` varchar(32) NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `notice_data` (
	`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
	`content` longtext NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci;
