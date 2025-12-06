<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivationToken extends Model
{
    protected $table = 'activation_token';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'token',
        'creado_en',
        'usado'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

