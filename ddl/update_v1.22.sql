alter table reservations add (
  report_levies int(3) default NULL,
  report_meetings int(3) default NULL,
  report_experiences int(3) default NULL,
  report_languages varchar(500) default null,
  report_ships int(3) default null
);

CREATE TABLE IF NOT EXISTS `shiplists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` int(11) NOT NULL,
  `shipname` varchar(30) DEFAULT NULL,
  `imo` varchar(7) DEFAULT NULL,
  `shiptype` varchar(50) DEFAULT NULL,  
  `visit` date DEFAULT NULL,  
  `returnvisit` date DEFAULT NULL,  
  `reservation` int(1) DEFAULT 0,   
  `publishers` varchar(1000) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
