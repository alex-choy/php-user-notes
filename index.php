<?php
	require_once 'header.php';


	if(isset($_COOKIE['username'])) {
		session_start();
		$username = $_COOKIE['username'];
		$_SESSION['username'] = $username;
		echo <<< _END
				<h4>Hello $username! Welcome to Decryptoid.<br></h4>
				<div style="border: 1px black solid; padding: 5px; width: 400px;">



					<h4>Place your inputs here.</h4>
					<form id="encrypter_form" action="simple_substitution.php" method="post" enctype="multipart/form-data" >
						<div hidden=true>
							<h4>Would you like to encrypt or decrypt?</h4>
							<label for='encrypt'>
							<input type='radio' value='encrypt' id='encrypt' name='e_or_d' onclick="e_or_d_change(this)" checked>Encrypt</option></label>
							<label for='decrypt'>
							<input type='radio' value='decrypt' id='decrypt' name='e_or_d' onclick="e_or_d_change(this)">Decrypt</option></label>

						</div>




						<h4 id="enc_or_dec_header">What kind of encryption would you like to do?</h4>
						<select id="encrypter_options" onchange="change_encrypter()">
							<option value="simple_substitution">Simple Substitution</option>
							<option value="double_transposition">Double Transpsition </option>
							<option value="rc4">RC4</option>
						</select><br><br>

						<div id='different_keys'>
							<div name='simple_substitution_key'>
								<label id="secret_label"  for="secret_key">Secret Key:
								<input type="text" name="secret_key" id="sub_key"  style="width:160px;" readonly>
								<button type="button" id="change_secret_key" onclick="new_sub_key()">Create Random Key</button><br></label>
							</div>
							<div name='double_transposition_key' hidden='true'>
								No key required for double transposition.<br>
								*Note: All characters will be lowercased and spaces removed.<br><br>
							</div>
							<div name='rc4_key' hidden='true'>
								<label for='rc4_key'>Secret RC4 Key: <input type='text' name='rc4_key' id='rc4_key' minlength='5' maxlength='32'></label>
							</div>

						</div>


						<label for="file_upload">File to encrypt/decrypt: </label>
						<input type="file" name="file_upload" id="file_upload"><br>
						<input type="submit" name="submit">
					</form>
				</div><br><br>

				If you'd like to decrypt your encryptions, go to <a href='user_content.php'>My Content</a>!<br><br>
				*Keep messages fewer than 256 characters.




		_END;



	} else {
		echo "<p><a href='user_login.php'>Login</a> or <a href='user_signup.php'>sign up</a> to use our services.</p><br><br><br><br>";
		echo "To use decryptoid, first login or create an account. <br>After this, submit the files you'd like encrypted, along with the rest of the form. <br>Then you'll be able to see the content you've uploaded, and decrypt whichever files you'd like!";
	}


	require_once 'footer.php';




?>
