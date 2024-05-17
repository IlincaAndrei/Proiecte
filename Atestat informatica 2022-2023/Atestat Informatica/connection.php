<?php
session_start();
$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "articledb";

$conn = mysqli_connect($dbServername , $dbUsername , $dbPassword , $dbName);
$tokenExpiration = 7200;



function csrf_token_input() {
  if (isset($_SESSION['csrf_token']) && isset($_SESSION['token-expire']) && time() < $_SESSION['token-expire']) {
	 $token = $_SESSION['csrf_token'];
	 } else {
$token = bin2hex(random_bytes(16));
$_SESSION['csrf_token'] = $token;
$_SESSION['token-expire'] = time() + $tokenExpiration;
	 }
  return "<input type='hidden' name='csrf_token' value='$token'>";
}

if (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  die('Invalid CSRF token');
}

if (!isset($_POST['csrf_token']) &&  !($_SESSION['csrf_token'])) {
  die('Token not set');
}
?>


