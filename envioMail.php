<?php
include "config.php";

error_reporting(1);


$H_Mail = new H_Mail();


$H_Mail->mail->SMTPDebug  = 3;
$H_Mail->mail->SMTPSecure = 'tls';
$H_Mail->mail->Host       = "smtp.office365.com";
$H_Mail->mail->Port       = 587;
$H_Mail->mail->Username   = "websitelaboral@proydesa.org";
$H_Mail->mail->Password   = "WSLaboral123";


$H_Mail->Subject('test envío');
$H_Mail->Body('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio officiis dolorum, et hic, iste temporibus. Neque ut, asperiores optio quis, eaque natus a aut adipisci necessitatibus doloremque ea, accusamus quidem?</p>');
$H_Mail->AddAddress('rodosoft@gmail.com');


echo '<pre>';
show_array($H_Mail->Send());
echo '</pre>';
