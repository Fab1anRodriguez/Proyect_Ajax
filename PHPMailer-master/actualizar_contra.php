<?php
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (isset($_POST['submit'])) {
    $contra_nueva = $_POST['contra_nueva'];
    $contra_nueva2 = $_POST['contra_nueva2'];
    $token = $_POST['token'];

    if ($contra_nueva == "" || $contra_nueva2 == "" || $token == "") {
        echo "<script>alert('Existen datos vacíos')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
    } elseif ($contra_nueva !== $contra_nueva2) {
        echo "<script>alert('Las contraseñas no coinciden')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
    } else {
        $sql = $con->prepare("SELECT id FROM usuario WHERE token = ?");
        $sql->execute([$token]);
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $contra_nueva_enc = password_hash($contra_nueva, PASSWORD_DEFAULT);
            $updatePassword = $con->prepare("UPDATE usuario SET password = ?, token = NULL WHERE id = ?");
            $updatePassword->execute([$contra_nueva_enc, $user['id']]);
            echo '<script>alert("Contraseña actualizada correctamente")</script>';
            echo '<script>window.location = "../login.html"</script>';
        } else {
            echo "<script>alert('Token inválido')</script>";
            echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
        }
    }
}
?>