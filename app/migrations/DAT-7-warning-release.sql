CREATE TABLE warning(
  id int AUTO_INCREMENT PRIMARY KEY,
  ext_id int,
	status varchar(255),
	location_lat float,
	location_long float,
	population integer,
	trust_level float
);