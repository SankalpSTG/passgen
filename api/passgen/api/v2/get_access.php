<?php
	//Get Access with no validation.
	//Original version requires parameters : device_id.
	
	$message = array();
	
	require "connections/database/dbh.inc.php";
	require "includes/encdec.php";
	
	$ipaddress = getUserIpAddr();
	$stmt = $conn->prepare("UPDATE shared_access SET ipaddress = ? WHERE device_id = ?");
	
	$stmt->bind_param("ss", $ipaddress, $_GET["device_id"]);
	
	if($stmt->execute()){
		$stmt->close();
		$message = array("error" => false, "message" => "Access Granted");
		echo json_encode($message);
	}else{
		$message = array("error" => false, "message" => "Unable to grant access");
		echo json_encode($message);
	}
	
	function getUserIpAddr(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			//ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			//ip pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
?>