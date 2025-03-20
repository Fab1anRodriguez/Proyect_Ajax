<?php
session_start();
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$sala_id = $_POST['sala_id'];
$ganador_id = $_POST['ganador_id'];

try {
    // actualizamos al ganador
    $sql = $con->prepare("UPDATE usuario u 
                         SET u.partidas_ganadas = u.partidas_ganadas + 1
                         WHERE u.ID_usuario = ?");
    $sql->execute([$ganador_id]);

    // actualizamos a los perdedores 
    $sql = $con->prepare("UPDATE usuario u 
                         INNER JOIN partidas p ON u.ID_usuario = p.ID_usuario 
                         SET u.partidas_perdidas = u.partidas_perdidas + 1
                         WHERE p.ID_sala = ? 
                         AND p.ID_usuario != ?");
    $sql->execute([$sala_id, $ganador_id]);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//7:17PM 19/03/2025
    // obtener las estadísticas de todos los jugadores en la partida
    $sql = $con->prepare("SELECT ID_usuario, Puntos, headshots, dano_total FROM partidas WHERE ID_sala = ?");
    $sql->execute([$sala_id]);
    $jugadores = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($jugadores as $jugador) {
        $sqlUpdate = $con->prepare("UPDATE usuario 
                                    SET Puntos = Puntos + ?, 
                                        headshots = headshots + ?, 
                                        dano_total = dano_total + ? 
                                    WHERE ID_usuario = ?");
        $sqlUpdate->execute([$jugador['Puntos'], $jugador['headshots'], $jugador['dano_total'], $jugador['ID_usuario']]);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>