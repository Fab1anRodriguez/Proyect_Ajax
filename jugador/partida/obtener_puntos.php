<?php
require_once('../../conex/conex.php');

// inicializar conexion a la base de datos
$conex = new Database;
$con = $conex->conectar();

$usuario_id = $_GET['usuario_id'];

    // Obtener puntos del jugador
    $sql = $con->prepare("SELECT Puntos FROM usuario WHERE ID_usuario = ?");
    $sql->execute([$usuario_id]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($resultado); 