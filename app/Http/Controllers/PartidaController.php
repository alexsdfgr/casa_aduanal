<?php
// app/Http/Controllers/PartidaController.php

namespace App\Http\Controllers;

use App\Models\Pedimento;
use App\Models\Partida;
use App\Models\ContribucionPartida;
use App\Models\IdentificadorPartida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartidaController extends Controller
{
    public function create(Pedimento $pedimento)
    {
        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.show', $pedimento)
                ->with('error', 'Los profesores no pueden agregar partidas.');
        }
        if (Auth::user()->rol === 'ALUMNO' && $pedimento->usuario_id !== Auth::id()) {
            abort(403);
        }
        return view('partidas.create', compact('pedimento'));
    }

    public function store(Request $request, Pedimento $pedimento)
    {
        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.show', $pedimento)
                ->with('error', 'No tienes permiso para agregar partidas.');
        }
        if (Auth::user()->rol === 'ALUMNO' && $pedimento->usuario_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'fraccion_arancelaria'   => 'required|string|max:10',
            'pais_origen_destino'    => 'required|string|max:5',
            'umc'                    => 'required|string|max:5',
            'cantidad_umc'           => 'required|numeric|min:0',
            'precio_valor_comercial' => 'required|numeric|min:0',
            'val_aduana_usd'         => 'required|numeric|min:0',
            'importe_precio_pagado'  => 'required|numeric|min:0',
            'precio_unitario'        => 'required|numeric|min:0',
            'descripcion'            => 'required|string',
        ]);

        DB::transaction(function () use ($request, $pedimento) {
            $secuencia = ($pedimento->partidas()->max('secuencia') ?? 0) + 1;

            $partida = Partida::create(array_merge(
                $request->except(['contribuciones', 'identificadores', '_token', '_method']),
                ['pedimento_id' => $pedimento->id, 'secuencia' => $secuencia]
            ));

            foreach ($request->input('contribuciones', []) as $c) {
                if (!empty($c['contribucion'])) {
                    ContribucionPartida::create(array_merge(['partida_id' => $partida->id], $c));
                }
            }

            foreach ($request->input('identificadores', []) as $ident) {
                if (!empty($ident['clave'])) {
                    IdentificadorPartida::create(array_merge(['partida_id' => $partida->id], $ident));
                }
            }
        });

        return redirect()->route('pedimentos.show', $pedimento)
            ->with('success', 'Partida agregada correctamente.');
    }

    public function edit(Pedimento $pedimento, Partida $partida)
    {
        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.show', $pedimento)
                ->with('error', 'Los profesores no pueden editar partidas.');
        }
        if (Auth::user()->rol === 'ALUMNO' && $pedimento->usuario_id !== Auth::id()) {
            abort(403);
        }
        $partida->load('contribuciones', 'identificadores');
        return view('partidas.edit', compact('pedimento', 'partida'));
    }

    public function update(Request $request, Pedimento $pedimento, Partida $partida)
    {
        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.show', $pedimento)
                ->with('error', 'No tienes permiso para editar partidas.');
        }

        $request->validate([
            'fraccion_arancelaria'   => 'required|string|max:10',
            'pais_origen_destino'    => 'required|string|max:5',
            'umc'                    => 'required|string|max:5',
            'cantidad_umc'           => 'required|numeric|min:0',
            'precio_valor_comercial' => 'required|numeric|min:0',
            'val_aduana_usd'         => 'required|numeric|min:0',
            'importe_precio_pagado'  => 'required|numeric|min:0',
            'precio_unitario'        => 'required|numeric|min:0',
            'descripcion'            => 'required|string',
        ]);

        DB::transaction(function () use ($request, $partida) {
            $partida->update(
                $request->except(['contribuciones', 'identificadores', '_token', '_method'])
            );

            $partida->contribuciones()->delete();
            foreach ($request->input('contribuciones', []) as $c) {
                if (!empty($c['contribucion'])) {
                    ContribucionPartida::create(array_merge(['partida_id' => $partida->id], $c));
                }
            }

            $partida->identificadores()->delete();
            foreach ($request->input('identificadores', []) as $ident) {
                if (!empty($ident['clave'])) {
                    IdentificadorPartida::create(array_merge(['partida_id' => $partida->id], $ident));
                }
            }
        });

        return redirect()->route('pedimentos.show', $pedimento)
            ->with('success', 'Partida actualizada correctamente.');
    }

    public function destroy(Pedimento $pedimento, Partida $partida)
    {
        if (Auth::user()->rol === 'PROFESOR') {
            return redirect()->route('pedimentos.show', $pedimento)
                ->with('error', 'No tienes permiso para eliminar partidas.');
        }
        if (Auth::user()->rol === 'ALUMNO' && $pedimento->usuario_id !== Auth::id()) {
            abort(403);
        }

        $partida->delete();

        return redirect()->route('pedimentos.show', $pedimento)
            ->with('success', 'Partida eliminada correctamente.');
    }
}
