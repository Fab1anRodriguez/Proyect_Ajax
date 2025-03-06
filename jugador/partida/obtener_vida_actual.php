<?php
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$usuario_id = $_GET['usuario_id'];

$query = "SELECT vida FROM usuario WHERE ID_usuario = ?";
$stmt = $con->prepare($query);
$stmt->execute([$usuario_id]);
$vida = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($vida); 