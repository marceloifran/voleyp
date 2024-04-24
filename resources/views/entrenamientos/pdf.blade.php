<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Entrenamiento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h3 {
            margin-top: 0;
        }
        .card p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="col-md-6">
        <div class="cancha" id="cancha" ondragover="allowDrop(event)" ondrop="drop(event)">
            <!-- Área de la cancha -->
            <!-- Aquí se mostrarán los jugadores cargados desde la base de datos -->
            @foreach($entrenamiento->configuracion_cancha as $jugador)
                <div class="jugador" style="left: {{ $jugador['left'] }}; top: {{ $jugador['top'] }};">{{ $jugador['icono'] }}</div>
            @endforeach
        </div>
    </div>
    
    
    <div class="container">
        <h1>Detalle del Entrenamiento</h1>
        <div class="card">
            <h3>Entrenamiento #{{ $entrenamiento->id }}</h3>
            <p><strong>Nombre:</strong> {{ $entrenamiento->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $entrenamiento->descripcion }}</p>
            <p><strong>Número Mínimo de Jugadores:</strong> {{ $entrenamiento->min_jugadores }}</p>
            <p><strong>Número Máximo de Jugadores:</strong> {{ $entrenamiento->max_jugadores }}</p>
            <p><strong>Duración:</strong> {{ $entrenamiento->duracion }} minutos</p>
            <p><strong>Comentarios o Notas:</strong> {{ $entrenamiento->comentarios }}</p>
            <input type="hidden" name="configuracion_cancha" id="configuracion_cancha">
        </div>
    </div>
</body>
</html>
