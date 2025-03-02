<?php
    session_start();
    require_once('../conex/conex.php');
    include 'mapas.php';
    // include '../mapas/avatars.php';
    
    $conex = new Database;
    $con = $conex->conectar();
    // <?php echo $u['avatar'];
?>

<?php
    $id_usuario = $_SESSION['id_usuario'];
    $sql = $con -> prepare("SELECT * FROM usuario INNER JOIN roles ON usuario.ID_rol = roles.ID_rol
    INNER JOIN estado ON usuario.ID_estado = estado.ID_estado INNER JOIN avatar ON usuario.ID_avatar = avatar.ID_avatar WHERE usuario.ID_usuario = '$id_usuario'");
    $sql -> execute();
    $u = $sql -> fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles_jugador/inicio.css">
    <title>Document</title>
	<link rel="stylesheet" href="../styles/styles_jugador/inicio.css">
</head>
<body>
    <header class="container-header">
		<div class="container-info-user">
			<label for=" " class="user"><?php echo $u['username'] ?></label>
		</div>
        
		<div class='container-sesion'>
			<button>CERRAR SESIÓN</button>
		</div>
    </header>

	<main class='container-main'>
		<div class='container-content'>
			<div class='container-menu'>
				<a href="avatars.php"><i class="bi bi-bag-fill"></i> AVATAR</a>
				<!-- <button class='avatar' onclick='mostrarAvatars()'></button> -->
			</div>

			<div class='container-avatares'>
				<img src="../img/avatares/<?php echo $u['imagen'] ?>" alt="">
			</div>

			<div class='container-buttons'>
				<button class='button-mapas' onclick='mostrarMapas()'>SELECCIONAR MAPA: <p id="selected-map"><strong>...</strong></p></button>
				<a href="salas.php?id_select_sala=<?php echo $mapas['ID_mapas']; ?>"><button class='button-iniciar'>INICIAR</button></a>
			</div>
		</div>
	</main>
</body>
<script>
    function mostrarMapas() {
      const mapas = document.getElementById('mapas');

      	if (mapas.style.display === "none" || mapas.style.display === "") {
        	mapas.style.display = "block";
      	} 
		else {
        	mapas.style.display = "none";
      	}
    }
</script>
<script>
	function mostrarAvatars() {
		const avatar = document.getElementById('avatars');

      	if (avatar.style.display === "none" || avatar.style.display === "") {
      	  avatar.style.display = "block";
      	} 
		else {
      	  avatar.style.display = "none";
      	}
	}
</script>
</html>