<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table = 'visita';

    protected $fillable = [
        'user_id',  
        'propiedad_id',
        'nombre_cliente',
        'telefono_cliente',
        'fecha_elegida',
        'fecha',
        'hora',
        'estado',
    ];
        
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);//'propiedad_id'
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
