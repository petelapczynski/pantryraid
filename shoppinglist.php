<?php
// Hiding notices:
error_reporting(E_ALL^E_NOTICE);
require_once "config.php";
include('session_user.php');
include('session_list.php');
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
	<script type="text/javascript" src="javascript_shoppinglist.js"></script>
</head>

<body id="body">


<div class="w3-container w3-center">
	<h1 style='font-family: "Space Bd BT", Arial, sans-serif'>Pantry Raid</h1>
	<h3 style='font-family: "Space Bd BT", Arial, sans-serif'>Shopping List</h3>
	<h5 style='font-family: "Space Bd BT", Arial, sans-serif'><a href = "manage_lists.php">Manage lists</a> >>> <a href = "logout.php">Sign Out</a></h5>
</div>

<div class="w3-container">	
	<div class="w3-section">			
		<button id="btnAdd" class="w3-button w3-medium w3-round w3-ripple w3-blue " onclick="btnAdd();">Add item</button>
		<button id="btnMainList" class="w3-button w3-medium w3-round w3-ripple w3-blue " onclick="btnMainList();">Back to List</button>
	</div>
	<div class="w3-section">
		<label class="w3-text-white" style="font-size: 1.0em;">Filter Items</label>
		<input id="inputFilter_1" type="search" class="w3-input w3-border w3-round w3-animate-input" style="width: 30vw; max-width: 80vw;" placeholder="Filter Items" onkeyup="tblFilter(1);">
	</div>
	<div class="w3-section">
		<table id="tblFilter_1" class="w3-table-all w3-card-4 ">
			<thead>
			<tr>
				<th onclick="loaderOn(tblSort,0,'text')">Type</th>
				<th onclick="loaderOn(tblSort,1,'text')">Item</th>
				<th onclick="loaderOn(tblSort,2,'number')">Qty</th>
				<th onclick="loaderOn(tblSort,3,'text')">Notes</th>
				<th id="th_action">Action</th>
			</tr>
			</thead>
			
			<?php 			
				//Get data from db list 
				try {
					$stmt = $conn->prepare("SELECT list_shopping_id, list_id, item_id, type, name, qty, notes FROM list_shopping WHERE list_id = ? ORDER BY list_shopping_id");
					$stmt->execute([$_SESSION['list_id']]);
					foreach ($stmt as $data) {
						$itmShopID = $data['list_shopping_id'];
						$itmID = $data['item_id'];
						$itmType = $data['type'];
						$itmName = $data['name'];
						$itmQty = (float)$data['qty'];
						$itmNotes = $data['notes'];
						echo '<tr id="'.$itmShopID.'" item_id="'.$itmID.'" class="w3-hover-light-blue">
							<td>'.$itmType.'</td>
							<td>'.$itmName.'</td>
							<td>'.$itmQty.'</td>
							<td>'.$itmNotes.'</td>
							<td class="col_action">
								<i id="btnMenu_'.$itmShopID.'" class="fas fa-caret-square-down w3-ripple" style="color:gray;" onclick="btnMenu('.$itmShopID.');"></i>							
								<i id="btnLess_'.$itmShopID.'" class="fas fa-plus-square w3-ripple" style="color:green;" onclick="btnMore('.$itmShopID.');"></i>							
								<i id="btnMore_'.$itmShopID.'" class="fas fa-minus-square w3-ripple" style="color:red;" onclick="btnLess('.$itmShopID.');"></i>							
								<i id="btnEdit_'.$itmShopID.'" class="fas fa-pen-square w3-ripple" style="color:yellow;" onclick="btnEdit('.$itmShopID.');"></i>							
							</td>
							</tr>';
					}
				}
				catch(PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
				$conn = null;
			?>
			
		</table>
	</div>
</div>

<!-- Delete Item Modal -->
<div id="id01" class="w3-modal" style="display:none;">
    <div class="w3-modal-content w3-animate-opacity w3-card-4">
		<header class="w3-container w3-teal"> 
			<span onclick="toggleModal('id01');" class="w3-button w3-display-topright">&times;</span>
			<h2>Delete Item</h2>
		</header>
		</header>
		<div class="w3-container">
			<p>Are you sure you want to delete the item?</p>
			<form id="id01_Form" class="w3-container" action="shopping_delete.php" method="post">
				<input type="hidden" id="id01_ItemID" name="itemID" value="" >
			</form>
		</div>
		<footer class="w3-container w3-teal w3-padding">
			<button type="button" class="w3-button w3-large w3-round w3-ripple w3-red w3-right w3-margin-left" onclick="itemDelete();">Delete</button>
			<button type="button" class="w3-button w3-large w3-round w3-ripple w3-light-gray w3-right w3-margin-left" onclick="toggleModal('id01');">Cancel</button>
		</footer>
    </div>
 </div>
 
<!-- Add/Edit Item Modal -->
<div id="id02" class="w3-modal" style="display:none; padding-top: 8vh;">
    <div class="w3-modal-content w3-animate-opacity w3-card-4">
		<header class="w3-container w3-teal"> 
			<span onclick="toggleModal('id02');" class="w3-button w3-display-topright" style="font-size: 2em;">&times;</span>
			<h2 id="id02_Title">Add/Edit Item</h2>
		</header>
		<div class="w3-container">
			<form id="id02_Form" class="w3-container" action="shopping_add.php" method="post">
				<input type="hidden" id="id02_ItemID" name="itemID" value="" >
				<br>
				<p>     
					<label class="w3-text-black">Type</label>
					<input name="itemType" type="text" list="dlType" class="w3-input w3-border" required/>
					<datalist id="dlType">
						<option>Baking</option>
						<option>Beverage</option>
						<option>Condiments</option>
						<option>Dairy</option>
						<option>Fruit</option>
						<option>Grains</option>
						<option>Meat</option>
						<option>Misc</option>
						<option>Pasta</option>
						<option>Sauce</option>
						<option>Snacks</option>
						<option>Vegetable</option>						
					</datalist>
				</p>
				<p>      
					<label class="w3-text-black">Item Name</label>
					<input name="itemName" class="w3-input w3-border" type="text" required>
				</p>
				<p>      
					<label class="w3-text-black">Quantity</label>
					<input name="itemQty" class="w3-input w3-border" type="number" value="1" required>
				</p>
				<p>
					<label class="w3-text-black">Notes, comments, location in store, etc.</label>
					<input name="itemNotes" class="w3-input w3-border" type="text" required>
				</p>				
				<br>
			</form>
		</div>
		<footer class="w3-container w3-teal w3-padding">
			
			<button id="id02_Submit" type="button" class="w3-button w3-large w3-round w3-ripple w3-green w3-right w3-margin-left" onclick="btnAddEditSubmit();">Add/Edit Item</button>
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