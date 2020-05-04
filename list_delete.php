<?php
require_once "config.php";
// define variables and set to empty values
// user_ID, listid
$userID = $listID = "" ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
		
		$stmt = $conn->prepare("DELETE FROM list_shopping WHERE list_id = ?");
		$stmt->execute([$listID]);		
		
		$stmt = $conn->prepare("DELETE FROM list_items WHERE list_id = ?");
		$stmt->execute([$listID]);
		
		$stmt = $conn->prepare("DELETE FROM list_users WHERE list_id = ? AND user_id = ?");
		$stmt->execute([$listID, $userID]);
		
		$stmt = $conn->prepare("DELETE FROM lists WHERE list_id = ?");
		$stmt->execute([$listID]);

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