<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\EstablecimientoRequest;
class EstablecimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $idUsuario = auth()->id();

        $establecimientos = Establecimiento::with('categoria')->where('user_id', $idUsuario)->paginate(10);
        return response()->json($establecimientos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(EstablecimientoRequest $request)
    {
        $imagen = $request->file('imagen_file');

        $rutaImagen = $imagen->store('img-establecimeintos', 'public');

        $request->merge(['imagen' => $rutaImagen]);

        $establecimiento =  Establecimiento::create($request->all());

        $establecimiento->load('categoria');
        return response()->json([
            'message' => 'Establecimiento creado exitosamente',
            'data' => $establecimiento
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $establecimiento = Establecimiento::findOrFail($id);
        $establecimiento->load('categoria');

        return  response()->json([
            'data' => $establecimiento
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(EstablecimientoRequest $request, string $id)
    {

        //
        $establecimiento = Establecimiento::findOrFail($id);
        if ($establecimiento->user_id != auth('api')->id()) {
            return response()->json([
                'message' => 'No tienes permiso para actualizar este establecimiento'
            ], 403);
        }

        if ($request->hasFile('imagen_file')) {

            $imagenAnterior = $establecimiento->imagen;

            $nuevaImagen=$request->file('imagen_file');

            $rutaImagen = $nuevaImagen->store('img-establecimeintos', 'public');

            $request->merge(['imagen' => $rutaImagen]);
        }

        $establecimiento->update($request->all());
        $establecimiento->load('categoria');

        if(isset($imagenAnterior)){
            Storage::disk('public')->delete($imagenAnterior);
        }



        return response()->json([
            'message' => 'Establecimiento actualizado exitosamente',
            'data' => $establecimiento
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $establecimiento = Establecimiento::findOrFail($id);
        if ($establecimiento->user_id != auth('api')->id()) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este establecimiento'
            ], 403);
        }

        $establecimiento->delete();

        return response()->json([
            'message' => 'Establecimiento eliminado exitosamente'
        ]);
    }



    public function indexPublic(Request $request)
    {

        $establecimientos =  Establecimiento::with('categoria')
        ->when($request->categoria_id, function($query) use ($request){
            $query->where('categoria_id', $request->categoria_id);
        })
        ->paginate(9);

        return  response()->json($establecimientos);

    }
}
