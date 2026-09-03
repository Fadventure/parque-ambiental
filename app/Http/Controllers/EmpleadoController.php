<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    /**
     * Listar todos los empleados (usuarios con rol empleado).
     */
    public function index()
    {
        $empleados = User::with('zona')->where('rol', 'empleado')->get();
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

        // Crear el usuario con todos los campos
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'empleado',
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
    public function show(User $empleado)
    {
        $empleado->load('zona');
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Mostrar formulario para editar un empleado.
     */
    public function edit(User $empleado)
    {
        $zonas = Zona::all();
        return view('empleados.edit', compact('empleado', 'zonas'));
    }

    /**
     * Actualizar un empleado.
     */
    public function update(Request $request, User $empleado)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $empleado->id,
            'zona_id' => 'required|exists:zonas,id',
            'tarea' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_contratacion' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'zona_id' => $request->zona_id,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_contratacion' => $request->fecha_contratacion,
            'tarea' => $request->tarea,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $empleado->update($userData);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Eliminar un empleado.
     */
    public function destroy(User $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado exitosamente.');
    }
}