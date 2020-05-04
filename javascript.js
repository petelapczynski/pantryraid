function loaderOn(callback, n, type) {
	document.getElementById('processing').style.display = 'block';
		if (! callback === undefined) {
			setTimeout(function(){
				document.getElementById('processing').style.display = 'block';
				callback(n, type);
			}, 1);			
		}
}

function loaderOff() {
	document.getElementById('processing').style.display = 'none';
	//setVisibility('processing', 'none', callback);
}

function toggleModal(id) {
	if (document.getElementById(id).style.display == "none") {
		document.getElementById(id).style.display = "block";
	} else {
		document.getElementById(id).style.display = "none";
	}
}

function tblFilter(ID) {
	document.getElementById('processing').style.display = 'block';
	var input, filter, arrFilter, table, tr, td;
	input = document.getElementById("inputFilter_" + ID);
	filter = input.value.toUpperCase();
	arrFilter = filter.split(" ");
	table = document.getElementById("tblFilter_" + ID);
	tr = table.getElementsByTagName("tr");
	
	for (var i = 1; i < tr.length; i++) {
		var tds = tr[i].getElementsByTagName("td");
		var flags = [];
		
		for (var k = 0; k < arrFilter.length; k++) {
			var flag = false;
			
			for (var j = 0; j < tds.length; j++) {
				var td = tds[j];
				if (td.innerText.toUpperCase().indexOf( arrFilter[k] ) > -1) {
					flag = true;
					break;
				} 
			}
			flags.push(flag);
		}
		
		if (flags.includes(false)) {
			tr[i].style.display = "none";
		} else {
			tr[i].style.display = "";
		}
	}
	document.getElementById('processing').style.display = 'none';
}

 function tblSort(n, type) {
  // n is column number, type can be text/number
  document.getElementById('processing').style.display = 'block';
  var table, rows, switching, i, x, xVal, y, yVal, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("tblFilter_1");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
	  
	  if (type == "text") {
		  xVal = x.innerHTML.toLowerCase();
		  yVal = y.innerHTML.toLowerCase();		  
	  } else if (type == "number") {
		  xVal = parseFloat(x.innerHTML.toLowerCase());
		  yVal = parseFloat(y.innerHTML.toLowerCase());
	  } else {
		  break;
	  }
	 
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (xVal > yVal) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (xVal < yVal) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
  document.getElementById('processing').style.display = 'none';
}

function btnToggleColumns() {
	var elms = document.getElementsByClassName("col_mobile");
	for (i = 0; i < elms.length; i++) {
		elms[i].classList.toggle("in")
	}
}

function btnShoppingList() {
	window.location.href = "shoppinglist.php";
}

function btnMenu(itmID) {
	document.getElementById("btnLess_" + itmID).classList.toggle("in");
	document.getElementById("btnMore_" + itmID).classList.toggle("in");
	document.getElementById("btnEdit_" + itmID).classList.toggle("in");
	document.getElementById("btnAddShoppingList_" + itmID).classList.toggle("in");
}

function btnLess(itmID) {
	var item = document.getElementById(itmID);
	var qty = parseFloat(item.cells.item(5).innerText);
	if (Number.isInteger(qty)) {
		qty = qty - 1;
	} else {
		qty = qty - (qty - Math.floor(qty));
	}
	
	if (qty >= 0) {
		item.cells.item(5).innerText = qty;
		itemUpdateQty(itmID, qty)
	} else {
		document.getElementById("id01_ItemID").value = itmID;
		toggleModal('id01');
	}
}

function btnMore(itmID) {
	var item = document.getElementById(itmID);
	var qty = parseFloat(item.cells.item(5).innerText);
	if (Number.isInteger(qty)) {
		qty = qty + 1;
	} else {
		qty = qty + (qty - Math.floor(qty));
	}
	item.cells.item(5).innerText = qty;
	itemUpdateQty(itmID, qty)
}

function btnEdit(itmID) {
	//Setup Form
	document.getElementById('id02_Title').innerText = 'Edit Item';
	document.getElementById('id02_Submit').innerText = 'Save changes';
	document.getElementById('id02_Submit').setAttribute('onclick','btnEditSubmit()');
	var form = document.getElementById('id02_Form');
	var item = document.getElementById(itmID);
	form.setAttribute('action','item_edit.php');
	//itemID itemLoc itemType itemName itemPrice itemExp itemQty
	form.itemID.value = itmID;
	form.itemLoc.value = item.cells.item(0).innerText;
	form.itemType.value = item.cells.item(1).innerText;
	form.itemName.value = item.cells.item(2).innerText;
	form.itemPrice.value = item.cells.item(3).innerText;
	form.itemExp.value = item.cells.item(4).innerText;
	form.itemQty.value = item.cells.item(5).innerText;	
	document.getElementById('id02').style.display='block';
}

