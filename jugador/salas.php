<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();

    if (isset($_GET['id_select_sala'])) {
        $id_selectsala = $_GET['id_select_sala'];
        $sqlSalas = $con->prepare("SELECT * FROM salas WHERE ID_mapas = :id_selectsala");
        $sqlSalas->bindParam(':id_selectsala', $id_selectsala, PDO::PARAM_INT);
        $sqlSalas->execute();
        $salas = $sqlSalas->fetchAll(PDO::FETCH_ASSOC);

        // Recorrer cada sala y crear una nueva si hay más de 5 jugadores
        foreach ($salas as $sala) {
            if ($sala['jugadores'] >= 5) {
                $name_sala = $sala['nombre_sala'];
                $jugadores = 0;
                $level_sala = $sala['nivel_requerido'];
                $ID_mapas = $sala['ID_mapas'];

                $sqlNewSala = $con->prepare("INSERT INTO salas (nombre_sala, jugadores, nivel_requerido, ID_mapas) VALUES (:name_sala, :jugadores, :level_sala, :ID_mapas)");
                $sqlNewSala->bindParam(':name_sala', $name_sala, PDO::PARAM_STR);
                $sqlNewSala->bindParam(':jugadores', $jugadores, PDO::PARAM_INT);
                $sqlNewSala->bindParam(':level_sala', $level_sala, PDO::PARAM_INT);
                $sqlNewSala->bindParam(':ID_mapas', $ID_mapas, PDO::PARAM_INT);
                $sqlNewSala->execute();
            }
        }

        // Volver a mostrar las salas
        $sqlSalas->execute();
        $salas = $sqlSalas->fetchAll(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/salas.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <main class="container-main">
        <div class="container-salas">
            <h3>Seleccione la sala</h3>
            <div class='container-div-salas'>  
                <?php foreach ($salas as $sala): ?>
                    <div class='container-name-salas'>
                        <h4><?php echo $sala['nombre_sala']; ?></h4>
                        <div class="container-persons">
                            <h4><i class="bi bi-person-fill"></i><?php echo $sala['jugadores']; ?>/5</h4>
                        </div>
                        
                        <div class="container-button">
                            <?php if ($sala['jugadores'] < 5): ?>
                                <a href="sala_espera.php?id_sala=<?php echo $sala['ID_sala'] ?>"><button>UNIRSE</button></a>
                            <?php else: ?>
                                <button disabled class='button-disabled'>LLENO</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>