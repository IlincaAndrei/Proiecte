<link rel="stylesheet" type="text/css" href="nav_styles.css" />
<nav>
        <ul>
            <li> <a href="index.php" style="margin-left:20px;"<?php if ($currentPage === 'index.php') { echo 'class="active"'; } ?> >ACASA</a> </li>
            <li> <a href="page2.php" <?php if ($currentPage === 'page2.php') { echo 'class="active"'; } ?>> NOU CONTINUT</a></li>
            <li> 
            <?php
            if(!isset($_SESSION['username']))
            {
                $logli = "<a href='login.php'";
                if($currentPage === 'login.php' || $currentPage === 'signin.php')
                {$logli.= "class='active'";}
                $logli.=">LOG IN</a>";
                echo $logli;
                
            }
            else
            {
                $profli = "<a href='profile.php'";
                if($currentPage === 'profile.php')
                {$profli.= "class='active'";}
                $profli.= ">".$_SESSION['username']."</a>";
                echo $profli;
            }
            ?>         
           </li><?php
          if($currentPage == "article-con.php" && isset($_SESSION['username']))
          {
              include_once 'connection.php';
             
              $ArticleId = $_SESSION['ArticleId'];
              $username = $_SESSION['username'];

             $stmt = $conn ->prepare("SELECT likes, dislikes FROM articles WHERE ArticleId = ?;");
           
             if (!$stmt) {
            throw new Exception("Query preparation failed");
             }
             $stmt -> bind_param("s", $ArticleId);
             $stmt-> execute();
             $res = $stmt ->get_result();
             if (!$res) {
             throw new Exception("Failed to retrieve results");
              }
             $row = mysqli_fetch_assoc($res);
             $like = $row['likes'];
             $dislike = $row['dislikes'];

              echo "<li id='ld_box'>
              <button name='l_btn' id='l_btn'>like</button>
              <p id='l_count'>$like</p>
              <button name='d_btn' id='d_btn'>dislike</button>
              <p id='d_count'>$dislike</p></li>";

 
 
   $sql = "SELECT like_dislike FROM likes_dislikes WHERE ArticleId = ? AND username = ?";
   $stmt = $conn->prepare($sql);
    if (!$stmt) {
            throw new Exception("Query preparation failed");
             }
   $stmt->bind_param("ss", $ArticleId, $username);
   $stmt->execute();
   $res = $stmt->get_result();
   if (!$res) {
             throw new Exception("Failed to retrieve results");
              }
  
 if(isset($res) && mysqli_num_rows($res) > 0)
 {
      $result = mysqli_fetch_assoc($res);
      $liked = intval($result['like_dislike']);
  
  
 if($liked == 1){

	echo "<script>document.getElementById('l_btn').style.color = '#ffcb6b';</script>";
    echo "<script>document.getElementById('d_btn').style.color = '#FFFFFF';</script>";
 
 }

 else {

	 echo "<script>document.getElementById('d_btn').style.color = '#ffcb6b';</script>";
    echo "<script>document.getElementById('l_btn').style.color = '#FFFFFF';</script>";
 }


 }
            
          }
          ?></ul>
        
 <div id="p_bar">
 </div>
 </nav>

 <script>
 document.addEventListener("DOMContentLoaded", function (){
 const progressbar = document.getElementById("p_bar");

 window.addEventListener('scroll',  function() {
    update(progressbar);
  });
   


 function update(progressbar){
      let h = document.documentElement;

    let st = h.scrollTop || document.body.scrollTop;
    let sh = h.scrollHeight || document.body.scrollHeight;

    let percent = st/(sh - h.clientHeight) *100;

    progressbar.style.width = percent + "%";
 }

   
   var like = document.getElementById('l_btn');
   var dislike = document.getElementById('d_btn');

   if(like !=null)
   like.addEventListener("click", function(){
   if(like.style.color == "rgb(255, 203, 107)") {
      like.style.color = "#FFFFFF";
      Handler(-1, 0, 0);
   }
   else if(dislike.style.color == "rgb(255, 203, 107)") {
      like.style.color = "rgb(255, 203, 107)";
      dislike.style.color = "#FFFFFF";
      Handler(1, -1, 1);
   }
   else {
      like.style.color = "rgb(255, 203, 107)";
      Handler(1, 0, 1);
   }
});

if(dislike != null)
dislike.addEventListener("click", function(){
   if(dislike.style.color == "rgb(255, 203, 107)") {
      dislike.style.color = "#FFFFFF";
      Handler(0, -1, 0);
   }
   else if(like.style.color == "rgb(255, 203, 107)") {
      dislike.style.color = "rgb(255, 203, 107)";
      like.style.color = "#FFFFFF";
      Handler(-1, 1, -1);
   }
   else {
      dislike.style.color = "rgb(255, 203, 107)";
      Handler(0, 1, -1);
   }
});

   function Handler(like, dislike, count)
   {
	   xhr = new XMLHttpRequest();
	   xhr.open("POST", "like_sys.php", true);
	   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
       xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            var response = JSON.parse(this.responseText);
            document.getElementById('l_count').innerHTML = response.likeCounter;
            document.getElementById('d_count').innerHTML = response.dislikeCounter;
        }
    };
	   xhr.send("like="+like+"&dislike="+dislike+"&count="+count);
   }
    });

 </script>

    </html>