<?php  

	require_once 'login.php';
	require_once 'header.php';
	echo "<h2>Simple Substitution Encryption</h2><br>";

	// if($_FILES['file_upload']['size'] != 0) echo "Files found<br>";

	if($_FILES['file_upload']['size'] != 0 && isset($_POST['secret_key']) && ($_POST['secret_key'] != '')) {

		$file_name = $_FILES['file_upload']['name'];
		switch ($_FILES['file_upload']['type']) {
			case 'text/plain': 	$ext = 'txt'; break;
			default: 			$ext = ''; break;
		}

		if($ext) {
			$text_size = filesize($_FILES['file_upload']['tmp_name']).'<br><br>';
			if($text_size < 256) {
					$conn = new mysqli($hn, $un, $pw, $db);
				if ($conn->connect_error) die($conn->connect_error);

				$username = $_COOKIE['username'];
				$encryption_type = 'Simple Substitution';
				$content = preg_replace('/[^a-z ]+/i', '', file_get_contents($_FILES['file_upload']['tmp_name']));
				$content = fix_string($conn, $content);
				$key = fix_string($conn, $_POST['secret_key']);
				$encrypted_msg = encrypt_msg($key, $content);

				$query = "INSERT INTO uploads VALUES (null,'$username','$encryption_type','$key', 'No', '$encrypted_msg', NOW())";

				$result = $conn->query($query);
				if(!$result) die ("Could not add to database.<br>");

				echo "Your upload has completed!<br>";
			} else  echo "Please submit files with less that 256 characters.<br>";
			

		} else echo "Sorry, only txt files are acceptable.<br>";

		
	} else {
		echo "Invalid input, please try again.<br>";
	}


	echo <<< _END
		<br><br><a href='index.php'>Back to index</a>
	_END;

	require_once 'footer.php';


	// Encrypt the content with the key
	function encrypt_msg($key, $content){
		$alpha = "abcdefghijklmnopqrstuvwxyz";
		$content = strtolower($content);
		$content_len = strlen($content);
		$encrypted_msg = "";

		for($i = 0; $i < $content_len; ++$i) {
			$temp_char = $content[$i];
			if($temp_char != " ") {
				$new_char_index = strpos($alpha, $temp_char);
				$new_char_letter = $key[$new_char_index];
				// echo "Original: $temp_char, Index in alpha: $new_char_index, New char letter: $new_char_letter<br>";
				$encrypted_msg .= $new_char_letter;

			} else $encrypted_msg .= " ";
			
		}
		return $encrypted_msg;
	}

	function decrypt_msg($key, $content) {
		$alpha = "abcdefghijklmnopqrstuvwxyz";
		$content_len = strlen($content);
		$decrypted_msg = "";

		for($i = 0; $i < $content_len; ++$i) {
			$temp_char = $content[$i];
			if($temp_char != " ") {
				$char_index = strpos($key, $temp_char);
				$char_letter = $alpha[$char_index];
				// echo "Letter: $temp_char, Index in key: $char_index, Letter in Alpha: $char_letter<br>";
				$decrypted_msg .= $char_letter;

			} else $decrypted_msg .= " ";
		}

		return $decrypted_msg;
	}

	function fix_string($conn, $string) {
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return htmlentities($conn->real_escape_string($string));
	}
?>