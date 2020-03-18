<?php  
	require_once 'setup.php';
	// require_once 'login.php';

	echo <<< _END
		<!DOCTYPE html>
			<html>
			<head>
				<title>Homepage</title>
				<script type="text/javascript" src="scripts.js"></script>
			</head>
			<body>
				<h1><a style="text-decoration: none;" href="index.php">Decryptoid</a><br></h1>
	_END;

	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die($conn->connect_error);
	// this is closed in footer.php

	$query = "CREATE TABLE uploads (
		id SMALLINT NOT NULL AUTO_INCREMENT,
		username VARCHAR(32) NOT NULL, 
		encryption_type VARCHAR(1), 
		key, VARCHAR(32) NOT NULL, 
		content VARCHAR(256) NOT NULL, 
		PRIMARY KEY (id)
	)";

	$result = $conn->query($query);
	
?>