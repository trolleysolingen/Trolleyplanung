alter table congregations add (
  show_lkw_numbers int(1) default 0
);


create table lkwnumbers (
  id INT NOT NULL AUTO_INCREMENT,
  route_id int not null,
  licenseplatenumber varchar(30),
  created datetime,
  PRIMARY KEY (`id`),
  INDEX `fk_lkwnumbers_routes_idx` (`route_id` ASC),
  CONSTRAINT `fk_lkwnumbers_routes_idx`
    FOREIGN KEY (`route_id`)
    REFERENCES `routes` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE = InnoDB;