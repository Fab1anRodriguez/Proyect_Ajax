<?php
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

// Para pruebas, mostraremos todas las armas disponibles
$sql = $con->prepare("SELECT ID_arma, imagen_armas, danio FROM armas");
$sql->execute();
$armas = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach($armas as $arma) {//hacemos un foreach para recorrer las armas y mostrarlas 
    $ruta_imagen = "../../img/armas/" . $arma['imagen_armas'];
    echo "<div class='arma-opcion' onclick='atacar({$arma['ID_arma']})'>";
    echo "<img src='{$ruta_imagen}' alt='Arma' style='width:50px;height:50px;'>";
    echo "<p>Daño: {$arma['danio']}</p>";
    echo "</div>";
} 