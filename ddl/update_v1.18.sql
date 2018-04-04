alter table routes add (
	weeks_displayed int not null default 12
);

alter table routes add (
	aktiv int(1) not null default 1,
	start_date date null
);