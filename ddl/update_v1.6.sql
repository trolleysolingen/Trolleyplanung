alter table congregations add (
  report int(1) default 0,
  report_start_date date default 0
);

ALTER TABLE reservations modify timeslot_id INT NULL;

alter table reservations add (
  report_necessary int(1) default 1,
  no_report_reason mediumtext default NULL,
  report_date date default NULL,
  minutes int(4) default NULL,
  books int(3) default NULL,
  magazines int(3) default NULL,
  brochures int(3) default NULL,
  tracts int(3) default NULL,
  conversations int(3) default NULL,
  reporter_id int(11)
);