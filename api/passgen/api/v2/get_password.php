<?php
	//Get Password with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url, user_name.
	
	$message = array();
	$passwords = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$stmt = $conn->prepare("SELECT password, pass_key, pass_iv FROM passwords WHERE unique_id = ? AND user_name = ? AND url LIKE '%".$_GET['url']."%'");
	$stmt->bind_param("ss", $_GET["unique_id"], $_GET["user_name"]);
	if($stmt->execute()){
		$result = $stmt->get_result();
		while($row = mysqli_fetch_assoc($result)){
			$decrypted_pass = encrypt_decrypt(1, $row["password"], $row["pass_key"], $row["pass_iv"]);
			$passwords[] = $decrypted_pass;
		}
		$stmt->close();
		$message = array("error" => false, "message" => "Found Password", "data" => $passwords);
		echo json_encode($message);
	}else{
		$message = array("error" => true, "message" => "Failed To Delete Password, Please Retry Later.");
		echo json_encode($message);
	}
?>