@extends('layouts.app')

@section('content')
<style>
    /* Estilo para la cancha */
    .cancha {
        position: relative;
        width: 100%;
        height: 70vh;
        max-width: 500px; /* Máximo ancho para mantener la escala */
        background-color: #c7f0d8;
        border: 2px solid #000;
        margin-bottom: 20px;
    }

    /* Estilo para los jugadores */
    .jugador {
        position: absolute;
        font-size: 24px;
        cursor: move;
    }

    /* Estilo para el contenedor de iconos */
    .icono-container {
        display: flex;
        flex-wrap: wrap;
        margin-right: -5px; /* Ajuste para eliminar el espacio entre los iconos */
    }

    /* Estilo para los iconos */
    .icono {
        font-size: 24px;
        cursor: pointer;
        margin: 0 5px 5px 0; /* Ajuste para el espacio entre los iconos */
    }

    /* Alineación de los formularios en columnas */
    .columna-formulario {
        column-count: 2;
        column-gap: 20px;
    }
</style>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="cancha" id="cancha" ondragover="allowDrop(event)" ondrop="drop(event)">
                <!-- Área de la cancha -->
                <!-- Aquí se mostrarán los jugadores cargados desde la base de datos -->
                @foreach($entrenamiento->configuracion_cancha as $jugador)
                    <div class="jugador" style="left: {{ $jugador['left'] }}; top: {{ $jugador['top'] }};">{{ $jugador['icono'] }}</div>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <div class="icono-container">
                <!-- Iconos para arrastrar a la cancha -->
                <!-- Asegúrate de que los eventos de arrastre estén configurados correctamente en JavaScript -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🔵</div> <!-- Círculo azul -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🟠</div> <!-- Círculo naranja -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🔴</div> <!-- Círculo rojo -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🔵</div> <!-- Triángulo azul -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🔺</div> <!-- Triángulo blanco -->
                <div class="icono" draggable="true" ondragstart="drag(event)">⚪</div> <!-- Círculo blanco -->
                <div class="icono" draggable="true" ondragstart="drag(event)">⚫</div> <!-- Círculo negro -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🏐</div>
                <div class="icono" draggable="true" ondragstart="drag(event)">&#10132;</div>
                <div class="icono" draggable="true" ondragstart="drag(event)">&#10132;</div>
            </div>
        </div>
    </div>
    <!-- Formulario para editar el entrenamiento -->
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-3 d-none-print">Detalle</h2>
            <form id="infoForm" method="POST" class="d-none-print columna-formulario" action="{{ route('entrenamientos.update', $entrenamiento->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Aquí se incluyen los campos del formulario para editar el entrenamiento -->
                <div class="form-group">
                    <label for="nombreEjercicio">Nombre del Ejercicio</label>
                    <input name="nombre" type="text" class="form-control" id="nombreEjercicio" placeholder="Ingrese el nombre del ejercicio" value="{{ $entrenamiento->nombre }}">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" class="form-control" id="descripcion" rows="3" placeholder="Ingrese la descripción del ejercicio">{{ $entrenamiento->descripcion }}</textarea>
                </div>
                <div class="form-group">
                    <label for="minJugadores">Número Mínimo de Jugadores</label>
                    <input type="number" name="min_jugadores" class="form-control" id="minJugadores" min="0" placeholder="Ingrese el número mínimo" value="{{ $entrenamiento->min_jugadores }}">
                </div>
                <div class="form-group">
                    <label for="maxJugadores">Número Máximo de Jugadores</label>
                    <input type="number" name="max_jugadores" class="form-control" id="maxJugadores" min="0" placeholder="Ingrese el número máximo" value="{{ $entrenamiento->max_jugadores }}">
                </div>
                <div class="form-group">
                    <label for="duracion">Duracion</label>
                    <input type="number" name="duracion" class="form-control" id="duracion" min="0" placeholder="Duracion" value="{{ $entrenamiento->duracion }}">
                </div>
                <div class="form-group">
                    <label for="comentarios">Comentarios o Notas</label>
                    <textarea class="form-control" name="comentarios" id="comentarios" rows="3" placeholder="Ingrese comentarios o notas adicionales">{{ $entrenamiento->comentarios }}</textarea>
                </div>
                {{-- <div class="form-group">
                    <label>Materiales Necesarios</label><br>
                    @foreach($entrenamiento->materiales as $material)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="material_{{ $material['id'] }}" name="materiales[]" value="{{ $material['id'] }}" {{ in_array($material['id'], $entrenamiento->materiales) ? 'checked' : '' }}>
                            <label class="form-check-label" for="material_{{ $material['id'] }}">{{ $material['nombre'] }}</label>
                        </div>
                    @endforeach
                </div> --}}
                
                {{-- <div class="form-group">
                    <label>Técnicas</label><br>
                    @foreach($entrenamiento->tecnicas as $tecnica)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="tecnica_{{ $tecnica['id'] }}" name="tecnicas[]" value="{{ $tecnica['id'] }}" {{ in_array($tecnica['id'], $entrenamiento->tecnicas) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tecnica_{{ $tecnica['id'] }}">{{ $tecnica['nombre'] }}</label>
                        </div>
                    @endforeach
                </div> --}}
                
                
                
                <input type="hidden" name="configuracion_cancha" id="configuracion_cancha" value="{{ json_encode($entrenamiento->configuracion_cancha) }}">
                <button type="submit" class="btn btn-primary">Guardar Entrenamiento</button>
            </form>
        </div>
    </div>
</div>
<script>
    // Función para permitir soltar elementos en la cancha
    function allowDrop(event) {
        event.preventDefault();
    }

    // Función para arrastrar un elemento
    function drag(event) {
        if (event.type === 'dragstart') {
            event.dataTransfer.setData("text", event.target.innerText);
        } else if (event.type === 'touchstart') {
            event.preventDefault(); // Evitar el comportamiento predeterminado del navegador para el evento táctil
            var touch = event.targetTouches[0];
            event.target.setAttribute('touchX', touch.clientX);
            event.target.setAttribute('touchY', touch.clientY);
            event.target.setAttribute('dragging', 'true');
        }
    }

    // Función para soltar un elemento en la cancha
    function drop(event) {
        event.preventDefault();
        if (event.type === 'drop') {
            var icono = event.dataTransfer.getData("text");
            var offsetX = event.clientX - event.target.getBoundingClientRect().left;
            var offsetY = event.clientY - event.target.getBoundingClientRect().top;
            agregarElemento(icono, offsetX, offsetY);
            guardarConfiguracion();
        } else if (event.type === 'touchend') {
            var icono = event.target.innerText;
            var offsetX = event.changedTouches[0].clientX - event.target.getBoundingClientRect().left;
            var offsetY = event.changedTouches[0].clientY - event.target.getBoundingClientRect().top;
            agregarElemento(icono, offsetX, offsetY);
            guardarConfiguracion();
        }
    }

    // Función para guardar la configuración de la cancha
    function guardarConfiguracion() {
        var jugadores = document.querySelectorAll('.jugador');
        var configuracion = [];

        jugadores.forEach(function(jugador) {
            var info = {
                icono: jugador.innerHTML,
                left: jugador.style.left,
                top: jugador.style.top
            };
            configuracion.push(info);
        });

        document.getElementById('configuracion_cancha').value = JSON.stringify(configuracion);
    }

    // Función para agregar un elemento en la cancha
    function agregarElemento(icono, offsetX, offsetY) {
        var elemento = document.createElement('div');
        elemento.className = 'jugador';
        elemento.innerHTML = icono;
        elemento.style.left = offsetX - 25 + 'px'; // Ajustar posición en base a coordenadas
        elemento.style.top = offsetY - 25 + 'px';
        document.getElementById('cancha').appendChild(elemento);

        // Añadir evento para mover al hacer clic y arrastrar
        elemento.addEventListener('mousedown', function(event) {
            moverElemento(event, elemento);
        });
    }

    // Función para mover un elemento
    function moverElemento(event, elemento) {
        var offsetX = event.clientX - elemento.getBoundingClientRect().left;
        var offsetY = event.clientY - elemento.getBoundingClientRect().top;

        // Función para actualizar la posición del elemento mientras se arrastra
        function mover(event) {
            var x = event.clientX - offsetX;
            var y = event.clientY - offsetY;

            // Limitar el movimiento dentro de la cancha
            if (x >= 0 && y >= 0 && x <= 500 && y <= 700) {
                elemento.style.left = x + 'px';
                elemento.style.top = y + 'px';
            }
        }

        // Función para detener el movimiento del elemento
        function soltar() {
            document.removeEventListener('mousemove', mover);
            document.removeEventListener('mouseup', soltar);
            guardarConfiguracion();
        }

        // Agregar eventos para mover y soltar el elemento
        document.addEventListener('mousemove', mover);
        document.addEventListener('mouseup', soltar);
    }
</script>

@endsection
