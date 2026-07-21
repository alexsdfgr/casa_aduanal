<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->rol === 'ALUMNO') {
            $empresas = Empresa::where('usuario_id', $user->id)->latest()->paginate(15);
        } else {
            $empresas = Empresa::with('usuario')->latest()->paginate(15);
        }

        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:200',
            'razon_social' => 'required|string|max:200',
            'id_fiscal'    => 'nullable|string|max:50',
            'rfc'          => 'nullable|string|max:20',
            'domicilio'    => 'required|string|max:400',
            'pais'         => 'required|string|max:100',
            'tipo'         => 'required|in:COMPRADOR,VENDEDOR,COMPRADOR/VENDEDOR',
        ], [
            'nombre.required'       => 'El nombre de la empresa es obligatorio.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'domicilio.required'    => 'El domicilio es obligatorio.',
            'pais.required'         => 'El país es obligatorio.',
            'tipo.required'         => 'Seleccione si es Comprador o Vendedor.',
            'tipo.in'               => 'El tipo de empresa no es válido.',
        ]);

        $data['usuario_id'] = Auth::id();

        Empresa::create($data);

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa "' . $data['nombre'] . '" registrada correctamente.');
    }

    public function destroy(Empresa $empresa)
    {
        $user = Auth::user();
        if ($user->rol === 'ALUMNO' && $empresa->usuario_id !== $user->id) {
            abort(403, 'No tienes permiso para eliminar esta empresa.');
        }

        $empresa->delete();

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }
}
