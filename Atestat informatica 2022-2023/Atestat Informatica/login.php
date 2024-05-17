<?php
 include_once 'connection.php';
 $currentPage="login.php";

?>

<html>
<head>
<title>LogIn</title>
 <link rel="stylesheet" type="text/css" href="StyleSheet1.css" />
  <link rel="stylesheet" type="text/css" href="log_style.css" />

</head>

<body>
<?php include_once 'navbar.php'; ?>
<div class="mainbody">
<div id="title">
<button id="h1" >LOG IN</button>
<button id="h2" onclick="location.href='signin.php'">SIGN UP</button>
</div>
<br>
<form action="log_sub.php" method="POST">
<label id="uslbl">UserName</label>
<input type="text" maxlength="25" name="usnm" id="usinp"></input>
<br>
<br>
<label id="pslbl">PassWord</label>
<input type="password" name="pswd" id="psinp"></input>
<br>
<div id="Error" style="color:red;"><?php if(isset($_SESSION['error_message'])) echo $_SESSION['error_message'];unset($_SESSION['error_message']);?></div>
<br>
<?php echo csrf_token_input(); ?>
<button type="submit" id="subinp">Submit</button>
<br>
<p id="p">*You need to be logged-in to write/comment/like/dislike articles</p>
</form>
</div>
</body>


</html>