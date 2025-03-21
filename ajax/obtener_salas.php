<?php
session_start();
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

if (isset($_GET['id_select_sala'])) {
    $id_select_sala = $_GET['id_select_sala'];
    
    // Verificar si ya existe una sala disponible
    $sqlSalas = $con->prepare("SELECT * FROM salas 
                              WHERE ID_mapas = ? 
                              AND estado = 'disponible' 
                              AND jugadores < 5 
                              LIMIT 1");
    $sqlSalas->execute([$id_select_sala]);
    $salas = $sqlSalas->fetchAll(PDO::FETCH_ASSOC);

    // Crear nueva sala solo si no existe ninguna disponible y la última está en estado 'jugada'
    if (empty($salas)) {
        $sqlCheckJugada = $con->prepare("SELECT estado, nombre_sala FROM salas WHERE ID_mapas = ? ORDER BY ID_sala DESC LIMIT 1");
        $sqlCheckJugada->execute([$id_select_sala]);
        $ultimaSala = $sqlCheckJugada->fetch(PDO::FETCH_ASSOC);

        if ($ultimaSala && $ultimaSala['estado'] === 'jugada') {
            $sqlNuevaSala = $con->prepare("INSERT INTO salas (nombre_sala, jugadores, ID_mapas, tiempo, estado) 
                                          VALUES (?, 0, ?, 300, 'disponible')");
            $sqlNuevaSala->execute([$ultimaSala['nombre_sala'], $id_select_sala]);
            
            $sqlSalas = $con->prepare("SELECT * FROM salas WHERE ID_mapas = ? AND estado = 'disponible' LIMIT 1");
            $sqlSalas->execute([$id_select_sala]);
            $salas = $sqlSalas->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    echo json_encode($salas);
}
?>