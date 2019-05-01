create table pokemon_dg_tmp
(
	id int
		constraint pokemon_pk
			primary key,
	name varchar,
	path varchar,
	level int
);

insert into pokemon_dg_tmp(id, name, path, level) select id, name, path, level from pokemon;

drop table pokemon;

alter table pokemon_dg_tmp rename to pokemon;

create unique index pokemon_name_uindex
	on pokemon (name);

create unique index pokemon_path_uindex
	on pokemon (path);

