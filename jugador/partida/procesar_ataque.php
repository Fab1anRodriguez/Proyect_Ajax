<?php
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

// Obtener datos del ataque
$atacante_id = $_POST['atacante_id'];
$objetivo_id = $_POST['objetivo_id'];
$arma_id = $_POST['arma_id'];
$sala_id = $_POST['sala_id'];

// Obtener el daño del arma
$sql = $con->prepare("SELECT danio FROM armas WHERE ID_arma = ?");
$sql->execute([$arma_id]);
$arma = $sql->fetch(PDO::FETCH_ASSOC);
$danio = intval($arma['danio']);

// Actualizar vida del objetivo
$sql = $con->prepare("UPDATE usuario SET vida = vida - ? WHERE ID_usuario = ?");
$sql->execute([$danio, $objetivo_id]);

// Actualizar puntos temporales del atacante
$sql = $con->prepare("UPDATE usuario SET Puntos = Puntos + ? WHERE ID_usuario = ?");
$sql->execute([$danio, $atacante_id]);

// Actualizar daño total en estadísticas
$sql = $con->prepare("
    UPDATE estadisticas 
    SET dano_total = dano_total + ? 
    WHERE ID_usuario = ?
");
$sql->execute([$danio, $atacante_id]);

// Verificar si el jugador fue eliminado
$sql = $con->prepare("SELECT vida FROM usuario WHERE ID_usuario = ?");
$sql->execute([$objetivo_id]);
$vida = $sql->fetch(PDO::FETCH_ASSOC)['vida'];

// Actualizar estadísticas de la partida actual
$sql = $con->prepare("
    INSERT INTO estadisticas_partida (ID_usuario, ID_sala, puntos_partida, dano_partida, eliminaciones_partida)
    VALUES (?, ?, ?, ?, 0)
    ON DUPLICATE KEY UPDATE
    puntos_partida = puntos_partida + ?,
    dano_partida = dano_partida + ?
");
$sql->execute([$atacante_id, $sala_id, $danio, $danio, $danio, $danio]);

if ($vida <= 0) {
    // Actualizar eliminaciones_totales
    $sql = $con->prepare("
        UPDATE estadisticas 
        SET eliminaciones_totales = eliminaciones_totales + 1 
        WHERE ID_usuario = ?
    ");
    $sql->execute([$atacante_id]);
}

echo json_encode(['success' => true]); 