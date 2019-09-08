<?php
	//Register with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, master_password, user_name.
	
	$message = array();
	$validation = false;
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	validatedata();
	
	if($validation){
		$authkey = password_hash($_GET["auth_key"], PASSWORD_DEFAULT);
		$encrypted_pass = password_hash($_GET["master_password"], PASSWORD_DEFAULT);
		
		$stmt = $conn->prepare("INSERT INTO users (user_name, unique_id, auth_key, device_id, master_password) VALUES (?, ?, ?, ?, ?)");
		
		$stmt->bind_param("sssss", $_GET["user_name"], $_GET["unique_id"], $authkey, $_GET["device_id"], $encrypted_pass);
		
		if($stmt->execute()){
			$stmt->close();
			$message = array("error" => false, "error_code" => 100, "message" => "User Registered Successfully");
			echo json_encode($message);
		}else{
			$message = array("error" => true, "error_code" => 101, "message" => "Failed to register user");
			echo json_encode($message);
		}
	}else{
		echo json_encode($message);
	}
	function validatedata(){
		require "connections/database/dbh.inc.php";
		require "includes/encdec.php";
		$message = array();
		if(isset($_GET["unique_id"]) && isset($_GET["auth_key"]) && isset($_GET["device_id"]) && isset($_GET["master_password"]) && isset($_GET["user_name"])){
			if(empty($_GET["unique_id"]) || empty($_GET["auth_key"]) || empty($_GET["device_id"]) || empty($_GET["master_password"]) || empty($_GET["user_name"])){
				$message = array("error" => true, "error_code" => 102, "message" => "Required All Parameters");
			}else{
				if(filter_var($_GET["unique_id"], FILTER_VALIDATE_EMAIL)){
					$stmt = $conn->prepare("SELECT serial_id FROM users WHERE unique_id = ?");
					$stmt->bind_param("s", $_GET["unique_id"]);
					if($stmt->execute()){
						$result = $stmt->get_result();
						if(mysqli_num_rows($result) == 0){
							$GLOBALS['validation'] = true;
							$message = array();
						}else{
							$message = array("error" => true, "error_code" => 105, "message" => "User Already Exists");
						}
					}else{
						$message = array("error" => true, "error_code" => 104, "message" => "Please Try Again Later");
					}
				}else{	
					$message = array("error" => true, "error_code" => 103, "message" => "Invalid User Credentials");
				}
			}
		}else{
			$message = array("error" => true, "error_code" => 102, "message" => "Required All Parameters");
		}
		$GLOBALS["message"] = $message;
	}
?>