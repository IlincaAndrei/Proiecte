<?php
include_once 'connection.php';
$currentPage="page2.php";

if(!isset($_SESSION['username']))
{
    header("location:login.php");
}
?>

 <!DOCTYPE html>

 <html lang="en" xmlns="http://www.w3.org/1999/xhtml">
 <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="StyleSheet2.css" />
    <link rel="stylesheet" type="text/css" href="StyleSheet1.css" />
   
    <title>Nou Continut</title>
 </head>
 <body>
 <?php include_once 'navbar.php'; ?>
  <div class ="mainbody" id="mainbody">
  <form id ="form1" action="submit.php" method="POST" enctype="multipart/form-data" onsubmit="NewLiner()">
   <label class="lbl1" style="font-size:20px;font-family:Tahoma;"> Titlu:</label>
   <input type="text" maxlength="40" class="ip1" name="title1"></input>
   <br>
   <input id="file0" type="file" name="file0" accept="image/png, image/jpeg"></input>
   <br>
   <label class="label_file" style="margin-left:10px;width:260px;" for="file0">Adauga imagine de previzualizare</label>
   <br>
   <img id="prv_img" style="max-height: 250px;max-width: 250px;display:inline-block; border-radius:5px; margin-bottom:10px ;"></img>
   <br>
   <?php csrf_token_input(); ?>
   <textarea id="prv" name="prv"></textarea>
   <br>
   <label style="font-size:20px;font-family:Tahoma;">Continut:</label>
   <br>
   <input type="button" id = "nH" value="Adauga o noua sectiune" onclick="NewHeader()"></input>
   <br>
   <br>
   <button type="submit" name="sub1" id="sub">Trimite articol</button>
  </form>
   </div>

   

 </body>
  <script src="page2-script.js"></script>
 </html>

