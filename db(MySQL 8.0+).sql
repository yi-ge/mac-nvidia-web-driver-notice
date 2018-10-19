-- MySQL 8.0+
CREATE TABLE IF NOT EXISTS `notice_user`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(120) NULL,
    `email_sent` BOOLEAN NOT NULL DEFAULT 0,
    `phone` VARCHAR(20) NULL,
    `phone_sent` BOOLEAN NOT NULL DEFAULT 0,
    `version` VARCHAR(20) NULL,
    `create_time` TIMESTAMP NOT NULL,
    `update_time` TIMESTAMP NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin;

CREATE TABLE `notice_order`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `out_trade_no` VARCHAR(32) NOT NULL UNIQUE,
    `payjs_order_id` VARCHAR(32) NULL,
    `transaction_id` VARCHAR(32) NULL,
    `total_fee` INT(16) NULL,
    `openid` VARCHAR(32) NULL,
    `mchid` VARCHAR(16) NULL,
    `time_end` VARCHAR(32) NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin;

CREATE TABLE `notice_data`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `content` LONGTEXT NOT NULL,
    `update_time` TIMESTAMP NULL,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_bin;
