<?php
require_once "config.php";
// define variables and set to empty values
// user_ID, listname
$userID = $listName = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$listName = test_input($_POST["listname"]);
	
	session_start();
	$userID = $_SESSION['user_id'];
	$listUUID = uniqid();
	
	try {
		
		$stmt = $conn->prepare("INSERT INTO lists (list_name, list_uuid) VALUES (?, ?)");
		$stmt->execute([$listName, $listUUID]);
		$last_id = $conn->lastInsertId();
		$stmt = $conn->prepare("INSERT INTO list_users (list_id, user_id) VALUES (?, ?)");
		$stmt->execute([$last_id, $userID]);
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