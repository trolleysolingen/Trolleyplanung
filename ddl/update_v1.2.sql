CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `congregation_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `text` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_messages_congregation_idx` (`congregation_id` ASC),
  INDEX `fk_messages_role_idx` (`role_id` ASC),
  CONSTRAINT `fk_messages_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_messages_roles`
    FOREIGN KEY (`role_id`)
    REFERENCES `roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE=InnoDB;