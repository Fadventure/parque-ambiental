<?php

namespace App\Http\Controllers;

use App\Models\Llamado;
use App\Models\Zona;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    /**
     * Mostrar todas las alertas con filtros.
     */
    public function index(Request $request)
    {
        $zonas = Zona::all(); // para el filtro del <select>

        // 🔽 CONSULTA CON FILTROS PARA EL HISTORIAL
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

        /* ============================================
           🟢 CÓDIGO PARA ARDUINO (guardado para después)
           ============================================ */
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

        // Alerta::findOrFail($id)->update(['atendido' => true]); // ← para Arduino

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

    /* ============================================
       🟢 MÉTODOS PARA ARDUINO (API)
       ============================================ */

    /**
     * Recibir alerta desde Arduino vía API.
     */
    public function recibirDesdeArduino(Request $request)
    {
        // Validar datos del Arduino
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'tipo' => 'required|in:Normal,Emergencia',
            'descripcion' => 'required|string|max:500',
            'temperatura' => 'nullable|numeric',
            'humedad' => 'nullable|numeric',
        ]);

        // Guardar la alerta
        $alerta = Llamado::create([
            'zona_id' => $request->zona_id,
            'user_id' => null, // Viene del sensor, no de un usuario
            'tipo' => $request->tipo,
            'estado' => 'Pendiente',
            'descripcion' => $request->descripcion,
        ]);

        // Actualizar temperatura y humedad en la zona (opcional)
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

    /* ============================================
       🟢 CUANDO TENGAS EL MODELO ALERTA (Arduino)
       ============================================ */
    // public function recibirDesdeArduino(Request $request)
    // {
    //     $alerta = Alerta::create([
    //         'zona_id' => $request->zona_id,
    //         'tipo' => $request->tipo,
    //         'descripcion' => $request->descripcion,
    //         'atendido' => false,
    //         'temperatura' => $request->temperatura,
    //         'humedad' => $request->humedad,
    //     ]);

    //     return response()->json(['success' => true, 'alerta' => $alerta]);
    // }
}