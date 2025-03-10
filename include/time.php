<?php
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $sql = $con->prepare("SELECT Ultimo_ingreso FROM usuario WHERE ID_usuario = ?");
    $sql->execute([$id_usuario]);
    $u = $sql->fetch();

    $ultima_conexion = new DateTime($u['Ultimo_ingreso']);
    $fecha_actual = new DateTime();
    $intervalo = $fecha_actual->diff($ultima_conexion);

    if ($intervalo->days > 5) {
        $updateEstado = $con->prepare("UPDATE usuario SET ID_estado = 2 WHERE ID_usuario = ?");
        $updateEstado->execute([$id_usuario]);

        echo '<script>alert("Tu cuenta ha sido bloqueada por inactividad de más de 5 días.")</script>';
        echo '<script>window.location = "../index.html"</script>';
    } else {
        // Actualizar la última conexión a la fecha actual
        $updateConexion = $con->prepare("UPDATE usuario SET Ultimo_ingreso = ? WHERE ID_usuario = ?");
        $updateConexion->execute([$fecha_actual->format('Y-m-d H:i:s'), $id_usuario]);
    }
} else {
    echo '<script>alert("Debes iniciar sesión para acceder a esta página")</script>';
    echo '<script>window.location = "../index.html"</script>';
}
?>