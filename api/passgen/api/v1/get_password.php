<?php
	require "connections/database/dbh.inc.php";
	$message = array();
	if(userAuthenticatedSuccessfully()){
		if(isset($_POST["unique_id"]) && isset($_POST["url"]) && isset($_POST["user_name"])){
			$url = $_POST["url"];
			$stmt = $conn->prepare("SELECT user_name FROM passwords WHERE unique_id = ? AND user_name = ? url LIKE '%".$url."%'");
			$stmt->bind_param("ss", $_POST["unique_id"], $_POST["user_name"]);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			$names = array();
			
			if(mysqli_num_rows($result) > 0){
				while($row = mysqli_fetch_assoc($result)){
					foreach($row as $value){
						$names[] = $value;
					}
				}
				$message[] = true;
				$message[] = "Successful";
				$message[] = $names;
			}else{
				$message[] = false;
				$message[] = "No Passwords Saved Yet";
			}
		}else{
			$message[] = false;
			$message[] = "Invalid Unique Id";
		}
	}else{
		$message[] = false;
		$message[] = "Authentication Failed";
	}
	echo json_encode($message);
	function userAuthenticatedSuccessfully(){
		if(isset($_POST["unique_id"]) && isset($_POST["device_id"])){
			if(!is_numeric($_POST["device_id"])){
				return false;
			}
			if(!filter_var($_POST["unique_id"], FILTER_VALIDATE_EMAIL)){
				return false;
			}
			if(isset($_POST["auth_key"])){
				require "connections/database/dbh.inc.php";
				$stmt = $conn->prepare("SELECT auth_key FROM users WHERE unique_id = ?");
				$stmt->bind_param("s", $_POST["unique_id"]);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->close();
				if(mysqli_num_rows($result) > 0){
					$row = mysqli_fetch_assoc($result);
					if(password_verify($_POST["auth_key"], $row["auth_key"])){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
		return false;
	}
?>