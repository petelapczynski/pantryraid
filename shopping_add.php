<?php
require_once "config.php";
// define variables and set to empty values
// userID listID itemID itemType itemName itemQty itemNotes
$userID = $listID = $itemID = $itemType = $itemName = $itemQty = $itemNotes = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$itemID = test_input($_POST["itemID"]);
	$itemType = test_input($_POST["itemType"]);
	$itemName = test_input($_POST["itemName"]);
	$itemQty = test_input($_POST["itemQty"]);
	$itemNotes = test_input($_POST["itemNotes"]);	

	session_start();
	$userID = $_SESSION['user_id'];
	$listID = $_SESSION['list_id'];
	
	try {
		$stmt = $conn->prepare("SELECT * FROM list_shopping WHERE list_id = ? AND item_id = ? AND type = ? AND name = ?");
		$stmt->execute([$listID, $itemID, $itemType, $itemName]);
		if($stmt->rowCount() == 0){
			$stmt = $conn->prepare("INSERT INTO list_shopping (list_id, item_id, type, name, qty, notes) VALUES (?, ?, ?, ?, ?, ?)");
			$stmt->execute([$listID, $itemID, $itemType, $itemName, $itemQty, $itemNotes]);
			$last_id = $conn->lastInsertId();
			echo "success";
		} else{
			// Item exists
			if($row = $stmt->fetch()){
				$qty = $row["qty"] + $itemQty;
				$stmt = $conn->prepare("UPDATE list_shopping SET qty = ? WHERE list_id = ? AND item_id = ? AND type = ? AND name = ?");
				$stmt->execute([$qty, $listID, $itemID, $itemType, $itemName]);
				echo "success";
			}
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
?>