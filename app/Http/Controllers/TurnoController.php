<?php

namespace App\Http\Controllers;

use App\Models\Propiedad;
use App\Models\Visita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnoController extends Controller
{
    // 1. Muestra la pantalla del turnero según el rol del usuario
    public function turnero()
    {
        // Si no inició sesión, vuelve al inicio y activa la apertura del login
        if (!Auth::check()) {
            return redirect()->route('inicio')->with('abrir_login', true);
        }

        $propiedades = Propiedad::orderBy('id')->get();
        $esAdmin = Auth::user()->rol === 'admin';

        return view('pagina.turnero', compact('propiedades', 'esAdmin'));
    }

    // 2. Devuelve los turnos de una propiedad en formato JSON para FullCalendar
    public function eventos($propiedad_id)
    {
        $esAdmin = Auth::user()->rol === 'admin';

        // Buscamos los turnos de esta propiedad desde hoy en adelante
        $query = Visita::where('propiedad_id', $propiedad_id)
            ->whereDate('fecha', '>=', Carbon::today()->toDateString());

        // Si es usuario común, SOLO ve los turnos 'disponible'
        // Si es admin, ve tanto los 'disponible' como los 'confirmada'
        if (!$esAdmin) {
            $query->where('estado', 'disponible');
        } else {
            $query->whereIn('estado', ['disponible', 'confirmada']);
        }

        $turnos = $query->orderBy('fecha')->orderBy('hora')->get();

        // Armamos cada turno con el formato exacto que pide la librería FullCalendar (title y start)
        $eventos = $turnos->map(function ($turno) use ($esAdmin) {
            $horaCorta = Carbon::parse($turno->hora)->format('H:i');
            $fechaObj  = Carbon::parse($turno->fecha);
            $esLibre   = $turno->estado === 'disponible';

            // Texto que se muestra dentro del calendario
            if ($esAdmin) {
                $titulo = $esLibre
                    ? $horaCorta . ' hs (Libre)'
                    : $horaCorta . ' hs — ' . $turno->nombre_cliente;
            } else {
                $titulo = $horaCorta . ' hs';
            }

            return [
                'id'    => $turno->id,
                'title' => $titulo,
                'start' => $fechaObj->format('Y-m-d') . 'T' . Carbon::parse($turno->hora)->format('H:i:s'),
                // Datos extra que leemos en JavaScript al hacer clic en un turno
                'extendedProps' => [
                    'turno_id'    => $turno->id,
                    'estado'      => $turno->estado,
                    'fecha_linda' => $fechaObj->locale('es')->translatedFormat('l d/m/Y') . ' a las ' . $horaCorta . ' hs',
                    'cliente'     => $turno->nombre_cliente,
                    'telefono'    => $turno->telefono_cliente,
                ],
            ];
        });

        return response()->json($eventos);
    }

    // 3. ADMIN: Crea uno o varios horarios disponibles para una fecha y propiedad
    public function habilitar(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['ok' => false, 'mensaje' => 'No autorizado.'], 403);
        }

        $datos = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'fecha'        => 'required|date|after_or_equal:today',
            'horas'        => 'required|array|min:1',
            'horas.*'      => 'required|date_format:H:i',
        ]);

        $creados = 0;

        foreach ($datos['horas'] as $hora) {
            // Evitamos duplicar si ese mismo día y hora ya está cargado
            $yaExiste = Visita::where('propiedad_id', $datos['propiedad_id'])
                ->whereDate('fecha', $datos['fecha'])
                ->where('hora', $hora . ':00')
                ->whereIn('estado', ['disponible', 'confirmada'])
                ->exists();

            if (!$yaExiste) {
                Visita::create([
                    'user_id'       => Auth::id(),
                    'propiedad_id'  => $datos['propiedad_id'],
                    'fecha'         => $datos['fecha'],
                    'hora'          => $hora . ':00',
                    'fecha_elegida' => $datos['fecha'] . ' ' . $hora,
                    'estado'        => 'disponible',
                ]);
                $creados++;
            }
        }

        return response()->json([
            'ok'      => true,
            'mensaje' => $creados > 0
                ? "Se habilitaron {$creados} horario(s) correctamente."
                : 'Esos horarios ya estaban habilitados para esa fecha.',
        ]);
    }

    // 4. ADMIN: Elimina un horario disponible que todavía nadie reservó
    public function eliminar($id)
    {
        if (Auth::user()->rol !== 'admin') {
            return response()->json(['ok' => false, 'mensaje' => 'No autorizado.'], 403);
        }

        $turno = Visita::findOrFail($id);

        if ($turno->estado !== 'disponible') {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'No se puede eliminar un turno que ya fue reservado.',
            ], 422);
        }

        $turno->delete();

        return response()->json([
            'ok'      => true,
            'mensaje' => 'Horario eliminado correctamente.',
        ]);
    }

    // 5. USUARIO: Reserva un turno disponible usando los datos de su cuenta logueada
    public function reservar(Request $request)
    {
        $datos = $request->validate([
            'turno_id' => 'required|exists:visita,id',
        ]);

        // Buscamos el turno verificando que siga 'disponible'
        $turno = Visita::where('id', $datos['turno_id'])
            ->where('estado', 'disponible')
            ->first();

        if (!$turno) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'Este horario acaba de ser reservado por otro usuario.',
            ], 409);
        }

        $usuario = Auth::user();

        // Actualizamos el registro a 'confirmada' con los datos de la cuenta logueada
        $turno->update([
            'user_id'          => $usuario->id,
            'nombre_cliente'   => trim($usuario->name . ' ' . ($usuario->apellido ?? '')),
            'telefono_cliente' => $usuario->telefono ?? 'Sin teléfono registrado',
            'estado'           => 'confirmada',
        ]);

        return response()->json([
            'ok'      => true,
            'mensaje' => '¡Visita confirmada! Un asesor se contactará contigo a tu número para dejar asentado tu turno para visita.',
        ]);
    }
}