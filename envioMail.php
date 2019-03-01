<?php
include "config.php";
// Turn off all error reporting
error_reporting(0);
$date= date();
$mail = new H_Mail();
$mail->Subject(utf8_decode($_REQUEST['asunto']));
$mail->Body(utf8_decode($_REQUEST['mensaje']));
$mail->AddAddress($_REQUEST['para']);
$mail->CharSet = 'UTF-8';
echo $mail->Send();
?>