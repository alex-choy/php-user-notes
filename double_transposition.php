<?php
	require_once 'header.php';
	echo "<h2>Double Transposition</h2><br>";
	if($_FILES['file_upload']['size'] != 0) {

		$file_name = $_FILES['file_upload']['name'];
		switch ($_FILES['file_upload']['type']) {
			case 'text/plain': 	$ext = 'txt'; break;
			default: 			$ext = ''; break;
		}

		if($ext) {
			$text_size = filesize($_FILES['file_upload']['tmp_name']).'<br><br>';
			if($text_size < 256) {
				$username = $_COOKIE['username'];
				$encryption_type = 'Double Transposition';
				$content = preg_replace('/[^a-z ]+/i', '', strtolower(file_get_contents($_FILES['file_upload']['tmp_name'])));
				$content = str_replace(' ', '', fix_string($conn, $content));
				$content_len = strlen($content);
				$key_x_len = 11;
				$key_y_len = intdiv($content_len, $key_x_len) + 1;

				$content_array = array(array());
				$shuffled_content = array(array());

				// Puts content into $content_array, initializes $shuffled_content
				for($i = 0; $i < (int)$key_y_len; ++$i) {
					for($j = 0; $j < $key_x_len; ++$j) {
						$index = ($i * $key_x_len) + $j;
						if($index < $content_len){
							$content_array[$i][$j] = $content[$index];
						} else {
							$content_array[$i][$j] = ' ';

						}
						$shuffled_content[$i][$j] = " "; // Initialize our shuffled content array
					}
				}

				$index_array_x = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
				$index_array_y = array();
				for($i = 0; $i < $key_y_len; ++$i) {
					$index_array_y[$i] = $i;
				}
				shuffle($index_array_x);
				shuffle($index_array_y);

				// Key 1 = y-axis, Key 2 = x-axis
				$key_1 = implode(" ", $index_array_y);
				$key_2 = implode(" ", $index_array_x);


				// Randomly places the contents into $shuffled_content
				for($i = 0; $i < count($index_array_y); ++$i) {
					$temp_y = $index_array_y[$i];
					for($j = 0; $j < count($index_array_x); ++$j) {
						$temp_x = $index_array_x[$j];
						$shuffled_content[$i][$j] = $content_array[$temp_y][$temp_x];
					}
				}
				$shuffled_text = "";

				// Puts the entire $shuffled_content into one string, as our ciphertext
				for($i = 0; $i < count($index_array_y); ++$i) {
					for($j = 0; $j < count($index_array_x); ++$j) {
						$temp = $shuffled_content[$i][$j];
						$shuffled_text .= $temp;
					}
				}


				$query = "INSERT INTO uploads VALUES (null, \"$username\", \"$encryption_type\", \"$key_2\", \"$key_1\", \"$shuffled_text\", NOW())";
				$result = $conn->query($query);
				if(!$result) die("Could not add to database.<br>" . $conn->error);
				echo "Your upload has completed!<br><br>";
			} else echo "Please submit files with less that 256 characters.";


		} else echo "Sorry only txt files are allowed.";

	} else {
		echo "Please fill in the proper fields.<br>";
	}

	echo <<< _END
		<br><br><a href='index.php'>Back to index</a>
	_END;

	require_once 'footer.php';

	function fix_string($conn, $string) {
		if (get_magic_quotes_gpc()) $string = stripslashes($string);
		return htmlentities($conn->real_escape_string($string));
	}
?>
