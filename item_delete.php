<?php
require_once "config.php";
// define variables and set to empty values
// userID listID itemID
$userID = $listID = $itemID = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$itemID = test_input($_POST["itemID"]);

	session_start();
	$userID = $_SESSION['user_id'];
	$listID = $_SESSION['list_id'];

	try {
		$stmt = $conn->prepare("DELETE FROM list_items WHERE list_id = ? AND item_id = ?");
		$stmt->execute([$listID, $itemID]);
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