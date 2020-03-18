<?php  	
	setcookie('username', $_SESSION['username'], time() - 2592000, '/');
	session_start();
	$_SESSION = array();
	session_destroy();
	header("Location: index.php");
?>