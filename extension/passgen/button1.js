document.getElementById("getpasswordbutton").addEventListener('click', getPasswordClicked);
document.getElementById("qrcodedone").addEventListener('click', getAuthKey);
document.getElementById("authfetchretry").addEventListener('click', getAuthKey);
document.getElementById("reshowqrcode").addEventListener('click', reshowQRCode);

function getPasswordClicked(){
	document.getElementById("getpasswordbutton").style.display = "none";
	var deviceid = sessionStorage.getItem("passgen_device_id");
	if(deviceid === null){
		deviceid = Math.floor(Math.random() * 1000000000);
		sessionStorage.setItem("passgen_device_id", deviceid);
	}
	deviceid = sessionStorage.getItem("passgen_device_id");
	var passgen_auth_key = parseInt(getPassGenAuthKey(deviceid));
	if(passgen_auth_key == 0){
		document.getElementById("login-text").style.display = "block";
		document.body.style.backgroundColor = "#fff";
		var qrcode = new QRCode(document.getElementById("qrspace"), {
			text: deviceid.toString(10),
			width: 128,
			height: 128,
			colorDark : "#000",
			colorLight : "#fff",//rgba(146, 172, 53, 1)",
			correctLevel : QRCode.CorrectLevel.H
		});
		document.getElementById("qrspace").style.display = "block";
		document.getElementById("qrcodedone").style.display = "block";
	}else{
		
	}
}
function getAuthKey(){
	document.getElementById("login-text").style.display = "none";
	document.body.style.backgroundColor = "#222";
	document.getElementById("failed-text").style.display = "none";
	document.getElementById("authfetchretry").style.display = "none";
	document.getElementById("reshowqrcode").style.display = "none";
	document.getElementById("qrspace").style.display = "none";
	document.getElementById("qrcodedone").style.display = "none";
	document.getElementById("failed-text").style.display = "block";
	document.getElementById("authfetchretry").style.display = "block";
	document.getElementById("reshowqrcode").style.display = "block";
}
function reshowQRCode(){
	document.getElementById("login-text").style.display = "block";
	document.body.style.backgroundColor = "#fff";
	document.getElementById("failed-text").style.display = "none";
	document.getElementById("authfetchretry").style.display = "none";
	document.getElementById("reshowqrcode").style.display = "none";
	document.getElementById("qrspace").style.display = "block";
	document.getElementById("qrcodedone").style.display = "block";
}