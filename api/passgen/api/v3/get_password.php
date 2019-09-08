<?php
	//Add Password with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url, user_name, password.
	
	$message = array();
	$validation = false;
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	validatedata();
	
	
	if($validation){
		$stmt = $conn->prepare("SELECT password, pass_key, pass_iv FROM passwords WHERE unique_id = ? AND user_name = ? AND url LIKE '%".$_POST['url']."%'");
		$stmt->bind_param("ss", $_POST["unique_id"], $_POST["user_name"]);
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
	}
	function validatedata(){
		require "connections/database/dbh.inc.php";
		$message = array();
		if(isset($_POST["unique_id"]) && isset($_POST["device_id"]) && isset($_POST["auth_key"]) && isset($_POST["url"]) && isset($_POST["user_name"])){
			if(empty($_POST["unique_id"]) || empty($_POST["device_id"]) || empty($_POST["auth_key"]) || empty($_POST["url"]) || empty($_POST["user_name"])){
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