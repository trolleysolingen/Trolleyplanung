alter table routes add (
  publishers int(3) default 2
);

create table publisher_reservations (
  `id` INT NOT NULL AUTO_INCREMENT,
  `publisher_id` INT NOT NULL,
  `reservation_id` INT NOT NULL,
  `guestname` VARCHAR(100) NULL,
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

insert into publisher_reservations (publisher_id, reservation_id, guestname)
select publisher1_id, id, if (publisher1_id=1, guestname, null)
from reservations
where publisher1_id is not null;

insert into publisher_reservations (publisher_id, reservation_id, guestname)
select publisher2_id, id, if (publisher2_id=1, guestname, null)
from reservations
where publisher2_id is not null;


ALTER TABLE `reservations` DROP FOREIGN KEY `fk_reservations_publishers1`;
ALTER TABLE `reservations` DROP FOREIGN KEY `fk_reservations_publishers2`;
ALTER TABLE `reservations` DROP `publisher1_id`, DROP `publisher2_id`;

fk_reservation_publisher1_idx