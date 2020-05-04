<?php
require_once "config.php";
// define variables and set to empty values
// user_ID, listcode
$userID = $listUUID = $listID = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$listUUID = test_input($_POST["listcode"]);
	
	session_start();
	$userID = $_SESSION['user_id'];
	
	try {
		$stmt = $conn->prepare("SELECT list_id FROM lists WHERE list_uuid = ?");
		$stmt->execute([$listUUID]);
		if($stmt->rowCount() == 1){
			//list exists
			if($row = $stmt->fetch()){
				$listID = $row["list_id"];
			}
		} else{
			echo "Error. Invalid List Code.";
			die();
		}
		
		$stmt = $conn->prepare("SELECT list_id FROM list_users WHERE list_id = ? AND user_id = ?");
		$stmt->execute([$listID, $userID]);
		if($stmt->rowCount() == 1){
			//list exists
			echo "Error: Duplicate.";
			die();
		} 
		
		$stmt = $conn->prepare("INSERT INTO list_users (list_id, user_id) VALUES (?, ?)");
		$stmt->execute([$listID, $userID]);
		$last_id = $conn->lastInsertId();
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