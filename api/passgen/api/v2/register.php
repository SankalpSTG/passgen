<?php
	//Register with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, master_password, user_name.
	
	$message = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$authkey = password_hash($_GET["auth_key"], PASSWORD_DEFAULT);
	$encrypted_pass = password_hash($_GET["master_password"], PASSWORD_DEFAULT);
	
	$stmt = $conn->prepare("INSERT INTO users (user_name, unique_id, auth_key, device_id, master_password) VALUES (?, ?, ?, ?, ?)");
	
	$stmt->bind_param("sssss", $_GET["user_name"], $_GET["unique_id"], $authkey, $_GET["device_id"], $encrypted_pass);
	
	if($stmt->execute()){
		$stmt->close();
		$message = array("error" => false, "message" => "User Registered Successfully");
		echo json_encode($message);
	}else{
		$message = array("error" => true, "message" => "Failed to register user");
		echo json_encode($message);
	}
?>