<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    /**
     * Vista principal con el listado.
     */
    public function index()
    {
        return view('estados.index');
    }

    /**
     * Endpoint REST: devuelve todos los estados en JSON.
     * Consumido vía AJAX por DataTables.
     */
    public function listar(): JsonResponse
    {
        $estados = Estado::select([
            'id', 'cvegeo', 'cve_ent', 'nomgeo',
            'nom_abrev', 'pob_total', 'pob_femenina',
            'pob_masculina', 'total_viviendas_habitadas'
        ])->orderBy('cvegeo')->get();

        return response()->json(['data' => $estados]);
    }

    /**
     * Endpoint REST: devuelve un estado por ID para la modal (AJAX).
     * Solo retorna los primeros 5 campos del metadato INEGI.
     */
    public function show(int $id): JsonResponse
    {
        $estado = Estado::findOrFail($id);

        // Solo los primeros 5 campos del metadato INEGI
        return response()->json([
            'cvegeo'    => $estado->cvegeo,
            'cve_ent'   => $estado->cve_ent,
            'nomgeo'    => $estado->nomgeo,
            'nom_abrev' => $estado->nom_abrev,
            'pob_total' => $estado->pob_total,
        ]);
    }
}
