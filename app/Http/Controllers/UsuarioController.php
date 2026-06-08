<?php
// app/Http/Controllers/UsuarioController.php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────
    public function index()
    {
        // ADMIN ve todos / PROFESOR solo ve alumnos
        if (auth()->user()->rol === 'ADMIN') {
            $usuarios = Usuario::orderBy('rol')->orderBy('nombre')->paginate(20);
        } elseif (auth()->user()->rol === 'PROFESOR') {
            $usuarios = Usuario::where('rol', 'ALUMNO')
                               ->where('profesor_id', auth()->id())
                               ->orderBy('nombre')
                               ->paginate(20);
        } else {
            abort(403, 'No tienes permiso.');
        }

        return view('usuarios.index', compact('usuarios'));
    }

    // ── CREATE ────────────────────────────────────────────
    public function create()
    {
        if (!in_array(auth()->user()->rol, ['ADMIN', 'PROFESOR'])) {
            abort(403, 'No tienes permiso para crear usuarios.');
        }
        return view('usuarios.create');
    }

    // ── STORE ─────────────────────────────────────────────
    public function store(Request $request)
    {
        if (!in_array(auth()->user()->rol, ['ADMIN', 'PROFESOR'])) {
            abort(403);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:200',
            'username' => 'required|string|max:60|unique:usuarios,username',
            'password' => 'required|string|min:6|confirmed',
            'rol' => auth()->user()->rol === 'PROFESOR' ? ['required', Rule::in(['ALUMNO'])] : ['required', Rule::in(['ADMIN', 'PROFESOR', 'ALUMNO'])],
            'grupo' => auth()->user()->rol === 'PROFESOR' ? 'required|string|max:50' : 'nullable|string|max:50',
        ]);

        $profesor_id = null;
        if (auth()->user()->rol === 'PROFESOR') {
            $data['rol'] = 'ALUMNO';
            $profesor_id = auth()->id();
        }

        Usuario::create([
            'nombre' => $data['nombre'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'rol' => $data['rol'],
            'activo' => true,
            'profesor_id' => $profesor_id,
            'grupo' => $data['grupo'] ?? null,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario "' . $data['username'] . '" creado correctamente.');
    }

    // ── EDIT ──────────────────────────────────────────────
    public function edit(Usuario $usuario)
    {
        if (auth()->user()->rol === 'PROFESOR' && $usuario->profesor_id !== auth()->id()) {
            abort(403, 'Solo puedes editar a tus propios alumnos.');
        } elseif (auth()->user()->rol === 'ALUMNO' || (auth()->user()->rol === 'PROFESOR' && $usuario->rol !== 'ALUMNO')) {
            abort(403);
        }
        return view('usuarios.create', compact('usuario'));
    }

    // ── UPDATE ────────────────────────────────────────────
    public function update(Request $request, Usuario $usuario)
    {
        if (auth()->user()->rol === 'PROFESOR' && $usuario->profesor_id !== auth()->id()) {
            abort(403, 'Solo puedes editar a tus propios alumnos.');
        } elseif (auth()->user()->rol === 'ALUMNO') {
            abort(403);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:200',
            'username' => [
                'required',
                'string',
                'max:60',
                Rule::unique('usuarios', 'username')->ignore($usuario->id)
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => auth()->user()->rol === 'PROFESOR' ? ['required', Rule::in(['ALUMNO'])] : ['required', Rule::in(['ADMIN', 'PROFESOR', 'ALUMNO'])],
            'activo' => 'boolean',
            'grupo' => auth()->user()->rol === 'PROFESOR' ? 'required|string|max:50' : 'nullable|string|max:50',
        ]);

        $usuario->nombre = $data['nombre'];
        $usuario->username = $data['username'];
        if (auth()->user()->rol === 'ADMIN') {
            $usuario->rol = $data['rol'];
        }
        $usuario->activo = $request->boolean('activo', true);
        $usuario->grupo = $data['grupo'] ?? null;

        if (!empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // ── DESTROY ───────────────────────────────────────────
    public function destroy(Usuario $usuario)
    {
        if (auth()->user()->rol === 'PROFESOR' && $usuario->profesor_id !== auth()->id()) {
            abort(403, 'Solo puedes eliminar a tus propios alumnos.');
        } elseif (auth()->user()->rol === 'ALUMNO') {
            abort(403);
        }

        if ($usuario->rol === 'ADMIN' && Usuario::where('rol', 'ADMIN')->count() <= 1) {
            return back()->with('error', 'No se puede eliminar el único administrador.');
        }

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}