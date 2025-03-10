
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles_jugador/recuperar.css">
    <title>Recuperar</title>
</head>
<body>
    <div class="cajita">
        <div class="login">
            <h1>RECUPERAR CONTRASEÑA</h1>
                <form action="config/env-correo.php" method="POST" autocomplete="off" >
                <label for="user">Correo Electronico</label>
                <input type="text" id="correo_recupera" name="correo">
                <input type="submit" id="submit" name="submit" class="btn-submit" value="Enviar Correo">
                <p>¿ Volver Al Menu... ?<br><a href="../login.html">¡Click Aqui!</a></p>
            </form>
        </div>
    </div>
     <?php
         if (isset($_GET['message'])) {
             $message = $_GET['message'];
             if ($message == 'ok') {
                 echo "<script>alert('El correo fue enviado correctamente.');</script>";
             } elseif ($message == 'error') {
                 echo "<script>alert('Hubo un error al enviar el correo.');</script>";
             }
        }
     ?>
    <video autoplay loop muted>
        <source src="video/video3.mp4" type="video/mp4">
    </video>
    <div class="capa"></div>
</body>
</html>