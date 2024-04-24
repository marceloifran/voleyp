@extends('layouts.app')
@section('content')
<div style="margin-top: 20px" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">Crear Partido</div>

                <div class="card-body">
                    <form id="partidoForm" method="POST" action="{{ route('partidos.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="rival">Nombre del Rival</label>
                            <input type="text" name="rival" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria_id" class="form-control" required>
                                <option value="" disabled selected>Selecciona una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Botones de navegación entre sets -->
                        <div style="margin-top:10px;margin-bottom: 10px;display: inline-block;">
                            <button id="retrocederSetBtn" type="button" class="btn btn-secondary" onclick="retrocederSet()">←</button>
                            <!-- Agregar un elemento para mostrar el número de set actual -->
<div id="infoSetActual" style="margin-bottom: 10px;display: inline-block; ">
    <p>Set actual: <span id="numeroSetActualSpan">1</span></p>
</div>

                            <button id="avanzarSetBtn" type="button" class="btn btn-secondary" onclick="avanzarSet()">→</button>
                        </div>
<br>
                        <!-- Botón para guardar partido -->
                        <button style="margin-bottom: 10px; margin-left: 5px;" id="guardarPartidoBtn" type="button" class="btn btn-success" onclick="guardarSet()">Guardar Set</button>
                        <!-- Botón para finalizar el partido -->
