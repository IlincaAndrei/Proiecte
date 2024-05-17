<?php
include_once 'connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$prv = null;
$title = htmlspecialchars($_POST['title1'], ENT_QUOTES, 'UTF-8');
if(isset($_POST['prv']) && $_POST['prv'] != null)
$prv = htmlspecialchars($_POST['prv'], ENT_QUOTES, 'UTF-8');
$author = $_SESSION['username'];
$currentDateTime = date('Y-m-d H:i:s');
$title = mysqli_real_escape_string($conn, $title);
$ArticleId = uniqid('', true);
$ct = 0;
$cont = "<br>";

if ($_FILES['file0']['name'] != "") {
    $imgName = $_FILES['file0']['name'];
    $imgTmpName = $_FILES['file0']['tmp_name'];
    $imgExt = explode('.', $imgName);
    $imgActualExt = strtolower(end($imgExt));
    $imgNameNew = uniqid('', true) . "." . $imgActualExt;
    $imgDestination = 'photos/prev_img/' . $imgNameNew;
    if (!move_uploaded_file($imgTmpName, $imgDestination)) {
        die("File upload failed!");
    }
}else {
    $imgDestination = null; 
}
if(isset($prv) && $prv != null)
$sql = "INSERT INTO articles (ArticleId, ArticleName, img_prev, preview, date, author_name) VALUES (?, ?, ?, ?, ?, ?);";
else $sql = "INSERT INTO articles (ArticleId, ArticleName, img_prev, preview, date, author_name) VALUES (?, ?,NULL, ?, ?, ?);";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error . " Query: " . $sql);
}
if(isset($prv) && $prv != null)
$stmt->bind_param("ssssss", $ArticleId, $title, $imgDestination, $prv, $currentDateTime, $author);
else $stmt->bind_param("sssss", $ArticleId, $title, $prv, $currentDateTime, $author);

if (!$stmt->execute()) {
    die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
}
$stmt->close();
$imgDestination = null;
while (isset($_POST['txt' . $ct])) {
    $head = $_POST['head' . $ct];
    $cont = $_POST['txt' . $ct];

    $cont = preg_replace("#\[sp\]#", "&nbsp;", $cont);
    $cont = preg_replace("#\[nl\]#", "<br>\n", $cont);

    if ($_FILES['imag' . $ct]['name'] != "") {

        $imgName = $_FILES['imag' . $ct]['name'];
        $imgTmpName = $_FILES['imag' . $ct]['tmp_name'];
        $imgExt = explode('.', $imgName);
        $imgActualExt = strtolower(end($imgExt));
        $imgNameNew = uniqid('', true) . "." . $imgActualExt;
        $imgDestination = 'photos/' . $imgNameNew;
        if (!move_uploaded_file($imgTmpName, $imgDestination)) {
            die("File upload failed!");
        }
    }

    $sql = "INSERT INTO sections (ArticleId, HeaderIndex, HeaderName, Image, Content) VALUES ( ?, ?, ?, ?, ?);";

    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error . " Query: " . $sql);
    }
   
$stmt->bind_param("sisss", $ArticleId, $ct, $head, $imgDestination, $cont);

    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    $ct++;
}$stmt->close();

header("Location: index.php");
exit();
?>
