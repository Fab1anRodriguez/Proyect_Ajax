<?php
//este archivo se encarga de determinar el ganador de la partida por el mayor puntaje de vida
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$id_sala = $_POST['sala_id'];

// obtener el jugador con más vida
$sql = $con->prepare("SELECT usuario.ID_usuario, usuario.username, usuario.vida 
                      FROM usuario 
                      INNER JOIN partidas ON usuario.ID_usuario = partidas.ID_usuario 
                      WHERE partidas.ID_sala = ? 
                      ORDER BY usuario.vida DESC 
                      LIMIT 1");

$sql->execute([$id_sala]);
$ganador = $sql->fetch(PDO::FETCH_ASSOC);

if ($ganador) {
    echo json_encode(['success' => true, 'ganador' => $ganador]);
} else {
    echo json_encode(['success' => false]);
}
?>