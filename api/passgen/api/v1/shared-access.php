<?php

  require "includes/encdec.php";
  require "connections/database/dbh.inc.php";

  //adding to Database (shared_access)
  $secret_key = generateRandomString(16);
  $secret_iv = generateRandomString(16);
  $string = $_POST["auth_key"];
  $encrypted_auth = encrypt_decrypt(1, $string, $secret_key, $secret_iv);

  echo $encrypted_auth;

 ?>
