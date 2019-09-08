<?php
	//Add Password with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url, user_name, password.
	
	$message = array();
	$validation = false;
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	validatedata();
	
	
	if($validation){
		$pass_key = generateRandomString(16);
		$pass_iv = generateRandomString(16);
		$encrypted_pass = encrypt_decrypt(0, $_POST["password"], $pass_key, $pass_iv);
		
		$stmt = $conn->prepare("UPDATE passwords SET password = ?, pass_key = ?, pass_iv = ? WHERE unique_id = ? AND user_name = ? AND url LIKE '%".$_POST['url']."%'");
		
		$stmt->bind_param("sssss", $encrypted_pass, $pass_key, $pass_iv, $_POST["unique_id"], $_POST["user_name"]);
		
		if($stmt->_POST()){
			$stmt->close();
			$message = array("error" => false, "error_code" => 100, "message" => "Successful");
			echo json_encode($message);
		}else{
			$message = array("error" => false, "error_code" => 109, "message" => "Successful");
			echo json_encode($message);
		}
	}
	function validatedata(){
		require "connections/database/dbh.inc.php";
		$message = array();
		if(isset($_POST["unique_id"]) && isset($_POST["device_id"]) && isset($_POST["auth_key"]) && isset($_POST["url"]) && isset($_POST["user_name"]) && isset($_POST["password"])){
			if(empty($_POST["unique_id"]) || empty($_POST["device_id"]) || empty($_POST["auth_key"]) || empty($_POST["url"]) || empty($_POST["user_name"]) || empty($_POST["password"])){
				$message = array("error" => true, "error_code" => 102, "message" => "Required All Parameters");
			}else{
				if(filter_var($_POST["unique_id"], FILTER_VALIDATE_EMAIL)){
					$stmt = $conn->prepare("SELECT serial_id FROM users WHERE unique_id = ?");
					$stmt->bind_param("s", $_POST["unique_id"]);
					if($stmt->_POST()){
						$result = $stmt->get_result();
						if(mysqli_num_rows($result) == 1){
							$GLOBALS['validation'] = true;
							$message = array();
						}else if(mysqli_num_rows($result) == 0){
							$message = array("error" => true, "error_code" => 105, "message" => "User Already Exists");
						}else{
							$message = array("error" => true, "error_code" => 106, "message" => "Database Got Compromised");
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