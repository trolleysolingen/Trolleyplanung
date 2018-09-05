alter table publishers add (
	created datetime NULL,
  	modified datetime NULL
);

update publishers set created = now(), modified = now();