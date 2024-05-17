<?php
 include_once 'connection.php';
 $currentPage="signin.php";
?>

<html>
<head>
<title>LogIn</title>
 <link rel="stylesheet" type="text/css" href="StyleSheet1.css" />
 <link rel="stylesheet" type="text/css" href="sign.css" />
</head>

<body>
<?php include_once 'navbar.php'; ?>
<div class="mainbody">
<div id="title">
<button id="h1" onclick="location.href='login.php'">LOG IN</button>
<button id="h2" >SIGN UP</button>
</div>
<br>
<form action="sign_sub.php" method="POST">
<label id="uslbl">UserName</label>
<input type="text" maxlength="25" name="usnm" onkeyup="checkUsernameAvailability(this.value)" id="usinp"></input>
<br>
<div id="usnmError" style="color: red;"></div>
<br>
<label id="pslbl">PassWord</label>
<?php echo csrf_token_input();?>
<input type="password" onkeyup="checkPasswordLength(this.value)" name="pswd" id="psinp"></input>
<br>
<div id="pswdError" style="color: red;"></div>
<br>
<button type="submit" id="subinp">Submit</button>
<br>

</form>
</div>
</body>
 
<script>
  function checkUsernameAvailability(username) {
    if (username.length >= 3) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("usnmError").innerHTML = this.responseText;
                if (xhr.responseText == 'Acest nume de utilizator nu este disponibil!') {
            document.getElementById('subinp').disabled = true;
          } else {
            document.getElementById('subinp').disabled = false;
          }
            }
        };
        xhr.open("POST", "check_username.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("username=" + username);
    } else {
        document.getElementById("usnmError").innerHTML = "Numele de utilizator trebuie sa contina minim 3 caractere!";
        document.getElementById('subinp').disabled = true;
    }
}

 function checkPasswordLength(password){
    if(password.length < 5){
        document.getElementById('subinp').disabled = true;
        document.getElementById('pswdError').innerHTML = "Parola trebuie sa contina minim 5 caractere!";
    }
    else{
        document.getElementById('subinp').disabled = false;
        document.getElementById('pswdError').innerHTML = "";
    }
 }
</script>

</html>