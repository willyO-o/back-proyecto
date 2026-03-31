<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    //
    // protected $primaryKey = 'id';
    protected $table = 'establecimiento';
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'imagen',
        'telefono',
        'email',
        'website',
        'horario_apertura',
        'horario_cierre',
        'latitud',
        'longitud',
        'estado',
        'categoria_id',
        'user_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    static function boot()
    {
        parent::boot();

        static::creating(function($establecimiento){
            $establecimiento->user_id = auth('api')->user()->id;
        });

    }

}
