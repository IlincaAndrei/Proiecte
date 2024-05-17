<?php
include_once 'connection.php';
$currentPage = 'index.php';
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Site articole</title>
    <link rel="stylesheet" type="text/css" href="StyleSheet1.css" />
</head>
<body>
 <?php include_once 'navbar.php'; ?>
    
    <div class="mainbody">
      <p style="font-family:Tahoma; font-size:25px; margin-bottom:0px; text-align:center;">
      <?php
      if(!isset($_SESSION['username']))
      echo "Bine ai venit!";
      else echo "Bine ai revenit, ".$_SESSION['username']."!";
      ?>
      </p>
      <br>
       <hr style='width:100%; height:3px; background-color:#303348; border-radius:10px; margin-bottom:40px;'></hr>

      
        <?php 
        $sql = "SELECT ArticleId, ArticleName, preview, img_prev, date FROM articles ORDER BY date DESC;";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result))
        {
            $ArticleName =$row["ArticleName"];
            $ArticleId = $row["ArticleId"];
            $ImagePath = $row["img_prev"];
            $Preview = $row["preview"];
          
            echo  "<form action='article-con.php' method='POST'><input type='submit' style='overflow:hidden;' name='ArticleName'class='Art_btn' value='$ArticleName'></input><br>"; 
            echo csrf_token_input();
            echo "<input type='hidden' name='ArticleId' value='$ArticleId'></input>";
            echo "<br><img class='prev_img' src=".$ImagePath."></img><br><br>";
            echo $Preview."<br><br></form>";
        }
        ?>
            
         
           
    </div>

</body>

</html>