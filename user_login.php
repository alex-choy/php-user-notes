<?php  

	require_once 'header.php';
	if (isset($_POST['username']) && $_POST['password'] != "") { // User is trying to login
		$username = fix_string($conn, $_POST['username']);
		$password = fix_string($conn, $_POST['password']);

		$query = "SELECT * FROM users WHERE username='$username'";
		$result = $conn->query($query);
		if(!$result) die($conn->error);
		elseif ($result->num_rows) {
			$row = $result->fetch_array(MYSQLI_NUM);
			$result->close();

			$hashed_pw = hash('ripemd128', "$salt1$password$salt2");
			if($hashed_pw == $row[2]) { // Successful login
				session_start();
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $password;
				setcookie('username', $username, time() + 60 * 60 * 24 * 7, '/');
				header("Location: index.php");
			} else {
				echo "Invalid username/password. Please try again.<br><a href='index.php'>Go Back</a>";
				$_POST['login_error'] = "Invalid username/password.";
			}
		} else {
			echo "Invalid username/password. Please try again.<br><a href='index.php'>Go Back</a>";
		}
	} else {

		echo <<< _END
		<div style="border: 1px solid black; padding: 5px; width: 20%;">
			<h2>Log In</h2>
			<form action="user_login.php" method="post">
				<input type="text" name="username"  placeholder='Username'><br>
				<input type="password" name="password" placeholder='Password'><br>
				<input type="submit" value="Login">
			</form>
		</div>

		<br><br>
		<a href='index.php'>Back to Index</a>

		_END;
	}

	require_once 'footer.php';

	function fix_string($conn, $string) {
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return htmlentities($conn->real_escape_string($string));
	}
?>