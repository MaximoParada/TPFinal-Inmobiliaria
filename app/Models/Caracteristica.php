<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    public function propiedades()
    {
        return $this->belongsToMany(Propiedad::class, 'caracteristica_propiedad')->withTimestamps();
    }
}
