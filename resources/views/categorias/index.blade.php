@extends('layouts.app')

@section('content')
    <section class="section" style="margin: 20px">
        <div class="alert alert-warning text-center">
            <h2 class="h2">Categorías</h2>
        </div>
        <div class="section-body" style="margin-bottom: 80px;">
            <div class="row">
                @foreach ($categorias as $categoria)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card mt-4 bg-success">
                            <h3 style="margin-top: 5px;" class="text-white text-center">Categoría # {{ $categoria->id }}</h3>
                            <div class="card-body">
                                <h5 class="card-title text-white">Nombre: {{ $categoria->nombre }}</h5>
                                <h5 class="card-title text-white">Cantidad de jugadores: {{ $categoria->jugadores->count() }}</h5>
                                {{-- <h5 class="card-title text-white">Descripcion: {{ $categorias->descripcion }}</h5> --}}
                              
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning">Editar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-center mb-4 fixed-bottom">
            <a href="{{ route('categorias.create') }}" class="btn btn-floating btn-lg btn-warning" style="padding: 10px 16px; margin-right: 10px;"><i class="fas fa-plus"></i></a>
        </div>
    </section>
@endsection