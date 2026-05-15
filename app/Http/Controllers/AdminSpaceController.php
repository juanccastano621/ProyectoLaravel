<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminSpaceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Spaces/Index', [
            'spaces' => Space::with('availabilities')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'availabilities' => 'required|array'
        ]);

        DB::beginTransaction();

        try {
            $slug = Str::slug($validated['name']);

            $counter = 1;
            $originalSlug = $slug;

            while (Space::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')
                    ->store('spaces', 'public');
            }

            $space = Space::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'capacity' => $validated['capacity'],
                'type' => 'Auditorio',
                'price_per_hour' => $validated['price_per_hour'],
                'is_active' => $validated['is_active'],
                'image_path' => $imagePath,
            ]);

            foreach ($validated['availabilities'] as $availability) {

                if (!($availability['enabled'] ?? false)) {
                    continue;
                }

                $space->availabilities()->create([
                    'day_of_week' => $availability['day_of_week'],
                    'start_time' => $availability['start_time'],
                    'end_time' => $availability['end_time'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.spaces.index')
                ->with('success', 'Espacio creado correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, Space $space)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
            'availabilities' => 'required|array'
        ]);

        DB::beginTransaction();

        try {

            $space->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'capacity' => $validated['capacity'],
                'type' => 'Auditorio',
                'price_per_hour' => $validated['price_per_hour'],
                'is_active' => $validated['is_active'],
            ]);

            if ($request->hasFile('image')) {

                if ($space->image_path) {
                    Storage::disk('public')
                        ->delete($space->image_path);
                }

                $path = $request->file('image')
                    ->store('spaces', 'public');

                $space->update([
                    'image_path' => $path
                ]);
            }

            $space->availabilities()->delete();

            foreach ($validated['availabilities'] as $availability) {

                if (!($availability['enabled'] ?? false)) {
                    continue;
                }

                $space->availabilities()->create([
                    'day_of_week' => $availability['day_of_week'],
                    'start_time' => $availability['start_time'],
                    'end_time' => $availability['end_time'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.spaces.index')
                ->with('success', 'Espacio actualizado correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Space $space)
    {
        if ($space->image_path) {
            Storage::disk('public')
                ->delete($space->image_path);
        }

        $space->delete();

        return back()->with('success', 'Espacio eliminado.');
    }
}