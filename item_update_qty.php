<?php
require_once "config.php";
// define variables and set to empty values
// userID listID itemID itemQty
$userID = $listID = $itemID = $itemQty = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$itemID = test_input($_POST["itemID"]);
	$itemQty = test_input($_POST["itemQty"]);

	session_start();
	$userID = $_SESSION['user_id'];
	$listID = $_SESSION['list_id'];
	
	try {
		$stmt = $conn->prepare("UPDATE list_items SET qty = ? WHERE list_id = ? AND item_id = ?");
		$stmt->execute([$itemQty, $listID, $itemID]);
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