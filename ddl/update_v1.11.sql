ALTER TABLE reservations DROP FOREIGN KEY fk_reservations_timeslots;     
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_timeslots` FOREIGN KEY (`timeslot_id`) REFERENCES `timeslots` (`id`) ON DELETE CASCADE;
  
ALTER TABLE publisher_reservations DROP FOREIGN KEY fk_reservation_publishers;
ALTER TABLE `publisher_reservations`
  ADD CONSTRAINT `fk_reservation_publishers` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;
  
  
alter table routes add (
 	maplink varchar(400)
);

  