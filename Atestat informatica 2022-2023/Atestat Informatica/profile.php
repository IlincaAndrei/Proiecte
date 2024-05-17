<?php
 ob_start();
 $currentPage='profile.php';
  include_once 'connection.php';
 include_once 'navbar.php';
 if(isset($_POST["logout"]))
  {
	  $_SESSION['username'] = null;
	  $_SESSION['password'] = null;
	
	  header("Location:index.php");
	  exit();
  }


?>

<html>

<head>
 <link rel="stylesheet" type="text/css" href="StyleSheet1.css" />
 <link rel="stylesheet" type="text/css" href="profile_style.css" />
 
</head>

<body>
<div class="mainbody">
<p style="font-size:30px;">Informatii profil</p>
<br>
 <?php
echo "<form method='POST' action = 'info_chg.php' >";
echo csrf_token_input();
echo "Nume de utilizator:<input type='text' name='usnm' id='usnm' onclick='' value = '".$_SESSION['username']."'>";
echo "<br>Parola:<input type='password' name='pswd' id='pswd' value = '".$_SESSION['password']."'>";
echo "<br><input type='submit' id='chg_btn' class = 'btn' value='Schimba datele contului'></input><br>";
echo "</form>";
 
?>
<?php
echo "<form method='POST'>".csrf_token_input()."<input type='submit' class = 'btn' name='logout' value='Log Out'></input></form>";

    ob_end_flush();
?>
<br>
</div>
</body>

</html>