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
            'operacion' => ['required', 'string', 'max:50'],
            'direccion' => ['required', 'string', 'max:255'],
            'zona' => ['required', 'string', 'max:255'],
            'ambientes' => ['required', 'integer', 'min:1'],
            'dormitorios' => ['required', 'integer', 'min:0'],
            'metros_cuadrados' => ['required', 'integer', 'min:1'],
            'precio' => ['required', 'numeric', 'min:0'],
            'expensas' => ['nullable', 'numeric', 'min:0'],
            'caracteristicas' => ['nullable', 'array'],
            'caracteristicas.*' => ['integer', 'exists:caracteristicas,id'],
        ]);

        $rutas = [];
        if ($request->hasFile('imagenes')) {
            $destino = public_path('img/propiedades');
            if (!is_dir($destino)) {
                mkdir($destino, 0755, true);
            }
            foreach ($request->file('imagenes') as $archivo) {
                if ($archivo && $archivo->isValid()) {
                    $nombre = time() . '_' . uniqid() . '.' . strtolower($archivo->getClientOriginalExtension() ?: 'jpg');
                    $archivo->move($destino, $nombre);
                    $rutas[] = 'img/propiedades/' . $nombre;
                }
            }
        }

        $propiedad = Propiedad::create([
            'titulo' => $validated['titulo'],
            'operacion' => $validated['operacion'],
            'direccion' => $validated['direccion'],
            'zona' => $validated['zona'],
            'ambientes' => $validated['ambientes'],
            'dormitorios' => $validated['dormitorios'],
            'metros_cuadrados' => $validated['metros_cuadrados'],
            'precio' => $validated['precio'],
            'expensas' => $validated['expensas'] ?? 0,
            'imagenes' => $rutas,
        ]);

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
        if ($request->hasFile('imagenes')) {
            $rutas = [];
            $destino = public_path('img/propiedades');

            if (!is_dir($destino)) {
                mkdir($destino, 0755, true);
            }

            foreach ($request->file('imagenes') as $archivo) {
                if ($archivo && $archivo->isValid()) {
                    $nombre = time() . '_' . uniqid() . '.' . strtolower($archivo->getClientOriginalExtension() ?: 'jpg');
                    $archivo->move($destino, $nombre);
                    $rutas[] = 'img/propiedades/' . $nombre;
                }
            }

            $propiedad->imagenes = $rutas;
            $propiedad->save();
        }

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