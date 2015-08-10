alter table routes add (
  publishers int(3) default 2
);

create table publisher_reservations (
  `id` INT NOT NULL AUTO_INCREMENT,
  `publisher_id` INT NOT NULL,
  `reservation_id` INT NOT NULL,
  `guestname` VARCHAR(100) NULL,
  created datetime NULL,
  modified datetime NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_publisher_reservations_idx` (`publisher_id` ASC),
  CONSTRAINT `fk_publisher_reservations`
    FOREIGN KEY (`publisher_id`)
    REFERENCES `publishers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  INDEX `fk_reservation_publishers_idx` (`reservation_id` ASC),
  CONSTRAINT `fk_reservation_publishers`
    FOREIGN KEY (`reservation_id`)
    REFERENCES `reservations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;