function btnAdd() {
	//Setup Form
	document.getElementById('id02_Title').innerText = 'Add Item';
	document.getElementById('id02_Submit').innerText = 'Add Item';
	document.getElementById('id02_Submit').setAttribute('onclick','btnAddSubmit()');
	var form = document.getElementById('id02_Form');
	form.setAttribute('action','item_add.php');
	form.itemID.value = "";
	form.itemLoc.value = "";
	form.itemType.value = "";
	form.itemName.value = "";
	form.itemPrice.value = "";
	form.itemExp.value = "";
	form.itemQty.value = 1;
	document.getElementById('id02').style.display='block';	
}

function itemUpdateQty(itmID, qty) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "item_update_qty.php", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			if (xhttp.responseText != "success") {
				alert(xhttp.responseText);
			} 
		}
    }
    xhttp.send("itemID=" + itmID + "&itemQty=" + qty);
}

function itemDelete() {
	//Submit from Delete modal
	loaderOn();
	var itmID = document.getElementById("id01_ItemID").value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "item_delete.php", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			if (xhttp.responseText != "success") {
				alert(xhttp.responseText);
				loaderOff();
			} else {
				location.reload(true);	
			}
		}
    };
    xhttp.send("itemID=" + itmID);
}

function btnAddSubmit() {
	//Submit from Add modal
	loaderOn();
	//itemLoc itemType itemName itemPrice itemExp itemQty
	var itmLoc, itmType, itmName, itmPrice, itmExp, itmQty;
	var form = document.getElementById('id02_Form');
	itmLoc = form.itemLoc.value;
	itmType = form.itemType.value;
	itmName = form.itemName.value;
	itmPrice = form.itemPrice.value;
	itmExp = form.itemExp.value;
	itmQty = form.itemQty.value;
	
	var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "item_add.php", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			if (xhttp.responseText != "success") {
				alert(xhttp.responseText);
				loaderOff();
			} else {
				location.reload(true);	
			}
		}
    };
    xhttp.send("itemLoc=" + itmLoc + "&itemType=" + itmType + "&itemName=" + itmName + "&itemPrice=" + itmPrice + "&itemExp=" + itmExp + "&itemQty=" + itmQty);
}	

function btnEditSubmit() {
	//Submit from Edit modal
	loaderOn();
	//itemID itemLoc itemType itemName itemPrice itemExp itemQty
	var itmID, itmLoc, itmType, itmName, itmPrice, itmExp, itmQty;
	var form = document.getElementById('id02_Form');
	itmID = form.itemID.value;
	itmLoc = form.itemLoc.value;
	itmType = form.itemType.value;
	itmName = form.itemName.value;
	itmPrice = form.itemPrice.value;
	itmExp = form.itemExp.value;
	itmQty = form.itemQty.value;
	
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "item_edit.php", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			if (xhttp.responseText != "success") {
				alert(xhttp.responseText);
				loaderOff();
			} else {
				location.reload(true);	
			}
		}
    };
    xhttp.send("itemID=" + itmID + "&itemLoc=" + itmLoc + "&itemType=" + itmType + "&itemName=" + itmName + "&itemPrice=" + itmPrice + "&itemExp=" + itmExp + "&itemQty=" + itmQty);
}

function btnAddShoppingList(itmID) {
	//add to ShoppingList button
	//itemID itemType itemName itemQty itemNotes
	var  item, itmType, itmName, itmQty, itmNotes;
	item = document.getElementById(itmID);
	itmType = item.cells.item(1).innerText;
	itmName = item.cells.item(2).innerText;
	itmQty = 1;
	itmNotes = "";
	
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "shopping_add.php", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
			if (xhttp.responseText != "success") {
				alert(xhttp.responseText);
			} else {
				document.getElementById("btnShoppingList").style.display = "inline-block";
			} 
		}
    };
    xhttp.send("itemID=" + itmID + "&itemType=" + itmType + "&itemName=" + itmName + "&itemQty=" + itmQty + "&itemNotes=" + itmNotes);
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;
    return [year, month, day].join('-');
}