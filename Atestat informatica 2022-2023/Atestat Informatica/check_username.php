<?php
include_once('connection.php');

$username = $_POST['username'];

try {
  $sql = "SELECT * FROM accounts WHERE username = ?";
  $stmt = $conn -> prepare($sql);
  if (!$stmt) {
    throw new Exception("Query preparation failed");
  }
  $stmt -> bind_param("s", $username);
  if (!$stmt -> execute()) {
    throw new Exception("Query execution failed");
  }
  $res = $stmt -> get_result();
  if (!$res) {
    throw new Exception("Failed to retrieve results");
  }
  if (mysqli_num_rows($res) > 0) {
    echo 'Acest nume de utilizator nu este disponibil!';
  } else {
    echo '';
  }
} catch (Exception $e) {
  echo "Error: " . $e -> getMessage();
}

?>
