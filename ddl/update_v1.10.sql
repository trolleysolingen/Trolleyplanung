ALTER TABLE publisher_reservations DROP FOREIGN KEY fk_publisher_reservations;

ALTER TABLE publisher_reservations ADD CONSTRAINT fk_publisher_reservations
     FOREIGN KEY (publisher_id) REFERENCES publishers (id) ON DELETE CASCADE;
	 
	 
ALTER TABLE todos DROP FOREIGN KEY fk_todos_reporters;

ALTER TABLE todos ADD CONSTRAINT fk_todos_reporters
     FOREIGN KEY (reporter_id) REFERENCES publishers (id) ON DELETE CASCADE;
     


ALTER TABLE reservations DROP FOREIGN KEY fk_reservations_publishers1;
ALTER TABLE reservations ADD CONSTRAINT fk_reservations_publishers1
     FOREIGN KEY (publisher1_id) REFERENCES publishers (id) ON DELETE CASCADE;
         
ALTER TABLE reservations DROP FOREIGN KEY fk_reservations_publishers2;
ALTER TABLE reservations ADD CONSTRAINT fk_reservations_publishers2
     FOREIGN KEY (publisher2_id) REFERENCES publishers (id) ON DELETE CASCADE;
     
ALTER TABLE publisher_reservations DROP FOREIGN KEY fk_reservation_publishers;
ALTER TABLE publisher_reservations ADD CONSTRAINT fk_reservation_publishers
     FOREIGN KEY (reservation_id) REFERENCES reservations (id) ON DELETE CASCADE;
     