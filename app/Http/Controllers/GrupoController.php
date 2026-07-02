<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol !== 'ADMIN') {
            abort(403, 'No tienes permiso para gestionar grupos.');
        }

        $grupos = Grupo::orderBy('nombre')->paginate(20);

        return view('grupos.index', compact('grupos'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->rol !== 'ADMIN') {
            abort(403);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:50|unique:grupos,nombre',
        ], [
            'nombre.unique' => 'El grupo ya existe.',
            'nombre.required' => 'El nombre del grupo es obligatorio.',
        ]);

        Grupo::create($data);

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo "' . $data['nombre'] . '" creado correctamente.');
    }

    public function destroy(Grupo $grupo)
    {
        if (Auth::user()->rol !== 'ADMIN') {
            abort(403);
        }

        $grupo->delete();

        return redirect()->route('grupos.index')
            ->with('success', 'Grupo eliminado correctamente.');
    }
}
