<?php
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	if(isset($_POST["unique_id"]) && isset($_POST["auth_key"]) && isset($_POST["device_id"]) && isset($_POST["url"]) && isset($_POST["user_name"]) && isset($_POST["password"])){
		$uid = mysqli_real_escape_string($conn, $_POST["unique_id"]);
		$authkey = mysqli_real_escape_string($conn, $_POST["auth_key"]);
		$devid = mysqli_real_escape_string($conn, $_POST["device_id"]);
		$url = mysqli_real_escape_string($conn, $_POST["url"]);
		$uname = mysqli_real_escape_string($conn, $_POST["user_name"]);
		$pass = mysqli_real_escape_string($conn, $_POST["password"]);
		
		if(empty($uid) || empty($authkey) || empty($devid) || empty($url) || empty($uname) || empty($pass)){
			$message = array(false, "Required All Data");
		}else{
			if (!filter_var($uid, FILTER_VALIDATE_EMAIL)) {
				$message = array(false, "Invalid Email Or Password");
			}else{
				if(!is_numeric($devid)){
					$message = array(false, "Please Retry Later");
				}else{
					$stmt = $conn->prepare("SELECT auth_key, master_password FROM users WHERE unique_id = ?");
					$stmt->bind_param("s", $uid);
					$stmt->execute();
					$result = $stmt->get_result();
					$stmt->close();
					if(mysqli_num_rows($result) == 1){
						$row = mysqli_fetch_assoc($result);
						if(password_verify($authkey, $row["auth_key"])){
							$pass_key = generateRandomString(16);
							$pass_iv = generateRandomString(16);
							$encrypted_pass = encrypt_decrypt(0, $pass, $pass_key, $pass_iv);
							$stmt = $conn->prepare("INSERT INTO passwords (unique_id, url, user_name, password, pass_key, pass_iv) VALUES (?, ?, ?, ?, ?, ?)");
							$stmt->bind_param("ssssss", $uid, $url, $uname, $encrypted_pass, $pass_key, $pass_iv);
							$stmt->execute();
							$stmt->close();
							$message = array(true, "Successful");
						}else{
							$message = array(false, "Device Error");
						}
					}else{
						$message = array(false, "Database Got Compromised");
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