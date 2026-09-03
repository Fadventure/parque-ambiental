@extends('layouts.admin')

@section('header_title', 'Gestión de Empleados')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Empleados</h2>
    <a href="{{ route('empleados.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
        + Nuevo Empleado
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Zona</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarea</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($empleados as $empleado)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $empleado->id }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $empleado->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $empleado->user->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                            {{ $empleado->zona->nombre ?? 'Sin zona' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $empleado->tarea }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $empleado->telefono ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="{{ route('empleados.edit', $empleado) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">✏️</a>
                        <form action="{{ route('empleados.destroy', $empleado) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Eliminar este empleado?')">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No hay empleados registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection