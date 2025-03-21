<?php
try {
    require_once(__DIR__ . '/../../conex/conex.php'); // usamos __dir__ 
    require __DIR__ . '/../../vendor/autoload.php'; // usamos __dir__ 
} catch (Exception $e) {
    echo json_encode(['error' => 'error al incluir archivos: ' . $e->getMessage()]);
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conex = new Database;
$con = $conex->conectar();
session_start();

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // configuracion del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // cambia esto por tu servidor smtp
        $mail->SMTPAuth = true;
        $mail->Username = 'juegosena854@gmail.com'; // cambia esto por tu correo
        $mail->Password = 'nukg gijp yzbi qubl'; // cambia esto por tu contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // remitente y destinatario
        $mail->setFrom('juegosena854@gmail.com', 'Garena FREE FIRE'); // cambia esto por tu correo y nombre
        $mail->addAddress($to);

        // contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}

function sendActivationEmail($to, $username) {
    $subject = "Cuenta Activada Exitosamente";
    $body = "
    <p>Hola {$username},</p>
    <p>Tu cuenta ha sido activada exitosamente. Ya puedes iniciar sesión y disfrutar del juego.</p>
    <p>Saludos,</p>
    <p>Equipo de Garena FREE FIRE</p>";

    return sendEmail($to, $subject, $body);
}

if (isset($_POST['submit'])) {
    $correo = $_POST['correo'];

    // verifica los nombres de las columnas en tu tabla `usuario`
    $sql = $con->prepare("SELECT ID_usuario AS id, username FROM usuario WHERE email = ?");
    $sql->execute([$correo]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(16)); // generar un token unico
        $_SESSION['token'] = $token;
        $_SESSION['user_id'] = $user['id'];

        $subject = "Correo de verificacion para cambiar contrasena";
        $body = "
        <p>Buen dia,</p>
        <p>Realizaste una solicitud para cambiar tu contrasena en juego FREE-FIRE. Si no fuiste tu, ignora este correo.</p>
        <p>Haz clic en el siguiente enlace para cambiar tu contrasena:</p>
        <p><a href='http://localhost/juego_FF/PHPMailer-master/nueva_contra.php?token=$token'>Cambiar mi contrasena</a></p>
        <p>Gracias por su espera 😊.</p>";

        if (sendEmail($correo, $subject, $body)) {
            header("Location: ../recuperar_correo.php?message=ok");
        } else {
            header("Location: ../recuperar_correo.php?message=error");
        }
        exit();
    } else {
        echo "<script>alert('El usuario no se encuentra registrado');</script>";
        echo "<script>window.location='../recuperar_correo.php'</script>";
    }
}
?>