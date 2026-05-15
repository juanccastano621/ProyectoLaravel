<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSpaceController extends Controller
{
    public function index()
    {
        $spaces = Space::orderBy('created_at', 'desc')->get();
        
        return Inertia::render('Admin/Spaces/Index', [
            'spaces' => $spaces
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Spaces/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'El nombre del espacio es obligatorio.',
            'capacity.min' => 'La capacidad debe ser al menos de 1 persona.',
            'price_per_hour.min' => 'El precio no puede ser un valor negativo.',
        ]);

        Space::create($validated);

        return redirect()->route('admin.spaces.index')
            ->with('success', '¡Excelente! El nuevo espacio ha sido creado correctamente.');
    }

    public function edit(Space $space)
    {
        return Inertia::render('Admin/Spaces/Edit', [
            'space' => $space
        ]);
    }

    public function update(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $space->update($validated);

        return redirect()->route('admin.spaces.index')
            ->with('success', 'Los cambios en el espacio se guardaron con éxito.');
    }

    public function destroy(Space $space)
    {
        $space->delete();

        return redirect()->route('admin.spaces.index')
            ->with('success', 'El espacio ha sido eliminado permanentemente.');
    }
}