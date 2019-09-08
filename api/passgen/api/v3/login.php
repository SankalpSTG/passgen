<?php
	//Register with no validation.
	//Original version requires parameters : unique_id, device_id, master_password, auth_key.
	
	$message = array();
	$validation = false;
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	validatedata();
	
	if($validation){
		$stmt = $conn->prepare("SELECT auth_key, master_password, device_id FROM users WHERE unique_id = ?");
		
		$stmt->bind_param("s", $_POST["unique_id"]);
		
		if($stmt->execute()){
			$result = $stmt->get_result();
			$stmt->close();
			$row = mysqli_fetch_assoc($result);
			if(password_verify($_POST["master_password"], $row["master_password"])){
				if($_POST["device_id"] == $row["device_id"]){
					$encrypted_auth = password_hash($_POST["auth_key"], PASSWORD_DEFAULT);
					$stmt = $conn->prepare("UPDATE users SET auth_key = ? WHERE unique_id = ?");
					$stmt->bind_param("ss", $encrypted_auth, $_POST["unique_id"]);
					if($stmt->execute()){
						$message = array("error" => false, "error_code" => 100, "message" => "Login Successful");
						echo json_encode($message);
					}else{
						$message = array("error" => false, "error_code" => 107, "message" => "Failed To Login");
						echo json_encode($message);
					}
				}else{
					$message = array("error" => true, "error_code" => 108, "message" => "User Is Registered With Different Device");
					echo json_encode($message);
				}
			}else{
				$message = array("error" => true, "error_code" => 103, "message" => "Invalid Credentials");
				echo json_encode($message);
			}
			$message = array("error" => false, "error_code" => 100, "message" => "User Registered Successfully");
			echo json_encode($message);
		}else{
			$message = array("error" => true, "error_code" => 107, "message" => "Failed to log in");
			echo json_encode($message);
		}
	}else{
		echo json_encode($message);
	}
	function validatedata(){
		require "connections/database/dbh.inc.php";
		$message = array();
		if(isset($_POST["unique_id"]) && isset($_POST["device_id"]) && isset($_POST["master_password"]) && isset($_POST["auth_key"])){
			if(empty($_POST["unique_id"]) || empty($_POST["device_id"]) || empty($_POST["master_password"]) || empty($_POST["auth_key"])){
				$message = array("error" => true, "error_code" => 102, "message" => "Required All Parameters");
			}else{
				if(filter_var($_POST["unique_id"], FILTER_VALIDATE_EMAIL)){
					$stmt = $conn->prepare("SELECT serial_id FROM users WHERE unique_id = ?");
					$stmt->bind_param("s", $_POST["unique_id"]);
					if($stmt->execute()){
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