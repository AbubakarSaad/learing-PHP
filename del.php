<?php
	
	$id = $_GET['id'];

	$sname = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "PRD";

	$conn = mysqli_connect($sname, $username, $password, $dbname);

	if(!$conn){
		die("Connection Failed: " . mysqli_connect_error());
	}

	$sql = "DELETE 
			FROM Patient
			WHERE Patientid=" . $id;

	$result = $conn->query($sql);

	$conn->close();
	
	header("Location: history.php");
?>