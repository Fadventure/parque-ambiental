<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Empleado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                            <dd class="text-lg font-semibold">{{ $empleado->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-lg font-semibold">{{ $empleado->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Zona</dt>
                            <dd class="text-lg font-semibold">{{ $empleado->zona->nombre ?? 'Sin zona' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tarea</dt>
                            <dd class="text-lg font-semibold">{{ $empleado->tarea }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="text-lg font-semibold">{{ $empleado->telefono ?? 'No registrado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="text-lg font-semibold">{{ $empleado->direccion ?? 'No registrada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha Contratación</dt>
                            <dd class="text-lg font-semibold">{{ $empleado->fecha_contratacion ?? 'No registrada' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('empleados.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                            Volver
                        </a>
                        <a href="{{ route('empleados.edit', $empleado) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>