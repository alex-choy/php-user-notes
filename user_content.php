<?php 
	require_once 'login.php';
	require_once 'header.php';
	
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_error) die($conn->connect_error);
	$username = $_COOKIE['username'];
	$query = "SELECT * FROM uploads WHERE username='$username'";


	$result = $conn->query($query);
	if(!$result) die("Couldn't get user uploads");

	$num_rows = $result->num_rows;

	echo 
		'<p style="font: 20px; background: yellow;" id="decrypted_message_space" hidden=true></p>';

	show_user_content($result, $num_rows, $conn);

	echo <<<_END
		</table><br><br>
		<br><a href="index.php">Back to Index</a><br><br>


	_END;

	require_once 'footer.php';


	function show_user_content($result, $num_rows, $conn) {

		echo "<h2>Your Content</h2>";
		echo <<<_END
			<style>
				td {
					padding: 5px;
				} 
				table {
					width: 100%;
					table-layout: fixed;
				}
			</style>
		_END;
		if($num_rows > 0) {
			echo "<table border=1><tr><th width='25'>ID</th><th width='100'>Encryption Type</th><th width='200'>Key</th><th width='150'>Second Key?</th><th>Content</th><th width='85'>Time</th><th width='70'>Decrypt?</th></tr>";
			for($i = 0; $i < $num_rows; ++$i) {
				$result->data_seek($i);
				$row = $result->fetch_array(MYSQLI_NUM);
				echo "\n<tr>";
				$decrypted_rc4_msg = "";
				$id = "";

				for($j = 0; $j < 7; ++$j) {
					if($j == 1) ++$j; // skip over the user's name in the DB


					$content = $row[$j];
					if($j == 2 && $content == "RC4") { // Do some additional RC4 decryption
						$id = $row[0];
						$key = $row[3];
						$file_contents = $row[5];
						$decrypted_rc4_msg = rc4_decrypt($key, $file_contents);

					} 
					$content = $conn->real_escape_string($content);
					// if($j == 5) $content = str_replace(" ", "&nbsp;", $content);
					echo "\n<td style=\"word-wrap: break-word\">$content</td>\n";
					
				}



				if($decrypted_rc4_msg) {
					$decrypted_rc4_msg = $conn->real_escape_string($decrypted_rc4_msg);
					echo "<td><button type='button' onclick=\"show_rc4('$decrypted_rc4_msg', '$id')\">\n Decrypt</button></td>";
				} else {
					echo '<td><button type="button" onclick="decrypt_text(this.parentNode.parentNode)">Decrypt</button></td>';
				}
				
				echo '</tr>';
			}
		} else echo "<p>You don't have any content right now, go <a href='index.php'>back to index</a> to enter some content!</h3><p>";
		

	}

	function rc4_decrypt($key, $text) {
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

 ?>
