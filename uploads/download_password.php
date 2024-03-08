<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

include("../php/db_connect.php");
$db=new DB_Connect();
$link=$db->connect();
  if($link == false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$yourUsername = $_POST['yourUsername'];
$yourEmail = $_POST['yourEmail'];
$imagePath="forgot.png";
// Check if a file has been uploaded
$sql = "SELECT yourPassword FROM user WHERE yourUsername = '$yourUsername' and yourEmail = '$yourEmail'";
$result = mysqli_query($link,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
$password = $row['yourPassword'];
$count = mysqli_num_rows($result);
if($count == 1) {
        $output = shell_exec("/usr/local/opt/python/libexec/bin/python encode_password.py $imagePath $password $yourUsername");
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

        $image_size1=filesize($imagePath);
        $image_size= round($image_size1 / 1024, 2);
        $filesize_in_bytes = filesize($image_name);
        $image_size_new = round($filesize_in_bytes / 1024, 2);


        $sql1 = "INSERT INTO lsb (message,receiver,user,time,cpu,imgName,size,newSize)
                VALUES ('$password','pwd change req','$yourUsername','$time_taken','$cpu_utilization','$image_name','$image_size','$image_size_new');";
        if ($res = mysqli_query($link, $sql1)) {
                echo "data inserted and image encoded with name ".$image_name;
                sendemail($image_name);
            }
        else {
            echo "ERROR: Could not able to execute $sql1. "
                                        .mysqli_error($link);
        }
        mysqli_close($link);
}else {
    echo "FAILED";
}

function sendemail($imgPath)
{
    // Replace with your email and password/App Password
    $email = "30nick12pujari99@gmail.com";
    $password = "mbadbtsgnodbqbbs";

    $to = "nikhilmpujari30@gmail.com";
    $subject = "Recover your password using Steganography";
    $message = "Please refer to the attached image. Your password is encoded into this image using LSB Image Steganography technqiue. To decode the image you need to download this image first then upload it to the forgot password page.";
    $headers = "From: $email";

    // SMTP details
    $smtpHost = "smtp.gmail.com";
    $smtpPort = "587";

    // Create SMTP object
    $smtp = new PHPMailer();
    $smtp->isSMTP();
    $smtp->SMTPDebug = 0;
    $smtp->SMTPAuth = true;
    $smtp->SMTPSecure = 'tls';
    $smtp->Host = $smtpHost;
    $smtp->Port = $smtpPort;
    $smtp->Username = $email;
    $smtp->Password = $password;

    // Set recipient email address
    $smtp->addAddress($to);
    $smtp->addAttachment($imgPath);

    // Set email content
    $smtp->Subject = $subject;
    $smtp->Body = $message;

    // Send email
    if($smtp->send()){
        echo "Email sent successfully.";
    } else {
        echo "Email sending failed.";
    }
}
?>