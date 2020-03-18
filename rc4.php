<?php  
	require_once 'header.php';
	echo "<h2>RC4 Encryption</h2>";
	if(isset($_POST['rc4_key']) && $_POST['rc4_key'] != '' && $_FILES['file_upload']['size'] != 0) {

		$file_name = $_FILES['file_upload']['name'];
		switch ($_FILES['file_upload']['type']) {
			case 'text/plain': 	$ext = 'txt'; break;
			default: 			$ext = ''; break;
		}

		if($ext) {
			$text_size = filesize($_FILES['file_upload']['tmp_name']).'<br><br>';

			if($text_size < 256) {
				$username = $_COOKIE['username'];
				$encryption_type = 'RC4';
				$key = fix_string($conn, $_POST['rc4_key']);
				$text = file_get_contents($_FILES['file_upload']['tmp_name']); 


				$text = str_replace('"', '', $text);
				$encrypted_text = rc4_encrypt($key, $text);
				$encrypted_text = $conn->real_escape_string($encrypted_text);
				// echo "Encrypted: $encrypted_text<br><br>";
				$query = "INSERT INTO uploads VALUES(null, '$username', '$encryption_type', '$key', 'No', '$encrypted_text', NOW())";
				$result = $conn->query($query);
				if(!$result) die ("Could not add to database with the query:<br>$query<br><br>" . $conn->error);
				echo "Upload completed!<br>";
			} else echo "Please submit files with less that 256 characters.<br>";
			

			
		} else echo "Sorry, only txt files are acceptable.<br>";

	} else {
		echo "Not filled in<br>";
	}

	echo "<a href='index.php'>Back to index</a>";

	require_once 'footer.php';




	function rc4_encrypt($key, $text) {
		// Key needs to be between 5 and 32 characters
		$s_box = array();

		for($i = 0; $i < 256; ++$i) {
			$s_box[$i] = $i;
		}
		$j = 0;
		$key_len = strlen($key);

		for($i = 0; $i < 256; ++$i) {
			$j = ($j + $s_box[$i] + ord($key[$i % $key_len])) % 256;
			swap($s_box, $i, $j);
		}

		$i = 0;
		$j = 0;
		$result = '';
		$text_len = strlen($text);

		for($k = 0; $k < $text_len; ++$k) {
			$i = ($i + 1) % 256;
			$j = ($j + $s_box[$i]) % 256;
			swap($s_box, $i, $j);
			$temp_output = $s_box[($s_box[$i] + $s_box[$j]) % 256];
			$result .= $text[$k] ^ chr($temp_output);
		}

		return $result;

	}

	function swap($s_box, $i, $j) {
		$temp = $s_box[$i];
		$s_box[$i] = $s_box[$j];
		$s_box[$j] = $temp;
	}

	function fix_string($conn, $string) {
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return htmlentities($conn->real_escape_string($string));
	}
	
?>

