<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use Symfony\Component\HttpKernel\HttpCache\Esi;

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
    public function store(Request $request)
    {

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
    public function update(Request $request, string $id)
    {
        //
        $establecimiento = Establecimiento::findOrFail($id);
        if ($establecimiento->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No tienes permiso para actualizar este establecimiento'
            ], 403);
        }

        $establecimiento->update($request->all());
        $establecimiento->load('categoria');



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
        if ($establecimiento->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este establecimiento'
            ], 403);
        }

        $establecimiento->delete();

        return response()->json([
            'message' => 'Establecimiento eliminado exitosamente'
        ]);
    }
}
