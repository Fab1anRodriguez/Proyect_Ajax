<?php
    session_start();
    require_once('../conex/conex.php');
	// include 'mapas.php';
    $conex = new Database;
    $con = $conex->conectar();
?>

<?php
    if (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
        $sql = $con -> prepare("SELECT * FROM usuario INNER JOIN roles ON usuario.ID_rol = roles.ID_rol
        INNER JOIN estado ON usuario.ID_estado = estado.ID_estado INNER JOIN avatar ON usuario.ID_avatar = avatar.ID_avatar WHERE usuario.ID_usuario = '$id_usuario'");
        $sql -> execute();
        $u = $sql -> fetch();
    }
    else {
        echo '<script>alert("Debes iniciar sesión para acceder a esta página")</script>';
        echo '<script>window.location = "../index.html"</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/inicio.css">
    <title>Document</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div id="userStatsModal" class="modal">
        <div class="modal-content">
            <span class="close"><i class="bi bi-x-circle-fill"></i></span>
            <h2>Estadísticas de <?php echo $u['username'] ?></h2>
        <div id="userStatsContent"></div>
        
        </div>
    </div>

    <header class="container-header">
		<div class="container-info-user">
			<label for="user" class="user" id="user"><?php echo $u['username']; ?><br><?php echo "Puntos: " . $u['Puntos'] . " - Nivel: " . $u['nivel']; ?></label>
		</div>
        
		<div class='container-sesion'>
            <form action="../include/logout.php" method="POST">
                <button type="submit">CERRAR SESIÓN</button>
            </form>
        </div>
    </header>

	<main class='container-main'>
		<div class='container-content'>
			<div class='container-menu'>
				<a href="avatars.php"><i class="bi bi-bag-fill"></i> AVATAR</a>
			</div>

			<div class='container-avatares'>
				<img src="../img/avatares/<?php echo $u['imagen'] ?>" alt="">
			</div>

			<div class='container-select'>
                <button id="btnSeleccionarMapa">SELECCIONAR MAPA</button>
                <div id="mapasModal" class="modal">
                    <div class="modal-content">
                        <span class="close"><i class="bi bi-x-circle-fill"></i></span>
                        <div class='container-columns-mapas'>
                        <?php
                            $sqlMapas = $con->prepare("SELECT * FROM mapas");
                            $sqlMapas->execute();
                            
                            while($mapa = $sqlMapas -> fetch(PDO::FETCH_ASSOC)){
                                $nivel_requerido = $mapa['nivel_requerido'];
                                if ($u['nivel'] < $nivel_requerido) {
                                    echo "<div class='container-img-mapas'>" .
                                            "<img src='../img/mapas/" . $mapa['imagen_mapas'] . "' alt='" . $mapa['mapas'] . "' class='mapa-bloqueado'>" .
                                            "<div class='container-name-mapas' id='container-name-mapas'>
                                                <p>" . $mapa['mapas'] . "</p>
                                                <p>Nivel requerido: " . $nivel_requerido . "</p>
                                            </div>" .
                                        "</div>";
                                } 
                                
                                else {
                                    echo "<div class='container-img-mapas'>" .
                                            "<img src='../img/mapas/" . $mapa['imagen_mapas'] . "' alt='" . $mapa['mapas'] . "' data-id='" . $mapa['ID_mapas'] . "' class='mapa-select'>" .
                                            "<div class='container-name-mapas' id='container-name-mapas'>
                                                <p>" . $mapa['mapas'] . "</p>
                                            </div>" .
                                        "</div>";
                                }
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-button">
                <button id="iniciarJuego" disabled>INICIAR</button>
            </div>
			
		</div>
	</main>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        let selectedMapaId = null;

        $('#btnSeleccionarMapa').click(function(){
            $('#mapasModal').show();
        });

        $('.close').click(function(){
            $('#mapasModal').hide();
        });

        $('.mapa-select').click(function(){
            selectedMapaId = $(this).data('id');
            $('#iniciarJuego').prop('disabled', false); // Habilitar el botón de iniciar
            $('#mapasModal').hide(); // Ocultar el modal de mapas
        });

        $('#iniciarJuego').click(function(){
            if (selectedMapaId) {
                window.location.href = 'salas.php?id_select_sala=' + selectedMapaId;
            }
        });

        $('#user').click(function(){
            mostrarEstadisticas();
        });
    });

    function mostrarEstadisticas(){
        $.ajax({
            type: "GET",
            url: "../ajax/estadisticas.php",
            data: { 'id_usuario': <?php echo $id_usuario; ?> },
            success: function(r){
                $('#userStatsContent').html(r);
                $('#userStatsModal').show();
            }
        });
    }

    $('.close').click(function(){
        $('#userStatsModal').hide();
    });
</script>

</html>