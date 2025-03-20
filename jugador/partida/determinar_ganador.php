<?php
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
//aqui se obtiene el jugador con mas vida para determina su victoria
$sql->execute([$id_sala]);
$ganador = $sql->fetch(PDO::FETCH_ASSOC);

if ($ganador) {
    // incrementar los puntos del ganador
    $sqlUpdate = $con->prepare("UPDATE usuario SET Puntos = Puntos + 100 WHERE ID_usuario = ?");
    $sqlUpdate->execute([$ganador['ID_usuario']]);

    // actualizar estadísticas de todos los jugadores en la tabla usuario
    $sql = $con->prepare("SELECT ID_usuario, Puntos, headshots, dano_total FROM partidas WHERE ID_sala = ?");
    $sql->execute([$id_sala]);
    $jugadores = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($jugadores as $jugador) {
        $sqlUpdate = $con->prepare("UPDATE usuario 
                                    SET Puntos = Puntos + ?, 
                                        headshots = headshots + ?, 
                                        dano_total = dano_total + ? 
                                    WHERE ID_usuario = ?");
        $sqlUpdate->execute([$jugador['Puntos'], $jugador['headshots'], $jugador['dano_total'], $jugador['ID_usuario']]);
    }

    echo json_encode(['success' => true, 'ganador' => $ganador]);
} else {
    echo json_encode(['success' => false]);
}
?>