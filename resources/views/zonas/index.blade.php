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
                            <span class="w-2.5 h-2.5 rounded-full {{ $zona->tiene_alerta ? 'bg-red-500' : 'bg-green-500' }}" id="alerta-{{ $zona->id }}"></span>
                        </div>

                        <p class="text-xs text-gray-500 mb-1">Administrador: {{ $zona->administrador->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mb-4">Empleados asignados: {{ $zona->empleados_count ?? 0 }}</p>

                        <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                            <div class="bg-gray-50 rounded p-2">
                                <p class="text-[11px] text-gray-400">Humedad</p>
                                <p class="font-semibold text-gray-800" id="humedad-{{ $zona->id }}">{{ $zona->humedad ?? '--' }}%</p>
                            </div>
                            <div class="bg-gray-50 rounded p-2">
                                <p class="text-[11px] text-gray-400">Temperatura</p>
                                <p class="font-semibold text-gray-800" id="temperatura-{{ $zona->id }}">{{ $zona->temperatura ?? '--' }}°C</p>
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
    @push('scripts')
        <script>
            async function actualizarZonas() {
                try {
                    const res = await fetch('/api/zonas');
                    const zonas = await res.json();

                    zonas.forEach(zona => {
                        const humedadEl = document.getElementById(`humedad-${zona.id}`);
                        const temperaturaEl = document.getElementById(`temperatura-${zona.id}`);
                        const alertaEl = document.getElementById(`alerta-${zona.id}`);

                        if (humedadEl) humedadEl.innerText = (zona.humedad ?? '--') + '%';
                        if (temperaturaEl) temperaturaEl.innerText = (zona.temperatura ?? '--') + '°C';
                        if (alertaEl) {
                            alertaEl.classList.toggle('bg-red-500', zona.tiene_alerta);
                            alertaEl.classList.toggle('bg-green-500', !zona.tiene_alerta);
                        }
                    });
                } catch (e) {
                    console.error('Error actualizando zonas', e);
                }
            }

            setInterval(actualizarZonas, 10000);
        </script>
    @endpush
</x-app-layout>