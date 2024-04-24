<?php

namespace App\Http\Controllers;

use App\Models\jugador;
use App\Models\categoria;
use Illuminate\Http\Request;

class JugadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jugadores = jugador::all();
        return view('jugadores.index', compact('jugadores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = categoria::all();
        return view('jugadores.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jugador = new jugador();
        $jugador->nombre_completo = $request->nombre_completo;
        $jugador->posicion = $request->posicion;
        $jugador->categoria_id = $request->categoria_id;
        $jugador->save();
        return redirect()->route('jugadores.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(jugador $jugador)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(jugador $jugador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, jugador $jugador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(jugador $jugador)
    {
        //
    }
}
