<?php
include_once 'connection.php';

$newusername = $_POST['usnm'];
$oldusername = $_SESSION['username'];
$oldpassword = $_SESSION['password'];
$newpassword = $_POST['pswd'];
$hashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);


$stmt1 = $conn->prepare("UPDATE articles SET author_name = ? WHERE author_name = ?");
$stmt1->bind_param("ss", $newusername, $oldusername);


$stmt2 = $conn->prepare("UPDATE likes_dislikes SET username = ? WHERE username = ?");
$stmt2->bind_param("ss", $newusername, $oldusername);


$stmt3 = $conn->prepare("UPDATE accounts SET username = ?, password = ? WHERE username = ?");
$stmt3->bind_param("sss", $newusername, $hashedPassword, $oldusername);


$stmt1->execute();
$stmt2->execute();
$stmt3->execute();


if ($stmt1->error || $stmt2->error || $stmt3->error) {

  echo "Error updating user information: " . $conn->error;
} else {

  $_SESSION['username'] = $newusername;
  $_SESSION['password'] = $newpassword;
  header("Location: profile.php");
}
?>
