<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    public $timestamps = false;

    protected $fillable = [
        'ride_id',
        'pasajero_id',
        'fecha_solicitud',
        'cantidad_asientos',
        'estado',
        'comentario',
        'actualizado_en',
        'notificado'
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class, 'ride_id');
    }

    public function pasajero()
    {
        return $this->belongsTo(Usuario::class, 'pasajero_id');
    }

    
}


