<?php
require "connections/database/dbh.inc.php";

if( isset($_POST["user_name"]) && isset($_POST["unique_id"]) && isset($_POST["master_password"]) && isset($_POST["auth_key"]) && isset($_POST["device_id"]) )
{

  $username = mysqli_real_escape_string($conn,$_POST["user_name"]);
  $master_password = mysqli_real_escape_string($conn,$_POST["master_password"]);
  $auth_key = mysqli_real_escape_string($conn,$_POST["auth_key"]);
  $device_id = mysqli_real_escape_string($conn,$_POST["device_id"]);
  $unique_id = mysqli_real_escape_string($conn,$_POST["unique_id"]); //Email
  //echo json_encode("success all set");

  //$validated = validate($username,$master_password,$auth_key,$device_id,$unique_id);
if (empty($username) || empty($auth_key) || empty($master_password) || empty($device_id) || empty($unique_id)) {
  echo "required field is empty";
  exit();
}

else {
    if (!filter_var($unique_id, FILTER_VALIDATE_EMAIL)) {
  $emailErr = "Invalid email format";
  echo "Invalid email\n";
}else{

    $sql = "SELECT master_password FROM users WHERE unique_id = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt,$sql)) {
      echo "Database failed\n";
    }

    else {
      mysqli_stmt_bind_param($stmt,"s",$unique_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if ($row = mysqli_fetch_assoc($result)) {

        if (password_verify($master_password,$row['master_password'])) {
          echo "Permission granted\n";
        }

        else {
          echo "Wrong password";
        }
      }

      else {
        echo "Error";
      }
    }

    mysqli_stmt_close($stmt);

}

}
}else {
  echo "not set";
}

?>
