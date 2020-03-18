<?php 

	require_once 'header.php';
	$email = $username = $password = "";

	if(isset($_POST['signup_em'])) 
		$email = fix_string($conn, $_POST['signup_em']);
	if(isset($_POST['signup_un'])) 
		$username = fix_string($conn, $_POST['signup_un']);
	if(isset($_POST['signup_pw'])) 
		$password = fix_string($conn, $_POST['signup_pw']);
	$fail = validateEmail($email);
	$fail .= validateUsername($username);
	$fail .= validatePassword($password);


	if($fail == "") {

		$hashed_pw = hash('ripemd128', "$salt1$password$salt2");
		$query = "INSERT INTO users VALUES('$email', '$username', '$hashed_pw')";
		$result = $conn->query($query);

		if(!$result) {
			die ($conn->error);
			// header('Location: index.php');
		} else {
			echo "Thanks for signing up $username!<br><br>";	
			session_start();
			setcookie('username', $username, time() + 60 * 60 * 24 * 7, '/');
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			// echo "Hello there $username, thanks for signing up!<br>";
			// display_when_logged_in($conn, $username);
			// header("Location: index.php");
			echo "<a href='index.php'>Let's make some entries!</a>";
		}


	} else {

		echo <<<_END
		<div style="margin-left: 10px;  border: 1px solid black; padding: 5px; width: 200px;">
			<h2>Sign Up Here</h2>
			<form onsubmit="return validateSignup(this);" action="user_signup.php" method="post" id="user_signup_form">
				<input type="text" name="signup_em" placeholder="Email" maxlength="64" required><br>
				<input type="text" name="signup_un" placeholder='Username' maxlength="32" required><br>
				<input type="password" name="signup_pw"placeholder='Password' maxlength="32" required><br><br>
				<input type="submit" value="Signup">
			</form>
		</div>

		<br><br>
		<a href='index.php'>Back to Index</a>
		_END;

		// echo '
		// <input type="text" name="login_signup_error" value="Please enter the proper login/signup information"><br>';'
		// echo '<a href="index.php">Back to Index</a>';
		// header('Location: index.php');
	}
	
	function fix_string($conn, $string) {
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return htmlentities($conn->real_escape_string($string));
	}

	function validateUsername($username) {
		$min_username_len = 5;

		if ($username == "") {
			return "Username is empty.<br>";
		} else if (strlen($username) < $min_username_len) {
			return "Username length must be greater than $min_username_len.<br>";
		} else if(preg_match("/[^a-zA-Z0-9_-]/", $username)) {
			return "Only letters, numbers, - and _ are allowed in usernames.<br>";
		}
		return "";
	}

	function validatePassword($password) {
		$min_password_len = 6;

		if($password == "") {
			return "Password is empty.<br>";
		} else if (strlen($password) < $min_password_len) {
			return "Password length must be greater than $min_password_len.<br>";
		} else if (!preg_match("/[a-z]/", $password) 
			|| !preg_match("/[A-Z]/", $password)
			|| !preg_match("/[0-9]/", $password)) {
			return "Passwords require one of each: a-z, A-Z, and 0-9.<br>";
		}
		return "";
	}

	function validateEmail($email) {
		if($email == "") return "Email is empty.<br>";
		else if(!((strpos($email, ".") > 0) &&
				  (strpos($email, "@") > 0)) ||
				   preg_match("/[^a-zA-Z0-9.@_-]/", $email)) {
			return "Email address is invalid.<br>";
		}
		return "";
	}

?>
