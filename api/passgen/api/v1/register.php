<?php
	require "connections/database/dbh.inc.php";
	if(isset($_POST["user_name"]) && isset($_POST["unique_id"]) && isset($_POST["auth_key"]) && isset($_POST["device_id"]) && isset($_POST["master_password"])){
		$uname = mysqli_real_escape_string($conn, $_POST["user_name"]);
		$uid = mysqli_real_escape_string($conn, $_POST["unique_id"]);
		$authkey = mysqli_real_escape_string($conn, $_POST["auth_key"]);
		$devid = mysqli_real_escape_string($conn, $_POST["device_id"]);
		$mpass = mysqli_real_escape_string($conn, $_POST["master_password"]);

		if(empty($uname) || empty($uid) || empty($authkey) || empty($devid) || empty($mpass)){
			$message = array(false, "Required All Parameters");
		}else{
			if (!filter_var($uid, FILTER_VALIDATE_EMAIL)) {
				$message = array(false, "Invalid Email Or Password");
			}else{
				if(!is_numeric($devid)){
					$message = array(false, "Please Retry Later");
				}else{
					$stmt = $conn->prepare("SELECT * FROM users WHERE unique_id = ?");
					$stmt->bind_param("s", $uid);
					$stmt->execute();
					$result = $stmt->get_result();
					$stmt->close();

					if(mysqli_num_rows($result) > 0){
						$message = array(false, 101, "User Already Exists");
					}else{
						$encrypted_auth = password_hash($authkey, PASSWORD_DEFAULT);
						$encrypted_pass = password_hash($mpass, PASSWORD_DEFAULT);
						$stmt = $conn->prepare("INSERT INTO users (user_name, unique_id, auth_key, device_id, master_password) VALUES (?, ?, ?, ?, ?)");
						$stmt->bind_param("sssis", $uname, $uid, $encrypted_auth, $devid, $encrypted_pass);
						$stmt->execute();
						$stmt->close();
						$message = array(true, "Successful");
					}
				}
			}
		}
		echo json_encode($message);
	}else{
		$message = array(false, "Required All Parameters");
		echo json_encode($message);
	}
?>
