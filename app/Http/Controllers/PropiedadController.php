<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Propiedad;
use Illuminate\Http\Request;

class PropiedadController extends Controller
{
    public function create()
    {
        $caracteristicas = Caracteristica::orderBy('nombre')->get();
        return view('pagina.crearPropiedad', compact('caracteristicas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'caracteristicas' => ['nullable', 'array'],
            'caracteristicas.*' => ['integer', 'exists:caracteristicas,id'],
        ]);

        // Ajustá estos campos a las columnas reales de tu tabla propiedades
        $propiedad = Propiedad::create([
            'titulo' => $validated['titulo'],
            'precio' => $validated['precio'],
        ]);

        // Asocia características en la tabla intermedia caracteristica_propiedad
        $propiedad->caracteristicas()->sync($validated['caracteristicas'] ?? []);

        return redirect('/')->with('success', 'Propiedad creada correctamente.');
    }

    public function edit(Propiedad $propiedad)
    {
        $caracteristicas = Caracteristica::orderBy('nombre')->get();
        $seleccionadas = $propiedad->caracteristicas()->pluck('caracteristicas.id')->toArray();

        return view('pagina.editarPropiedad', compact('propiedad', 'caracteristicas', 'seleccionadas'));
    }

    public function update(Request $request, Propiedad $propiedad)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'precio' => ['required', 'numeric', 'min:0'],
            'caracteristicas' => ['nullable', 'array'],
            'caracteristicas.*' => ['integer', 'exists:caracteristicas,id'],
        ]);

        // Ajustá estos campos a las columnas reales de tu tabla propiedades
        $propiedad->update([
            'titulo' => $validated['titulo'],
            'precio' => $validated['precio'],
        ]);

        // Actualiza (asocia y desasocia) características en la tabla intermedia
        $propiedad->caracteristicas()->sync($validated['caracteristicas'] ?? []);

        return redirect('/')->with('success', 'Propiedad actualizada correctamente.');
    }

    public function destroy(Propiedad $propiedad)
    {
        $propiedad->caracteristicas()->detach();
        $propiedad->delete();

        return redirect('/')->with('success', 'Propiedad eliminada correctamente.');
    }
}