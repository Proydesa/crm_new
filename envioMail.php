<?php
include "config.php";

error_reporting(1);

$mail = new H_Mail();

$mail->Subject('test envío');
$mail->Body('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio officiis dolorum, et hic, iste temporibus. Neque ut, asperiores optio quis, eaque natus a aut adipisci necessitatibus doloremque ea, accusamus quidem?</p>');
$mail->AddAddress('rodosoft@gmail.com');
$mail->AddBCC('rodosoft@gmail.com');


print_r($mail->Send());
