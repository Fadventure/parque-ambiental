<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alertas y emergencias
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- FILTROS --}}
            <form method="GET" class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Zona</label>
                    <select name="zona" class="border border-gray-300 rounded-md text-sm px-3 py-2">
                        <option value="">Todas</option>
                        @foreach($zonas ?? [] as $zona)
                            <option value="{{ $zona->id }}" {{ request('zona') == $zona->id ? 'selected' : '' }}>
                                {{ $zona->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                    <select name="tipo" class="border border-gray-300 rounded-md text-sm px-3 py-2">
                        <option value="">Todos</option>
                        <option value="emergencia" {{ request('tipo') === 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                        <option value="normal" {{ request('tipo') === 'normal' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Estado</label>
                    <select name="estado" class="border border-gray-300 rounded-md text-sm px-3 py-2">
                        <option value="">Todos</option>
                        <option value="atendido" {{ request('estado') === 'atendido' ? 'selected' : '' }}>Atendido</option>
                        <option value="no_atendido" {{ request('estado') === 'no_atendido' ? 'selected' : '' }}>No atendido</option>
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
            </form>

            {{-- TABLA --}}
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
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
                                    {{ $alerta->tipo === 'emergencia' ? '🔥 Emergencia' : '📋 Normal' }}
                                </td>
                                <td class="px-6 py-3">{{ $alerta->zona }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $alerta->origen }}</td>
                                <td class="px-6 py-3">{{ $alerta->mensaje }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        {{ $alerta->atendido ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $alerta->atendido ? 'Atendido' : 'No atendido' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $alerta->fecha }}</td>
                                <td class="px-6 py-3 text-right">
                                    @if(!$alerta->atendido)
                                        <form action="{{ route('alertas.atender', $alerta->id) }}" method="POST">
                                            @csrf
                                            <button class="text-indigo-600 text-xs font-medium hover:underline">
                                                Marcar atendida
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
</x-app-layout>