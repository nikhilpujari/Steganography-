<?php
  include("db_connect.php");
  $db=new DB_Connect();
  $link=$db->connect();
 if($link == false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    $yourName=$_POST["yourName"];
    $yourEmail=$_POST["yourEmail"];
    $yourUsername=$_POST["yourUsername"];
    $yourPassword=$_POST["yourPassword"];

$sql = "INSERT INTO user (yourName,yourEmail,yourUsername,yourPassword)
VALUES ('$yourName','$yourEmail','$yourUsername','$yourPassword');";
if ($res = mysqli_query($link, $sql)) {
        echo "data inserted";
    }
else {
    echo "ERROR: Could not able to execute $sql. "
                                .mysqli_error($link);
}
    mysqli_close($link);
?>
