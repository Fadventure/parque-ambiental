<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zona;
// use App\Models\Alerta; // descomentalo cuando tengas el modelo

class AlertaController extends Controller
{
    public function index(Request $request)
    {
        $zonas = Zona::all(); // para el filtro del <select>

        // Por ahora sin datos reales todavía:
        $alertas = collect();

        // Cuando tengas el modelo Alerta, sería algo así:
        // $alertas = Alerta::query()
        //     ->when($request->zona, fn($q) => $q->where('zona_id', $request->zona))
        //     ->when($request->tipo, fn($q) => $q->where('tipo', $request->tipo))
        //     ->when($request->estado === 'atendido', fn($q) => $q->where('atendido', true))
        //     ->when($request->estado === 'no_atendido', fn($q) => $q->where('atendido', false))
        //     ->when($request->fecha, fn($q) => $q->whereDate('created_at', $request->fecha))
        //     ->latest()
        //     ->get();

        return view('alertas.index', compact('zonas', 'alertas'));
    }

    public function atender($id)
    {
        // Alerta::findOrFail($id)->update(['atendido' => true]);

        return back()->with('success', 'Alerta marcada como atendida');
    }
}