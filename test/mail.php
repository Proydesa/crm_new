<?php 

require_once '../config.php';

// Turn off all error reporting

$CFG = new stdClass();

$mail = new H_Mail();

$mail->Subject('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat quis consequuntur vitae, assumenda placeat magnam, deleniti provident, tenetur illum tempore inventore dolores iure incidunt cupiditate saepe ratione, atque doloribus facilis!</p>');
$mail->Body('test de envío');
$mail->AddAddress('rodosoft@hotmail.com');
//$mail->CharSet = 'UTF-8';

print_r($mail);