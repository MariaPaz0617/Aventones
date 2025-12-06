<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'placa',
        'color',
        'marca',
        'modelo',
        'año',
        'capacidad_asientos',
        'foto',
        'creado_en',
        'actualizado_en'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function rides()
    {
        return $this->hasMany(Ride::class, 'vehiculo_id');
    }
}


