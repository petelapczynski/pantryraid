<?php
// Hiding notices:
error_reporting(E_ALL^E_NOTICE);
require_once "config.php";
include('session_user.php');
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
	<script type="text/javascript" src="javascript_managelists.js"></script>
</head>

<body id="body">

<div class="w3-container w3-center">
	<h1 style='font-family: "Space Bd BT", Arial, sans-serif'>Pantry Raid</h1>
	<h3 style='font-family: "Space Bd BT", Arial, sans-serif'>Manage Lists</h3>
	<h5 style='font-family: "Space Bd BT", Arial, sans-serif'>Welcome 
	<?php 
		echo $_SESSION['user_name']; 
	?> 
	>>> <a href = "logout.php">Sign Out</a></h5>
	<form id="listForm" action="" method="post">
		<div class="w3-container w3-center" style="display: inline-block;">
			<div class="w3-section" style="margin:auto">
				<p style="text-align: left;">
					<label class="w3-text-white" style="font-size: 1.0em; display:block;">Select a list
					<?php 
						if (isset($_SESSION['list_code']) && !empty($_SESSION['list_code'])) {
							echo "- Current List: " . $_SESSION['list_name'] . ", List Code: " . $_SESSION['list_code'];
						}	
					?>
					</label>
					<span>
						<select id="listid" class="w3-select w3-border w3-round w3-left w3-margin-right" name="listid" onchange="listSelection();">
							<option value="" disabled selected>Select a list</option>
							<?php
								// query list info
								try {
									$sql = "SELECT lists.list_id, lists.list_name, lists.list_uuid 
									FROM lists, list_users, users 
									WHERE lists.list_id = list_users.list_id 
									AND list_users.user_id = users.user_id AND users.user_id = ? order by lists.list_id DESC";
									$stmt = $conn->prepare($sql);
									$stmt->execute([$_SESSION['user_id']]);
									foreach ($stmt as $data) {
										$listID = $data['list_id'];
										$listName = $data['list_name'];
										$listCode = $data['list_uuid'];
										echo '<option value="' . $listID . '">' . $listName . '</option>';	
									}
								}
								catch(PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
								
														
							?>
						</select>
						<button id="btnSelectList" type="button" class="w3-button w3-medium w3-round w3-ripple w3-blue w3-left" onclick="btnSubmitSelectList();">Select List</button>
						<button id="btnEditList" type="button" class="w3-button w3-medium w3-round w3-ripple w3-yellow w3-left" onclick="btnClickEditList();" style="display:none;">Edit List</button>
						<button id="btnDeleteList" type="button" class="w3-button w3-medium w3-round w3-ripple w3-red w3-left" onclick="btnClickDeleteList();" style="display:none;">Delete List</button>
					</span>
				</p>
				<div style="clear:both;"></div>
				
				<p style="text-align: left;">
					<label class="w3-text-white" style="font-size: 1.0em; display:block;">Create a new list</label>
					<span>	
						<input id="listname" type="text" class="w3-input w3-border w3-round w3-left w3-margin-right" placeholder="Enter a list name" name="listname">
						<button id="btnCreateList" type="button" class="w3-button w3-medium w3-round w3-ripple w3-blue w3-left" onclick="btnSubmitCreateList();">Create List</button>
					</span>
				</p>
				<div style="clear:both;"></div>
				
				<p style="text-align: left;">
					<label class="w3-text-white" style="font-size: 1.0em; display:block;">Link another members shared list</label>
					<span>	
						<input id="listcode" type="text" class="w3-input w3-border w3-round w3-left w3-margin-right" placeholder="Enter the list code" name="listcode">
						<button id="btnLinkList" type="button" class="w3-button w3-medium w3-round w3-ripple w3-blue w3-left" onclick="btnSubmitLinkList();">Link List</button>
					<span>
				</p>
				<div style="clear:both;"></div>
			</div>
		</div>
	</form>
</div>
 
 
 <!-- Delete List Modal -->
<div id="id01" class="w3-modal" style="display:none;">
    <div class="w3-modal-content w3-animate-opacity w3-card-4">
		<header class="w3-container w3-teal"> 
			<span onclick="toggleModal('id01');" class="w3-button w3-display-topright">&times;</span>
			<h2>Delete List</h2>
		</header>
		</header>
		<div class="w3-container">
			<p>Are you sure you want to delete the List?</p>
			<form id="id01_Form" class="w3-container" action="list_delete.php" method="post">
				<input type="hidden" id="id01_ListID" name="listID" value="" >
			</form>
		</div>
		<footer class="w3-container w3-teal w3-padding">
			<button type="button" class="w3-button w3-large w3-round w3-ripple w3-red w3-right w3-margin-left" onclick="listDelete();">Delete</button>
			<button type="button" class="w3-button w3-large w3-round w3-ripple w3-light-gray w3-right w3-margin-left" onclick="toggleModal('id01');">Cancel</button>
		</footer>
    </div>
 </div>
 
<!-- Edit List Modal -->
<div id="id02" class="w3-modal" style="display:none; padding-top: 8vh;">
    <div class="w3-modal-content w3-animate-opacity w3-card-4">
		<header class="w3-container w3-teal"> 
			<span onclick="toggleModal('id02');" class="w3-button w3-display-topright" style="font-size: 2em;">&times;</span>
			<h2 id="id02_Title">Edit List</h2>
		</header>
		<div class="w3-container">
			<form id="id02_Form" class="w3-container" action="list_edit.php" method="post">
				<input type="hidden" id="id02_ListID" name="listID" value="" >
				<br>
				<p>      
					<label class="w3-text-black">List Name</label>
					<input name="listName" class="w3-input w3-border" type="text" required>
				</p>		
				<br>
			</form>
		</div>
		<footer class="w3-container w3-teal w3-padding">
			
			<button id="id02_Submit" type="button" class="w3-button w3-large w3-round w3-ripple w3-green w3-right w3-margin-left" onclick="btnEditSubmit();">Save changes</button>
			<button type="button" class="w3-button w3-large w3-round w3-ripple w3-light-gray w3-right w3-margin-left" onclick="toggleModal('id02');">Cancel</button>
		</footer>
    </div>
</div> 
 
<!-- Loading Interstatial -->
<div id="processing">
	<div id="loader"></div>
</div>

</body>
</html>