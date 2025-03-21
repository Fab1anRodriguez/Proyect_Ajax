<?php
session_start();
require_once('../../conex/conex.php');
$conex = new Database;
$con = $conex->conectar();

$ruta_avatares = "../../img/avatares/";

$id_usuario = $_SESSION['id_usuario'];
$id_sala = $_GET['id_sala'];

$sql = $con->prepare("SELECT usuario.ID_usuario, usuario.username, usuario.vida, avatar.imagen 
                      FROM usuario 
                      INNER JOIN partidas ON usuario.ID_usuario = partidas.ID_usuario 
                      INNER JOIN avatar ON usuario.ID_avatar = avatar.ID_avatar 
                      WHERE partidas.ID_sala = ?");
$sql->execute([$id_sala]);
$jugadores = $sql->fetchAll(PDO::FETCH_ASSOC);

//hacemos inner join con la tabla partida para mostrar los puntos que lleva nuestro jugador en la partida
$sql = $con->prepare("SELECT username, vida, Puntos, avatar.imagen
                      FROM usuario 
                      INNER JOIN avatar ON usuario.ID_avatar = avatar.ID_avatar
                      WHERE ID_usuario = ?");
$sql->execute([$id_usuario]);
$jugadorActual = $sql->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partida en Curso</title>
    <link rel="stylesheet" href="../../styles/styles_jugador/partida.css">
    <link rel="stylesheet" href="../../styles/styles_jugador/armas.css">
</head>
<body>
    <div class="container">
    <header>
        <h1>Partida en Curso</h1>
        <div id="container-contador">2:00</div>
    </header>

    <main>
        <!-- mostrar jugador actual -->
        <div class="jugador-actual">
            <h2>Username: <?php echo $jugadorActual['username']; ?></h2>
            <div class="stats">
                <img src="<?php echo $ruta_avatares . $jugadorActual['imagen']; ?>" alt="Avatar" style="width: 150px; height: 220px;">
                <div class="vida-barra">
                    <div class="vida-actual" style="width: <?php echo ($jugadorActual['vida']/100)*100; ?>%">
                        <?php echo $jugadorActual['vida']; ?>/100
                    </div>
                </div>
            </div>
        </div>

        <!-- grid de jugadores -->
        <div class="jugadores-grid">
            <?php foreach($jugadores as $jugador):
                     if($jugador['ID_usuario'] != $id_usuario): ?>
                    <div class="jugador-card" data-id="<?php echo $jugador['ID_usuario']; ?>">
                        <img src="<?php echo $ruta_avatares . $jugador['imagen']; ?>" alt="Avatar">
                        <h3><?php echo $jugador['username']; ?></h3>
                        <div class="vida-barra">
                            <div class="vida-actual" style="width: <?php echo ($jugador['vida']/100)*100; ?>%">
                                <?php echo $jugador['vida']; ?>/100
                            </div>
                        </div>
                        <button onclick="seleccionarObjetivo(<?php echo $jugador['ID_usuario']; ?>)" 
                                class="btn-atacar">Atacar</button>
                    </div>
                    <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        <button onclick="abandonarPartida()" class="btn-abandonar">Abandonar Partida</button>
    </footer>
    </div>

    <!-- Modal para seleccionar arma -->
    <div id="modal-armas" class="modal">
        <div class="modal-content">
            <h2>Selecciona un arma</h2>
            <div id="lista-armas" class="lista-armas"></div>
        </div>
    </div>

    <div id="modal-estadisticas" class="modal">
        <div class="modal-content">
            <h2>Resumen de partida</h2>
            <div class="estadisticas">
                <p id="puntos-totales">Puntos totales: 0</p>
                <p id="eliminaciones">Eliminaciones: 0</p>
                <p id="daño-total">Daño total: 0</p>
            </div>
            <button onclick="location.href='../inicio.php'" class="btn-salir">Volver al lobby</button>
        </div>
    </div>

    <!-- Agregar modal para mostrar ganador -->
    <div id="modal-ganador" class="modal">
        <div class="modal-content">
            <h2>¡Fin de la partida!</h2>
            <div class="ganador-info">
                <h3>¡El ganador es:</h3>
                <p id="nombre-ganador"></p>
            </div>
            <button onclick="location.href='../inicio.php'" class="btn-salir">Volver al lobby</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/armas.js"></script>
    <script>
        // variables principales del juego
        const USUARIO_ACTUAL_ID = <?php echo $id_usuario; ?>;
        const SALA_ACTUAL_ID = <?php echo $id_sala; ?>;
        
        // variables para controlar el estado del juego
        let objetivoSeleccionado = null; // guarda el id del jugador que vamos a atacar
        let armaActual = null; // guarda el arma seleccionada
        let partidaTerminada = false; // nos dice si la partida ya termino
        let estadisticasActualizadas = false; // nos dice si ya guardamos los resultados

        $(document).ready(function() {
            startTimer();
            window.intervalJugadorActual = setInterval(actualizarJugadorActual, 2000);
            window.intervalOtrosJugadores = setInterval(actualizarOtrosJugadores, 2000);
            window.intervalPuntos = setInterval(actualizarPuntos, 2000);
                    });

        // inicia el temporizador de la partida
        function startTimer() {
            const contadorElement = document.getElementById('container-contador');
            const intervalo = setInterval(function() {
                $.ajax({
                    url: '../../ajax/actualizar_tiempo.php',
                    method: 'POST',
                    data: { id_sala: SALA_ACTUAL_ID },
                    success: function(response) {
                        const datos = JSON.parse(response);
                        
                        // convierte el tiempo total en minutos y segundos
                        const minutos = Math.floor(datos.tiempo / 60);
                        const segundos = datos.tiempo % 60;
                        contadorElement.innerText = `${minutos}:${segundos < 10 ? '0' : ''}${segundos}`;

                        // si el tiempo llega a 0, termina la partida
                        if (datos.tiempo <= 0 && !partidaTerminada) {
                            finalizarPartida();
                        }
                    },
                    error: function() {
                        clearInterval(intervalo);
                    }
                });
            }, 1000);
        }

        // detiene la partida y muestra el ganador
        function finalizarPartida() {
            partidaTerminada = true;
            
            // detiene todas las actualizaciones automaticas
            clearInterval(window.intervalJugadorActual);
            clearInterval(window.intervalOtrosJugadores);
            clearInterval(window.intervalPuntos);
            
            // busca quien gano la partida
            determinarGanador();
            
            // vuelve al lobby despues de 5 segundos
            setTimeout(() => {
                window.location.href = '../inicio.php';
            }, 5000);
        }

        // muestra el modal con el nombre del ganador
        function mostrarGanador(ganador) {
            $('#modal-ganador').show();
            $('#nombre-ganador').text(ganador.username);
        }

        // obtiene el jugador con mas vida y lo declara ganador
        function determinarGanador() {
            $.ajax({
                url: 'determinar_ganador.php',
                method: 'POST',
                data: { sala_id: SALA_ACTUAL_ID },
                success: function(response) {
                    const datos = JSON.parse(response);
                    if (datos.success) {
                        mostrarGanador(datos.ganador);
                        if (!estadisticasActualizadas) {
                            evaluarPorUltimoJugador(datos.ganador);
                        }
                    }
                }
            });
        }

        // actualiza la informacion de todos los jugadores excepto el actual
        function actualizarOtrosJugadores() {
            if (estadisticasActualizadas) return;
            
            $.ajax({
                // obtiene la vida del jugador actual
                url: 'obtener_vida_actual.php',
                method: 'GET',
                data: { usuario_id: USUARIO_ACTUAL_ID },
                success: function(currentResponse) {
                    const datosActual = JSON.parse(currentResponse);
                    $.ajax({
                        // obtiene la vida de todos los jugadores
                        url: 'obtener_vidas.php',
                        data: { sala_id: SALA_ACTUAL_ID },
                        method: 'GET',
                        success: function(r) {
                            const vidas = JSON.parse(r);
                            let jugadoresVivos = 0;
                            let ultimoJugadorVivo = null;

                            // cuenta los jugadores vivos y guarda el ultimo
                            if (parseInt(datosActual.vida) > 0) {
                                jugadoresVivos++;
                                ultimoJugadorVivo = {
                                    ID_usuario: USUARIO_ACTUAL_ID,
                                    username: $('.jugador-actual h2').text().replace('Username: ', ''),
                                    vida: datosActual.vida
                                };
                            }

                            // actualiza la barra de vida de cada jugador
                            vidas.forEach(jugador => {
                                if (jugador.ID_usuario != USUARIO_ACTUAL_ID) {
                                    const porcentaje = (jugador.vida / 100) * 100;
                                    $(`.jugador-card[data-id="${jugador.ID_usuario}"] .vida-actual`)
                                        .css('width', `${porcentaje}%`)
                                        .text(`${jugador.vida}/100`);

                                    if (parseInt(jugador.vida) > 0) {
                                        jugadoresVivos++;
                                        ultimoJugadorVivo = jugador;
                                    }
                                }
                            });

                            // si solo queda un jugador vivo, termina la partida
                            if (jugadoresVivos === 1 && ultimoJugadorVivo && !estadisticasActualizadas) {
                                mostrarGanador(ultimoJugadorVivo);
                                desactivarControlesJuego();
                                evaluarPorUltimoJugador(ultimoJugadorVivo);
                            }
                        }
                    });
                }
            });
        }

        // guarda las estadisticas finales de la partida
        function evaluarPorUltimoJugador(ganador) {
            if (estadisticasActualizadas) return;
            
            estadisticasActualizadas = true;
            
            // detiene todas las actualizaciones
            clearInterval(window.intervalJugadorActual);
            clearInterval(window.intervalOtrosJugadores);
            clearInterval(window.intervalPuntos);
            
            desactivarControlesJuego();
            
            // guarda los datos finales en la base de datos
            $.ajax({
                url: 'actualizar_estadisticas_partida.php',
                method: 'POST',
                data: {
                    sala_id: SALA_ACTUAL_ID,
                    ganador_id: ganador.ID_usuario
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        setTimeout(() => {
                            window.location.href = '../inicio.php';
                        }, 5000);
                    }
                }
            });
        }


        ////////////////////////////

        function abandonarPartida() {
            $.ajax({
                url: 'abandonar_partida.php',
                method: 'POST',
                data: {
                    usuario_id: USUARIO_ACTUAL_ID,
                    sala_id: SALA_ACTUAL_ID
                },
                success: function(response) {
                    window.location.replace('../inicio.php');
                }
            });
        }

        // actualiza nuestra barra de vida
        function actualizarJugadorActual() {
            $.ajax({
                url: 'obtener_vida_actual.php',
                method: 'GET',
                data: { usuario_id: USUARIO_ACTUAL_ID },
                success: function(r) {
                    const datos = JSON.parse(r);
                    // calcula cuanto mide la barra de vida
                    const porcentaje = (datos.vida / 100) * 100;//se calcula asi la vida ya que es un porcentaje ejem: (50/100) * 100 = 50%
                    $('.jugador-actual .vida-actual')
                        .css('width', `${porcentaje}%`)//ajusta el ancho de la barra de vida al porcentaje calculado
                        .text(`${datos.vida}/100`);//actualiza el texto dentro de la barra de vida para mostrar la vida actual ejem: (50/100) 
                    // evalua si morimos y desactiva los controles
                    if (datos.vida <= 0) {
                        desactivarControlesJuego();
                        mostrarMensaje('has muerto', 'error');
                    }
                }
            });
        }

        function actualizarOtrosJugadores() {
            $.ajax({
                url: 'obtener_vida_actual.php',
                method: 'GET',
                data: { usuario_id: USUARIO_ACTUAL_ID },
                success: function(currentResponse) {
                    const datosActual = JSON.parse(currentResponse);
                    $.ajax({
                        url: 'obtener_vidas.php',
                        data: { sala_id: SALA_ACTUAL_ID },
                        method: 'GET',
                        success: function(r) {
                            const vidas = JSON.parse(r);
                            let jugadoresVivos = 0;
                            let ultimoJugadorVivo = null;

                            // Contamos jugadores vivos y encontramos al último vivo
                            if (parseInt(datosActual.vida) > 0) {
                                jugadoresVivos++;
                                ultimoJugadorVivo = {
                                    ID_usuario: USUARIO_ACTUAL_ID,
                                    username: $('.jugador-actual h2').text().replace('Username: ', ''),
                                    vida: datosActual.vida
                                };
                            }

                            vidas.forEach(jugador => {
                                if (jugador.ID_usuario != USUARIO_ACTUAL_ID) {
                                    const porcentaje = (jugador.vida / 100) * 100;
                                    $(`.jugador-card[data-id="${jugador.ID_usuario}"] .vida-actual`)
                                        .css('width', `${porcentaje}%`)
                                        .text(`${jugador.vida}/100`);

                                    if (parseInt(jugador.vida) > 0) {
                                        jugadoresVivos++;
                                        ultimoJugadorVivo = jugador;
                                    }
                                }
                            });

                            if (jugadoresVivos === 1 && ultimoJugadorVivo && !estadisticasActualizadas) {
                                // Primero mostramos el modal del ganador
                                mostrarGanador(ultimoJugadorVivo);
                                desactivarControlesJuego();
                                
                                // Después de 3 segundos actualizamos las estadísticas
                                setTimeout(() => {
                                    if (!estadisticasActualizadas) {
                                        evaluarPorUltimoJugador(ultimoJugadorVivo);
                                    }
                                }, 3000);
                            }
                        }
                    });
                }
            });
        }

        function actualizarPuntos() {
            $.ajax({
                url: 'obtener_estadisticas.php',
                method: 'GET',
                data: { usuario_id: USUARIO_ACTUAL_ID },
                success: function(r) {
                    const datos = JSON.parse(r);
                    if (datos && datos.Puntos !== undefined) {
                        $('.jugador-actual .stats p').text(`Puntos: ${datos.Puntos}`);
                    }
                }
            });
        }

        // pide al servidor las armas disponibles y las muestra en una ventana
        function cargarArmas() {
        $.ajax({
        url: 'obtener_armas.php',
        method: 'GET',
        success: function(r) {
            // pone las armas en la ventana
            $('#lista-armas').html(r);
            
            // cuando hacemos click en un arma, atacamos
            $('.arma-opcion').off('click').on('click', function() {
                const dano = parseInt($(this).data('dano'));
                // 10% de probabilidad de headshot
                const esHeadshot = Math.random() < 0.30;//utilizamos la librereria Math.random() para que el headshot tenga una probabilidad de 30%
                console.log('Daño seleccionado:', dano, 'Headshot:', esHeadshot);
                realizarAtaque(objetivoSeleccionado, dano, esHeadshot);
            });
            
            // muestra la ventana de armas
            $('#modal-armas').show();
                }
            });
        }

        // guarda el jugador que queremos atacar y muestra las armas
        function seleccionarObjetivo(usuarioId) {
            //si el objetivo tiene menos vida a 0 o igual a 0 no lo deje ejecutar la funcion cargarArmas
            const vidaActual = parseInt($(`.jugador-card[data-id="${usuarioId}"] .vida-actual`).text().split('/')[0]);
            if (vidaActual <= 0) {
                return;
            }
            else{
                objetivoSeleccionado = usuarioId;
                cargarArmas();
            }
        }

        // funcion que procesa el ataque a otro jugador
        function realizarAtaque(objetivoId, dano, esHeadshot) {
            // muestra en consola los detalles del ataque para debugging
            console.log('Realizando ataque:', { objetivoId, dano, esHeadshot });

            // verifica que tengamos un objetivo valido y daño definido
            if (!objetivoId || dano === undefined) {
                cerrarVentana();
                return;
            }

            // primera verificacion: revisa si el atacante esta vivo
            $.ajax({
                url: 'obtener_vida_actual.php',
                method: 'GET',
                data: { usuario_id: USUARIO_ACTUAL_ID },
                success: function(response) {
                    const datos = JSON.parse(response);
                    
                    // si el atacante esta muerto, cancela el ataque
                    if (datos.vida <= 0) {
                        cerrarVentana();
                        return;
                    }

                    // segunda verificacion: revisa si el objetivo esta vivo
                    $.ajax({
                        url: 'obtener_vida_actual.php',
                        method: 'GET',
                        data: { usuario_id: objetivoId },
                        success: function(targetResponse) {
                            const targetData = JSON.parse(targetResponse);
                            
                            // si el objetivo esta muerto, cancela el ataque
                            if (targetData.vida <= 0) {
                                cerrarVentana();
                                return;
                            }

                            $.ajax({
                                url: 'procesar_ataque.php',
                                method: 'POST',
                                data: {
                                    objetivo_id: objetivoId,
                                    dano: dano,
                                    es_headshot: esHeadshot ? 1 : 0,//esto es para que el headshot sea 1 o 0, si es true, es 1, si es false, es 0
                                    id_sala: SALA_ACTUAL_ID  // Agregamos el id de la sala
                                },
                                dataType: 'json',
                                success: function(data) {
                                    if (data.success) {
                                        actualizarVidaJugador(objetivoId, data.vida_restante);
                                        if (data.esHeadshot) {//aqui le decimos que si es headshot, muestre un mensaje diferente
                                            mostrarMensaje(`¡HEADSHOT! Daño causado: ${data.dano_causado}`, 'critical');
                                            cerrarVentana();
                                        } else {//si no es headshot simplemente mostramos el mensaje del daño q hizo
                                            mostrarMensaje(`Daño causado: ${data.dano_causado}`, 'info');
                                            cerrarVentana();
                                        }
                                        actualizarPuntos();//al final actualizamos los puntos
                                    } else {
                                        mostrarMensaje('Error al realizar el ataque', 'error');//por si no se pudo realizar el ataque, mostramos este mensaje                                    }
                                    cerrarVentana();//y llamamos a la funcion cerrarVentana() para cerrar la ventana :b
                                    objetivoSeleccionado = null;
                                    }
                                }
                            });
                        }
                    });
                }
            });
        }

        function desactivarControlesJuego() {
            const botonesAtaque = document.querySelectorAll('.btn-atacar');
            botonesAtaque.forEach(boton => {
                boton.disabled = true;
                boton.style.opacity = '0.5';
                boton.style.cursor = 'not-allowed';
            });
            cerrarVentana();
            $('.arma-opcion').off('click');
        }

        function mostrarMensaje(mensaje, tipo = 'info') {
            const divMensaje = document.createElement('div');
            divMensaje.className = `mensaje ${tipo}`;
            divMensaje.textContent = mensaje;
            document.body.appendChild(divMensaje);
            setTimeout(() => divMensaje.remove(), 3000);
        }

        function actualizarVidaJugador(jugadorId, vidaRestante) {
            if (vidaRestante !== undefined) {
                const porcentaje = (vidaRestante / 100) * 100;
                $(`.jugador-card[data-id="${jugadorId}"] .vida-actual`)
                    .css('width', `${porcentaje}%`)
                    .text(`${vidaRestante}/100`);
            }
        }

        function cerrarVentana() {
            $('#modal-armas').hide();
            objetivoSeleccionado = null;
        }

        
    </script>
</body>
</html>