<button style="margin-bottom: 10px; margin-left: 5px;" id="finalizarPartidoBtn" type="button" class="btn btn-primary" onclick="finalizarPartido()">Finalizar Partido</button>


                        <!-- Tabla para mostrar los jugadores y las acciones -->
                        <div id="tablaJugadores">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Jugador</th>
                                        <th class="text-center">Ataque</th>
                                        <th  class="text-center">CA</th>
                                        <th  class="text-center">Saque</th>
                                        <th  class="text-center">Bloqueo</th>
                                        <th  class="text-center">Recepción</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTabla">
                                    <!-- Aquí se llenarán los datos de los jugadores -->
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal de estadísticas -->
<div class="modal fade" id="estadisticasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Estadísticas de Porcentajes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Aquí se mostrarán las estadísticas de porcentajes -->
                <div id="estadisticasContenido">
                    <!-- Aquí se llenarán las estadísticas dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    var sets = []; // Array para almacenar los datos de cada set
    var numeroSetActual = 1; // Variable para rastrear el número de set actual

    function sumar(id, isRed) {
    var span = document.getElementById(id);
    var valor = parseInt(span.textContent);
    if (isRed) {
        var spanRojo = document.getElementById(`${id}_rojo`);
        spanRojo.textContent = parseInt(spanRojo.textContent) + 1;
    } else {
        span.textContent = valor + 1;
    }
}


    function sumarRecepcion(id) {
        var span = document.getElementById(id);
        var valor = parseInt(span.textContent);
        span.textContent = valor + 1;
    }

    // Función para restar al contador
    function restar(id, jugadorId) {
        var span = document.getElementById(id);
        var valor = parseInt(span.textContent);
        if (valor > 0) {
            span.textContent = valor - 1;
            actualizarBotonRecepcion(jugadorId);
        }
    }

    function restarRecepcion(id, jugadorId) {
        restar(id, jugadorId);
    }

    function actualizarBotonRecepcion(jugadorId) {
        var sumaRecepcion = 0;
        for (var i = 1; i <= 4; i++) {
            var id = `recepcion_${jugadorId}_${i}`;
            var button = document.getElementById(id);
            if (button) {
                sumaRecepcion += parseInt(button.textContent);
            }
        }
        document.getElementById(`recepcion_${jugadorId}`).textContent = sumaRecepcion;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('numeroSetActualSpan').textContent = numeroSetActual;

        document.getElementById('categoria').addEventListener('change', function() {
            var categoriaId = this.value;
            if (categoriaId) {
                fetch(`/jugadores-por-categoria/${categoriaId}`)
                    .then(response => response.json())
                    .then(data => {
                        var cuerpoTabla = document.getElementById('cuerpoTabla');
                        cuerpoTabla.innerHTML = '';
                        data.forEach(jugador => {
                            var fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td class="text-center">${jugador.nombre_completo}</td>
                                <td class="text-center">
    <button type="button" class="btn btn-sm btn-primary" onclick="sumar('ataque_${jugador.id}')"><span id="ataque_${jugador.id}" class="contador">0</span></button>
    <button type="button" class="btn btn-sm btn-danger red-btn" onclick="sumar('ataque_${jugador.id}', true)"><span id="ataque_${jugador.id}_rojo" class="contador-red">0</span></button>
</td>
<td class="text-center">
    <button type="button" class="btn btn-sm btn-primary" onclick="sumar('contrataque_${jugador.id}')"><span id="contrataque_${jugador.id}" class="contador">0</span></button>
    <button type="button" class="btn btn-sm btn-danger red-btn" onclick="sumar('contrataque_${jugador.id}', true)"><span id="contrataque_${jugador.id}_rojo" class="contador-red">0</span></button>
</td>
<td class="text-center">
    <button type="button" class="btn btn-sm btn-primary" onclick="sumar('saque_${jugador.id}')"><span id="saque_${jugador.id}" class="contador">0</span></button>
    <button type="button" class="btn btn-sm btn-danger red-btn" onclick="sumar('saque_${jugador.id}', true)"><span id="saque_${jugador.id}_rojo" class="contador-red">0</span></button>
</td>
<td class="text-center">
    <button type="button" class="btn btn-sm btn-primary" onclick="sumar('bloqueo_${jugador.id}')"><span id="bloqueo_${jugador.id}" class="contador">0</span></button>
    <button type="button" class="btn btn-sm btn-danger red-btn" onclick="sumar('bloqueo_${jugador.id}', true)"><span id="bloqueo_${jugador.id}_rojo" class="contador-red">0</span></button>
</td>

                                <td class="text-center">
                                    <!-- Contadores de recepción -->
                                    <button type="button" class="btn btn-sm btn-success" onclick="sumarRecepcion('recepcion_${jugador.id}_1')"><span id="recepcion_${jugador.id}_1" class="contador">0</span></button>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="sumarRecepcion('recepcion_${jugador.id}_2')"><span id="recepcion_${jugador.id}_2" class="contador">0</span></button>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="sumarRecepcion('recepcion_${jugador.id}_3')"><span id="recepcion_${jugador.id}_3" class="contador">0</span></button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="sumarRecepcion('recepcion_${jugador.id}_4')"><span id="recepcion_${jugador.id}_4" class="contador">0</span></button>
                                </td>
                            `;
                            cuerpoTabla.appendChild(fila);
                        });
                    });
            }
        });

        // Resto del código para cambiar de set, guardar set, mostrar set y guardar partido
    });
    function guardarSet() {
    if (sets.length < 5) {
        var jugadores = document.querySelectorAll('#cuerpoTabla tr');
        var set = [];
        jugadores.forEach(jugador => {
            var jugadorId = jugador.querySelector('td').textContent;
            var ataques = parseInt(jugador.cells[1].querySelector('.contador').textContent);
            var ataquesRojo = 0; 
            var ataquesRojoSpans = jugador.cells[1].querySelectorAll('.contador-red');
            ataquesRojoSpans.forEach(span => {
                ataquesRojo += parseInt(span.textContent);
            });

            var contrataques = parseInt(jugador.cells[2].querySelector('.contador').textContent);
            var contrataquesRojo = 0;
            var contrataquesRojoSpans = jugador.cells[2].querySelectorAll('.contador-red');
            contrataquesRojoSpans.forEach(span => {
                contrataquesRojo += parseInt(span.textContent);
            });

            // Repite el mismo proceso para saques y bloqueos rojos...
            
            var saques = parseInt(jugador.cells[3].querySelector('.contador').textContent);
            var saquesRojo = 0;
            var saquesRojoSpans = jugador.cells[3].querySelectorAll('.contador-red');
            saquesRojoSpans.forEach(span => {
                saquesRojo += parseInt(span.textContent);
            });

            var bloqueos = parseInt(jugador.cells[4].querySelector('.contador').textContent);
            var bloqueosRojo = 0;
            var bloqueosRojoSpans = jugador.cells[4].querySelectorAll('.contador-red');
            bloqueosRojoSpans.forEach(span => {
                bloqueosRojo += parseInt(span.textContent);
            });

            // Resto del código para obtener los contadores de recepción...

            var recepciones = [];
            for (var i = 0; i < 4; i++) {
                recepciones.push(parseInt(jugador.cells[5].querySelectorAll('.contador')[i].textContent));
            }

            set.push({
                jugadorId: jugadorId,
                ataques: ataques,
                ataquesRojo: ataquesRojo,
                contrataques: contrataques,
                contrataquesRojo: contrataquesRojo,
                // Repite el mismo proceso para saques y bloqueos rojos...
                saques: saques,
                saquesRojo: saquesRojo,
                bloqueos: bloqueos,
                bloqueosRojo: bloqueosRojo,
                recepciones: recepciones
            });
        });
        sets.push(set);
        numeroSetActual = sets.length;
        alert('Set guardado correctamente.');
    } else {
        alert('Ya se han guardado el máximo número de sets disponibles (5).');
    }
}
function mostrarSet(numeroSet) {
    if (numeroSet > 0 && numeroSet <= sets.length) {
        var set = sets[numeroSet - 1];
        var jugadores = document.querySelectorAll('#cuerpoTabla tr');
        jugadores.forEach((jugador, index) => {
            var datosJugador = set[index];
            if (datosJugador) { // Verificar si hay datos para este jugador en este set
                jugador.cells[1].querySelector('.contador').textContent = datosJugador.ataques;
                jugador.cells[2].querySelector('.contador').textContent = datosJugador.contrataques;
                jugador.cells[3].querySelector('.contador').textContent = datosJugador.saques;
                jugador.cells[4].querySelector('.contador').textContent = datosJugador.bloqueos;

                // Restablecer los contadores rojos al valor cero
                jugador.cells[1].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');
                jugador.cells[2].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');
                jugador.cells[3].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');
                jugador.cells[4].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');

                for (var i = 0; i < 4; i++) {
                    jugador.cells[5].querySelectorAll('.contador')[i].textContent = datosJugador.recepciones[i];
                }
            } else {
                // Si no hay datos para este jugador en este set, limpiar los contadores
                jugador.cells[1].querySelector('.contador').textContent = '0';
                jugador.cells[2].querySelector('.contador').textContent = '0';
                jugador.cells[3].querySelector('.contador').textContent = '0';
                jugador.cells[4].querySelector('.contador').textContent = '0';
                for (var i = 0; i < 4; i++) {
                    jugador.cells[5].querySelectorAll('.contador')[i].textContent = '0';
                }
                // Restablecer los contadores rojos al valor cero
                jugador.cells[1].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');
                jugador.cells[2].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');
                jugador.cells[3].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');
                jugador.cells[4].querySelectorAll('.contador-red').forEach(span => span.textContent = '0');
            }
        });
        // Actualizar el número de set actual en el elemento HTML
        document.getElementById('numeroSetActualSpan').textContent = numeroSet;
    } else {
        // Si el número de set no es válido, simplemente limpiamos la tabla
        limpiarTabla();
    }
}



function avanzarSet() {
    if (numeroSetActual < 5) {
        numeroSetActual++;
        console.log(numeroSetActual);
        limpiarTabla(); // Agregar esta línea para limpiar la tabla
        mostrarSet(numeroSetActual);
        document.getElementById('numeroSetActualSpan').textContent = numeroSetActual; // Actualizar el número de set actual
    } else {
        console.log(numeroSetActual);
        alert('Ya has alcanzado el máximo número de sets disponibles (5).');
    }
}

function limpiarTabla() {
    // Selecciona todos los contadores y contadores rojos
    var contadores = document.querySelectorAll('.contador, .contador-red');
    contadores.forEach(contador => {
        contador.textContent = '0';
    });
}

function retrocederSet() {
    if (numeroSetActual > 1) {
        // Guardar los contadores del set actual antes de retroceder
        guardarContadoresSet(numeroSetActual);
        numeroSetActual--;
        mostrarSet(numeroSetActual);
        mostrarContadoresSet(numeroSetActual);
        document.getElementById('numeroSetActualSpan').textContent = numeroSetActual; // Actualizar el número de set actual
    } else {
        alert('No hay sets anteriores.');
    }
}
function guardarContadoresSet(numeroSet) {
    if (numeroSet > 0 && numeroSet <= sets.length) {
        var jugadores = document.querySelectorAll('#cuerpoTabla tr');
        var set = [];
        jugadores.forEach(jugador => {
            var jugadorId = jugador.cells[0].textContent;
            var ataques = parseInt(jugador.cells[1].querySelector('.contador').textContent);
            var contrataques = parseInt(jugador.cells[2].querySelector('.contador').textContent);
            var saques = parseInt(jugador.cells[3].querySelector('.contador').textContent);
            var bloqueos = parseInt(jugador.cells[4].querySelector('.contador').textContent);
            var recepciones = [];
            for (var i = 0; i < 4; i++) {
                recepciones.push(parseInt(jugador.cells[5].querySelectorAll('.contador')[i].textContent));
            }
            set.push({
                jugadorId: jugadorId,
                ataques: ataques,
                contrataques: contrataques,
                saques: saques,
                bloqueos: bloqueos,
                recepciones: recepciones
            });
        });
        sets[numeroSet - 1] = set;
    }
}
function mostrarContadoresSet(numeroSet) {
    if (numeroSet > 0 && numeroSet <= sets.length) {
        var set = sets[numeroSet - 1];
        var jugadores = document.querySelectorAll('#cuerpoTabla tr');
        jugadores.forEach((jugador, index) => {
            var datosJugador = set[index];
            if (datosJugador) {
                var contadorAtaque = jugador.querySelector('.contador-ataque');
                if (contadorAtaque) {
                    contadorAtaque.textContent = datosJugador.ataques;
                }
                var contadorContrataque = jugador.querySelector('.contador-contrataque');
                if (contadorContrataque) {
                    contadorContrataque.textContent = datosJugador.contrataques;
                }
                var contadorSaque = jugador.querySelector('.contador-saque');
                if (contadorSaque) {
                    contadorSaque.textContent = datosJugador.saques;
                }
                var contadorBloqueo = jugador.querySelector('.contador-bloqueo');
                if (contadorBloqueo) {
                    contadorBloqueo.textContent = datosJugador.bloqueos;
                }

                // Mostrar los contadores rojos de manera similar a los contadores regulares
                var ataquesRojoSpan = jugador.cells[1].querySelector('.contador-red');
                if (ataquesRojoSpan) { // Verificar si el span de los botones rojos existe
                    ataquesRojoSpan.textContent = datosJugador.ataquesRojo;
                }
                var contrataquesRojoSpan = jugador.cells[2].querySelector('.contador-red');
                if (contrataquesRojoSpan) { // Verificar si el span de los botones rojos existe
                    contrataquesRojoSpan.textContent = datosJugador.contrataquesRojo;
                }
                var saquesRojoSpan = jugador.cells[3].querySelector('.contador-red');
                if (saquesRojoSpan) { // Verificar si el span de los botones rojos existe
                    saquesRojoSpan.textContent = datosJugador.saquesRojo;
                }
                var bloqueosRojoSpan = jugador.cells[4].querySelector('.contador-red');
                if (bloqueosRojoSpan) { // Verificar si el span de los botones rojos existe
                    bloqueosRojoSpan.textContent = datosJugador.bloqueosRojo;
                }

                // Repetir el mismo proceso para los contadores de recepción...
                for (var i = 1; i <= 4; i++) {
                    var recepcionSpan = jugador.querySelector(`#recepcion_${datosJugador.jugadorId}_${i}`);
                    if (recepcionSpan) {
                        recepcionSpan.textContent = datosJugador.recepciones[i - 1];
                    }
                }
            }
        });
    } else {
        // Si el número de set no es válido, simplemente limpiamos la tabla
        limpiarTabla();
    }
}

function finalizarPartido() {
    // Guardar los datos del último set antes de finalizar el partido
    guardarContadoresSet(numeroSetActual);

    // Verificar que se hayan guardado al menos un set
    if (sets.length > 0) {
        // Realizar la solicitud POST al controlador para almacenar los datos del partido
        fetch('{{ route("partidos.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                fecha: document.querySelector('input[name="fecha"]').value,
                rival: document.querySelector('input[name="rival"]').value,
                categoria_id: document.querySelector('select[name="categoria_id"]').value,
                sets: sets
            })
        })
        .then(response => {
            if (response.ok) {
                // Redireccionar a la página de inicio, por ejemplo
                
                window.location.href = '{{ route("partidos.index") }}';
            } else {
                // Mostrar un mensaje de error si la solicitud falla
                alert('Error al guardar el partido. Por favor, inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el partido. Por favor, inténtalo de nuevo.');
        });
    } else {
        alert('No se ha guardado ningún set. Por favor, guarda al menos un set antes de finalizar el partido.');
    }
}


</script>
@endsection
