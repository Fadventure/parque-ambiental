<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $zona->nombre }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('zonas.index') }}" class="text-sm text-indigo-600 hover:underline mb-4 inline-block">
                ← Volver a zonas
            </a>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $zona->nombre }}</h3>
                        <span class="w-2.5 h-2.5 rounded-full {{ $zona->tiene_alerta ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    </div>

                    @if(auth()->user()->esAdmin())
                        <div class="flex gap-3">
                            <a href="{{ route('zonas.edit', $zona->id) }}"
                               class="text-sm text-gray-500 hover:text-gray-700">
                                Editar
                            </a>
                            <form action="{{ route('zonas.destroy', $zona->id) }}" method="POST"
                                  onsubmit="return confirm('¿Seguro que querés eliminar esta zona?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <p class="text-sm text-gray-500 mb-1">
                    Administrador: {{ $zona->administrador->name ?? 'Sin asignar' }}
                </p>
                <p class="text-sm text-gray-500 mb-6">
                    Empleados asignados: {{ $zona->empleados_count ?? 0 }}
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Humedad actual</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $zona->humedad ?? '--' }}%</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-400 mb-1">Temperatura actual</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $zona->temperatura ?? '--' }}°C</p>
                    </div>
                </div>
            </div>

            {{-- Espacio para el historial de lecturas del ESP32, cuando lo conectemos --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Historial de lecturas</h3>
                <p class="text-sm text-gray-400">Todavía no hay datos de sensores para esta zona.</p>
            </div>

        </div>
    </div>
</x-app-layout>