<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;


class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $disponible = empty($request->input('disponible', 0)) ? 0 : 1;

        $request->merge(['disponible' => $disponible]);
        $servicio = Servicio::create($request->all());
        return response()->json([
            'message' => 'Servicio creado exitosamente',
            'data' => $servicio
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $servicio =  Servicio::findOrFail($id);
        return response()->json([
            'data' => $servicio
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //


        $disponible = empty($request->input('disponible', 0)) ? 0 : 1;
        $request->merge(['disponible' => $disponible]);
        $servicio =  Servicio::findOrFail($id);
        $servicio->update($request->all());
        return response()->json([
            'message' => 'Servicio actualizado exitosamente',
            'data' => $servicio
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $servicio =  Servicio::findOrFail($id);
        $servicio->delete();
        return response()->json([
            'message' => 'Servicio eliminado  exitosamente',
            'data' => $servicio
        ]);
    }
}
