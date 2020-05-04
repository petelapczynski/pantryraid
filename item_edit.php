<?php
require_once "config.php";
// define variables and set to empty values
// userID listID itemID itemLoc itemType itemName itemPrice itemExp itemQty
$userID = $listID = $itemID = $itemLoc = $itemType = $itemName = $itemPrice = $itemExp = $itemQty = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$itemID = test_input($_POST["itemID"]);
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
		$stmt = $conn->prepare("UPDATE list_items SET location = ?, type = ?, name = ?, qty = ?, price = ?, expiration = ? WHERE list_id = ? AND item_id = ?");
		$stmt->execute([$itemLoc, $itemType, $itemName, $itemQty, $itemPrice, $itemExp, $listID, $itemID]);
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

function null_input($data) {
	if ($data == "") {
		$data = NULL;
	}
	return $data;
}
?>