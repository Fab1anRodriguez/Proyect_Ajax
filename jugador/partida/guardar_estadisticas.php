<?php
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$usuario_id = $_POST['usuario_id'];
$sala_id = $_POST['sala_id'];

// Obtener estadísticas de la partida actual
$sql = $con->prepare("
    SELECT puntos_partida, dano_partida, eliminaciones_partida 
    FROM estadisticas_partida 
    WHERE ID_usuario = ? AND ID_sala = ?
");
$sql->execute([$usuario_id, $sala_id]);
$stats_partida = $sql->fetch(PDO::FETCH_ASSOC);

if ($stats_partida) {
    // Actualizar puntos totales del usuario
    $sql = $con->prepare("
        UPDATE usuario 
        SET Puntos = Puntos + ? 
        WHERE ID_usuario = ?
    ");
    $sql->execute([$stats_partida['puntos_partida'], $usuario_id]);

    // Actualizar estadísticas globales
    $sql = $con->prepare("
        UPDATE estadisticas 
        SET dano_total = dano_total + ?,
            eliminaciones_totales = eliminaciones_totales + ?
        WHERE ID_usuario = ?
    ");
    $sql->execute([
        $stats_partida['dano_partida'],
        $stats_partida['eliminaciones_partida'],
        $usuario_id
    ]);
}

echo json_encode(['success' => true]); 