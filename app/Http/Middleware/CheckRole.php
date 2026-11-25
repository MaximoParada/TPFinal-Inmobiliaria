<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'mensaje' => 'Debés iniciar sesión para acceder.',
                ], 401);
            }

            return redirect()->route('inicio')->with('abrir_login', true);
        }

        /** @var User|null $usuario */
        $usuario = Auth::user();

        if (!$usuario || !in_array($usuario->rol, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'mensaje' => 'No tenés permisos para realizar esta acción.',
                ], 403);
            }

            abort(403, 'No tenés permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}