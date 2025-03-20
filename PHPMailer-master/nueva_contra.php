<?php
require_once('../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

if (isset($_POST['submit'])) {
    $token = $_POST['token'];
    $contra_nueva = $_POST['contra_nueva'];
    $contra_nueva2 = $_POST['contra_nueva2'];

    if (empty($token) || empty($contra_nueva) || empty($contra_nueva2)) {
        echo "<script>alert('Todos los campos son obligatorios.')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
        exit();
    }

    if ($contra_nueva !== $contra_nueva2) {
        echo "<script>alert('Las contraseñas no coinciden.')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
        exit();
    }

    // Verificar el token
    $sql = $con->prepare("SELECT * FROM usuario WHERE token = ?");
    $sql->execute([$token]);
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Actualizar la contraseña
        $contra_nueva_hash = password_hash($contra_nueva, PASSWORD_BCRYPT);
        $sql = $con->prepare("UPDATE usuario SET password = ?, token = NULL WHERE ID_usuario = ?");
        $sql->execute([$contra_nueva_hash, $usuario['ID_usuario']]);

        echo "<script>alert('Contraseña actualizada correctamente.')</script>";
        echo "<script>window.location = '../index.php'</script>";
    } else {
        echo "<script>alert('Token inválido.')</script>";
        echo "<script>window.location = 'nueva_contra.php?token=$token'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/nueva_contra.css">
    <title>Cambiar Contraseña</title>
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
            <h1>CAMBIAR CONTRASEÑA</h1>
            <form action="actualizar_contra.php" method="POST" autocomplete="off">
                <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
                <label for="contra_nueva">Nueva Contraseña:</label>
                <input type="password" id="contra_nueva" name="contra_nueva" required>
                <br>
                <label for="contra_nueva2">Confirmar Nueva Contraseña:</label>
                <input type="password" id="contra_nueva2" name="contra_nueva2" required>
                <br>
                <button type="submit" name="submit">Cambiar Contraseña</button>
            </form>
        </div>
    </div>
    <div class="capa"></div>
    <script>
        function validarContrasenas() {
            const contra_nueva = document.getElementById('contra_nueva').value;
            const contra_nueva2 = document.getElementById('contra_nueva2').value;

            if (contra_nueva !== contra_nueva2) {
                alert('Las contraseñas no coinciden.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>