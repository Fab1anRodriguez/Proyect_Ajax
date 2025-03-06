<?php
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$sala_id = $_GET['sala_id'];

$query = "SELECT u.ID_usuario, u.vida 
          FROM usuario u 
          INNER JOIN partidas p ON u.ID_usuario = p.ID_usuario 
          WHERE p.ID_sala = ?";

$stmt = $con->prepare($query);
$stmt->execute([$sala_id]);
$vidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($vidas); 