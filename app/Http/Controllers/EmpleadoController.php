<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    /**
     * Listar todos los empleados.
     */
    public function index()
    {
        $empleados = Empleado::with(['user', 'zona'])->get();
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Mostrar formulario para crear un nuevo empleado.
     */
    public function create()
    {
        $zonas = Zona::all();
        return view('empleados.create', compact('zonas'));
    }

    /**
     * Guardar un nuevo empleado.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'zona_id' => 'required|exists:zonas,id',
            'tarea' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_contratacion' => 'nullable|date',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'empleado'
        ]);

        // Crear el empleado asociado
        Empleado::create([
            'user_id' => $user->id,
            'zona_id' => $request->zona_id,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_contratacion' => $request->fecha_contratacion,
            'tarea' => $request->tarea,
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Mostrar un empleado específico.
     */
    public function show(Empleado $empleado)
    {
        $empleado->load(['user', 'zona']);
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Mostrar formulario para editar un empleado.
     */
    public function edit(Empleado $empleado)
    {
        $zonas = Zona::all();
        return view('empleados.edit', compact('empleado', 'zonas'));
    }

    /**
     * Actualizar un empleado.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $empleado->user_id,
            'zona_id' => 'required|exists:zonas,id',
            'tarea' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_contratacion' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        // Actualizar usuario
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $empleado->user->update($userData);

        // Actualizar empleado
        $empleado->update([
            'zona_id' => $request->zona_id,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_contratacion' => $request->fecha_contratacion,
            'tarea' => $request->tarea,
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Eliminar un empleado.
     */
    public function destroy(Empleado $empleado)
    {
        // Eliminar el usuario asociado (cascade eliminará el empleado)
        $empleado->user->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}