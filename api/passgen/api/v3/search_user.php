<?php
	//search usernames with no validation.
	//Original version requires parameters : unique_id, device_id, authentication_key, url.
	
	$message = array();
	$users = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$stmt = $conn->prepare("SELECT user_name FROM passwords WHERE unique_id = ? AND url LIKE '%".$_POST['url']."%'");
	$stmt->bind_param("s", $_POST["unique_id"]);
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
	function validatedata(){
		require "connections/database/dbh.inc.php";
		$message = array();
		if(isset($_POST["unique_id"]) && isset($_POST["device_id"]) && isset($_POST["auth_key"]) && isset($_POST["url"])){
			if(empty($_POST["unique_id"]) || empty($_POST["device_id"]) || empty($_POST["auth_key"])){
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