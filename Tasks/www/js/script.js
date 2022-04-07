function getTasks(str) {
	if (str == "") {
		document.getElementById("selection").innerHTML = "";
		return;
	} else {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("tableOutput").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("POST", "sql/list_tasks.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded", true);
		xmlhttp.send("status=" + str);
	}
}

function getQueueStatus(str) {
	if (str == "") {
		document.getElementById("queueSelection").innerHTML = "";
		return;
	} else {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("queueOutput").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("POST", "sql/list_queue.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded", true);
		xmlhttp.send("queueStatus=" + str);
	}
}

function getQueueStatusWithTask(str, task) {
	if (str == "") {
		document.getElementById("queueSelection").innerHTML = "";
		return;
	} else {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("queueOutput").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("POST", "sql/list_queue.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded", true);
		xmlhttp.send("queueStatus=" + str + "&" + "task=" + task);
	}
}