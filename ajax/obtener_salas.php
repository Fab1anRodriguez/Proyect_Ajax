<?php
session_start();
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

if (isset($_GET['id_select_sala'])) {
    $id_select_sala = $_GET['id_select_sala'];
    $sqlSalas = $con->prepare("SELECT * FROM salas WHERE ID_mapas = ?");
    $sqlSalas->execute([$id_select_sala]);
    $salas = $sqlSalas->fetchAll(PDO::FETCH_ASSOC);

    // verificar si todas las salas estan llenas
    $todasLlenas = true;
    foreach ($salas as $sala) {
        //verificamos si quedan espacios vacios menos de 3 en la sala
        if ($sala['jugadores'] < 3) {
            // si encuentra una sala con espacio vacio mara la variable como false y sale del bucle
            $todasLlenas = false;
            break;
        }
    }

    // crear una nueva sala si todas estan llenas
    if ($todasLlenas) {// si es true pasa a crear una nueva sala
        $nuevoNombreSala = "Sala " . (count($salas) + 1);
        $sqlNuevaSala = $con->prepare("INSERT INTO salas (nombre_sala, jugadores, ID_mapas, tiempo) VALUES (?, 0, ?, 300)");
        $sqlNuevaSala->execute([$nuevoNombreSala, $id_select_sala]);

        $sqlSalas->execute([$id_select_sala]);
        $salas = $sqlSalas->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($salas);
}
?>