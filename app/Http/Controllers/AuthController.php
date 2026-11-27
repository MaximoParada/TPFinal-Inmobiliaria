<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Ingresá tu correo electrónico.',
            'email.email' => 'Ingresá un correo electrónico válido.',
            'password.required' => 'Ingresá tu contraseña.',
        ]);

        if (!Auth::attempt($credenciales, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales ingresadas no son correctas.'],
            ]);
        }

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('inicio'),
            ]);
        }

        return redirect()->route('inicio');
    }

    public function register(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                Password::min(6)->mixedCase()->numbers(),
            ],
        ], [
            'name.required' => 'Ingresá tu nombre.',
            'apellido.required' => 'Ingresá tu apellido.',
            'telefono.required' => 'Ingresá tu teléfono.',
            'email.required' => 'Ingresá tu correo electrónico.',
            'email.email' => 'Ingresá un correo válido.',
            'email.unique' => 'Ese correo ya está registrado.',
            'password.required' => 'Ingresá una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.mixed' => 'La contraseña debe incluir al menos una letra mayúscula.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
        ]);

        $usuario = User::create([
            'name' => trim($datos['name']),
            'apellido' => trim($datos['apellido']),
            'telefono' => trim($datos['telefono']),
            'email' => strtolower(trim($datos['email'])),
            'password' => $datos['password'],
            'rol' => 'user',
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('inicio'),
            ], 201);
        }

        return redirect()->route('inicio');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'redirect' => route('inicio'),
            ]);
        }

        return redirect()->route('inicio');
    }
}