<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel principal
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TARJETAS RESUMEN --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                <div class="bg-white rounded-lg shadow-sm border-l-4 border-red-500 p-5">
                    <p class="text-xs font-semibold text-gray-500 tracking-wide">EMERGENCIAS ACTIVAS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $emergenciasActivas ?? 3 }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border-l-4 border-yellow-400 p-5">
                    <p class="text-xs font-semibold text-gray-500 tracking-wide">TOTAL ALERTAS HOY</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $alertasHoy ?? 2 }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border-l-4 border-gray-500 p-5">
                    <p class="text-xs font-semibold text-gray-500 tracking-wide">NO ATENDIDAS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $noAtendidas ?? 3 }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-500 p-5">
                    <p class="text-xs font-semibold text-gray-500 tracking-wide">ZONAS MONITOREADAS</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $zonasMonitoreadas ?? 5 }}</p>
                    <p class="text-xs text-gray-400 mt-1">con sensores activos</p>
                </div>
            </div>

            {{-- ULTIMAS ALERTAS --}}
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Últimas alertas</h2>

                <div class="space-y-3">
                    @forelse($ultimasAlertas ?? [] as $alerta)
                        <div class="flex items-start justify-between rounded-md border-l-4 p-4
                                    {{ $alerta->tipo === 'critica' ? 'bg-red-50 border-red-400' : 'bg-yellow-50 border-yellow-400' }}">
                            <div>
                                <p class="text-sm font-medium {{ $alerta->tipo === 'critica' ? 'text-red-700' : 'text-yellow-700' }}">
                                    {{ $alerta->mensaje }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $alerta->zona }} · {{ $alerta->origen }}</p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-4">{{ $alerta->hora }}</span>
                        </div>
                    @empty
                        <div class="flex items-start justify-between rounded-md border-l-4 border-red-400 bg-red-50 p-4">
                            <div>
                                <p class="text-sm font-medium text-red-700">Temperatura crítica: 42°C detectada en sector norte</p>
                                <p class="text-xs text-gray-500 mt-1">Invernadero B · Sensor Arduino</p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-4">08:14</span>
                        </div>
                        <div class="flex items-start justify-between rounded-md border-l-4 border-yellow-400 bg-yellow-50 p-4">
                            <div>
                                <p class="text-sm font-medium text-yellow-700">Humedad por debajo del umbral mínimo (38%)</p>
                                <p class="text-xs text-gray-500 mt-1">Hidroponía · Sensor Arduino</p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-4">07:52</span>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- EMERGENCIAS RECIENTES --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Emergencias recientes</h2>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-medium">TIPO</th>
                            <th class="pb-3 font-medium">ESTADO</th>
                            <th class="pb-3 font-medium">FECHA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($emergenciasRecientes ?? [] as $llamado)
                            <tr>
                                <td class="py-3 flex items-center gap-2">
                                    {{ $llamado->tipo === 'emergencia' ? '🔥' : '📋' }}
                                    {{ ucfirst($llamado->tipo) }}
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        {{ $llamado->atendido ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $llamado->atendido ? 'Atendido' : 'No atendido' }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-500">{{ $llamado->fecha }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 flex items-center gap-2">🔥 Emergencia</td>
                                <td class="py-3"><span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">No atendido</span></td>
                                <td class="py-3 text-gray-500">2026-09-01 08:14</td>
                            </tr>
                            <tr>
                                <td class="py-3 flex items-center gap-2">📋 Normal</td>
                                <td class="py-3"><span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Atendido</span></td>
                                <td class="py-3 text-gray-500">2026-09-01 07:52</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>