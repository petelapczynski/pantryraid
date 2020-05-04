<?php
require_once "config.php";
// define variables and set to empty values
// userID itemLoc itemType itemName itemPrice itemExp itemQty
$userID = $listID = $itemLoc = $itemType = $itemName = $itemPrice = $itemExp = $itemQty = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$itemLoc = test_input($_POST["itemLoc"]);
	$itemType = test_input($_POST["itemType"]);
	$itemName = test_input($_POST["itemName"]);
	$itemPrice = null_input(test_input($_POST["itemPrice"]));
	$itemExp = null_input(test_input($_POST["itemExp"]));
	$itemQty = test_input($_POST["itemQty"]);	

	session_start();
	$userID = $_SESSION['user_id'];
	$listID = $_SESSION['list_id'];
	
	try {
		$stmt = $conn->prepare("SELECT * FROM list_items WHERE list_id = ? AND location = ? AND type = ? AND name = ?");
		$stmt->execute([$listID, $itemLoc, $itemType, $itemName]);
		if($stmt->rowCount() == 0){
			$stmt = $conn->prepare("INSERT INTO list_items (list_id, location, type, name, qty, price, expiration) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->execute([$listID, $itemLoc, $itemType, $itemName, $itemQty, $itemPrice, $itemExp]);
			$last_id = $conn->lastInsertId();
			echo "success";
		} else{
			echo "Error: Item exists";
		}
		
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

function null_input($data) {
	if ($data == "") {
		$data = NULL;
	}
	return $data;
}
?>