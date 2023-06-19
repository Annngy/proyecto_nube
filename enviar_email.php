<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$direccion = $_POST['direccion'];


//require '/phpmailer/src/PHPMailer.php';
//require '/phpmailer/src/SMTP.php';
//require '/phpmailer/src/Exception.php';

require __DIR__.'/PHPMailer/src/Exception.php';
require __DIR__.'/PHPMailer/src/PHPMailer.php';
require __DIR__.'/PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'tecnoshop142@gmail.com';                     //SMTP username
    $mail->Password   = 'zkxupxawwtklctjp';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS y 465 ENCRYPTION_SMTPS`

    //Recipients
    $mail->setFrom('tecnoshop142@gmail.com', 'Proyecto');
    $mail->addAddress($correo, $nombre);     //Add a recipient


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Compra finalizada';

    $cuerpo = 'Hola ' . $nombre . ',<br><br> 
    gracias por realizar tu compra en TecnoShop<br><br>Tu pedido sera enviado lo antes posible
    <br><br>
    Direccion de envio: ' . $direccion . '<br><br> 
    Saludos.';

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Correo de compra en Tecno Shop';

    $mail->setLanguage('es', 'PHPMailer/language/phpmailer.lang-es.php');
    $mail->SMTPDebug = 0;
    $mail->send();
    
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
  exit;
}