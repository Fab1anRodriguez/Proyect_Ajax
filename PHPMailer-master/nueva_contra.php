<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cs/index.css">
    <title>Recuperar</title>
</head>
<body>
    <div class="cajita">
        <div class="login">
            <h1>CAMBIAR CONTRASEÑA</h1>
                <form action="actualizar_contra.php" method="POST" autocomplete="off" >
                <label for="user">Ingresa La Nueva Contraseña</label>
                <input type="password" id="contra_nueva" name="contra_nueva">
                <label for="user">Confirmar Contraseña</label>
                <input type="password" id="contra_nueva2" name="contra_nueva2">
                <input type="submit" id="submit" name="submit" class="btn-submit" value="Cambiar Contraseña">
            </form>
        </div>
    </div>
    <video autoplay loop muted>
        <source src="video/video1.mp4" type="video/mp4">
    </video>
    <div class="capa"></div>
</body>
</html>