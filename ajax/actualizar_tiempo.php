<?php
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$id_sala = $_POST['id_sala'];

// obtener el tiempo actual
$sql = $con->prepare("SELECT tiempo FROM salas WHERE ID_sala = ?");
$sql->execute([$id_sala]);
$sala = $sql->fetch(PDO::FETCH_ASSOC);

if ($sala) {
    $tiempo = $sala['tiempo'];

    // restar 1 segundo
    if ($tiempo > 0) {
        $tiempo;
        $sql = $con->prepare("UPDATE salas SET tiempo = ? WHERE ID_sala = ?");
        $sql->execute([$tiempo, $id_sala]);
    }

    echo json_encode(['tiempo' => $tiempo]);
} else {
    echo json_encode(['error' => 'Sala no encontrada']);
}
?>