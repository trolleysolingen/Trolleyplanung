alter table publishers add (
	dataprotection int(1) not null default 0,
	dataprotection_date datetime null
);