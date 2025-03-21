<?php
session_start();
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$atacante_id = $_SESSION['id_usuario'];
$objetivo_id = $_POST['objetivo_id'];
$dano_base = intval($_POST['dano']);
$id_sala = $_POST['id_sala'];
$esHeadshot = $_POST['es_headshot'] == 1;

// Calcular el daño final
$dano = $esHeadshot ? $dano_base * 2 : $dano_base;

// Obtener y actualizar vida del objetivo
$sql = $con->prepare("SELECT vida FROM usuario WHERE ID_usuario = ?");
$sql->execute([$objetivo_id]);
$vida_actual = $sql->fetch(PDO::FETCH_ASSOC)['vida'];
$vida_restante = max(0, $vida_actual - $dano);

// Solo actualizar vida en la tabla usuario
$sql = $con->prepare("UPDATE usuario SET vida = ? WHERE ID_usuario = ?");
$sql->execute([$vida_restante, $objetivo_id]);

// Actualizar estadísticas en la tabla partidas
$sql = $con->prepare("UPDATE partidas 
                     SET puntos_partida = puntos_partida + ?,
                         dano_total = dano_total + ?,
                         headshots = CASE 
                            WHEN ? = 1 THEN headshots + 1
                            ELSE headshots
                         END
                     WHERE ID_sala = ? AND ID_usuario = ?");
$sql->execute([
    $dano,        // puntos igual al daño
    $dano,        // daño total
    $esHeadshot,
    $id_sala,
    $atacante_id
]);

// Devolver respuesta
echo json_encode([
    'success' => true,
    'dano_causado' => $dano,
    'vida_restante' => $vida_restante,
    'puntos_ganados' => $dano,
    'esHeadshot' => $esHeadshot
]);
?>