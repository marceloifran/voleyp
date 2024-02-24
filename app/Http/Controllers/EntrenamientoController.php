<?php

namespace App\Http\Controllers;

use App\Models\entrenamiento;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class EntrenamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entrenamientos = entrenamiento::all();
        return view('entrenamientos.index', compact('entrenamientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entrenamientos.create');
    }


    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'min_jugadores' => 'required|integer|min:0',
            'max_jugadores' => 'required|integer|min:0',
            'duracion' => 'required|integer|min:0',
            'comentarios' => 'nullable|string',
            'materiales' => 'nullable|array', // Aseguramos que sea un arreglo
            'tecnicas' => 'nullable|array', // Aseguramos que sea un arreglo
            'configuracion_cancha' => 'nullable|string',
        ]);
    
        // Crear una instancia de Entrenamiento con los datos del formulario
        $entrenamiento = new Entrenamiento([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'min_jugadores' => $request->min_jugadores,
            'max_jugadores' => $request->max_jugadores,
            'duracion' => $request->duracion,
            'comentarios' => $request->comentarios,
            'configuracion_cancha' => $request->configuracion_cancha,
        ]);
    
        // Convertir los materiales y técnicas a cadenas JSON y almacenarlas
        $entrenamiento->materiales = $request->has('materiales') ? json_encode($request->materiales) : null;
        $entrenamiento->tecnicas = $request->has('tecnicas') ? json_encode($request->tecnicas) : null;
    
        // Guardar el entrenamiento en la base de datos
        $entrenamiento->save();
    
        // Redirigir al usuario a la página de índice de entrenamientos
        return redirect()->route('entrenamientos.index');
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(entrenamiento $entrenamiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $entrenamiento = Entrenamiento::findOrFail($id);
        $entrenamiento->materiales = json_decode($entrenamiento->materiales, true); // Convertir el JSON a array
        $entrenamiento->tecnicas = json_decode($entrenamiento->tecnicas, true); // Convertir el JSON a array
        $entrenamiento->configuracion_cancha = json_decode($entrenamiento->configuracion_cancha, true); // Convertir el JSON a array
        return view('entrenamientos.editar', compact('entrenamiento'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $entrenamiento = Entrenamiento::findOrFail($id);
    
        $entrenamiento->nombre = $request->input('nombre');
        $entrenamiento->descripcion = $request->input('descripcion');
        $entrenamiento->min_jugadores = $request->input('min_jugadores');
        $entrenamiento->max_jugadores = $request->input('max_jugadores');
        $entrenamiento->duracion = $request->input('duracion');
        $entrenamiento->comentarios = $request->input('comentarios');
        $entrenamiento->configuracion_cancha = json_decode($request->input('configuracion_cancha'), true);
    
        // Actualizar los materiales y técnicas
        $entrenamiento->materiales = $request->input('materiales');
        $entrenamiento->tecnicas = $request->input('tecnicas');
    
        $entrenamiento->save();
    
        return redirect()->route('entrenamientos.index')->with('success', 'Entrenamiento actualizado correctamente');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(entrenamiento $entrenamiento)
    {
        //
    }


    public function generarPDF($id)
    {
        // Obtener el entrenamiento específico
        $entrenamiento = Entrenamiento::findOrFail($id);
        $entrenamiento->materiales = json_decode($entrenamiento->materiales, true); // Convertir el JSON a array
        $entrenamiento->tecnicas = json_decode($entrenamiento->tecnicas, true); // Convertir el JSON a array
        $entrenamiento->configuracion_cancha = json_decode($entrenamiento->configuracion_cancha, true); // Convertir el JSON a array
    
        // Renderizar la vista del PDF con el entrenamiento específico
        $html = view('entrenamientos.pdf', compact('entrenamiento'))->render();
    
        // Crear una nueva instancia de Dompdf
        $dompdf = new Dompdf();
    
        // Cargar el HTML generado en Dompdf
        $dompdf->loadHtml($html);
    
        // (Opcional) Configurar opciones de Dompdf, como tamaño de página, orientación, etc.
        $dompdf->setPaper('A4', 'portrait');
    
        // Renderizar el PDF
        $dompdf->render();
    
        // Descargar el PDF al navegador
        return $dompdf->stream("entrenamiento_$id.pdf");
    }
    
    

}
