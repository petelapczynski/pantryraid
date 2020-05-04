<?php
require_once "config.php";
// define variables and set to empty values
// user_ID, listid
$userID = $listID = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$listID = test_input($_POST["listid"]);
	
	session_start();
	$userID = $_SESSION['user_id'];
	
	try {
		$stmt = $conn->prepare("SELECT list_users.list_id, lists.list_uuid, lists.list_name FROM list_users, lists WHERE list_users.list_id = lists.list_id AND list_users.user_id = ? AND list_users.list_id = ?");
		$stmt->execute([$userID, $listID]);
		if($stmt->rowCount() == 1){
			//user has access to list
			if($row = $stmt->fetch()){
				$_SESSION['list_id'] = $listID;
				$_SESSION['list_code'] = $row["list_uuid"];
				$_SESSION['list_name'] = $row["list_name"];
			}	
		} else{
			echo "Oops! Something went wrong. Please try again later.";
			die();
		}
		$stmt = $conn->prepare("UPDATE list_users SET last_selected = NOW() WHERE user_id = ? AND list_id = ?");
		$stmt->execute([$userID, $listID]);
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