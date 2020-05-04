<?php
$servername = "localhost";
$database = "database";
$username = "username";
$password = "password";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
} catch(PDOException $e) {
	die("Error: Connection failed. " . $e->getMessage());
}
?>