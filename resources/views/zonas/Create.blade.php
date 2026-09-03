<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva zona
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-lg shadow-sm p-6">

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm p-4 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('zonas.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la zona</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               placeholder="Ej: Invernadero A"
                               class="w-full border border-gray-300 rounded-md text-sm px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Administrador</label>
                        <select name="administrador_id" class="w-full border border-gray-300 rounded-md text-sm px-3 py-2">
                            <option value="">Sin asignar</option>
                            @foreach($administradores as $admin)
                                <option value="{{ $admin->id }}" {{ old('administrador_id') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Humedad inicial (%)</label>
                            <input type="number" step="0.1" name="humedad" value="{{ old('humedad') }}"
                                   placeholder="Opcional"
                                   class="w-full border border-gray-300 rounded-md text-sm px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Temperatura inicial (°C)</label>
                            <input type="number" step="0.1" name="temperatura" value="{{ old('temperatura') }}"
                                   placeholder="Opcional"
                                   class="w-full border border-gray-300 rounded-md text-sm px-3 py-2">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('zonas.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2 rounded-md">
                            Guardar zona
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>