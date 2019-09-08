<?php
	//Delete Password with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url, user_name.
	
	$message = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$stmt = $conn->prepare("DELETE FROM passwords WHERE unique_id = ? AND user_name = ? AND url LIKE '%".$_GET['url']."%'");
	$stmt->bind_param("ss", $_GET["unique_id"], $_GET["user_name"]);
	if($stmt->execute()){
		$stmt->close();
		$message = array("error" => false, "message" => "Password Deleted");
		echo json_encode($message);
	}else{
		$message = array("error" => true, "message" => "Failed To Delete Password, Please Retry Later.");
		echo json_encode($message);
	}
?>