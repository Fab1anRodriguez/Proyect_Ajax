<?php

require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

if (isset($_POST['enviar'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)){
        echo "<script>alert('Existen datos vacíos')</script>";
        echo "<script>window.location = '../index.html'</script>";
        exit();
    } else {
        $password_descr = htmlentities(addslashes($password));
        $sqlUser = $con->prepare("SELECT * FROM usuario WHERE username = ?");
        $sqlUser->execute([$username]);
        $u = $sqlUser->fetch();

        if ($u) {
            // Verificar si el último ingreso fue hace más de 10 días
            $ultimoIngreso = new DateTime($u['Ultimo_ingreso']);
            $fechaActual = new DateTime();
            $diferenciaDias = $fechaActual->diff($ultimoIngreso)->days;

            if ($diferenciaDias > 10) {
                // Bloquear la cuenta del usuario
                $sqlBloquear = $con->prepare("UPDATE usuario SET ID_estado = 2 WHERE ID_usuario = ?");
                $sqlBloquear->execute([$u['ID_usuario']]);
                echo "<script>alert('Usted no ha entrado por más de 10 días, su cuenta ha sido bloqueada. El administrador se encargará de desbloquearla.')</script>";
                echo "<script>window.location = '../index.html'</script>";
                exit();
            }

            if (password_verify($password_descr, $u["password"])) {
                if ($u["ID_estado"] == 1) {
                    session_start();
                    $_SESSION['id_usuario'] = $u['ID_usuario'];
                    $_SESSION['username'] = $u['username'];
                    $_SESSION['rol'] = $u['ID_rol'];
                    $_SESSION['estado'] = $u['ID_estado'];

                    if ($_SESSION['rol'] == 1) {
                        header("Location: ../admi/admi.php");
                    } elseif ($_SESSION['rol'] == 2) {
                        header("Location: ../jugador/inicio.php");
                    }
                } else {
                    echo "<script>alert('Usuario inactivo')</script>";
                    echo "<script>window.location = '../index.html'</script>";
                }
            } else {
                echo '<script>alert("Contraseña incorrecta")</script>';
                echo '<script>window.location = "../index.html"</script>';
            }
        } else {
            echo '<script>alert("El usuario no existe")</script>';
            echo '<script>window.location = "../index.html"</script>';
        }
    }
}
?>