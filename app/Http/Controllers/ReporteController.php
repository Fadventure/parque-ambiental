<?php

namespace App\Http\Controllers;

use App\Models\Llamado;
use App\Models\Zona;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las zonas para el filtro
        $zonas = Zona::all();

        // Obtener los datos para el resumen
        $totalLlamados = Llamado::count();
        $totalEmergencias = Llamado::where('tipo', 'Emergencia')->count();
        $totalAtendidos = Llamado::where('estado', 'Atendido')->count();
        $porcentajeAtendidos = $totalLlamados > 0 
            ? round(($totalAtendidos / $totalLlamados) * 100) 
            : 0;

        // 📊 Datos para gráficos (agrupados por mes)
        $datosGraficos = $this->obtenerDatosGraficos($request);

        // 📋 Datos para la tabla detalle
        $detalleLlamados = $this->obtenerDetalleLlamados($request);

        return view('reportes.index', compact(
            'zonas',
            'totalLlamados',
            'totalEmergencias',
            'porcentajeAtendidos',
            'datosGraficos',
            'detalleLlamados'
        ));
    }

    /**
     * Exportar reporte a PDF.
     */
    public function exportarPDF(Request $request)
    {
        // Obtener los mismos datos que en el index
        $totalLlamados = Llamado::count();
        $totalEmergencias = Llamado::where('tipo', 'Emergencia')->count();
        $totalAtendidos = Llamado::where('estado', 'Atendido')->count();
        $porcentajeAtendidos = $totalLlamados > 0 
            ? round(($totalAtendidos / $totalLlamados) * 100) 
            : 0;

        $detalleLlamados = $this->obtenerDetalleLlamados($request);
        $datosGraficos = $this->obtenerDatosGraficos($request);

        $pdf = Pdf::loadView('reportes.pdf', compact(
            'totalLlamados',
            'totalEmergencias',
            'porcentajeAtendidos',
            'detalleLlamados',
            'datosGraficos'
        ));

        return $pdf->download('reporte_alertas_' . date('Y-m-d') . '.pdf');
    }

    private function obtenerDatosGraficos(Request $request)
    {
        // Obtener datos agrupados por mes y tipo
        $query = Llamado::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('YEAR(created_at) as año'),
            DB::raw('tipo'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('año', 'mes', 'tipo')
        ->orderBy('año', 'asc')
        ->orderBy('mes', 'asc');

        // Filtrar por zona si se selecciona
        if ($request->filled('zona_id')) {
            $query->where('zona_id', $request->zona_id);
        }

        // Filtrar por año si se selecciona
        if ($request->filled('anio')) {
            $query->whereYear('created_at', $request->anio);
        }

        $resultados = $query->get();

        // Preparar datos para los gráficos
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $emergencias = array_fill(0, 12, 0);
        $normales = array_fill(0, 12, 0);

        foreach ($resultados as $dato) {
            $indice = $dato->mes - 1;
            if ($dato->tipo == 'Emergencia') {
                $emergencias[$indice] = $dato->total;
            } else {
                $normales[$indice] = $dato->total;
            }
        }

        // Datos para gráfico de pastel (distribución por zona)
        $distribucionZonas = Llamado::select(
            'zonas.nombre as zona',
            DB::raw('COUNT(llamados.id) as total')
        )
        ->join('zonas', 'llamados.zona_id', '=', 'zonas.id')
        ->when($request->filled('zona_id'), function ($query) use ($request) {
            return $query->where('llamados.zona_id', $request->zona_id);
        })
        ->groupBy('zonas.nombre')
        ->orderBy('total', 'desc')
        ->get();

        return [
            'meses' => $meses,
            'emergencias' => $emergencias,
            'normales' => $normales,
            'distribucionZonas' => $distribucionZonas,
        ];
    }

    private function obtenerDetalleLlamados(Request $request)
    {
        $query = Llamado::with(['zona', 'usuario'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('zona_id')) {
            $query->where('zona_id', $request->zona_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        return $query->take(50)->get();
    }
}