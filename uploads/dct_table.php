<?php
  include("../php/db_connect.php");
  $db=new DB_Connect();
  $link=$db->connect();
 if($link == false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    session_start();
    $myusername=$_SESSION["yourUsername"];
$sql = "SELECT * FROM dct where user='$myusername' order by id desc;";
if ($res = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($res) > 0) {
        echo "<thead><tr>";
        echo "<th scope='col'>#</th>";
        echo "<th scope='col'>Time Taken</th>";
        echo "<th scope='col'>CPU Utilised</th>";
        echo "<th scope='col'>Orginal Image Size(kb)</th>";
        echo "<th scope='col'>Encoded Image Size(kb)</th>";
        echo "</tr></thead><tbody>";
        while ($row = mysqli_fetch_array($res)) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['time']."</td>";
            echo "<td>".$row['cpu']."</td>";
            echo "<td>".$row['size']."</td>";
            echo "<td>".$row['newSize']."</td>";
             echo "</tr>";
        
         }
         echo "</tbody>";
  }
    else {
        echo "No records found for current user.";
    }
}
else {
    echo "ERROR: Could not able to execute $sql. "
                                .mysqli_error($link);
}
    mysqli_close($link);
?>
