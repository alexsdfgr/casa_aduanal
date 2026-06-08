<?php
// app/Http/Controllers/PedimentoController.php

namespace App\Http\Controllers;

use App\Models\Pedimento;
use App\Models\ProveedorComprador;
use App\Models\Factura;
use App\Models\IdentificadorPedimento;
use App\Models\TasaPedimento;
use App\Models\CuadroLiquidacion;
use App\Models\PagoElectronico;
use App\Models\AgenteAduanal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedimentoController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Pedimento::with('cuadroLiquidacion')
            ->orderBy('created_at', 'desc');

        // ALUMNO solo ve sus propios pedimentos
        if (Auth::user()->rol === 'ALUMNO') {
            $query->where('usuario_id', Auth::id());
        } elseif (Auth::user()->rol === 'PROFESOR') {
            $query->whereHas('usuario', function ($q) {
                $q->where('profesor_id', Auth::id());
            });
        }

        if ($request->filled('busqueda')) {
            $b = $request->busqueda;
            $query->where(function ($q) use ($b) {
                $q->where('num_pedimento', 'like', "%$b%")
                    ->orWhere('rfc_importador', 'like', "%$b%")
                    ->orWhere('nombre_importador', 'like', "%$b%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $pedimentos = $query->paginate(15)->withQueryString();

        return view('pedimentos.index', compact('pedimentos'));
    }

    // ── CREATE ────────────────────────────────────────────────────────────
    public function create()
    {
        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.index')
                ->with('error', 'Los profesores solo pueden revisar pedimentos.');
        }
        return view('pedimentos.create');
    }

    // ── STORE ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.index')
                ->with('error', 'No tienes permiso para crear pedimentos.');
        }

        $request->validate([
            'pedimento.num_pedimento' => 'required|string|max:20|unique:pedimentos,num_pedimento',
            'pedimento.tipo_operacion' => 'required|in:IMP,EXP',
            'pedimento.cve_pedimento' => 'required|string|max:5',
            'pedimento.regimen' => 'required|string|max:10',
            'pedimento.destino_origen' => 'required|string|max:2',
            'pedimento.tipo_cambio' => 'required|numeric|min:0',
            'pedimento.peso_bruto' => 'required|numeric|min:0',
            'pedimento.aduana_entrada_salida' => 'required|string|max:5',
            'pedimento.rfc_importador' => 'required|string|max:13',
            'pedimento.nombre_importador' => 'required|string|max:200',
            'pedimento.domicilio_importador' => 'required|string|max:400',
            'pedimento.valor_dolares' => 'required|numeric|min:0',
            'pedimento.valor_aduana' => 'required|numeric|min:0',
            'pedimento.precio_pagado_valor_comercial' => 'required|numeric|min:0',
        ], [
            'pedimento.num_pedimento.unique' => 'Ya existe un pedimento con ese número.',
            'pedimento.num_pedimento.required' => 'El número de pedimento es obligatorio.',
            'pedimento.rfc_importador.required' => 'El RFC del importador es obligatorio.',
            'pedimento.nombre_importador.required' => 'El nombre del importador es obligatorio.',
        ]);

        DB::transaction(function () use ($request) {
            // ✅ Usa el guard correcto de tu sistema personalizado
            $dataPed = $request->input('pedimento');
            $dataPed = collect($request->input('pedimento'))
                ->except(['usuario_id'])   // nunca confiar en lo que manda el form
                ->toArray();
            $dataPed['usuario_id'] = Auth::user()->id;


            $pedimento = Pedimento::create($dataPed);

            // Proveedor / Comprador
            $prv = $request->input('proveedor', []);
            if (!empty($prv['id_fiscal']) || !empty($prv['nombre'])) {
                ProveedorComprador::create(array_merge(['pedimento_id' => $pedimento->id], $prv));
            }

            // Factura
            $fac = $request->input('factura', []);
            if (!empty($fac['num_cfdi']) || !empty($fac['num_factura'])) {
                Factura::create(array_merge(['pedimento_id' => $pedimento->id], $fac));
            }

            // Identificadores del pedimento
            foreach ($request->input('identificadores', []) as $ident) {
                if (!empty($ident['clave'])) {
                    IdentificadorPedimento::create(array_merge(
                        ['pedimento_id' => $pedimento->id],
                        $ident
                    ));
                }
            }
            // En store() — agregar DENTRO del DB::transaction, al final antes del cierre
            // Partidas
            foreach ($request->input('partidas', []) as $pt) {
                if (empty($pt['fraccion']))
                    continue;

                $contribs = $pt['contribs'] ?? [];
                $idents = $pt['idents'] ?? [];
                unset($pt['contribs'], $pt['idents']);

                if (empty($pt['descripcion'])) {
                    $pt['descripcion'] = 'SIN DESCRIPCIÓN';
                }

                $partida = $pedimento->partidas()->create($pt);

                foreach ($contribs as $c) {
                    if (!empty($c['con'])) {
                        $partida->contribuciones()->create($c);
                    }
                }

                foreach ($idents as $id) {
                    if (!empty($id['clave'])) {
                        $partida->identificadores()->create($id);
                    }
                }
            }
            // Tasas a nivel pedimento
            foreach ($request->input('tasas', []) as $tasa) {
                if (!empty($tasa['contribucion'])) {
                    TasaPedimento::create(array_merge(
                        ['pedimento_id' => $pedimento->id],
                        $tasa
                    ));
                }
            }

            // Cuadro de liquidación
            $liq = $request->input('liquidacion', []);
            if (!empty(array_filter($liq))) {
                CuadroLiquidacion::create(array_merge(['pedimento_id' => $pedimento->id], $liq));
            }

            // Pago electrónico
            $pag = $request->input('pago', []);
            if (!empty($pag['linea_captura']) || !empty($pag['importe_pagado'])) {
                PagoElectronico::create(array_merge(['pedimento_id' => $pedimento->id], $pag));
            }

            // Agente aduanal
            $ag = $request->input('agente', []);
            if (!empty($ag['nombre']) || !empty($ag['patente'])) {
                AgenteAduanal::create(array_merge(['pedimento_id' => $pedimento->id], $ag));
            }
        });

        // Enviar notificación al profesor si el usuario es alumno y el profesor tiene email
        $usuario = Auth::user();
        if ($usuario->rol === 'ALUMNO' && $usuario->profesor_id) {
            $profesor = \App\Models\Usuario::find($usuario->profesor_id);
            if ($profesor && $profesor->email) {
                // Hay que obtener el pedimento con el num_pedimento para pasarlo al Mailable
                $pedimentoNotificar = \App\Models\Pedimento::where('num_pedimento', $request->input('pedimento.num_pedimento'))->first();
                if ($pedimentoNotificar) {
                    \Illuminate\Support\Facades\Mail::to($profesor->email)->send(new \App\Mail\NotificacionPedimento($pedimentoNotificar));
                }
            }
        }

        return redirect()->route('pedimentos.index')
            ->with('success', 'Pedimento creado correctamente.');
    }

    // ── SHOW ──────────────────────────────────────────────────────────────
    public function show(Pedimento $pedimento)
    {
        $this->verificarAcceso($pedimento);

        $pedimento->load([
            'partidas.contribuciones',
            'partidas.identificadores',
            'proveedores',
            'facturas',
            'identificadores',
            'tasas',
            'cuadroLiquidacion',
            'pagoElectronico',
            'agente',
        ]);

        return view('pedimentos.show', compact('pedimento'));
    }

    // ── EDIT ──────────────────────────────────────────────────────────────
    public function edit(Pedimento $pedimento)
    {
        $this->verificarAcceso($pedimento);

        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.show', $pedimento)
                ->with('error', 'Los profesores no pueden editar pedimentos.');
        }

        $pedimento->load([
            'proveedores',
            'facturas',
            'identificadores',
            'tasas',
            'cuadroLiquidacion',
            'pagoElectronico',
            'agente',
        ]);

        return view('pedimentos.edit', compact('pedimento'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────
    public function update(Request $request, Pedimento $pedimento)
    {
        $this->verificarAcceso($pedimento);

        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.show', $pedimento)
                ->with('error', 'No tienes permiso para editar pedimentos.');
        }

        $request->validate([
            'pedimento.num_pedimento' => 'required|string|max:20|unique:pedimentos,num_pedimento,' . $pedimento->id,
            'pedimento.tipo_operacion' => 'required|in:IMP,EXP',
            'pedimento.regimen' => 'required|string|max:10',
            'pedimento.rfc_importador' => 'required|string|max:13',
            'pedimento.nombre_importador' => 'required|string|max:200',
            'pedimento.domicilio_importador' => 'required|string|max:400',
            'pedimento.valor_dolares' => 'required|numeric|min:0',
            'pedimento.valor_aduana' => 'required|numeric|min:0',
            'pedimento.precio_pagado_valor_comercial' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $pedimento) {
            $dataUpdate = collect($request->input('pedimento'))
                ->except(['usuario_id'])
                ->toArray();
            $pedimento->update($dataUpdate);
            // Reemplazar relaciones
            $pedimento->proveedores()->delete();
            $prv = $request->input('proveedor', []);
            if (!empty($prv['id_fiscal']) || !empty($prv['nombre'])) {
                ProveedorComprador::create(array_merge(['pedimento_id' => $pedimento->id], $prv));
            }

            $pedimento->facturas()->delete();
            $fac = $request->input('factura', []);
            if (!empty($fac['num_cfdi']) || !empty($fac['num_factura'])) {
                Factura::create(array_merge(['pedimento_id' => $pedimento->id], $fac));
            }

            $pedimento->identificadores()->delete();
            foreach ($request->input('identificadores', []) as $ident) {
                if (!empty($ident['clave'])) {
                    IdentificadorPedimento::create(array_merge(
                        ['pedimento_id' => $pedimento->id],
                        $ident
                    ));
                }
            }

            $pedimento->tasas()->delete();
            foreach ($request->input('tasas', []) as $tasa) {
                if (!empty($tasa['contribucion'])) {
                    TasaPedimento::create(array_merge(
                        ['pedimento_id' => $pedimento->id],
                        $tasa
                    ));
                }
            }

            $pedimento->cuadroLiquidacion()->delete();
            $liq = $request->input('liquidacion', []);
            if (!empty(array_filter($liq))) {
                CuadroLiquidacion::create(array_merge(['pedimento_id' => $pedimento->id], $liq));
            }

            $pedimento->pagoElectronico()->delete();
            $pag = $request->input('pago', []);
            if (!empty($pag['linea_captura']) || !empty($pag['importe_pagado'])) {
                PagoElectronico::create(array_merge(['pedimento_id' => $pedimento->id], $pag));
            }

            $pedimento->agente()->delete();
            $ag = $request->input('agente', []);
            if (!empty($ag['nombre']) || !empty($ag['patente'])) {
                AgenteAduanal::create(array_merge(['pedimento_id' => $pedimento->id], $ag));
            }
            $pedimento->partidas()->each(function ($p) {
                $p->contribuciones()->delete();
                $p->identificadores()->delete();
                $p->delete();
            });
            foreach ($request->input('partidas', []) as $pt) {
                if (empty($pt['fraccion']))
                    continue;

                $contribs = $pt['contribs'] ?? [];
                $idents = $pt['idents'] ?? [];
                unset($pt['contribs'], $pt['idents']);

                if (empty($pt['descripcion'])) {
                    $pt['descripcion'] = 'SIN DESCRIPCIÓN';
                }

                $partida = $pedimento->partidas()->create($pt);

                foreach ($contribs as $c) {
                    if (!empty($c['con'])) {
                        $partida->contribuciones()->create($c);
                    }
                }

                foreach ($idents as $id) {
                    if (!empty($id['clave'])) {
                        $partida->identificadores()->create($id);
                    }
                }
            }
        });

        return redirect()->route('pedimentos.show', $pedimento)
            ->with('success', 'Pedimento actualizado correctamente.');
    }

    // ── DESTROY ───────────────────────────────────────────────────────────
    public function destroy(Pedimento $pedimento)
    {
        $this->verificarAcceso($pedimento);

        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.index')
                ->with('error', 'No tienes permiso para eliminar pedimentos.');
        }

        $pedimento->delete();

        return redirect()->route('pedimentos.index')
            ->with('success', 'Pedimento eliminado correctamente.');
    }

    // ── DASHBOARD (llamado desde web.php) ─────────────────────────────────
    public function dashboard()
    {
        $user = Auth::user();
        $query = Pedimento::query();

        if ($user->rol === 'ALUMNO') {
            $query->where('usuario_id', $user->id);
        } elseif ($user->rol === 'PROFESOR') {
            $query->whereHas('usuario', function ($q) use ($user) {
                $q->where('profesor_id', $user->id);
            });
        }

        $stats = [
            'total' => (clone $query)->count(),
            'borrador' => (clone $query)->where('estado', 'borrador')->count(),
            'transmitido' => (clone $query)->where('estado', 'transmitido')->count(),
            'pagado' => (clone $query)->where('estado', 'pagado')->count(),
            'liberado' => (clone $query)->where('estado', 'liberado')->count(),
        ];

        $recientes = (clone $query)
            ->with('cuadroLiquidacion')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard', compact('stats', 'recientes'));
    }

    public function liberar(Pedimento $pedimento)
    {
        $this->verificarAcceso($pedimento);

        if (Auth::user()->rol !== 'PROFESOR' && Auth::user()->rol !== 'ADMIN') {
            abort(403, 'No tienes permiso para liberar pedimentos.');
        }

        $pedimento->estado = 'liberado';
        $pedimento->save();

        return back()->with('success', 'Pedimento marcado como liberado correctamente.');
    }

    // ── Helper ────────────────────────────────────────────────────────────
    private function verificarAcceso(Pedimento $pedimento): void
    {
        if (Auth::user()->rol === 'ALUMNO' && $pedimento->usuario_id !== Auth::id()) {
            abort(403, 'No tienes acceso a este pedimento.');
        } elseif (Auth::user()->rol === 'PROFESOR' && $pedimento->usuario->profesor_id !== Auth::id()) {
            abort(403, 'No tienes acceso a los pedimentos de este alumno.');
        }
    }
}
