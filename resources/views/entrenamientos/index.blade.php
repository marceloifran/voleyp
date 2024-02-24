@extends('layouts.app')

@section('content')
    <section class="section" style="margin: 20px">
        <div class="alert alert-warning text-center">
            <h2 class="h2">Entrenamientos</h2>
        </div>
        <div class="section-body" style="margin-bottom: 80px;">
            <div class="row">
                @foreach ($entrenamientos as $entrenamiento)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card mt-4 bg-success">
                            <h3 style="margin-top: 5px;" class="text-white text-center">Entrenamiento # {{ $entrenamiento->id }}</h3>
                            <div class="card-body">
                                <h5 class="card-title text-white">Nombre: {{ $entrenamiento->nombre }}</h5>
                                {{-- <h5 class="card-title text-white">Descripcion: {{ $entrenamientos->descripcion }}</h5> --}}
                                <a href="{{ route('entrenamientos.pdf', $entrenamiento->id) }}" class="btn btn-primary">Generar PDF</a>
                                <a href="{{ route('entrenamientos.edit', $entrenamiento->id) }}" class="btn btn-warning">Editar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-center mb-4 fixed-bottom">
            <a href="{{ route('entrenamientos.create') }}" class="btn btn-floating btn-lg btn-warning" style="padding: 10px 16px; margin-right: 10px;"><i class="fas fa-plus"></i></a>
        </div>
    </section>
@endsection
