<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use App\Models\User;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function index()
    {
                error_log("/zonas (get)");

        $zonas = Zona::all();

        return view('zonas.index', compact('zonas'));
    }

    public function create()
    {
        error_log("ENTRO A LA FUNCION CREATE");
        $administradores = User::where('rol', 'admin')->get();
        error_log("ejecuto consulta");
        return view('zonas.create', compact('administradores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'administrador_id' => 'nullable|exists:users,id',
            'humedad' => 'nullable|numeric',
            'temperatura' => 'nullable|numeric',
        ]);

        Zona::create($validated);

        return redirect()->route('zonas.index')->with('success', 'Zona creada correctamente');
    }

    public function show(string $id)
    {
                error_log("ENTRO A LA FUNCjjdsjgsldW");

        $zona = Zona::findOrFail($id);

        return view('zonas.show', compact('zona'));
    }

    public function edit(string $id)
    {
        $zona = Zona::findOrFail($id);
        $administradores = User::where('rol', 'admin')->get();

        return view('zonas.edit', compact('zona', 'administradores'));
    }

    public function update(Request $request, string $id)
    {

            error_log("ENTRO A LA FUNCION update");

        $zona = Zona::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'administrador_id' => 'nullable|exists:users,id',
            'humedad' => 'nullable|numeric',
            'temperatura' => 'nullable|numeric',
        ]);

        $zona->update($validated);

        return redirect()->route('zonas.show', $zona->id)->with('success', 'Zona actualizada');
    }

    public function destroy(string $id)
    {
        Zona::findOrFail($id)->delete();

        return redirect()->route('zonas.index')->with('success', 'Zona eliminada');
    }
}