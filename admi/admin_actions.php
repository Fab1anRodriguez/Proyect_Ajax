<?php
include '../conex/conex.php';
header('Content-Type: application/json');

$conexion = new database();
$con = $conexion->conectar();

// procesar solicitudes post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        // cambiar estado de usuario
        if ($data['action'] === 'cambiarEstado') {
            $stmt = $con->prepare("UPDATE usuario SET ID_estado = :estado WHERE ID_usuario = :id");
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_INT);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            
            $exito = $stmt->execute();
            echo json_encode([
                'success' => $exito,
                'error' => $exito ? null : 'no se pudo actualizar el estado'
            ]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
        exit;
    }
}

// obtener lista de usuarios
$sql = "SELECT
    ID_usuario AS ID,
    username AS Nombre,
    nivel AS Nivel,
    Puntos AS Puntos,
    ID_estado AS Estado
    FROM usuario";

$result = $con->query($sql);
$jugadores = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jugadores);


