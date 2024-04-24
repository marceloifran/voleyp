<?php

namespace App\Http\Controllers;

use App\Models\partido;
use App\Models\categoria;
use App\Models\jugador;
use App\Models\set;
use Illuminate\Http\Request;

class PartidoController extends Controller
{

    
public function jugadoresPorCategoria($categoriaId)
{
    $jugadores = jugador::where('categoria_id', $categoriaId)->get();
    return response()->json($jugadores);
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partidos = partido::all();
        return view('partidos.index',compact('partidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = categoria::all();
        return view('partidos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
     public function store(Request $request)
     {
         try {
             // Validar los datos del formulario
            //  $request->validate([
            //      'fecha' => 'required|date',
            //      'rival' => 'required|string',
            //      'categoria_id' => 'required|exists:categorias,id',
            //      'sets' => 'required|array|min:1|max:5', // Se espera un array de sets con un mínimo de 1 y máximo de 5
            //      'sets.*.numero' => 'required|integer', // Número del set
            //      'sets.*.jugadores' => 'required|array|min:1', // Se espera un array de jugadores en cada set
            //      'sets.*.jugadores.*.nombre' => 'required|string', // Nombre del jugador
            //      'sets.*.jugadores.*.ataques' => 'required|integer|min:0', // Número de ataques
            //      'sets.*.jugadores.*.contrataques' => 'required|integer|min:0', // Número de contrataques
            //      'sets.*.jugadores.*.saques' => 'required|integer|min:0', // Número de saques
            //      'sets.*.jugadores.*.bloqueos' => 'required|integer|min:0', // Número de bloqueos
            //      'sets.*.jugadores.*.recepciones' => 'required|array|min:4|max:4', // Recepciones del jugador
            //      'sets.*.jugadores.*.recepciones.*' => 'required|integer|min:0', // Valor de cada recepción
            //  ]);
     
             // Crear el partido con los datos principales
             $partido = new Partido([
                 'fecha' => $request->fecha,
                 'rival' => $request->rival,
                 'categoria_id' => $request->categoria_id,
                 'jugador_id' => $request->jugador_id,
                 // Agregar más campos aquí si es necesario
             ]);
     
             // Guardar el partido en la base de datos
             $partido->save();
     
             // Guardar los sets
             foreach ($request->sets as $setDatos) {
                 $set = new Set([
                     'numero' => $setDatos['numero'],
                     // Agregar más campos aquí si es necesario
                 ]);
     
                 // Guardar los jugadores en el set
                 foreach ($setDatos['jugadores'] as $datosJugador) {
                     $jugador = new Jugador([
                         // Asignar los datos del jugador
                         'nombre' => $datosJugador['nombre'],
                         'ataques' => $datosJugador['ataques'],
                         'contrataques' => $datosJugador['contrataques'],
                         'saques' => $datosJugador['saques'],
                         'bloqueos' => $datosJugador['bloqueos'],
                         // Agregar más campos aquí si es necesario
                     ]);
     
                     // Guardar las recepciones del jugador
                     $jugador->recepciones()->create([
                         'recepcion_1' => $datosJugador['recepciones'][0],
                         'recepcion_2' => $datosJugador['recepciones'][1],
                         'recepcion_3' => $datosJugador['recepciones'][2],
                         'recepcion_4' => $datosJugador['recepciones'][3],
                     ]);
     
                     // Guardar el jugador en el set
                     $set->jugadores()->save($jugador);
                 }
     
                 // Relacionar el set con el partido y guardarlo
                 $partido->sets()->save($set);
             }
     
             // Redireccionar o responder con algún mensaje de éxito
             return redirect()->route('partidos.index')->with('success', 'Partido creado exitosamente.');
         } catch (Exception $e) {
             \Log::error('Error al guardar el partido: ' . $e->getMessage());
             return back()->with('error', 'Error al guardar el partido: ' . $e->getMessage())->withInput();
         }
     }
     
     
    /**
     * Display the specified resource.
     */
    public function show(partido $partido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(partido $partido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, partido $partido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(partido $partido)
    {
        //
    }
}
