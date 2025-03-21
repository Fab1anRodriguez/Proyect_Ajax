<?php
include '../conex/conex.php';
header('Content-Type: application/json');

$conexion = new database();
$con = $conexion->conectar();
//aqui hacemos la consulta para obtener las estadisticas de los usuarios
//hacemos el cpunt para obtener el total de partidas jugadas
$sql = "SELECT 
    username,
    (SELECT COUNT(*) FROM partidas WHERE partidas.ID_usuario = usuario.ID_usuario) as partidas_jugadas,
    IFNULL(Puntos, 0) as total_puntos,
    IFNULL(dano_total, 0) as total_dano,
    IFNULL(headshots, 0) as total_headshots,
    IFNULL(partidas_ganadas, 0) as partidas_ganadas,
    IFNULL(partidas_perdidas, 0) as partidas_perdidas
    FROM usuario";

$result = $con->query($sql);
$estadisticas = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($estadisticas, JSON_NUMERIC_CHECK);
?>
