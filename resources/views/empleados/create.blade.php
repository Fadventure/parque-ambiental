@extends('layouts.admin')

@section('header_title', 'Nuevo Empleado')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Agregar Nuevo Empleado</h2>

        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- CHECKBOX PARA ROL ADMIN --}}
            <div class="mb-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="es_admin" id="es_admin" value="1" {{ old('es_admin') ? 'checked' : '' }}
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-m font-medium text-gray-700">Es Administrador</span>
                </label>
                @error('es_admin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
                    <input type="password" name="password" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zona *</label>
                    <select name="zona_id" id="zona_id"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccionar zona</option>
                        @foreach($zonas as $zona)
                            <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                                {{ $zona->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('zona_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tarea *</label>
                    <select name="tarea" id="tarea"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccionar una zona primero</option>
                    </select>
                    @error('tarea') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Contratación</label>
                    <input type="date" name="fecha_contratacion" value="{{ old('fecha_contratacion') }}" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('fecha_contratacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('direccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('empleados.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Crear Empleado
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 👇 SCRIPT COMPLETO --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const zonaSelect = document.getElementById('zona_id');
    const tareaSelect = document.getElementById('tarea');
    const esAdminCheckbox = document.getElementById('es_admin');

    // 👇 TRABAJOS POR ZONA
    const trabajosPorZona = {
        'Invernaderos': ['Encargado de cultivos', 'Técnico de riego', 'Operario de mantenimiento'],
        'Hidroponía': ['Técnico de hidroponía', 'Operario de bombas', 'Encargado de cultivos'],
        'Mantenimiento': ['Técnico eléctrico', 'Técnico en sistemas y sensores', 'Operario general'],
    };

    function actualizarTareas() {
        const zonaNombre = zonaSelect.options[zonaSelect.selectedIndex]?.text;
        const tareas = trabajosPorZona[zonaNombre] || [];

        tareaSelect.innerHTML = '<option value="">Seleccionar tarea</option>';
        
        if (tareas.length === 0) {
            tareaSelect.innerHTML += '<option value="">No hay tareas disponibles para esta zona</option>';
        } else {
            tareas.forEach(tarea => {
                const option = document.createElement('option');
                option.value = tarea;
                option.textContent = tarea;
                tareaSelect.appendChild(option);
            });
        }
    }

    // 👇 FUNCIÓN PARA HABILITAR/DESHABILITAR ZONA Y TAREA
    function toggleZonaYTarea() {
        const esAdmin = esAdminCheckbox.checked;
        
        // Deshabilitar o habilitar zona
        zonaSelect.disabled = esAdmin;
        zonaSelect.classList.toggle('bg-gray-100', esAdmin);
        zonaSelect.classList.toggle('cursor-not-allowed', esAdmin);
        
        // Deshabilitar o habilitar tarea
        tareaSelect.disabled = esAdmin;
        tareaSelect.classList.toggle('bg-gray-100', esAdmin);
        tareaSelect.classList.toggle('cursor-not-allowed', esAdmin);
        
        // Si es admin, limpiar los valores
        if (esAdmin) {
            zonaSelect.value = '';
            tareaSelect.innerHTML = '<option value="">Seleccionar una zona primero</option>';
        } else {
            // Si no es admin, restaurar el comportamiento normal
            if (zonaSelect.value) {
                actualizarTareas();
            }
        }
    }

    // Evento cuando cambia el checkbox
    esAdminCheckbox.addEventListener('change', toggleZonaYTarea);

    // Evento cuando cambia la zona (solo si no es admin)
    zonaSelect.addEventListener('change', function() {
        if (!esAdminCheckbox.checked) {
            actualizarTareas();
        }
    });

    // 👉 Ejecutar al cargar la página (para mantener el estado)
    toggleZonaYTarea();

    // Si hay una zona seleccionada y no es admin, cargar tareas
    if (zonaSelect.value && !esAdminCheckbox.checked) {
        actualizarTareas();
        const tareaAnterior = "{{ old('tarea') }}";
        if (tareaAnterior) {
            tareaSelect.value = tareaAnterior;
        }
    }
});
</script>
@endsection