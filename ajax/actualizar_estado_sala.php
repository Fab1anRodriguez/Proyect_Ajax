<?php
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

if (isset($_GET['id_sala']) && isset($_GET['estado'])) {
    $id_sala = $_GET['id_sala'];
    $estado = $_GET['estado'];

    // Actualizar estado de la sala
    $sqlUpdate = $con->prepare("UPDATE salas SET estado = ? WHERE ID_sala = ?");
    $success = $sqlUpdate->execute([$estado, $id_sala]);

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Parametros incompletos']);
}
?>