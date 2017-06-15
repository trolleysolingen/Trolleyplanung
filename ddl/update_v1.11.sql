ALTER TABLE reservations DROP FOREIGN KEY fk_reservations_timeslots;     
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_timeslots` FOREIGN KEY (`timeslot_id`) REFERENCES `timeslots` (`id`) ON DELETE CASCADE;
  
ALTER TABLE publisher_reservations DROP FOREIGN KEY fk_reservation_publishers;
ALTER TABLE `publisher_reservations`
  ADD CONSTRAINT `fk_reservation_publishers` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;
  
  
alter table routes add (
 	maplink varchar(400)
);

ALTER TABLE dayslots DROP FOREIGN KEY fk_dayslots_routes;
ALTER TABLE dayslots 
  ADD CONSTRAINT `fk_dayslots_routes` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
  

ALTER TABLE reservations DROP FOREIGN KEY fk_reservations_routes;
ALTER TABLE reservations
  ADD CONSTRAINT `fk_reservations_routes` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
  
ALTER TABLE timeslots DROP FOREIGN KEY fk_timeslotes_routes;
ALTER TABLE timeslots
  ADD CONSTRAINT `fk_timeslotes_routes` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
  
alter table congregations add (
	typ varchar(50)
);

update congregations set typ = 'Trolley';

alter table reservations add (
  videos int(3) default NULL,
  jworgcard int(3) default NULL,
  contacts int(3) default NULL
);