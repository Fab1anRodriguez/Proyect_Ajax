<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();

    if(isset($_GET['id_avatar']) && isset($_GET['id_usuario'])) {
        $id_avatar = $_GET['id_avatar'];
        $id_usuario = $_GET['id_usuario'];

        $sqlUpdateAvatars = $con->prepare("UPDATE usuario SET ID_avatar = ? WHERE ID_usuario = ?");
        $sqlUpdateAvatars->execute([$id_avatar, $id_usuario]);
    } 
    
    else {
        echo 'error';
    }
?>