<?php
session_start();
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$sala_id = $_POST['sala_id'];
$ganador_id = $_POST['ganador_id'];

try {
    // Verificar si la partida ya fue actualizada
    $sqlCheck = $con->prepare("SELECT estado FROM partidas WHERE ID_sala = ? LIMIT 1");
    $sqlCheck->execute([$sala_id]);
    $estado = $sqlCheck->fetch(PDO::FETCH_ASSOC);
    
    if ($estado && $estado['estado'] === 'finalizada') {
        echo json_encode(['success' => true, 'message' => 'Estadísticas ya actualizadas']);
        exit;
    }

    $con->beginTransaction();

    // Marcar la partida como finalizada y actualizar fecha_fin
    $sqlUpdateEstado = $con->prepare("UPDATE partidas 
                                    SET estado = 'finalizada',
                                    fecha_fin = NOW() 
                                    WHERE ID_sala = ?");
    $sqlUpdateEstado->execute([$sala_id]);

    // Restaurar vida de todos los jugadores
    $sqlVida = $con->prepare("UPDATE usuario u 
                             INNER JOIN partidas p ON u.ID_usuario = p.ID_usuario 
                             SET u.vida = 100 
                             WHERE p.ID_sala = ?");
    $sqlVida->execute([$sala_id]);

    // Actualizar partidas ganadas del ganador
    $sqlGanador = $con->prepare("UPDATE usuario SET partidas_ganadas = partidas_ganadas + 1 
                                WHERE ID_usuario = ?");
    $sqlGanador->execute([$ganador_id]);

    // Actualizar partidas perdidas de los demás jugadores
    $sqlPerdedores = $con->prepare("UPDATE usuario u 
                                   INNER JOIN partidas p ON u.ID_usuario = p.ID_usuario 
                                   SET u.partidas_perdidas = u.partidas_perdidas + 1
                                   WHERE p.ID_sala = ? AND u.ID_usuario != ?");
    $sqlPerdedores->execute([$sala_id, $ganador_id]);

    // Obtener y actualizar estadísticas individuales
    $sqlEstadisticas = $con->prepare("SELECT ID_usuario, puntos_partida, headshots, dano_total 
                                     FROM partidas WHERE ID_sala = ?");
    $sqlEstadisticas->execute([$sala_id]);
    $jugadores = $sqlEstadisticas->fetchAll(PDO::FETCH_ASSOC);

    // Actualizar estadísticas para cada jugador
    foreach ($jugadores as $jugador) {
        $sqlUpdate = $con->prepare("UPDATE usuario 
                                   SET Puntos = Puntos + ?,
                                       headshots = headshots + ?,
                                       dano_total = dano_total + ?
                                   WHERE ID_usuario = ?");
        $sqlUpdate->execute([
            $jugador['puntos_partida'],
            $jugador['headshots'],
            $jugador['dano_total'],
            $jugador['ID_usuario']
        ]);
    }

    $con->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($con->inTransaction()) {
        $con->rollBack();
    }
    echo json_encode(['error' => $e->getMessage()]);
}
?>