<?php
include("../php/db_connect.php");
$db=new DB_Connect();
$link=$db->connect();
  if($link == false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
// Check if a file has been uploaded
if(isset($_FILES['imageFile'])) {
    $receiver = $_POST['receiver'];
    $secretMessage = $_POST['message'];
    $username = $_POST['username'];
        $target_file = basename($_FILES["imageFile"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["imageFile"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "EXISTS";
            $uploadOk = 0;
        }

        // Check file size
        $image_size=$_FILES["imageFile"]["size"];
        if ($image_size > 500000) {
            echo "LARGE";
            $uploadOk = 0;
        }
        $image_size=$image_size/1024;

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "INVALID IMAGE";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk !=0){
            if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
                //echo "The file ". basename( $_FILES["imageFile"]["name"]). " has been uploaded.";

                // Run Python script to encode message in the image
                $imagePath = $target_file;
                echo "hi";
                $output = shell_exec("/usr/local/opt/python/libexec/bin/python encode_pvd.py $imagePath $secretMessage");
                echo $output;

                $image_name = '';
                $cpu_utilization = '';
                $time_taken = '';

                $pattern = '/Image Name:\s*(\S+)/';
                if (preg_match($pattern, $output, $matches) === 1 && isset($matches[1])) {
                    $image_name = $matches[1];
                } else {
                    // Handle the case where the pattern did not match anything.
                    echo "Error: could not find image name in output.";
                }
                
                preg_match('/CPU Utilization:\s*([\d\.]+)/', $output, $matches);
                if (!empty($matches[1])) {
                    $cpu_utilization = $matches[1];
                }

                preg_match('/Time taken:\s*([\d\.]+)\s*seconds/', $output, $matches);
                if (!empty($matches[1])) {
                    $time_taken = $matches[1];
                }

                $filesize_in_bytes = filesize($image_name);
                $image_size_new = round($filesize_in_bytes / 1024, 2);

                $sql1 = "INSERT INTO pvd (message,receiver,user,time,cpu,imgName,size,newSize)
                        VALUES ('$secretMessage','$receiver','$username','$time_taken','$cpu_utilization','$image_name','$image_size','$image_size_new');";
                if ($res = mysqli_query($link, $sql1)) {
                        echo "data inserted and image encoded with name ".$image_name;
                    }
                else {
                    echo "ERROR: Could not able to execute $sql1. "
                                                .mysqli_error($link);
                }
                mysqli_close($link);
                //echo "The encoded image has been saved to the server.";

                // header('Content-Type: image/png');
                // header('Content-Disposition: attachment; filename="photo_encoded.png"');
                // readfile('photo_encoded.png');

                } 
                else {
                echo "Sorry, there was an error uploading your file.";
                
                }
        }
}

?>