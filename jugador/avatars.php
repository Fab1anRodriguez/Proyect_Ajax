<?php
    session_start();
    require_once('../conex/conex.php');
    $conex = new Database;
    $con = $conex->conectar();
?>

<?php
    if (isset($_GET['seleccionar'])) {
        $id_usuario = $_SESSION['id_usuario'];
        $id_avatar = $_GET['id_avatar'];
        $sqlUpdateAvatar = $con -> prepare("UPDATE usuario SET ID_avatar = '$id_avatar' WHERE ID_usuario = '$id_usuario'");
        $sqlUpdateAvatar -> execute();
        $UpdateAvatar = $sqlUpdateAvatar -> fetch();
    }
?>

<?php
    $sqlavatars = $con->prepare("SELECT * FROM avatar");
    $sqlavatars->execute();
    $a = $sqlavatars->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/avatars.css">
</head>
<body>
    <main class="container-main-avatars" id="avatars">
        <div class="container-div-avatars">
            <h3>Seleccione el Avatar</h3>
            <div class='container-columns-avatars'>
                <?php
                    $sqlavatars = $con->prepare("SELECT * FROM avatar");
                    $sqlavatars->execute();

                    while($avatars = $sqlavatars -> fetch(PDO::FETCH_ASSOC)){
                        echo "<div class='container-img-avatars'>" .
                            "<img src='../img/avatares/" . $avatars["imagen"] . "' alt=''>" . 
                            "<div class='container-name-avatars'>" .
                                "<input type='submit' name='id_img' id='id_img' value='" . $avatars['ID_avatar'] . "'>" .
                                "<p>" . $avatars['avatar'] . "</p>" .
                            "</div>" .
                            "</div>";
                    }
                ?>
            </div>
            <div class='container-select-avatars' name='container-select-avatars' id='container-select-avatars'>

            </div>
        </div>
    </main>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		$('#id_img').val(0);
		recargarLista4();

		$('#id_img').change(function(){
			recargarLista4();
		});
	})

	function recargarLista4(){
		$.ajax({
			type:"POST",
			url:"../ajax/avatar_select.php",
			data:"id_img=" + $('#id_img').val(),
			success:function(r){
				$('#container-select-avatars').html(r);
			}
		});
	}
</script>
</html>