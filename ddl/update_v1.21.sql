create table ships (
  id INT NOT NULL AUTO_INCREMENT,
  route_id int not null,
  name varchar(30),
  publisher varchar(101)
  created datetime,
  PRIMARY KEY (`id`),
  INDEX `fk_ships_routes_idx` (`route_id` ASC),
  CONSTRAINT `fk_ships_routes_idx`
    FOREIGN KEY (`route_id`)
    REFERENCES `routes` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE = InnoDB;