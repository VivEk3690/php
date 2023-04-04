<!DOCTYPE html>
<html>
<head>
	<title>Modify Password Form</title>
</head>
<body>
	<h1>Modify Password Form</h1>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label for="username">Existing Username:</label>
		<input type="text" name="username" required><br><br>
		<label for="new_password">New Password:</label>
		<input type="password" name="new_password" required><br><br>
		<label for="confirm_new_password">Confirm New Password:</label>
		<input type="password" name="confirm_new_password" required><br><br>
		<input type="submit" name="modify" value="Modify">
		<input type="submit" name="sign_in" value="Sign-In">
	</form>

	<?php
	// Define database credentials
	$servername = "localhost";
	$username = "your_username";
	$password = "your_password";
	$dbname = "kidsGames";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Check which button was clicked
		if (isset($_POST["modify"])) {
			// Retrieve form data
			$username = mysqli_real_escape_string($conn, $_POST["username"]);
			$new_password = mysqli_real_escape_string($conn, $_POST["new_password"]);
			$confirm_new_password = mysqli_real_escape_string($conn, $_POST["confirm_new_password"]);

			// Check if username exists in the database
			$sql = "SELECT registrationOrder FROM player WHERE userName = '$username'";
			$result = $conn->query($sql);

			if ($result->num_rows == 0) {
				// Username does not exist, display error message
				echo "Sorry, this username does not exist. Please enter a valid username.";
			} else {
				// Username exists, check if passwords match
				if ($new_password != $confirm_new_password) {
					// Passwords do not match, display error message
					echo "Sorry, the passwords you entered do not match. Please try again.";
				} else {
					// Passwords match, update password in the database
					$row = $result->fetch_assoc();
					$registration_order = $row["registrationOrder"];
					$pass_code = password_hash($new_password, PASSWORD_DEFAULT);
					$sql = "UPDATE authenticator SET passCode = '$pass_code' WHERE registrationOrder = '$registration_order'";

					if ($conn->query($sql) === TRUE) {
						// Password updated successfully, redirect to login page with success message
						header("Location: login.php?password_updated=1");
						exit;
					} else {
						// Error updating password, display error message
						echo "Error: " . $conn->error;
					}
				}
			}
		} elseif (isset($_POST["sign_in"])) {
			// Sign-In button was clicked, redirect to login page
			header("Location: login.php");
			exit;
		}
	}

	// Close connection
	$conn->close();
	?>
</body>
</html>
