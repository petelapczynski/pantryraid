<?php
	require_once "config.php";
	session_start();
   
	$user_check = $_SESSION['user_name'];
	// Prepare a select statement
	$sql = "SELECT user_id, user_name, user_password FROM users WHERE user_name = :username";
	if($stmt = $conn->prepare($sql)){
		// Bind variables to the prepared statement as parameters
		$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
		// Set parameters
		$param_username = trim($user_check);
		// Attempt to execute the prepared statement
		if($stmt->execute()){
			// Check if username exists, if yes then verify password
			if($stmt->rowCount() == 1){
				if($row = $stmt->fetch()){
					// Store data in session variables
					$_SESSION['loggedin'] = true;
					$_SESSION['user_id'] = $row["user_id"];
					$_SESSION['user_name'] = $row["user_name"];
				}
			}
		} else{
			echo "Oops! Something went wrong. Please try again later.";
		}
		// Close statement
		unset($stmt);
	}
	
	if(!isset($_SESSION['user_name'])){
		header("location: login.php");
		die();
	}
?>