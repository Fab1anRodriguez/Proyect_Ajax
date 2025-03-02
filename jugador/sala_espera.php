<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();

    if (isset($_GET['id_sala'])) {
        $id_select_sala = addslashes($_GET['id_sala']);
        $sqlSelectSalas = $con -> prepare("SELECT * FROM partidas INNER JOIN usuario ON partidas.ID_usuario = usuario.ID_usuario
        INNER JOIN salas ON partidas.ID_sala = salas.ID_sala WHERE ID_partida = '$id_select_sala'");
        $sqlSelectSalas -> execute();
        $select_salas = $sqlSelectSalas -> fetch();
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
            <h3><?php echo $select_salas['nombre_sala'] ?></h3>
            <div class='container-div-salas'>  
                <div class='container-name-salas'>
                    <h4><?php echo $select_salas['username'] ?></h4>
                </div> 
            </div>
        </div>
    </main>
</body>
</html>