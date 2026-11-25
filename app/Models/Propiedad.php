<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    protected $table = 'propiedades';

    protected $fillable = [
        'titulo',
        'operacion',
        'direccion',
        'zona',
        'ambientes',
        'dormitorios',
        'metros_cuadrados',
        'precio',
        'expensas',
        'imagenes'
    ];

    // Convierte automáticamente el JSON de la BD en un array de PHP
    protected $casts = [
        'imagenes' => 'array'
    ];

    // Relación 1 a Muchos: Una propiedad tiene muchas visitas
    public function visitas()
    {
        return $this->hasMany(Visita::class);//'propiedad_id'
    }
   
    public function getImagenesListaAttribute(): array
    {
        $imgs = $this->imagenes;

       // Si en la BD quedó guardado como string JSON por algún motivo, lo decodifica
       if (is_string($imgs)) {
           $decoded = json_decode($imgs, true);
           $imgs = json_last_error() === JSON_ERROR_NONE ? $decoded : [$imgs];
        }

       if (is_array($imgs) && count($imgs) > 0) {
           return array_values(array_filter($imgs));
        }

       return ['img/sin-imagen.jpg'];
    }

    public function caracteristicas()
    {
        return $this->belongsToMany(Caracteristica::class, 'caracteristica_propiedad')->withTimestamps();
    }
}
