<?php
include '../conex/conex.php';
require '../PHPMailer-master/config/env-correo.php'; // incluir la funcion de envio de correo

header('Content-Type: application/json');

$conexion = new database();
$con = $conexion->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        // cambiar estado de usuario
        if ($data['action'] === 'cambiarEstado') {
            $stmt = $con->prepare("UPDATE usuario SET ID_estado = ?, Ultimo_ingreso = NOW() WHERE ID_usuario = ?");
            $exito = $stmt->execute([$data['estado'], $data['id']]);

            if ($exito) {
                if ($data['estado'] == 1) { // estado activo
                    // obtener email y username del usuario
                    $stmt = $con->prepare("SELECT email, username FROM usuario WHERE ID_usuario = ?");
                    $stmt->execute([$data['id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        if (!sendActivationEmail($user['email'], $user['username'])) {
                            error_log("error al enviar correo de activacion a {$user['email']}");
                        }
                    }
                }

                echo json_encode([
                    'success' => true,
                    'error' => null
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'no se pudo actualizar el estado'
                ]);
            }
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'error: ' . $e->getMessage()]);
        exit;
    }
}
?>


