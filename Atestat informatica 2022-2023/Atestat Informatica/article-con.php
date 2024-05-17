<?php

include_once 'connection.php';

if(isset($_POST['ArticleId'])) {
    $_SESSION['ArticleId'] = $_POST['ArticleId'];
}

$currentPage = "article-con.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="StyleSheet1.css">
    <link rel="stylesheet" type="text/css" href="article_style.css">
    <title>Article</title>
</head>
<body>
<?php include_once 'navbar.php'; ?>
<div class="mainbody">
    <div id="cont">
        <?php
        if (!isset($_SESSION['ArticleId'])) {
            http_response_code(400);
            exit;
        }

        $ArticleId = $_SESSION['ArticleId'];

        $sql = "SELECT ArticleId, date, ArticleName, author_name FROM articles WHERE ArticleId = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            exit;
        }
        $stmt->bind_param("s", $ArticleId);
        if (!$stmt->execute()) {
            http_response_code(500);
            exit;
        }
        $res = $stmt->get_result();
        if (!$res) {
            http_response_code(500);
            exit;
        }
        $row = $res->fetch_assoc();
        if (!$row) {
            http_response_code(404);
            exit;
        }

        if(isset($_SESSION['username']) && ($_SESSION['username'] == $row['author_name'])) {
            echo '<button onclick="confirmDelete()">Remove Article</button>';
        }

        $date = date("d-m-Y H:i", strtotime($row['date']));
        $ArticleName = htmlspecialchars($row["ArticleName"], ENT_QUOTES, 'UTF-8');
        $AuthorName = htmlspecialchars($row["author_name"], ENT_QUOTES, 'UTF-8');
        echo "<p id='title'>".$ArticleName."</p><br>";
        echo "<div id='h2' style='float:right; display:flex; flex-direction:column; margin-right:15px;'><p id='author'>Author:".$AuthorName."</p><br>";
        echo "<p id='date' style='font-size:15px; margin-top:0;'>Date:".$date."</p></div>";

        $sql = "SELECT HeaderName, HeaderIndex, Image, Content FROM sections WHERE ArticleId = ? ORDER BY HeaderIndex";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            exit;
        }
        $stmt->bind_param("s", $ArticleId);
        if (!$stmt->execute()) {
            http_response_code(500);
            exit;
        }
        $res = $stmt->get_result();
        if (!$res) {
            http_response_code(500);
            exit;
        }

        while($row = $res->fetch_assoc()) {
            $HeaderName = htmlspecialchars($row["HeaderName"], ENT_QUOTES, 'UTF-8');
            $Image = htmlspecialchars($row["Image"], ENT_QUOTES, 'UTF-8');
            $Content = htmlspecialchars($row["Content"], ENT_QUOTES, 'UTF-8');
            echo "<div id='section'>";
            echo "<p id='head'>".$HeaderName."</p>";
            echo "<hr></hr>";
            echo "<img src='".$Image."'>";
            echo "<p id='content'>".nl2br($Content)."</p>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<script>
    function confirmDelete() {
        if(confirm("Acest articol va fi sters.Confirmati?")) {
            window.location.href = "art_remove.php";
        }
    }
</script>
</body>
</html>
