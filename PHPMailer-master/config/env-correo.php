<?php


require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isset($_POST['submit'])){
    
    $correo = $_POST['correo'];

    $sql = $con->prepare("SELECT username FROM usuario WHERE email = '$correo'");
    $sql->execute();
    $username = $sql->fetch(PDO::FETCH_ASSOC);

    if ($username){
  
    $mail= new PHPMailer(true);

    try {
        //Server settings

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;;
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'juegosena854@gmail.com';                     //SMTP username
        $mail->Password   = 'nukg gijp yzbi qubl';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
        $mail->Port       = 587;                                       //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
    
        //Recipients
    $mail->setFrom('juegosena854@gmail.com', 'Garena FREE FIRE');
        $mail->addAddress($correo);     //Add a recipient
      
    
        //Content
       
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "Correo de verificación para cambiar contraseña";
        $mail->Body    = "
        <p>Buen dia,</p>
        <p>Realizaste una solicitud para cambiar tu contraseña en juego FREE-FIRE. Si no fuiste tú, ignora este correo.</p>
        <p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p>
        <p><a href='http://localhost/juego/PHPMailer-master/nueva_contra.php'>Cambiar mi contraseña</a></p>
        <p>Este enlace de verificacion caducara en 15 minutos.</p>";
    
        $mail->send();
        header("Location: ../recuperar_correo.php?message=ok");
        exit();
        } catch (Exception $e) {
            error_log("Error al enviar el correo: " . $mail->ErrorInfo);
            header("Location: ../recuperar_correo.php?message=error");
            exit();
    }
        
    }
    
    else {
        echo"<script>alert('El usuario no se encuentra registrado');</script>";
        echo "<script>window.location='../recuperar_correo.php'</script>";
    }

   
    
}

?>