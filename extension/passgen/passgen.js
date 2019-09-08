function getPwdInputs() {
	var ary = 0;
	var inputs = document.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++) {
		if (inputs[i].type.toLowerCase() === "password") {
			ary += 1;
		}
	}
	if(ary >= 1){
		alert("This page has password fields");
	}
}
function firstpage(){
	if(window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	}else{  
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (this.readyState==4 && this.status==200) {
			//alert("Responded");
			alert("Hello World");
		}
	}
	xmlhttp.open("GET","https://www.techfriendsindia.com/notofthissite/passgen/ext_login.php",true);
	xmlhttp.send();
}
function verifyIfLoginExists(){	
	var passgen_auth_key = localStorage.getItem("passgen_auth_key");
	if(passgen_auth_key == null){
		//creating notice element
		var elem = document.createElement("div");
		elem.setAttribute("id", "noticediv");
		document.body.insertBefore(elem, document.body.firstElementChild);
		//document.body.appendChild(elem);
		alert("added");
		//create notice header
		elem = document.createElement("div");
		elem.setAttribute("id", "noticedivheader");
		elem.textContent = "Header";
		document.getElementById("noticediv").appendChild(elem);
		//create notice body
		elem = document.createElement("div");
		elem.setAttribute("id", "noticedivbody");
		elem.textContent = "body";
		document.getElementById("noticediv").appendChild(elem);
		//create notice footer
		elem = document.createElement("div");
		elem.setAttribute("id", "noticedivfooter");
		elem.textContent = "footer";
		document.getElementById("noticediv").appendChild(elem);
		
		elem = document.getElementById("noticediv");
		elem.setAttribute("z-index", "1001");
		elem.setAttribute("position", "absolute");
		elem.setAttribute("overflow", "hidden");
		elem.style.width = "440px";
		elem.setAttribute("max-width", "100%");
		elem.style.backgroundColor = "#f00";
		elem.style.fontSize = "24px";
	}
}
//verifyIfLoginExists();
//getPwdInputs();
//firstpage();