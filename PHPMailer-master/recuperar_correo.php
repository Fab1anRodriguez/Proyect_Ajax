<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .cajita {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .login h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login form {
            display: flex;
            flex-direction: column;
        }

        .login label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .login input[type="text"],
        .login input[type="password"],
        .login input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .login input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .login input[type="submit"]:hover {
            background-color: #218838;
        }

        .login p {
            margin-top: 10px;
            color: #555;
        }

        .login a {
            color: #007bff;
            text-decoration: none;
        }

        .login a:hover {
            text-decoration: underline;
        }

        .capa {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="cajita">
        <div class="login">
            <h1>RECUPERAR CONTRASEÑA</h1>
                <form action="config/env-correo.php" method="POST" autocomplete="off" >
                <label for="user">Correo Electronico</label>
                <input type="text" id="correo_recupera" name="correo">
                <input type="submit" id="submit" name="submit" class="btn-submit" value="Enviar Correo">
                <p>¿ Volver Al Menu... ?<br><a href="../index.html">¡Click Aqui!</a></p>
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
</body>
</html>