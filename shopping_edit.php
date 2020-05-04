<?php
require_once "config.php";
// define variables and set to empty values
// userID listID itemID itemLoc itemType itemName itemQty itemNotes
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
		$stmt = $conn->prepare("UPDATE list_shopping SET type = ?, name = ?, qty = ?, notes = ? WHERE list_id = ? AND list_shopping_id = ?");
		$stmt->execute([$itemType, $itemName, $itemQty, $itemNotes, $listID, $itemID]);
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