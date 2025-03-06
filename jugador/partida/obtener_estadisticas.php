<?php
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$usuario_id = $_GET['usuario_id'];

// Obtener todas las estadísticas
$sql = $con->prepare("
    SELECT 
        e.partidas_ganadas,
        e.partidas_perdidas,
        e.eliminaciones_totales,
        e.dano_total,
        u.Puntos as puntos_partida
    FROM estadisticas e
    INNER JOIN usuario u ON e.ID_usuario = u.ID_usuario
    WHERE e.ID_usuario = ?
");

$sql->execute([$usuario_id]);
$stats = $sql->fetch(PDO::FETCH_ASSOC);

if (!$stats) {
    $stats = [
        'partidas_ganadas' => 0,
        'partidas_perdidas' => 0,
        'eliminaciones_totales' => 0,
        'dano_total' => 0,
        'puntos_partida' => 0
    ];
}

echo json_encode($stats); 