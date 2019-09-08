<?php
	//Update Password with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url, user_name, password.
	
	$message = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$pass_key = generateRandomString(16);
	$pass_iv = generateRandomString(16);
	$encrypted_pass = encrypt_decrypt(0, $_GET["password"], $pass_key, $pass_iv);
	
	$stmt = $conn->prepare("UPDATE passwords SET password = ?, pass_key = ?, pass_iv = ? WHERE unique_id = ? AND user_name = ? AND url LIKE '%".$_GET['url']."%'");
	
	$stmt->bind_param("sssss", $encrypted_pass, $pass_key, $pass_iv, $_GET["unique_id"], $_GET["user_name"]);
	
	if($stmt->execute()){
		$stmt->close();
		$message = array("error" => false, "message" => "Password Updated");
		echo json_encode($message);
	}else{
		$message = array("error" => false, "message" => "Failed to update password");
		echo json_encode($message);
	}
?>