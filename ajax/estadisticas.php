<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();

    if (isset($_GET['id_usuario'])) {
        $id_usuario = $_GET['id_usuario'];
        $sql = $con->prepare("SELECT * FROM usuario WHERE ID_usuario = ?");
        $sql->execute([$id_usuario]);
        $estadisticas = $sql->fetch(PDO::FETCH_ASSOC);

        if ($estadisticas) {
            echo "<p>Puntos: " . $estadisticas['Puntos'] . "</p>";
            echo "<p>Nivel: " . $estadisticas['nivel'] . "</p>";
            echo "<p>Partidas Ganadas: " . $estadisticas['partidas_ganadas'] . "</p>";
            echo "<p>Partidas Perdidas: " . $estadisticas['partidas_perdidas'] . "</p>";
            echo "<p>Dañol total: " . $estadisticas['dano_total'] . "</p>";
            echo "<p>Headshots: " . $estadisticas['headshots'] . "</p>";
        } 
        
        else {
            echo "<p>No se encontraron estadísticas para este usuario.</p>";
        }
    } 

    else {
        echo "<p>ID de usuario no proporcionado.</p>";
    }
?>