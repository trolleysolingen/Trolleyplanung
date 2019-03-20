alter table reservations add (
  report_levies int(3) default NULL,
  report_meetings int(3) default NULL,
  report_experiences int(3) default NULL,
  report_languages varchar(500) default null,
  report_ships int(3) default null
);

