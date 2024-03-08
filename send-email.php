<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';

    // Replace with your email and password/App Password
    $email = "30nick12pujari99@gmail.com";
    $password = "mbadbtsgnodbqbbs";

    $to = "nikhilmpujari30@gmail.com";
    $subject = "Test Email";
    $message = "This is a test email sent from XAMPP.";
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
    $smtp->addAttachment('uploads/photo.png');

    // Set email content
    $smtp->Subject = $subject;
    $smtp->Body = $message;

    // Send email
    if($smtp->send()){
        echo "Email sent successfully.";
    } else {
        echo "Email sending failed.";
    }
?>
