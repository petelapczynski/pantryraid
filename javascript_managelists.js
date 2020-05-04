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

function listSelection() {
	var e = document.getElementById('listid');
	var listid = e.options[e.selectedIndex].value;
	var listname = e.options[e.selectedIndex].innerText;
	if (listid.length > 0 ) {
		document.getElementById('btnEditList').style.display = 'inline-block';
		document.getElementById('btnDeleteList').style.display = 'inline-block';
		//setup forms
		document.getElementById("id01_ListID").value = listid;
		document.getElementById("id02_ListID").value = listid;
		var form = document.getElementById('id02_Form');
		//listname listid
		form.listID.value = listid;
		form.listName.value = listname;
	} else {
		document.getElementById('btnEditList').style.display = 'none';
		document.getElementById('btnDeleteList').style.display = 'none';
	} 	
}

function btnClickDeleteList() {
	toggleModal('id01');
}

function listDelete() {
	// Delete button
	// listid
	loaderOn();
	var listid = document.getElementById("id01_ListID").value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "list_delete.php", true);
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
    xhttp.send("listid=" + listid);
}

function btnClickEditList() {
	document.getElementById('id02').style.display='block';
}

function btnEditSubmit() {
	// Save button
	// listid listname
	loaderOn();
	var form = document.getElementById('id02_Form');
	var listid = form.listID.value;
	var listname = form.listName.value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "list_edit.php", true);
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
    xhttp.send("listid=" + listid + "&listname=" + listname);
}

function btnSubmitSelectList() {
	// Select button
	// listid
	var form = document.getElementById('listForm');
	var e = form.listid;
	var listid = e.options[e.selectedIndex].value;
	if (listid.length > 0 ) {
		loaderOn();
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "list_select.php", true);
		xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
				if (xhttp.responseText != "success") {
					alert(xhttp.responseText);
					loaderOff();
				} else {
					window.location.href = "index.php";
				}
			}
		};
		xhttp.send("listid=" + listid);
	} 
}	

function btnSubmitCreateList() {
	// Create button
	// listname
	
	var form = document.getElementById('listForm');
	var listname = form.listname.value;
	if (listname.length > 0 ) {
		loaderOn();
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "list_add.php", true);
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
		xhttp.send("listname=" + listname);
	}
}

function btnSubmitLinkList() {
	// Link List button
	// listcode
	var form = document.getElementById('listForm');
	var listcode = form.listcode.value;
	if (listcode.length > 0 ) {
		loaderOn();
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "list_adduser.php", true);
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
		xhttp.send("listcode=" + listcode);
	}
}