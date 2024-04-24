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

    /* Estilo para el contenedor de iconos */
    .icono-container {
        display: flex;
        flex-wrap: wrap;
        margin-right: -5px; /* Ajuste para eliminar el espacio entre los iconos */
        justify-content: flex-start; /* Alinear los iconos a la derecha */
    }

    /* Estilo para los iconos */
    .icono {
        font-size: 24px;
        cursor: pointer;
        margin: 0 2px 2px 0; /* Ajuste para el espacio entre los iconos */
    }

    /* Estilo para los jugadores */
    .jugador {
        position: absolute;
        font-size: 24px;
        cursor: move;
    }

    /* Alineación de los formularios en columnas */
    .columna-formulario {
        column-count: 2;
        column-gap: 20px;
    }

    /* Estilos específicos para la impresión */
    @media print {
        /* Mostrar los elementos ocultos al exportar a PDF */
        .d-none-print {
            display: block !important;
        }

        /* Ajustes de estilo para los formularios */
        form {
            margin-bottom: 20px;
        }

        /* Ajustes de estilo para los inputs y selects */
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            border: 1px solid #000 !important;
            padding: 5px !important;
            margin-bottom: 10px !important;
            width: calc(100% - 12px) !important; /* Ajuste para el ancho de los inputs */
            box-sizing: border-box; /* Considerar el borde en el ancho */
        }
    }
</style>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="cancha" id="cancha" ondragover="allowDrop(event)" ondrop="drop(event)" onclick="removeElement(event)">
                <!-- Área de la cancha -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="icono-container">
                {{-- //agregar texto para escribir y poner como un elemento mas --}}
                <div class="icono" draggable="true" ondragstart="drag(event)">🔵</div> <!-- Círculo azul -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🟠</div> <!-- Círculo naranja -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🔴</div> <!-- Círculo rojo -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🔺</div> <!-- Triángulo blanco -->
                <div class="icono" draggable="true" ondragstart="drag(event)">⚪</div> <!-- Círculo blanco -->
                <div class="icono" draggable="true" ondragstart="drag(event)">⚫</div> <!-- Círculo negro -->
                <div class="icono" draggable="true" ondragstart="drag(event)">🏐</div>
                <div class="icono" draggable="true" ondragstart="drag(event)">&#10132;</div>
                <div class="icono" draggable="true" ondragstart="drag(event)">&#10132;</div>
            </div>
            <h2 class="mt-3 d-none-print">Detalle</h2>
            <form id="infoForm" method="POST" class="d-none-print columna-formulario" action="{{route('entrenamientos.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nombreEjercicio">Nombre del Ejercicio</label>
                    <input name="nombre" type="text" class="form-control" id="nombreEjercicio" placeholder="Ingrese el nombre del ejercicio">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" class="form-control" id="descripcion" rows="3" placeholder="Ingrese la descripción del ejercicio"></textarea>
                </div>
                <div class="form-group">
                    <label for="minJugadores">Número Mínimo de Jugadores</label>
                    <input type="number" name="min_jugadores" class="form-control" id="minJugadores" min="0" placeholder="Ingrese el número mínimo">
                </div>
                <div class="form-group">
                    <label for="maxJugadores">Número Máximo de Jugadores</label>
                    <input type="number" name="max_jugadores" class="form-control" id="maxJugadores" min="0" placeholder="Ingrese el número máximo">
                </div>
                <div class="form-group">
                    <label for="duracion">Duracion</label>
                    <input type="number" name="duracion" class="form-control" id="duracion" min="0" placeholder="Duracion">
                </div>
                <div class="form-group">
                    <label for="comentarios">Comentarios o Notas</label>
                    <textarea class="form-control" name="comentarios" id="comentarios" rows="3" placeholder="Ingrese comentarios o notas adicionales"></textarea>
                </div>
                <div class="form-group">
                    <label>Materiales Necesarios</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="balón" id="materialBalon" name="materiales[]">
                        <label class="form-check-label" for="materialBalon">Balón</label>
                    </div>
                    <!-- Otros materiales aquí -->
                </div>
                <div class="form-group">
                    <label>Tecnicas</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="técnica1" id="tecnica1" name="tecnicas[]">
                        <label class="form-check-label" for="tecnica1">Técnica 1</label>
                    </div>
                    <!-- Otras técnicas aquí -->
                </div>
                <!-- Agrega el campo oculto para la configuración de la cancha -->
                <input type="hidden" name="configuracion_cancha" id="configuracion_cancha">
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

    // Función para eliminar un elemento de la cancha al hacer clic
    function removeElement(event) {
        if (event.target.classList.contains('jugador')) {
            event.target.remove();
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
    // Función para actualizar la posición del elemento mientras se arrastra
    function mover(event) {
        // Calcular la posición del cursor dentro del elemento
        var x = event.clientX - canchaRect.left - offsetX;
        var y = event.clientY - canchaRect.top - offsetY;

        // Ajustar la posición para mantener el cursor centrado sobre el elemento
        x -= elemento.offsetWidth / 2;
        y -= elemento.offsetHeight / 2;

        // Limitar el movimiento dentro de los límites de la cancha
        x = Math.max(0, Math.min(x, canchaRect.width - elemento.offsetWidth));
        y = Math.max(0, Math.min(y, canchaRect.height - elemento.offsetHeight));

        // Actualizar la posición del elemento
        elemento.style.left = x + 'px';
        elemento.style.top = y + 'px';
    }

    // Obtener las dimensiones de la cancha
    var canchaRect = document.getElementById('cancha').getBoundingClientRect();

    // Calcular el desplazamiento inicial del cursor en relación con el elemento
    var offsetX = event.clientX - elemento.getBoundingClientRect().left;
    var offsetY = event.clientY - elemento.getBoundingClientRect().top;

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
