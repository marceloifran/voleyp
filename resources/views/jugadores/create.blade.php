@extends('layouts.app')
@section('content')
<div style="margin-top: 20px" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Crear Jugador</div>

                <div class="card-body">
                    <form id="viajeForm" method="POST" action="{{ route('jugadores.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="nombre_completo">Nombre Completo</label>
                                    <input type="text" name="nombre_completo" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="posicion">Posicion</label>
                                    <input type="text" name="posicion" class="form-control" required>
                                </div>
                            </div>
                        <div class="form-group">
                            <label for="categoria_id">Categoria</label>
                            <select id="categoria_id" name="categoria_id" class="form-control" required>
                                <option value="" disabled selected>Selecciona una categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button style="margin: 10px;" type="submit" class="btn btn-primary">Cargar Jugador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
