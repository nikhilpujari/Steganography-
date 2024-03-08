<?php
  include("db_connect.php");
  $db=new DB_Connect();
  $link=$db->connect();
    if($link == false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    $yourUsername=$_POST["yourUsername"];
    $yourPassword=$_POST["yourPassword"];


    $sql = "SELECT id FROM user WHERE yourUsername = '$yourUsername' and yourPassword = '$yourPassword'";
    $result = mysqli_query($link,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    
    $count = mysqli_num_rows($result);
    if($count == 1) {
        echo "LOGINED";
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["yourUsername"] = $yourUsername;
    }else {
       echo "FAILED";
    }
?>
