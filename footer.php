<?php  

	$conn->close();

	if(isset($_COOKIE['username'])) echo '<br><br><a href="user_logout.php">Logout</a><br>';

	echo <<< _END
		</body></html>
		<br><br><footer style="float: right; position: absolute; bottom: 0; width: 100%; height: 30px;"><p style="font-size:10px;">Made by Alex Choy, CS 174, Spring 2019</p></footer>
	_END;

?>