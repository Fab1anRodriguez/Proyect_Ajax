
<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();

    // $id_sala = $_GET['id_select_sala'];
    $sqlSalas = $con -> prepare("SELECT * FROM salas");
    $sqlSalas -> execute();
    $salas = $sqlSalas -> fetch()
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
                <div class='container-name-salas'>
                    <h4><?php echo $salas['nombre_sala']; ?></h4>
                    <div class="container-persons">
                        <h4><i class="bi bi-person-fill"></i>1/5</h4>
                    </div>
                    
                    <div class="container-button">
                        <a href="sala_espera.php?id_sala=<?php echo $salas['ID_sala'] ?>"><button>UNIRSE</button></a>
                    </div>
                </div>
                <!-- <div class='container-name-salas'>
                    <h4>Mapa 2</h4>
                    <div class="container-persons">
                        <h4><i class="bi bi-person-fill"></i>1/5</h4>
                    </div>
                    
                    <div class="container-button">
                        <button>UNIRSE</button>
                    </div>
                </div>
                <div class='container-name-salas'>
                    <h4>Mapa 3</h4>
                    <div class="container-persons">
                        <h4><i class="bi bi-person-fill"></i>5/5</h4>
                    </div>
                    
                    <div class="container-button">
                        <button>UNIRSE</button>
                    </div>
                </div>
                <div class='container-name-salas'>
                    <h4>Mapa 4</h4>
                    <div class="container-persons">
                        <h4><i class="bi bi-person-fill"></i>2/5</h4>
                    </div>

                    <div class="container-button">
                        <button>UNIRSE</button>
                    </div>
                </div>
                <div class='container-name-salas'>
                    <h4>Mapa 5</h4>
                    <div class="container-persons">
                        <h4><i class="bi bi-person-fill"></i>5/5</h4>
                    </div>
                    
                    <div class="container-button">
                        <button>UNIRSE</button>
                    </div>
                </div>  -->
            </div>
        </div>
    </main>
</body>
</html>