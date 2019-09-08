<?php
	//Share Access with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key.
	
	$message = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$pass_key = generateRandomString(16);
	$pass_iv = generateRandomString(16);
	$encrypted_pass = encrypt_decrypt(0, $_GET["auth_key"], $pass_key, $pass_iv);
	
	$stmt = $conn->prepare("INSERT INTO shared_access (unique_id, auth_key, device_id, pass_key, pass_iv) VALUES (?, ?, ?, ?, ?)");
	
	$stmt->bind_param("sssss", $_GET["unique_id"], $encrypted_pass, $_GET["device_id"], $pass_key, $pass_iv);
	
	if($stmt->execute()){
		$stmt->close();
		$message = array("error" => false, "message" => "Access Granted");
		echo json_encode($message);
	}else{
		$message = array("error" => false, "message" => "Unable to grant access");
		echo json_encode($message);
	}
?>