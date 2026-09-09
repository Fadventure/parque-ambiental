<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Alertas y Emergencias
            </h2>
            <button onclick="document.getElementById('modalAlerta').classList.remove('hidden')" 
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                + Nueva Alerta
            </button>
        </div>
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
                    <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                    <select name="tipo" class="border border-gray-300 rounded-md text-sm px-3 py-2">
                        <option value="">Todos</option>
                        <option value="Emergencia" {{ request('tipo') === 'Emergencia' ? 'selected' : '' }}>Emergencia</option>
                        <option value="Normal" {{ request('tipo') === 'Normal' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Estado</label>
                    <select name="estado" class="border border-gray-300 rounded-md text-sm px-3 py-2">
                        <option value="">Todos</option>
                        <option value="Pendiente" {{ request('estado') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Atendido" {{ request('estado') === 'Atendido' ? 'selected' : '' }}>Atendido</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}"
                           class="border border-gray-300 rounded-md text-sm px-3 py-2">
                </div>

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                    Filtrar
                </button>
                <a href="{{ route('alertas.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium px-4 py-2 rounded-md">
                    Limpiar
                </a>
            </form>

            {{-- 🔹 AGREGADO: MOSTRAR FILTROS ACTIVOS --}}
            @if(request()->filled('zona_id') || request()->filled('tipo') || request()->filled('estado') || request()->filled('fecha'))
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-md mb-4 text-sm flex items-center justify-between">
                    <span>
                        🔍 Filtros activos:
                        @if(request()->filled('zona_id'))
                            <span class="font-medium">{{ $zonas->firstWhere('id', request('zona_id'))->nombre ?? '' }}</span>
                        @endif
                        @if(request()->filled('tipo'))
                            <span class="font-medium">{{ request('tipo') }}</span>
                        @endif
                        @if(request()->filled('estado'))
                            <span class="font-medium">{{ request('estado') }}</span>
                        @endif
                        @if(request()->filled('fecha'))
                            <span class="font-medium">{{ request('fecha') }}</span>
                        @endif
                    </span>
                    <a href="{{ route('alertas.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        ✕ Limpiar filtros
                    </a>
                </div>
            @endif

            {{-- TABLA DE ALERTAS --}}
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        {{-- 🔹 AGREGADO: CONTADOR DE ALERTAS --}}
                        <caption class="text-left text-xs text-gray-400 px-6 py-2">
                            {{ $alertas->count() }} alertas encontradas
                        </caption>
                        <tr class="text-left text-xs text-gray-500">
                            <th class="px-6 py-3 font-medium">TIPO</th>
                            <th class="px-6 py-3 font-medium">ZONA</th>
                            <th class="px-6 py-3 font-medium">ORIGEN</th>
                            <th class="px-6 py-3 font-medium">MENSAJE</th>
                            <th class="px-6 py-3 font-medium">ESTADO</th>
                            <th class="px-6 py-3 font-medium">FECHA</th>
                            <th class="px-6 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($alertas ?? [] as $alerta)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $alerta->tipo == 'Emergencia' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $alerta->tipo == 'Emergencia' ? '🔥 Emergencia' : '📋 Normal' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-700">{{ $alerta->zona->nombre ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-gray-500">
                                    {{ $alerta->usuario->name ?? 'Sensor Arduino' }}
                                </td>
                                <td class="px-6 py-3 text-gray-700 max-w-xs truncate">{{ $alerta->descripcion }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $alerta->estado == 'Atendido' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $alerta->estado == 'Atendido' ? '✅ Atendido' : '⏳ Pendiente' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500 text-xs">{{ $alerta->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-3 text-right">
                                    @if($alerta->estado == 'Pendiente')
                                        <form action="{{ route('alertas.atender', $alerta->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="text-green-600 hover:text-green-800 text-xs font-medium hover:underline">
                                                ✅ Atender
                                            </button>
                                        </form>
                                    @endif
                                    @if(auth()->user()->esAdmin())
                                        <form action="{{ route('alertas.destroy', $alerta->id) }}" method="POST" class="inline ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs" onclick="return confirm('¿Eliminar esta alerta?')">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    No hay alertas registradas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL PARA NUEVA ALERTA --}}
    <div id="modalAlerta" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">🚨 Nueva Alerta</h3>
                <button onclick="document.getElementById('modalAlerta').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>

            <form action="{{ route('alertas.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="tipo" value="Normal" checked>
                            <span class="text-sm">Alerta</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="tipo" value="Emergencia">
                            <span class="text-sm">Emergencia</span>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zona *</label>
                    <select name="zona_id" class="w-full rounded-md border-gray-300" required>
                        <option value="">Seleccionar zona</option>
                        @foreach($zonas ?? [] as $zona)
                            <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                    <textarea name="descripcion" rows="3" 
                              class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500"
                              placeholder="Describí la situación..." required></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('modalAlerta').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Enviar reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT PARA ABRIR MODAL CON ESC --}}
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('modalAlerta').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>