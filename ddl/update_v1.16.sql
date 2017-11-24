ALTER TABLE dayslots DROP FOREIGN KEY fk_dayslots_congregations;     
ALTER TABLE `dayslots`
  ADD CONSTRAINT `fk_dayslots_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE CASCADE;
    
ALTER TABLE messages DROP FOREIGN KEY fk_messages_congregations;     
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE CASCADE;
    
ALTER TABLE publishers DROP FOREIGN KEY fk_publishers_congregations;     
ALTER TABLE `publishers`
   ADD CONSTRAINT `fk_publishers_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE CASCADE;

ALTER TABLE todos DROP FOREIGN KEY fk_todos_workers;    
ALTER TABLE `todos`	
	ADD CONSTRAINT `fk_todos_workers` FOREIGN KEY (`worker_id`) REFERENCES `publishers` (`id`) ON DELETE CASCADE;

ALTER TABLE reservations DROP FOREIGN KEY fk_reservations_congregations;    
ALTER TABLE `reservations`		
  ADD CONSTRAINT `fk_reservations_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE CASCADE;
   
ALTER TABLE routes DROP FOREIGN KEY fk_routes_congregations;    
ALTER TABLE `routes` 
   ADD CONSTRAINT `fk_routes_congregations`
    FOREIGN KEY (`congregation_id`)
    REFERENCES `congregations` (`id`)
    ON DELETE CASCADE;