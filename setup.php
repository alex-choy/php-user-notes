<?php  
	require_once 'login.php';
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die($conn->connect_error);

	
	$salt1 = 'pz$%kb';
	$salt2 = 'qmiv%!x';

	// $query = "DROP TABLE users";
	// $result = $conn->query($query);
	
	$query = "CREATE TABLE users (
		email VARCHAR(32) NOT NULL UNIQUE,
		username VARCHAR(32) NOT NULL UNIQUE, 
		password VARCHAR(32) NOT NULL, 
		PRIMARY KEY (email)
	)";

	$result = $conn->query($query);

	// $query = "DROP TABLE uploads";
	// $result = $conn->query($query);

	

	$query = "CREATE TABLE uploads (
		id SMALLINT NOT NULL AUTO_INCREMENT,
		username VARCHAR(32) NOT NULL, 
		encryption_type VARCHAR(32), 
		secret_key_1 VARCHAR(32) NOT NULL, 
		secret_key_2 VARCHAR(128),
		content VARCHAR(256) NOT NULL, 
		created_at TIMESTAMP,
		PRIMARY KEY (id)
	)";

	$result = $conn->query($query);
?>