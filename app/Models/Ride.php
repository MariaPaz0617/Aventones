<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $table = 'rides';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'vehiculo_id',
        'nombre',
        'lugar_salida',
        'lugar_llegada',
        'fecha',
        'hora',
        'costo',
        'cantidad_espacios',
        'creado_en',
        'actualizado_en',
        'activo'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'ride_id');
    }
}


