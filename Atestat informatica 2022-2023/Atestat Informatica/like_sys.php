<?php
include_once 'connection.php';

try {
  $like = intval($_POST['like']);
  $dislike = intval($_POST['dislike']);
  $count = intval($_POST['count']);
  $username = $_SESSION['username'];
  $ArticleId = $_SESSION['ArticleId'];

  $sql = "DELETE FROM likes_dislikes WHERE username = ? AND ArticleId = ? ;";
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    throw new Exception("Prepare statement error: " . $conn->error);
  }
  $stmt->bind_param("ss", $username, $ArticleId);
  $stmt->execute();
  if ($stmt->errno) {
    throw new Exception("Execution error: " . $stmt->error);
  }

  if ($count) {
    $sql = "INSERT INTO likes_dislikes (ArticleId, username, like_dislike) VALUES (?, ?, ?);";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
      throw new Exception("Prepare statement error: " . $conn->error);
    }
    $stmt->bind_param("ssi", $ArticleId, $username, $count);
    $stmt->execute();
    if ($stmt->errno) {
      throw new Exception("Execution error: " . $stmt->error);
    }
  }

  $sql = "SELECT likes, dislikes FROM articles WHERE ArticleId = ?;";
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    throw new Exception("Prepare statement error: " . $conn->error);
  }
  $stmt->bind_param("s", $ArticleId);
  $stmt->execute();
  if ($stmt->errno) {
    throw new Exception("Execution error: " . $stmt->error);
  }
  $res = $stmt->get_result();
  if (!$res) {
    throw new Exception("Get result error: " . $conn->error);
  }
  $row = mysqli_fetch_assoc($res);
  $likeCounter = intval($row['likes']) + $like;
  $dislikeCounter = intval($row['dislikes']) + $dislike;

  $sql = "UPDATE articles SET likes = ?, dislikes = ? WHERE ArticleId = ?";
  $stmt = $conn->prepare($sql);
  if (!$stmt) {
    throw new Exception("Prepare statement error: " . $conn->error);
  }
  $stmt->bind_param("iis", $likeCounter, $dislikeCounter, $ArticleId);
  $stmt->execute();
  if ($stmt->errno) {
    throw new Exception("Execution error: " . $stmt->error);
  }

  $response['likeCounter'] = $likeCounter;
  $response['dislikeCounter'] = $dislikeCounter;

  echo json_encode($response);
} catch (Exception $e) {
  http_response_code(500);
  echo "Error: " . $e->getMessage();
}
?>
