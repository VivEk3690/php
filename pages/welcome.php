<?php
// Start the session
session_start();
include('config.php');
include('function.php');

// Check if the user is not logged in
if (!isset($_SESSION["userName"])) {
	menuBar();
	echo '<h1>You need to login first.</h1>';

} else {
	menuBar();

	// Connect to the database
	$servername = DB_SERVER;
	$username = DB_USERNAME;
	$password = DB_PASSWORD;
	$dbname = DB_NAME;

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	// Get the user's data from the player table
	$registrationOrder = $_SESSION["registrationOrder"];
	$sql = "SELECT fName, lName FROM player WHERE registrationOrder = '$registrationOrder'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	$fName = $row["fName"];
	$lName = $row["lName"];

	// initialise array of letters
	$letters = array();
	for ($i = 0; $i < 6; $i++) {
		$letters[] = chr(rand(97, 122)); // Generate a random letter
	}
	//echo implode(" ", $letters);
	$_SESSION["letter"] = $letters;

	// initialise array of numbers
	$numbers = array();
	for ($i = 0; $i < 6; $i++) {
		$numbers[] = rand(0, 100); // Generate a random number 
	}
	//echo implode(" ", $letters);
	$_SESSION["number"] = $numbers;


	//initialise lives
	$_SESSION["lives"] = 6;
	$_SESSION["status"] = "incomplete";

	mysqli_close($conn);
}
?>
<?php
if(isset($_SESSION["userName"])){
echo <<<_END
	<!DOCTYPE html>
<html>

<head>
	<title>Welcome</title>
	<!-- <link rel="stylesheet" type="text/css" href="finalStyle.css"> -->
</head>

<body>
	<h2>Welcome,
	$fName $lName !
	</h2>
	<p>You are now logged in.</p>
	<p>to play game <a href="level1.php">Click here</a></p>
	<br />
	<a href="logout.php">Logout</a>
</body>

</html>
_END;
}
		// <?php echo "$fName $lName";

?>