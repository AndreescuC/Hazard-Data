CREATE TABLE client_user(
	id integer auto_increment primary key,
	username varchar(255),
	password varchar(255),
	user_apikey varchar(255),
	first_name varchar(255),
	last_name varchar(255),
	email varchar(255),
	home_town varchar(255),
	trust_level varchar(255)
);