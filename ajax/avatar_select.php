<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();

    $img = $_POST['id_img'];

    $sqlavatars = $con->prepare("SELECT * FROM avatar WHERE ID_avatar = '$img'");
    $sqlavatars->execute();

    $cadena = "<div name='container-select-avatars' id='container-select-avatars'>";

    while($avatar = $sqlavatars -> fetch(PDO::FETCH_ASSOC)){
        $cadena .= "<img src='../img/avatares/" . $avatar['imagen'] . "' width='500px' height='300px' alt=''>";
    }

    $cadena .= "</div>";

    echo $cadena;
?>