<?php
require_once "config.php";
// define variables and set to empty values
// user_ID, listname, listid
$userID = $listName = $listID = "" ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$listName = test_input($_POST["listname"]);
	$listID = test_input($_POST["listid"]);
	
	session_start();
	$userID = $_SESSION['user_id'];
	
	try {
		
		$stmt = $conn->prepare("SELECT list_id FROM list_users WHERE user_id = ? AND list_id = ?");
		$stmt->execute([$userID, $listID]);
		if($stmt->rowCount() == 1){
			//user has access to list
		} else{
			echo "Oops! Something went wrong. Please try again later.";
			die();
		}
		$stmt = $conn->prepare("UPDATE lists SET list_name = ? WHERE list_id = ?");
		$stmt->execute([$listName, $listID]);
		echo "success";
	}
	catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>