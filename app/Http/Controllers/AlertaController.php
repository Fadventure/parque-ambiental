<?php

namespace App\Http\Controllers;

use App\Models\Llamado;
use App\Models\Zona;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    /**
     * Mostrar todas las alertas con filtros.
     */
    public function index(Request $request)
    {
        $zonas = Zona::all();

        $alertas = Llamado::with(['zona', 'usuario'])
            ->when($request->zona_id, function ($query) use ($request) {
                return $query->where('zona_id', $request->zona_id);
            })
            ->when($request->tipo, function ($query) use ($request) {
                return $query->where('tipo', $request->tipo);
            })
            ->when($request->estado, function ($query) use ($request) {
                return $query->where('estado', $request->estado);
            })
            ->when($request->fecha, function ($query) use ($request) {
                return $query->whereDate('created_at', $request->fecha);
            })
            ->latest()
            ->get();

        return view('alertas.index', compact('zonas', 'alertas'));
    }

    /**
     * Guardar una nueva alerta (desde el panel web).
     */
    public function store(Request $request)
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'tipo' => 'required|in:Normal,Emergencia',
            'descripcion' => 'required|string|max:500',
        ]);

        Llamado::create([
            'zona_id' => $request->zona_id,
            'user_id' => auth()->id(),
            'tipo' => $request->tipo,
            'estado' => 'Pendiente',
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('alertas.index')
            ->with('success', 'Alerta reportada exitosamente.');
    }

    /**
     * Marcar una alerta como atendida.
     */
    public function atender($id)
    {
        $alerta = Llamado::findOrFail($id);
        $alerta->update(['estado' => 'Atendido']);

        return redirect()->route('alertas.index')
            ->with('success', 'Alerta marcada como atendida.');
    }

    /**
     * Eliminar una alerta (solo admin).
     */
    public function destroy($id)
    {
        $alerta = Llamado::findOrFail($id);
        $alerta->delete();

        return redirect()->route('alertas.index')
            ->with('success', 'Alerta eliminada.');
    }

    /**
     * Exportar alertas a PDF.
     */
    public function exportarPDF(Request $request)
    {
        $alertas = Llamado::with(['zona', 'usuario'])
            ->when($request->zona_id, function ($query) use ($request) {
                return $query->where('zona_id', $request->zona_id);
            })
            ->when($request->tipo, function ($query) use ($request) {
                return $query->where('tipo', $request->tipo);
            })
            ->when($request->estado, function ($query) use ($request) {
                return $query->where('estado', $request->estado);
            })
            ->when($request->fecha, function ($query) use ($request) {
                return $query->whereDate('created_at', $request->fecha);
            })
            ->latest()
            ->get();

        $totalAlertas = $alertas->count();
        $emergencias = $alertas->where('tipo', 'Emergencia')->count();
        $atendidas = $alertas->where('estado', 'Atendido')->count();

        $pdf = Pdf::loadView('alertas.pdf', compact(
            'alertas',
            'totalAlertas',
            'emergencias',
            'atendidas'
        ));

        return $pdf->download('reporte_alertas_' . date('Y-m-d') . '.pdf');
    }

    /* ============================================
       🟢 MÉTODOS PARA ARDUINO (API)
       ============================================ */

    /**
     * Recibir alerta desde Arduino vía API.
     */
    public function recibirDesdeArduino(Request $request)
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'tipo' => 'required|in:Normal,Emergencia',
            'descripcion' => 'required|string|max:500',
            'temperatura' => 'nullable|numeric',
            'humedad' => 'nullable|numeric',
        ]);

        $alerta = Llamado::create([
            'zona_id' => $request->zona_id,
            'user_id' => null,
            'tipo' => $request->tipo,
            'estado' => 'Pendiente',
            'descripcion' => $request->descripcion,
        ]);

        if ($request->has('temperatura') || $request->has('humedad')) {
            $zona = Zona::find($request->zona_id);
            if ($zona) {
                $zona->update([
                    'temperatura' => $request->temperatura ?? $zona->temperatura,
                    'humedad' => $request->humedad ?? $zona->humedad,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Alerta recibida desde Arduino',
            'alerta' => $alerta
        ], 201);
    }

    /**
     * Obtener alertas para Arduino (últimas 10).
     */
    public function obtenerParaArduino()
    {
        $alertas = Llamado::with(['zona'])
            ->where('estado', 'Pendiente')
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'alertas' => $alertas
        ]);
    }
}