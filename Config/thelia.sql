
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- content_code
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `content_code`;

CREATE TABLE `content_code`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `content_id` INTEGER NOT NULL,
    `code` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `UNIQUE_code` (`code`),
    INDEX `idx_content_code_content_fk` (`content_id`),
    CONSTRAINT `fk_content_code_content_id`
        FOREIGN KEY (`content_id`)
        REFERENCES `content` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
