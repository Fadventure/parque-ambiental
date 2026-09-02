<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel principal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Grid de tarjetas de resumen -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Emergencias activas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm text-gray-500">EMERGENCIAS ACTIVAS</div>
                    <div class="text-3xl font-bold text-red-600">3</div>
                </div>
                <!-- Total alertas hoy -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500">TOTAL ALERTAS HOY</div>
                    <div class="text-3xl font-bold text-yellow-600">2</div>
                </div>
                <!-- No atendidas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-500">
                    <div class="text-sm text-gray-500">NO ATENDIDAS</div>
                    <div class="text-3xl font-bold text-gray-600">3</div>
                </div>
                <!-- Zonas monitoreadas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm text-gray-500">ZONAS MONITOREADAS</div>
                    <div class="text-3xl font-bold text-green-600">5</div>
                    <div class="text-xs text-gray-400">con sensores activos</div>
                </div>
            </div>
            
            <!-- Últimas alertas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <h3 class="font-semibold text-lg mb-4">Últimas alertas</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                        <span class="text-red-500 font-bold">⚠️</span>
                        <div>
                            <p class="font-medium text-red-700">Temperatura crítica: 42°C detectada en sector norte</p>
                            <p class="text-sm text-gray-500">Invernadero B · Sensor Arduino</p>
                        </div>
                        <span class="ml-auto text-xs text-gray-400">08:14</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <span class="text-yellow-500 font-bold">⚡</span>
                        <div>
                            <p class="font-medium text-yellow-700">Humedad por debajo del umbral mínimo (38%)</p>
                            <p class="text-sm text-gray-500">Hidroponía · Sensor Arduino</p>
                        </div>
                        <span class="ml-auto text-xs text-gray-400">07:52</span>
                    </div>
                </div>
            </div>

            <!-- Tabla de emergencias (simulada) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Emergencias recientes</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">🔥 Emergencia</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">No atendido</span></td>
                            <td class="px-6 py-4 text-sm text-gray-500">2026-09-01 08:14</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">📋 Normal</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Atendido</span></td>
                            <td class="px-6 py-4 text-sm text-gray-500">2026-09-01 07:52</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>