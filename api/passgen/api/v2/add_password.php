<?php
	//Add Password with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url, user_name, password.
	
	$message = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$pass_key = generateRandomString(16);
	$pass_iv = generateRandomString(16);
	$encrypted_pass = encrypt_decrypt(0, $_GET["password"], $pass_key, $pass_iv);
	
	$stmt = $conn->prepare("INSERT INTO passwords (unique_id, url, user_name, password, pass_key, pass_iv) VALUES (?, ?, ?, ?, ?, ?)");
	
	$stmt->bind_param("ssssss", $_GET["unique_id"], $_GET["url"], $_GET["user_name"], $encrypted_pass, $pass_key, $pass_iv);
	
	if($stmt->execute()){
		$stmt->close();
		$message = array("error" => false, "message" => "Password Added");
		echo json_encode($message);
	}else{
		$message = array("error" => true, "message" => "Failed to add password into database");
		echo json_encode($message);
	}
?>