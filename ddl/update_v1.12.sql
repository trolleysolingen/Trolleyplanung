alter table timeslots add (
	day varchar(20),
	old_id INT
);

CREATE TABLE IF NOT EXISTS `temp_days` (
  `id` INT NOT NULL,
  `day` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

insert into temp_days (id, day) values 
(0, 'monday'),
(1, 'tuesday'),
(2, 'wednesday'),
(3, 'thursday'),
(4, 'friday'),
(5, 'saturday'),
(6, 'sunday');

insert into timeslots (congregation_id, start, end, route_id, day, old_id)
select ts.congregation_id, ts.start, ts.end, route_id, td.day, ts.id
from timeslots ts, temp_days td;

update reservations r set 
timeslot_id = (select ts.id from timeslots ts, temp_days td where ts.old_id = r.timeslot_id and ts.day = td.day and td.id = weekday(r.day));


delete from timeslots where day is null;

alter table timeslots drop column old_id;

drop table temp_days;
