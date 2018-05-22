CREATE TABLE parameters(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	warning_radius FLOAT,
	warning_tolerance FLOAT,
	api_key_generation_min_value FLOAT,
	api_key_generation_max_value FLOAT,
	api_key_generation_max_attempts FLOAT,
	firabase_server_key VARCHAR(255)
);