<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_name = :username";
        
        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $user_id = $row["user_id"];
                        $username = $row["user_name"];
                        $hashed_password = $row["user_password"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION['loggedin'] = true;
                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['user_name'] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($conn);
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
			</div>
			<div class="w3-section">			
				<button type="submit" class="w3-button w3-medium w3-round w3-ripple w3-blue">Login</button>
				<p class="w3-text-white" style="font-size: 1.0em;">Need an account? <a href="signup.php">Sign Up here</a>.</p>
			</div>
		</div>
	</form>
	<div style = "font-size:15px; color:#cc0000; margin-top:10px"><?php echo $username_err; ?></div>
	<div style = "font-size:15px; color:#cc0000; margin-top:10px"><?php echo $password_err; ?></div>
</div>
 
<!-- Loading Interstatial -->
<div id="processing">
	<div id="loader"></div>
</div>

</body>
</html>