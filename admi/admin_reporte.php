<?php
include '../conex/conex.php';
header('Content-Type: application/json');

// crear conexion
$conexion = new database();
$con = $conexion->conectar();

// consulta para obtener estadisticas de jugadores
$sql = "SELECT 
    u.ID_usuario, 
    u.username, 
    COUNT(p.ID_partida) AS partidas_jugadas,
    SUM(p.puntos_partida) AS total_puntos,
    SUM(p.dano_total) AS total_dano,
    SUM(p.headshots) AS total_headshots,
    u.partidas_ganadas, 
    u.partidas_perdidas
    FROM usuario u
    LEFT JOIN partidas p ON u.ID_usuario = p.ID_usuario
    GROUP BY u.ID_usuario";

// ejecutar consulta y devolver resultados
$result = $con->query($sql);
$estadisticas = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($estadisticas); 
?>
