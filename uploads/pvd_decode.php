<?php
    include("../php/db_connect.php");
    $db=new DB_Connect();
    $link=$db->connect();
      if($link == false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    session_start();
    $myusername=$_SESSION["yourUsername"];
    $ImgName = $_POST['imageFile'];
    //echo "<script>alert($ImgName);</script>";
    $sql1 = "SELECT id FROM pvd WHERE (receiver = '$myusername' or user = '$myusername') and imgName='$ImgName'";
    $result = mysqli_query($link,$sql1);
    //echo $result;
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

    $count = mysqli_num_rows($result);
    if($count == 1) {
        // Get the uploaded file
        $target_file = basename($_FILES["imageFile1"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if file is a valid image
        $check = getimagesize($_FILES["imageFile1"]["tmp_name"]);
        if($check === false) {
            die("Error: File is not a valid image.");
        }

        // Check file size
        if ($_FILES["imageFile1"]["size"] > 500000) {
            die("Error: File is too large.");
        }

        // Move the file to the uploads directory
        if (!move_uploaded_file($_FILES["imageFile1"]["tmp_name"], $target_file)) {
            die("Error: Failed to upload file.");
        }

        // Execute the decode.py script with the uploaded file's path as argument
        $command = "/usr/local/opt/python/libexec/bin/python decode_pvd.py $target_file";
        $output = shell_exec($command);
        //echo $output;
        $sql2 = "SELECT message FROM pvd WHERE user = '$myusername' and imgName='$ImgName'";
        $result2 = mysqli_query($link,$sql2);
        $row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
        $message = $row2['message'];
        echo $message;
        // Display the extracted message
        //echo "<h2>Secret Message:</h2>";
        //echo "<p>" . $output . "</p>";
     }
    else {
       echo "DENIED";
    }

?>
