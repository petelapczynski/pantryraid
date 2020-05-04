<?php
	require_once "config.php";
	session_start();
	
	$sql = "SELECT lists.list_id, lists.list_uuid, lists.list_name FROM lists, list_users WHERE lists.list_id = list_users.list_id AND list_users.user_id = :userid ORDER BY list_users.last_selected DESC";
	if($stmt = $conn->prepare($sql)){
		$stmt->bindParam(":userid", $param_userid, PDO::PARAM_STR);
		$param_userid = $_SESSION['user_id'];
		if($stmt->execute()){
			if($stmt->rowCount() >= 1){
				if($row = $stmt->fetch()){
					$_SESSION['list_id'] = $row["list_id"];
					$_SESSION['list_code'] = $row["list_uuid"];
					$_SESSION['list_name'] = $row["list_name"];
				}
			}
		} else {
			echo "Oops! Something went wrong. Please try again later.";
		}
	unset($stmt);
	}
	
	if(!isset($_SESSION['list_id'])){
		header("location: manage_lists.php");
		die();
	}
	
?>