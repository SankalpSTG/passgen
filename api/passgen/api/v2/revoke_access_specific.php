<?php
	//Add Password with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key.
	
	$message = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$pass_key = generateRandomString(16);
	$pass_iv = generateRandomString(16);
	$encrypted_pass = encrypt_decrypt(0, $_GET["auth_key"], $pass_key, $pass_iv);
	
	$stmt = $conn->prepare("DELETE FROM shared_access WHERE unique_id = ? AND device_id = ?");
	
	$stmt->bind_param("ss", $_GET["unique_id"], $_GET["device_id"]);
	
	if($stmt->execute()){
		$stmt->close();
		$message = array("error" => false, "message" => "Access Revoked");
		echo json_encode($message);
	}else{
		$message = array("error" => true, "message" => "Unable to revoke access");
		echo json_encode($message);
	}
?>