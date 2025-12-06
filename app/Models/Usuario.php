<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'rol_id',
        'nombre',
        'apellido',
        'cedula',
        'fecha_nacimiento',
        'email',
        'telefono',
        'foto',
        'password',
        'estado',
        'creado_en',
        'actualizado_en',
        'activo',
        'token'
    ];

    // Relaciones

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'usuario_id');
    }

    public function rides()
    {
        return $this->hasMany(Ride::class, 'usuario_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'pasajero_id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'usuario_id');
    }

    public function activationToken()
    {
        return $this->hasOne(ActivationToken::class, 'usuario_id');
    }
}


