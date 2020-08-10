<?php

//mail to respondent
include "classes/class.phpmailer.php"; // include the class name

$mail = new PHPMailer(); // create a new object
// $mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587-465
$mail->IsHTML(true);
$mail->Username = "your_mail@gmail.com";
$mail->Password = "your_password";
$mail->SetFrom("your_mail@gmail.com");
$mail->Subject = $mailSubject;
//$txtEmail=$txtEmail;

//$mailsha= sha1($email);
//$mailstr= urlencode($mailsha);
$mail->Body = $message;
//$mail->Body = '<img src="images/logo.png">';
//$mail->AddAttachment("attachment/Inquiry_form.pdf");
$mail->AddAddress($txtEmail);

if(!$mail->Send()){
	echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
	echo"<script>alert(\"Message Send to $txtEmail\");</script>";
}
?>