<?php
	require_once "config.php";
// Hiding notices:
//error_reporting(E_ALL^E_NOTICE);
	session_start();
	
	$username = $password = $confirm_password = "";
	$username_err = $password_err = $confirm_password_err = "";
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Validate username
		if(empty(trim($_POST["username"]))){
			$username_err = "Please enter a username.";
		} else{
			// Prepare a select statement
			$sql = "SELECT user_id FROM users WHERE user_name = :username";
			
			if($stmt = $conn->prepare($sql)){
				// Bind variables to the prepared statement as parameters
				$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
				
				// Set parameters
				$param_username = trim($_POST["username"]);
				
				// Attempt to execute the prepared statement
				if($stmt->execute()){
					if($stmt->rowCount() == 1){
						$username_err = "This username is already taken.";
					} else{
						$username = trim($_POST["username"]);
					}
				} else{
					echo "Oops! Something went wrong. Please try again later.";
				}

				// Close statement
				unset($stmt);
			}
		}
		
		// Validate password
		if(empty(trim($_POST["password"]))){
			$password_err = "Please enter a password.";     
		} elseif(strlen(trim($_POST["password"])) < 6){
			$password_err = "Password must have at least 6 characters.";
		} else{
			$password = trim($_POST["password"]);
		}
		
		// Validate confirm password
		if(empty(trim($_POST["confirm_password"]))){
			$confirm_password_err = "Please confirm password.";     
		} else{
			$confirm_password = trim($_POST["confirm_password"]);
			if(empty($password_err) && ($password != $confirm_password)){
				$confirm_password_err = "Password did not match.";
			}
		}
		
		// Check input errors before inserting in database
		if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
			
			// Prepare an insert statement
			$sql = "INSERT INTO users (user_name, user_password) VALUES (:username, :password)";
			 
			if($stmt = $conn->prepare($sql)){
				// Bind variables to the prepared statement as parameters
				$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
				$stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
				
				// Set parameters
				$param_username = $username;
				//$param_password = $password;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
				
				// Attempt to execute the prepared statement
				if($stmt->execute()){
					// Redirect to login page
					header("location: login.php");
				} else{
					echo "Something went wrong. Please try again later.";
				}
				// Close statement
				unset($stmt);
			}
		}
	}	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Pantry Raid</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	 <meta name="viewport" content="width=device-width" initial-scale=1 />
	<link rel="stylesheet" title="main" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="https://www.w3schools.com/w3css/4/w3.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
	<script type="text/javascript" src="javascript.js"></script>
</head>

<body id="body">
<div class="w3-container w3-center">
	<h1 style='font-family: "Space Bd BT", Arial, sans-serif'>Pantry Raid</h1>
	<h3 style='font-family: "Space Bd BT", Arial, sans-serif'>The world's foremost pantry storage network.</h3>	
	<form action="" method="post">
		<div class="w3-container w3-center" style="display: inline-block;">
			<div class="w3-section" style="margin:auto">
				<p style="text-align: left;">
					<label class="w3-text-white" style="font-size: 1.0em;">Username</label>
					<input type="text" class="w3-input w3-border w3-round" placeholder="Enter Username" name="username" required>
				</p>
				<p style="text-align: left;">
					<label class="w3-text-white" style="font-size: 1.0em;">Password</label>
					<input type="password" class="w3-input w3-border w3-round" placeholder="Enter Password" name="password" required>
				</p>
				<p style="text-align: left;">
					<label class="w3-text-white" style="font-size: 1.0em;">Confirm Password</label>
					<input type="password" class="w3-input w3-border w3-round" placeholder="Confirm Password" name="confirm_password" required>
				</p>
			</div>
			<div class="w3-section">			
				<button type="submit" class="w3-button w3-medium w3-round w3-ripple w3-blue">Sign Up</button>
				<p class="w3-text-white" style="font-size: 1.0em;">Already have an account? <a href="login.php">Login here</a>.</p>
			</div>
		</div>
	</form>
	<div style = "font-size:15px; color:#cc0000; margin-top:10px"><?php echo $username_err; ?></div>
	<div style = "font-size:15px; color:#cc0000; margin-top:10px"><?php echo $password_err; ?></div>
	<div style = "font-size:15px; color:#cc0000; margin-top:10px"><?php echo $confirm_password_err; ?></div>
</div>
 
<!-- Loading Interstatial -->
<div id="processing">
	<div id="loader"></div>
</div>

</body>
</html>