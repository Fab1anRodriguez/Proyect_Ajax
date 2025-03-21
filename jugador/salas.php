<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();

    if (isset($_GET['id_select_sala'])) {
        $id_select_sala = $_GET['id_select_sala'];
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
        <a href="inicio.php" class="back-button">
            <i class="bi bi-arrow-left-circle-fill"></i>
        </a>

        <div class="container-salas">
            <h3>Seleccione la sala</h3>
            <div class='container-div-salas'>
            </div>
        </div>
    </main>
</body>
<script>
    async function updateSalas() {
        try {
            const id_select_sala = <?php echo json_encode($id_select_sala); ?>;
            const response = await fetch(`../ajax/obtener_salas.php?id_select_sala=${id_select_sala}`);
            if (response.ok) {
                const salas = await response.json();
                let output = ''; 
                // Filtramos salas disponibles y no jugadas
                salas.filter(sala => sala.jugadores < 5 && sala.estado !== 'jugada').forEach(function(sala) {
                    output += `
                        <div class='container-name-salas'>
                            <h4>${sala.nombre_sala}</h4>
                            <div class="container-persons">
                                <h4><i class="bi bi-person-fill"></i>${sala.jugadores}/5</h4>
                            </div>
                            <div class="container-button">
                                <a href="sala_espera.php?id_sala=${sala.ID_sala}"><button>UNIRSE</button></a>
                            </div>
                        </div>
                    `;
                });
                document.querySelector('.container-div-salas').innerHTML = output;
            } else {
                console.error('Error en la respuesta del servidor');
            }
        } catch (error) {
            console.error('Error en la solicitud', error);
        }
    }
    
    setInterval(updateSalas, 1000);
</script>
</html>