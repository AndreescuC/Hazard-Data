CREATE TABLE warning(
  id int AUTO_INCREMENT PRIMARY KEY,
  ext_id VARCHAR(255),
	hazard_type INTEGER,
	`status` varchar(255),
	location_lat float,
	location_long float,
	population INTEGER,
	gravity INTEGER,
	trust_level float,
	date_created DATETIME,
	date_modified DATETIME
);