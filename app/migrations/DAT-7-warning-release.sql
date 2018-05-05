CREATE TABLE warning(
  id int AUTO_INCREMENT PRIMARY KEY,
  ext_id VARCHAR(255),
	status varchar(255),
	location_lat float,
	location_long float,
	population integer,
	trust_level float
);