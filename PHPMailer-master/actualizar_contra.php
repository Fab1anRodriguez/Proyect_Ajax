<?php
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();
session_start();

if (isset($_POST['submit'])) {
    $contra_nueva = $_POST['contra_nueva'];
    $contra_nueva2 = $_POST['contra_nueva2'];
    $token = $_POST['token'];

    // Depuración: Mostrar los valores de las variables
    error_log("contra_nueva: " . $contra_nueva);
    error_log("contra_nueva2: " . $contra_nueva2);
    error_log("token: " . $token);

    if ($contra_nueva == "" || $contra_nueva2 == "" || $token == "") {
        echo "<script>alert('Existen datos vacíos')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
    } elseif ($contra_nueva !== $contra_nueva2) {
        echo "<script>alert('Las contraseñas no coinciden')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
    } elseif ($token !== $_SESSION['token']) {
        echo "<script>alert('Token inválido')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $contra_nueva_enc = password_hash($contra_nueva, PASSWORD_DEFAULT);
        $updatePassword = $con->prepare("UPDATE usuario SET password = ? WHERE ID_usuario = ?");
        $updatePassword->execute([$contra_nueva_enc, $user_id]);
        unset($_SESSION['token']);
        unset($_SESSION['user_id']);
        echo '<script>alert("Contraseña actualizada correctamente")</script>';
        echo '<script>window.location = "../index.html"</script>';
    }
}
?>