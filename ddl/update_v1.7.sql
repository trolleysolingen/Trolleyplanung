alter table publishers add (
  send_mail_when_partner int(1) default NULL,
  log_out int(1) default 0,
  last_login datetime default NULL
);

alter table congregations add (
  killswitch int(1) default 0
);