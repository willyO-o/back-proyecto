<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    //

    protected $table =  'servicio';
    protected $fillable = [
        'establecimiento_id',
        'nombre_servicio',
        'descripcion_servicio',
        'precio',
        'tipo',
        'icono',
        'disponible',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'disponible' => 'boolean',

    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
