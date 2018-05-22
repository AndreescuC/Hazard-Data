CREATE TABLE data_provider(
  id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255),
	`status` INT,
	`code` VARCHAR(255),
	scope VARCHAR(255),
	query_url TEXT
);