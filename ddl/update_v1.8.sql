create table routes (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `congregation_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_routes_congregations_idx` (`congregation_id` ASC),
  CONSTRAINT `fk_routes_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

alter table reservations add (
  route_id int null,
  CONSTRAINT `fk_reservations_routes`
    FOREIGN KEY (`route_id`)
    REFERENCES `routes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

alter table timeslots add (
  route_id int null,
  CONSTRAINT `fk_timeslotes_routes`
    FOREIGN KEY (`route_id`)
    REFERENCES `routes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);


-- Solingen Mitte and Solingen Ohligs

insert into routes (name, congregation_id)
select 'Route Mitte', id
from congregations
where name like '%Solingen Mitte%';

insert into routes (name, congregation_id)
select 'Route Ohligs', id
from congregations
where name like '%Solingen Mitte%';

update reservations set
route_id = (select id from routes where name = 'Route Mitte')
where congregation_id =
 (select id from congregations where name like '%Solingen Mitte%');

update timeslots set
route_id = (select id from routes where name = 'Route Mitte')
where congregation_id =
 (select id from congregations where name like '%Solingen Mitte%');

update reservations set
route_id = (select id from routes where name = 'Route Ohligs'),
congregation_id = (select id from congregations where name like '%Solingen Mitte%')
where congregation_id =
 (select id from congregations where name like '%Solingen Ohligs%');

update timeslots set
route_id = (select id from routes where name = 'Route Ohligs'),
congregation_id = (select id from congregations where name like '%Solingen Mitte%')
where congregation_id =
 (select id from congregations where name like '%Solingen Ohligs%');

update publishers set
  congregation_id = (select id from congregations where name like '%Solingen Mitte%')
where congregation_id =
      (select id from congregations where name like '%Solingen Ohligs%');

update congregations set name = 'Solingen' where name like '%Solingen Mitte%';


-- other congregations

insert into routes (name, congregation_id)
select 'Standard', id
from congregations
where name not like '%Solingen%';

update reservations set
route_id = (select id from routes where congregation_id = reservations.congregation_id)
where route_id is null;

update timeslots set
route_id = (select id from routes where congregation_id = timeslots.congregation_id)
where route_id is null;


-- dayslots

create table dayslots (
  id INT NOT NULL AUTO_INCREMENT,
  congregation_id int not null,
  route_id int not null,
  monday int(1) default 1,
  tuesday int(1) default 1,
  wednesday int(1) default 1,
  thursday int(1) default 1,
  friday int(1) default 1,
  saturday int(1) default 1,
  sunday int(1) default 1,
  PRIMARY KEY (`id`),
  INDEX `fk_dayslots_congregations_idx` (`congregation_id` ASC),
  CONSTRAINT `fk_dayslots_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  INDEX `fk_dayslots_routes_idx` (`route_id` ASC),
  CONSTRAINT `fk_dayslots_routes`
    FOREIGN KEY (`route_id`)
    REFERENCES `routes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

insert into dayslots (congregation_id, route_id, monday, tuesday, wednesday, thursday, friday, saturday, sunday)
select c.id, r.id, monday, tuesday, wednesday, thursday, friday, saturday, sunday
from congregations c, routes r
where c.id = r.congregation_id;

update dayslots set
monday = (select monday from congregations where name like '%Solingen Ohligs%'),
tuesday = (select tuesday from congregations where name like '%Solingen Ohligs%'),
wednesday = (select wednesday from congregations where name like '%Solingen Ohligs%'),
thursday = (select thursday from congregations where name like '%Solingen Ohligs%'),
friday = (select friday from congregations where name like '%Solingen Ohligs%'),
saturday = (select saturday from congregations where name like '%Solingen Ohligs%'),
sunday = (select sunday from congregations where name like '%Solingen Ohligs%')
where
route_id = (select id from routes where name = 'Route Ohligs') and
congregation_id = (select id from congregations where name = 'Solingen');

alter table congregations drop column monday;
alter table congregations drop column tuesday;
alter table congregations drop column wednesday;
alter table congregations drop column thursday;
alter table congregations drop column friday;
alter table congregations drop column saturday;
alter table congregations drop column sunday;


-- delete Ohligs

delete from messages where congregation_id =
      (select id from congregations where name like '%Solingen Ohligs%');
delete from congregations where name like '%Solingen Ohligs';

alter table routes add (
   description text
);

update routes set description = 'Zugangscode fuer Schluesseltresor in Solingen Mitte: 1935\n
Das Gartenhaeuschen mit dem Tresor befindet sich hinter dem Saal.\n\n
Die Route beginnt am Saal, geht ueber die Haltestelle Rathausplatz und dann in die Innenstadt.\n
Dann koennen mehrere Stationen auf der Hauptstrasse gemacht werden.' where name = 'Route Mitte';

update routes set description = 'Zugangscode fuer Schluesseltresor in Solingen Ohligs: 3576\n' where name = 'Route Ohligs';