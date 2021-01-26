<?php
include("config.php");

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
} catch(PDOException $e) {
	echo "error";
	die("Error: Connection failed. " . $e->getMessage());
}


// LOAD TABLE [list_items]
/*	
	try {

		$stmt = $conn->prepare("INSERT INTO list_items (list_id, location, type, name, qty, price, expiration) 
		VALUES (:list_id, :location, :type, :name, :qty, :price, :expiration)");
		
		$stmt->bindParam(':list_id', $list_id);
		$stmt->bindParam(':location', $itmLoc);
		$stmt->bindParam(':type', $itmType);
		$stmt->bindParam(':name', $itmName);
		$stmt->bindParam(':qty', $itmQty);
		$stmt->bindParam(':price', $itmPrice);
		$stmt->bindParam(':expiration', $itmExp);
				
		$list_id = 1;

		//Get data from CSV 
		$row = 1;
		$file = fopen("data_05042020.csv","r");
		
		//Loop records
		while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
			$itmLoc = $data[0];
			$itmType = $data[1];
			$itmName = $data[2];
			$itmQty = $data[3];
			$itmPrice = $data[4];
			$itmPrice = NULL;
			$itmExp = $data[5];
			$itmExp = NULL;
					
			$row++;
			
			$stmt->execute();
			
		}
		//Close file
		fclose($file);
			
		$last_id = $conn->lastInsertId();
		echo "New record(s) created successfully. Last ID: " . $last_id;
	} catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
    }
	$conn = null;
*/

// LOAD table [list_shopping]
/*
	try {

		$stmt = $conn->prepare("INSERT INTO list_shopping (list_id, type, name, qty, notes) 
		VALUES (:list_id, :type, :name, :qty, :notes)");
		
		$stmt->bindParam(':list_id', $list_id);
		$stmt->bindParam(':type', $itmType);
		$stmt->bindParam(':name', $itmName);
		$stmt->bindParam(':qty', $itmQty);
		$stmt->bindParam(':notes', $notes);
				
		$list_id = 1;

		//Get data from CSV 
		$row = 1;
		$file = fopen("data_shoppinglist.csv","r");
		
		//Loop records
		while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
			$itmType = $data[1];
			$itmName = $data[2];
			$itmQty = $data[3];
			$notes = $data[4];
					
			$row++;
			
			$stmt->execute();
			
		}
		//Close file
		fclose($file);
			
		$last_id = $conn->lastInsertId();
		echo "New record(s) created successfully. Last ID: " . $last_id;
	} catch(PDOException $e) {
		echo $sql . "<br>" . $e->getMessage();
    }
	$conn = null;
*/
?>
