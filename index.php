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
	<script type="text/javascript" src="javascript.js"></script>
</head>

<body id="body">

<div class="w3-container w3-center">
	<h1 style='font-family: "Space Bd BT", Arial, sans-serif'>Pantry Raid</h1> 
	<h5 style='font-family: "Space Bd BT", Arial, sans-serif'>Welcome 
		<?php 
			echo $_SESSION['user_name'];
			if (isset($_SESSION['list_name'])) {
					echo ". You are currently viewing list '" . $_SESSION['list_name'] . "'";
			} 
		?>
	</h5>
	<h5 style='font-family: "Space Bd BT", Arial, sans-serif'><a href = "manage_lists.php">Manage lists</a> >>> <a href = "logout.php">Sign Out</a></h5>
</div>

<div class="w3-container">	
	<div class="w3-section">			
		<button id="btnAdd" class="w3-button w3-medium w3-round w3-ripple w3-blue " onclick="btnAdd();">Add item</button>
		<button id="btnToggleColumns" class="w3-button w3-medium w3-round w3-ripple w3-blue " onclick="btnToggleColumns();">Toggle Columns</button>
		<?php 
			$linecount = 0;
			$stmt = $conn->prepare("SELECT count(*) as count FROM list_shopping WHERE list_id = ?");
			$stmt->execute([$_SESSION['list_id']]);
			if($stmt->rowCount() == 1){
				if($row = $stmt->fetch()){
					$linecount = $row["count"];
				}			
			} else{
				echo "Oops! Something went wrong. Please try again later.";
				die();
			}
		
			if ($linecount > 0){
				echo '<button id="btnShoppingList" class="w3-button w3-medium w3-round w3-ripple w3-blue" onclick="btnShoppingList();">View Shopping List Items<span class="w3-badge w3-margin-left w3-margin-right w3-white">'.$linecount.'</span></button>';				
			} else {
				echo '<button id="btnShoppingList" class="w3-button w3-medium w3-round w3-ripple w3-blue ninja" onclick="btnShoppingList();" style="display:none;">View Shopping List Items</button>';								
			}
		?>		
		
	</div>
	<div class="w3-section">
		<label class="w3-text-white" style="font-size: 1.0em;">Filter Items</label>
		<input id="inputFilter_1" type="search" class="w3-input w3-border w3-round w3-animate-input" style="width: 30vw; max-width: 80vw;" placeholder="Filter Items" onkeyup="tblFilter(1);">
	</div>
	<div class="w3-section">
		<table id="tblFilter_1" class="w3-table-all w3-card-4 ">
			<thead>
			<tr>
				<th onclick="loaderOn(tblSort,0,'text')">Location</th>
				<th onclick="loaderOn(tblSort,1,'text')">Type</th>
				<th onclick="loaderOn(tblSort,2,'text')">Item</th>
				<th onclick="loaderOn(tblSort,3,'text')" class="w3-tooltip col_mobile">
					<span style="position:absolute;left:0;bottom:40px" class="w3-text w3-tag w3-round-xlarge w3-animate-opacity w3-white">Best purchase or sale price</span>
					Price
				</th>
				<th onclick="loaderOn(tblSort,4,'text')" class="w3-tooltip col_mobile">
					<span style="position:absolute;left:0;bottom:40px" class="w3-text w3-tag w3-round-xlarge w3-animate-opacity w3-white">Earliest expiration date</span>
					Exp Date</th>
				<th onclick="loaderOn(tblSort,5,'number')">Qty</th>
				<th id="th_action">Action</th>
			</tr>
			</thead>
			
			<?php 
				//Get data from db list 
				try {
					$stmt = $conn->prepare("SELECT item_id, location, type, name, qty, price, expiration FROM list_items WHERE list_id = ? ORDER BY location, type, name");
					$stmt->execute([$_SESSION['list_id']]);
					foreach ($stmt as $data) {
						$itmID = $data['item_id'];
						$itmLoc = $data['location'];
						$itmType = $data['type'];
						$itmName = $data['name'];
						$itmQty = (float)$data['qty'];
						$itmPrice = $data['price'];
						$itmExp = $data['expiration'];
						echo '<tr id="'.$itmID.'" class="w3-hover-light-blue">
							<td>'.$itmLoc.'</td>
							<td>'.$itmType.'</td>
							<td>'.$itmName.'</td>
							<td class="col_mobile">'.$itmPrice.'</td>
							<td class="col_mobile">'.$itmExp.'</td>
							<td>'.$itmQty.'</td>
							<td class="col_action">
								<i id="btnMenu_'.$itmID.'" class="fas fa-caret-square-down w3-ripple" style="color:gray;" onclick="btnMenu('.$itmID.');"></i>							
								<i id="btnLess_'.$itmID.'" class="fas fa-plus-square w3-ripple" style="color:green;" onclick="btnMore('.$itmID.');"></i>							
								<i id="btnMore_'.$itmID.'" class="fas fa-minus-square w3-ripple" style="color:red;" onclick="btnLess('.$itmID.');"></i>							
								<i id="btnEdit_'.$itmID.'" class="fas fa-pen-square w3-ripple" style="color:yellow;" onclick="btnEdit('.$itmID.');"></i>							
								<i id="btnAddShoppingList_'.$itmID.'" class="fas fa-cart-plus w3-ripple" style="color:blue;" onclick="btnAddShoppingList('.$itmID.');"></i>							
							</td>
							</tr>';
					}
				}
				catch(PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
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
			<form id="id01_Form" class="w3-container" action="item_delete.php" method="post">
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
			<form id="id02_Form" class="w3-container" action="item_add.php" method="post">
				<input type="hidden" id="id02_ItemID" name="itemID" value="" >
				<br>
				<p>     
					<label class="w3-text-black">Location</label>
					<input name="itemLoc" type="text" list="dlLoc" class="w3-input w3-border required"/>
					<datalist id="dlLoc">
					    <?php 
							//Get unique locations from list
							try {
								$stmt = $conn->prepare("SELECT DISTINCT location FROM list_items WHERE list_id = ? ORDER BY location");
								$stmt->execute([$_SESSION['list_id']]);
								foreach ($stmt as $data) {
									echo '<option>' . $data['location'] . '</option>';
								}
							}
							catch(PDOException $e) {
							    echo '<option>Downstairs Freezer</option>
        						<option>Downstairs Pantry</option>
        						<option>Refrigerator</option>
        						<option>Upstairs Cupboard</option>
        						<option>Upstairs Freezer</option>
        						<option>Upstairs Pantry</option>';
							}
						?>
					</datalist>
				</p>
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
					<label class="w3-text-black">Price</label>
					<input name="itemPrice" class="w3-input w3-border" type="number">
				</p>				
				<p>      
					<label class="w3-text-black">Expiration</label>
					<input name="itemExp" class="w3-input w3-border" type="date">
				</p>
				<p>      
					<label class="w3-text-black">Quantity</label>
					<input name="itemQty" class="w3-input w3-border" type="number" value="1" required>
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