ALTER TABLE publisher_reservations DROP FOREIGN KEY fk_publisher_reservations;

ALTER TABLE publisher_reservations ADD CONSTRAINT fk_publisher_reservations
     FOREIGN KEY (publisher_id) REFERENCES publishers (id) ON DELETE CASCADE;
	 
	 
ALTER TABLE todos DROP FOREIGN KEY fk_todos_reporters;

ALTER TABLE todos ADD CONSTRAINT fk_todos_reporters
     FOREIGN KEY (reporter_id) REFERENCES publishers (id) ON DELETE CASCADE;