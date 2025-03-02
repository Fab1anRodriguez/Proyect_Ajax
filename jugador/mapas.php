<?php

// session_start();
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/mapas.css">
</head>
<body>
    <main class="container-main-mapas" id="mapas">
        <div class="container-mapas">
            <h3>Seleccione el mapa</h3>
            <?php
                $sqlmapas = $con->prepare("SELECT * FROM mapas");
                $sqlmapas->execute();
                $m = $sqlmapas->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class='container-img-column'>  
                <?php foreach ($m as $mapas){ ?>
                    <div class='container-img' id='container-img'>
                        <img src="../img/mapas/<?php echo $mapas['imagen_mapas']; ?>" alt="">
                        <div class='container-name'>
                            <p><?php echo $mapas['mapas']; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div>
                <button class='select' id='select-button'>SELECCIONAR</button>
                <p id="selected-map">Mapa seleccionado: <strong>...</strong></p>
            </div>
        </div>
    </main>
</body>
<script>
    const container = document.querySelectorAll(".container-img");
    const select = document.getElementById("select-button");
    const selectedMapText = document.getElementById("selected-map");
    let mapapre = "..."

    container.forEach(map => {
        map.addEventListener("click", function () {

            container.forEach(m => m.classList.remove('select'));

            this.classList.add("selected");

            mapapre = this.innerText;
                
        });
    });
        
    select.addEventListener("click", function () {
        alert("Mapa seleccionado: " + mapapre);
        selectedMapText.innerHTML = `Mapa seleccionado: <strong>${mapapre}</strong>`;
    });

</script>
</html>