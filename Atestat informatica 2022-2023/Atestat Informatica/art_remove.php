<?php
include_once('connection.php');

if (!isset($_SESSION['ArticleId'])) {
  http_response_code(400);
  exit("Invalid request");
}

$ArticleId = $_SESSION['ArticleId'];

$sql = "SELECT img_prev FROM articles WHERE ArticleId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ArticleId);
$stmt->execute();
$res = $stmt->get_result();

if(mysqli_num_rows($res) > 0) {
  $row = mysqli_fetch_assoc($res);
  $img_path = $row['img_prev'];
  if(file_exists($img_path) && !unlink($img_path)) {
    http_response_code(500);
    exit("Failed to delete article image");
  }
}

$sql = "SELECT Image FROM sections WHERE ArticleId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ArticleId);
$stmt->execute();
$res = $stmt->get_result();

if(mysqli_num_rows($res) > 0) {
  while($row = mysqli_fetch_assoc($res)) {
    $img_path = $row['Image'];
    if(file_exists($img_path) && !unlink($img_path)) {
      http_response_code(500);
      exit("Failed to delete section image");
    }
  }
}

$sql = "DELETE FROM articles WHERE ArticleId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ArticleId);
if (!$stmt->execute()) {
  http_response_code(500);
  exit("Failed to delete article");
}

$sql = "DELETE FROM sections WHERE ArticleId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ArticleId);
if (!$stmt->execute()) {
  http_response_code(500);
  exit("Failed to delete sections");
}

$sql = "DELETE FROM likes_dislikes WHERE ArticleId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ArticleId);
if (!$stmt->execute()) {
  http_response_code(500);
  exit("Failed to delete likes_dislikes");
}

header("location:index.php");

?>