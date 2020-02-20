<?php

    $dbServerName = "sql1.njit.edu";
    $dbUser = "spp34";
	$dbPassword = "Discovery05!";
	$dbName = "spp34";

	$conn = mysqli_connect($dbServerName, $dbUser, $dbPassword, $dbName);
	 
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}

	echo "Connected successfully\n";



	$v = $_POST['credentials'];
	$data = json_decode($v);



	// User ID and Hashed Password from Middle End
	$user=$data->{'name'};
	$pw_hashed = $data->{'password'};


	// Test case
	$user = "spp34";
	$pw_hashed = "89a7e6eabbc4c9477277ec9b246c6417dc352e69418bf3ef4d75e9c19bbbedd6";


	// SQL Query 
	$sql = "SELECT USER, PASS FROM USERLOG WHERE USER='$user'";
	// SQL Query Results
	$result = $conn->query($sql);


	$login_status = FALSE;
	// Check if result exists
	if ($result->num_rows > 0) {

		$row = $result->fetch_assoc();

		$u = $row["USER"];
		$hp = $row["PASS"];

		if ($pw_hashed == $hp) {
			$login_status = TRUE;
			echo "Login Successful\n";
		}
	}
	else {
		$login_status = FALSE;
		echo "Username not in database";
	}
	
	$sts = new StdClass;
	if ($login_status == TRUE) {
		$sts->local = True;
	}
	else {
		$sts->local = FALSE;
	}

	$h = json_encode($sts);

	$ch = curl_init();

	// Replace URL with Middle endpoint
	curl_setopt($ch, CURLOPT_URL, "https://web.njit.edu/~ml637/testing/back.php");
	curl_setopt($ch, CURLOPT_POST, TRUE);

	// login results
	curl_setopt($ch, CURLOPT_POSTFIELDS, "local=".$h);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	$output = curl_exec($ch);

	echo $output, "\n";

	curl_close($ch);
	
	/*

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        #echo "<tr><td>".$row["USER"]."</td><td>".$row["PASS"]." </td></tr>";
	        echo "".$row["USER"]."\t".$row["PASS"];
	        echo "\n";

    	}
	} 
	else {
    	echo "0 results";
	}

	echo "\n";
	

	*/

	mysqli_close($conn);
?>