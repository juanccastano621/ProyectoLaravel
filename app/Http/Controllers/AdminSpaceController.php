<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminSpaceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Spaces/Index', [
            'spaces' => Space::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'capacity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('spaces', 'public');
        }

        Space::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'capacity' => $validated['capacity'],
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Espacio creado correctamente.');
    }

    public function update(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'capacity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Borrar imagen anterior si existe
            if ($space->image_path) {
                Storage::disk('public')->delete($space->image_path);
            }
            $space->image_path = $request->file('image')->store('spaces', 'public');
        }

        $space->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'capacity' => $validated['capacity'],
            'image_path' => $space->image_path,
        ]);

        return redirect()->back()->with('success', 'Espacio actualizado.');
    }

    public function destroy(Space $space)
    {
        if ($space->image_path) {
            Storage::disk('public')->delete($space->image_path);
        }
        $space->delete();
        return redirect()->back()->with('success', 'Espacio eliminado.');
    }
}