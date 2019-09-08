<?php
	//search usernames with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url.
	
	$message = array();
	$users = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$stmt = $conn->prepare("SELECT user_name FROM passwords WHERE unique_id = ? AND url LIKE '%".$_GET['url']."%'");
	$stmt->bind_param("s", $_GET["unique_id"]);
	if($stmt->execute()){
		$result = $stmt->get_result();
		while($row = mysqli_fetch_assoc($result)){
			$users[] = $row["user_name"];
		}
		$stmt->close();
		$message = array("error" => false, "message" => "Found Users", "data" => $users);
		echo json_encode($message);
	}else{
		$message = array("error" => true, "message" => "Please Retry Later.");
		echo json_encode($message);
	}
?>