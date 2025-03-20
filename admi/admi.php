<?php
require_once '../conex/conex.php';
$conexion = new database();
$con = $conexion->conectar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    
    <!-- archivos css necesarios -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* estilo para el fondo y texto */
        body {
            background: linear-gradient(135deg, #1e1e2f, #3a3a5a);
            color: white;
            font-family: Arial, sans-serif;
        }
        /* estilo para el contenedor principal */
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        /* estilos para las pestañas */
        .nav-tabs .nav-link {
            color: white;
            font-weight: bold;
        }
        .nav-tabs .nav-link.active {
            background-color: #4a4a7a;
            border-color: #4a4a7a;
        }
        /* estilo para las tablas */
        .table-dark {
            border-radius: 10px;
            overflow: hidden;
        }
        /* efecto hover para botones */
        .btn {
            transition: all 0.3s;
        }
        .btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Panel de Administración</h1>
        
        <!-- menu de pestañas -->
        <ul class="nav nav-tabs" id="adminTabs">
            <li class="nav-item">
                <a class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" href="#usuarios">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="reporte-tab" data-bs-toggle="tab" href="#reporte">Reporte de Juegos</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- tabla de usuarios -->
            <div class="tab-pane fade show active" id="usuarios">
                <table class="table table-dark table-hover text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nivel</th>
                            <th>Puntos</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="usuariosBody">
                        <?php
                        // obtener lista de usuarios
                        $sql = "SELECT ID_usuario, username, nivel, Puntos, ID_estado FROM usuario";
                        $result = $con->query($sql);
                        
                        // mostrar cada usuario en la tabla
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $estado = ($row['ID_estado'] == 1) ? 'Desbloqueado' : 'Bloqueado';
                            $btnClass = ($row['ID_estado'] == 1) ? 'btn-danger' : 'btn-success';
                            $btnText = ($row['ID_estado'] == 1) ? 'Bloquear' : 'Desbloquear';
                            
                            echo "<tr>
                                    <td>{$row['ID_usuario']}</td>
                                    <td>{$row['username']}</td>
                                    <td>{$row['nivel']}</td>
                                    <td>{$row['Puntos']}</td>
                                    <td id='estado-{$row['ID_usuario']}'>{$estado}</td>
                                    <td><button class='btn {$btnClass}' id='btn-{$row['ID_usuario']}' 
                                        onclick='cambiarEstado({$row['ID_usuario']})'>{$btnText}</button></td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- tabla de reportes -->
            <div class="tab-pane fade" id="reporte">
                <table class="table table-dark table-hover text-center">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Partidas Jugadas</th>
                            <th>Total Puntos</th>
                            <th>Total Daño</th>
                            <th>Headshots</th>
                            <th>Ganadas</th>
                            <th>Perdidas</th>
                        </tr>
                    </thead>
                    <tbody id="reporteBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // cargar reporte cuando se hace clic en la pestaña
        document.addEventListener("DOMContentLoaded", function() {
            let reporteTab = document.getElementById("reporte-tab");
            if (reporteTab) {
                reporteTab.addEventListener("click", cargarReporte);
            }
        });

        // funcion para cargar datos del reporte
        function cargarReporte() {
            fetch("admin_reporte.php")
                .then(response => response.json())
                .then(data => {
                    let reporteBody = document.getElementById("reporteBody");
                    if (reporteBody) {
                        reporteBody.innerHTML = "";
                        data.forEach(jugador => {
                            reporteBody.innerHTML += `
                                <tr>
                                    <td>${jugador.username}</td>
                                    <td>${jugador.partidas_jugadas}</td>
                                    <td>${jugador.total_puntos}</td>
                                    <td>${jugador.total_dano}</td>
                                    <td>${jugador.total_headshots}</td>
                                    <td>${jugador.partidas_ganadas}</td>
                                    <td>${jugador.partidas_perdidas}</td>
                                </tr>`;
                        });
                    }
                });
        }

        // funcion para cambiar estado de usuario
        function cambiarEstado(id) {
            let estado = document.getElementById(`estado-${id}`).innerText === 'Desbloqueado' ? 2 : 1;
            
            fetch('admin_actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'cambiarEstado',
                    id: id,
                    estado: estado
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    actualizarInterfazUsuario(id, estado);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // funcion para actualizar la interfaz despues de cambiar estado
        function actualizarInterfazUsuario(id, estado) {
            let estadoText = estado === 1 ? 'Desbloqueado' : 'Bloqueado';
            let btnClass = estado === 1 ? 'btn-danger' : 'btn-success';
            let btnText = estado === 1 ? 'Bloquear' : 'Desbloquear';
            
            document.getElementById(`estado-${id}`).innerText = estadoText;
            let btn = document.getElementById(`btn-${id}`);
            btn.className = `btn ${btnClass}`;
            btn.innerText = btnText;
        }

        // funcion para cargar usuarios
        function cargarUsuarios() {
            fetch('admin_actions.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        let usuariosBody = document.getElementById('usuariosBody');
                        usuariosBody.innerHTML = '';
                        data.forEach(jugador => {
                            let estadoText = jugador.Estado === 1 ? 'Desbloqueado' : 'Bloqueado';
                            let btnClass = jugador.Estado === 1 ? 'btn-danger' : 'btn-success';
                            let btnText = jugador.Estado === 1 ? 'Bloquear' : 'Desbloquear';
                            usuariosBody.innerHTML += `
                                <tr>
                                    <td>${jugador.ID}</td>
                                    <td>${jugador.Nombre}</td>
                                    <td>${jugador.Nivel}</td>
                                    <td>${jugador.Puntos}</td>
                                    <td id="estado-${jugador.ID}">${estadoText}</td>
                                    <td><button id="btn-${jugador.ID}" class="btn ${btnClass}" onclick="cambiarEstado(${jugador.ID})">${btnText}</button></td>
                                </tr>`;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Llama a la función para cargar los usuarios al cargar la página
        document.addEventListener('DOMContentLoaded', cargarUsuarios);
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
