function loaderOn(callback, n, type) {
	document.getElementById('processing').style.display = 'block';
	if (callback instanceof Function) {
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

function tblSort(n, type){
  // n is column number, type can be text,number,dollar,percent
	document.getElementById('processing').style.display = 'block';
    var tbl = document.getElementById("tblFilter_1").tBodies[0];
    var sortArray = [];
	var preSortArray = [];
    for(var i=0, len=tbl.rows.length; i<len; i++){
        var row = tbl.rows[i];
		var sortnr;

		if (type == "text") {
			sortnr = row.cells[n].innerText.toLowerCase();
		} else if (type == "number") {
			sortnr = parseFloat(row.cells[n].innerText);
		} else if (type == "dollar") { 
			sortnr = dollarToFloat(row.cells[n].innerText);
		} else if (type == "percent") {
			sortnr = percentToFloat(row.cells[n].innerText);
		}
       
		sortArray.push([sortnr, row]);
		preSortArray.push([sortnr, row]);
    }
	if (type == "text") {
		sortArray.sort(function(a,b){
			var x = a[0];
			x = x.toLowerCase();
			var y = b[0];
			y = y.toLowerCase();
			if (x < y) {return -1;}
			if (x > y) {return 1;}
			return 0;
		});
		if (sortArray.equals(preSortArray)){
			sortArray.reverse();
		}
	} else {
		sortArray.sort(function(a,b){
			return a[0] - b[0];
		});
		if (sortArray.equals(preSortArray)){
			sortArray.sort(function(a,b) {
				return b[0] - a[0];
			});
		}
	}
    for(var i=0, len=sortArray.length; i<len; i++){
        tbl.appendChild(sortArray[i][1]);
    }
    sortArray = null;
	preSortArray = null;
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

Array.prototype.equals = function (array) {
    // if the other array is a falsy value, return
    if (!array)
        return false;

    // compare lengths - can save a lot of time 
    if (this.length != array.length)
        return false;

    for (var i = 0, l=this.length; i < l; i++) {
        // Check if we have nested arrays
        if (this[i] instanceof Array && array[i] instanceof Array) {
            // recurse into the nested arrays
            if (!this[i].equals(array[i]))
                return false;       
        }           
        else if (this[i] != array[i]) { 
            // Warning - two different object instances will never be equal: {x:20} != {x:20}
            return false;   
        }           
    }       
    return true;
}