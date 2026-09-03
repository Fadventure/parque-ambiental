<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'humedad' => 'required|numeric',
            'temperatura' => 'required|numeric',
        ]);

        $zona = Zona::findOrFail($validated['zona_id']);

        $zona->update([
            'humedad' => $validated['humedad'],
            'temperatura' => $validated['temperatura'],
            'tiene_alerta' => $validated['temperatura'] > 40 || $validated['humedad'] < 30,
        ]);

        return response()->json(['ok' => true]);
    }
}