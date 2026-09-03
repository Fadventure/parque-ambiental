<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Zonas del parque
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->esAdmin())
            <div class="flex justify-end mb-4">
                <a href="{{ route('zonas.create') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                    + Nueva zona
                </a>
            </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                @forelse($zonas ?? [] as $zona)
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-900">{{ $zona->nombre }}</h3>
                            <span class="w-2.5 h-2.5 rounded-full {{ $zona->tiene_alerta ? 'bg-red-500' : 'bg-green-500' }}"></span>
                        </div>

                        <p class="text-xs text-gray-500 mb-1">Administrador: {{ $zona->administrador->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mb-4">Empleados asignados: {{ $zona->empleados_count ?? 0 }}</p>

                        <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                            <div class="bg-gray-50 rounded p-2">
                                <p class="text-[11px] text-gray-400">Humedad</p>
                                <p class="font-semibold text-gray-800">{{ $zona->humedad ?? '--' }}%</p>
                            </div>
                            <div class="bg-gray-50 rounded p-2">
                                <p class="text-[11px] text-gray-400">Temperatura</p>
                                <p class="font-semibold text-gray-800">{{ $zona->temperatura ?? '--' }}°C</p>
                            </div>
                        </div>

                        <a href="{{ route('zonas.show', $zona->id) }}" class="text-indigo-600 text-sm font-medium hover:underline">
                            Ver detalle →
                        </a>
                    </div>
                @empty
                    @foreach(['Invernadero A', 'Invernadero B', 'Hidroponía', 'Vivero Central', 'Sector Norte'] as $nombreZona)
                        <div class="bg-white rounded-lg shadow-sm p-5">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-900">{{ $nombreZona }}</h3>
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            </div>
                            <p class="text-xs text-gray-500 mb-1">Administrador: —</p>
                            <p class="text-xs text-gray-500 mb-4">Empleados asignados: 0</p>
                            <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                                <div class="bg-gray-50 rounded p-2">
                                    <p class="text-[11px] text-gray-400">Humedad</p>
                                    <p class="font-semibold text-gray-800">--%</p>
                                </div>
                                <div class="bg-gray-50 rounded p-2">
                                    <p class="text-[11px] text-gray-400">Temperatura</p>
                                    <p class="font-semibold text-gray-800">--°C</p>
                                </div>
                            </div>
                            <a href="#" class="text-indigo-600 text-sm font-medium hover:underline">Ver detalle →</a>
                        </div>
                    @endforeach
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>