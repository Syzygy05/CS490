<?php
    $dbServerName = "sql1.njit.edu";
    $dbUser = "spp34";
	$dbPassword = "Discovery05!";
	$dbName = "spp34";
	$conn = mysqli_connect($dbServerName, $dbUser, $dbPassword, $dbName);
	 
	if (!$conn)
    	die("Connection failed: " . mysqli_connect_error());
	$v = $_POST['credentials'];
	$data = json_decode($v);

	// User ID and Hashed Password from Middle End
	$user=$data->{'user'};
	$pw_hashed = $data->{'password'};

	//No need for hardcoded user here - have that in the database directly, and just type that into frontend like any credential

	// SQL Query 
	$sql = "SELECT * FROM USERLOG WHERE USER='$user' AND PASS='$pw_hashed'";
	// SQL Query Results
	$result = $conn->query($sql);

	// Check if result exists
	if ($result->num_rows > 0)
		$login_status = "success";
	else
		$login_status = "failure";

	mysqli_close($conn); //close connection before returning from script

	echo '{"njit":"", "local":"' . $login_status . '"}'; //return JSON formatted string Result directly to mid
?>
