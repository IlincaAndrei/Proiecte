<?php
  include_once 'connection.php';

  $name = $_POST["usnm"];
  $pswd = $_POST["pswd"];
   $_SESSION['username'] =$name;
  $_SESSION['password'] = $pswd;
  $pswd = password_hash($pswd, PASSWORD_DEFAULT);
  

 

  $stmt =$conn ->prepare("INSERT INTO ACCOUNTS (username, password) VALUES (?, ?);");
  $stmt -> bind_param("ssi", $name, $pswd);
  $stmt -> execute();
  
  if ($stmt->affected_rows > 0) {
    header("Location: index.php");
} else {
  
    $_SESSION['error'] = "Signup failed, please try again.";
    http_response_code(500);
    header("Location: signup.php");
}
?>