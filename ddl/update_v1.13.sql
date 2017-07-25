ALTER TABLE todos DROP FOREIGN KEY fk_todos_reporters;     
ALTER TABLE `todos`
  ADD CONSTRAINT `fk_todos_reporters` FOREIGN KEY (`reporter_id`) REFERENCES `publishers` (`id`) ON DELETE CASCADE;