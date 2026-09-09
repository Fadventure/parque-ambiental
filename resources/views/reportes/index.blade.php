<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reportes Estadísticos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- FILTROS --}}
            <form method="GET" class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Zona</label>
                    <select name="zona_id" class="border border-gray-300 rounded-md text-sm px-3 py-2">
                        <option value="">Todas</option>
                        @foreach($zonas ?? [] as $zona)
                            <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>
                                {{ $zona->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Año</label>
                    <select name="anio" class="border border-gray-300 rounded-md text-sm px-3 py-2">
                        <option value="">Todos</option>
                        @for($i = now()->year; $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ request('anio') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                    Filtrar
                </button>
                <a href="{{ route('reportes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium px-4 py-2 rounded-md">
                    Limpiar
                </a>
            </form>

            {{-- TARJETAS DE RESUMEN --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
                    <p class="text-xs font-semibold text-gray-500 tracking-wide">TOTAL LLAMADOS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalLlamados }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-red-500">
                    <p class="text-xs font-semibold text-gray-500 tracking-wide">EMERGENCIAS</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $totalEmergencias }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500">
                    <p class="text-xs font-semibold text-gray-500 tracking-wide">ATENDIDOS</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $porcentajeAtendidos }}%</p>
                </div>
            </div>

            {{-- GRÁFICOS --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Llamados por mes</h3>

                {{-- Selector de tipo de gráfico --}}
                <div class="flex gap-2 mb-4">
                    <button onclick="cambiarGrafico('barras')" class="px-3 py-1 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        📊 Barras
                    </button>
                    <button onclick="cambiarGrafico('lineas')" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        📈 Líneas
                    </button>
                    <button onclick="cambiarGrafico('pastel')" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        🥧 Pastel
                    </button>
                </div>

                {{-- Contenedor del gráfico --}}
                <div class="relative" style="height: 300px;">
                    <canvas id="graficoPrincipal"></canvas>
                </div>
            </div>

            {{-- GRÁFICO DE PASTEL: Distribución por zona --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Distribución de llamados por zona</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="graficoPastel"></canvas>
                </div>
                <div class="flex flex-wrap gap-3 mt-4 justify-center text-sm">
                    @foreach($datosGraficos['distribucionZonas'] as $zona)
                        <span class="px-3 py-1 bg-gray-100 rounded-full">
                            {{ $zona->zona }}: {{ $zona->total }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- TABLA DE DETALLE --}}
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <h3 class="text-base font-semibold text-gray-900 px-6 pt-4">Detalle de llamados</h3>
                <table class="w-full text-sm mt-2">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs text-gray-500">
                            <th class="px-6 py-3 font-medium">ZONA</th>
                            <th class="px-6 py-3 font-medium">TIPO</th>
                            <th class="px-6 py-3 font-medium">ESTADO</th>
                            <th class="px-6 py-3 font-medium">ORIGEN</th>
                            <th class="px-6 py-3 font-medium">FECHA</th>
                            <th class="px-6 py-3 font-medium">HORA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($detalleLlamados as $llamado)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-700">{{ $llamado->zona->nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $llamado->tipo == 'Emergencia' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $llamado->tipo }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $llamado->estado == 'Atendido' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $llamado->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500">
                                    {{ $llamado->usuario ? 'Empleado' : 'Sensor Arduino' }}
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $llamado->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $llamado->created_at->format('H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    No hay llamados registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- SCRIPT PARA GRÁFICOS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos del gráfico
        const meses = @json($datosGraficos['meses']);
        const emergencias = @json($datosGraficos['emergencias']);
        const normales = @json($datosGraficos['normales']);
        const distribucionZonas = @json($datosGraficos['distribucionZonas']);

        let graficoPrincipal = null;
        let graficoPastel = null;

        function crearGrafico(tipo) {
            const ctx = document.getElementById('graficoPrincipal').getContext('2d');

            if (graficoPrincipal) {
                graficoPrincipal.destroy();
            }

            let datasets = [];

            if (tipo === 'pastel') {
                // Gráfico de pastel: combinamos emergencias y normales por mes
                const totales = emergencias.map((v, i) => v + normales[i]);
                const labels = meses.filter((_, i) => totales[i] > 0);
                const data = totales.filter(v => v > 0);
                const colores = ['#ef4444', '#3b82f6', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#84cc16', '#06b6d4', '#d946ef'];

                graficoPrincipal = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Llamados',
                            data: data,
                            backgroundColor: colores.slice(0, data.length),
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            } else {
                // Gráfico de barras o líneas
                graficoPrincipal = new Chart(ctx, {
                    type: tipo === 'barras' ? 'bar' : 'line',
                    data: {
                        labels: meses,
                        datasets: [
                            {
                                label: 'Emergencias',
                                data: emergencias,
                                backgroundColor: 'rgba(239, 68, 68, 0.6)',
                                borderColor: '#ef4444',
                                borderWidth: 2,
                                borderRadius: 4,
                                tension: 0.3,
                                fill: false
                            },
                            {
                                label: 'Normales',
                                data: normales,
                                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                borderColor: '#3b82f6',
                                borderWidth: 2,
                                borderRadius: 4,
                                tension: 0.3,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }
        }

        function cambiarGrafico(tipo) {
            // Actualizar botones
            document.querySelectorAll('#graficoPrincipal').forEach(btn => {
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            event.target.classList.remove('bg-gray-200', 'text-gray-700');
            event.target.classList.add('bg-indigo-600', 'text-white');

            crearGrafico(tipo);
        }

        function crearGraficoPastel() {
            const ctx = document.getElementById('graficoPastel').getContext('2d');

            if (graficoPastel) {
                graficoPastel.destroy();
            }

            const labels = distribucionZonas.map(z => z.zona);
            const data = distribucionZonas.map(z => z.total);
            const colores = ['#ef4444', '#3b82f6', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'];

            graficoPastel = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colores.slice(0, data.length),
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        // Inicializar gráficos
        document.addEventListener('DOMContentLoaded', function() {
            crearGrafico('barras');
            crearGraficoPastel();
        });
    </script>
</x-app-layout>