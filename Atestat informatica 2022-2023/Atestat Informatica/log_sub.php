<?php
  include_once 'connection.php';

  $name = $_POST["usnm"];
  $pswd = $_POST["pswd"];

  $sql = "SELECT  username, password FROM ACCOUNTS WHERE username = ?;";
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    throw new Exception("Error preparing statement: " . $conn->error);
  }
  $stmt->bind_param("s", $name);
  if (!$stmt->execute()) {
    throw new Exception("Error executing statement: " . $stmt->error);
  }

  $result = $stmt->get_result();
  if (!$result) {
    throw new Exception("Error getting result: " . $stmt->error);
  }
  $row = mysqli_fetch_assoc($result);
  if (!$row) {
    $_SESSION['error_message'] = "Numele de utilizator si parola nu se potrivesc!";
    header("Location: login.php");
    exit();
  }

  if(password_verify($pswd, $row['password'])) { 
    $_SESSION['username'] = $name;
    $_SESSION['password'] = $pswd;
   
    header("Location: index.php");
    exit();
  }
  else {
    $_SESSION['error_message'] = "Numele de utilizator si parola nu se potrivesc!";
    header("Location: login.php");
    exit();
  }
?>
