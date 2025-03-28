<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('equipments')->paginate(10);
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:areas',
            'description' => 'nullable|string|max:500'
        ]);

        $area = Area::create($validatedData);

        return redirect()->route('areas.index')
            ->with('success', 'Area created successfully.');
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
            'description' => 'nullable|string|max:500'
        ]);

        $area->update($validatedData);

        return redirect()->route('areas.index')
            ->with('success', 'Area updated successfully.');
    }

    public function destroy(Area $area)
    {
        // Check if area has equipments before deleting
        if ($area->equipments()->count() > 0) {
            return redirect()->route('areas.index')
                ->with('error', 'Cannot delete area with existing equipment.');
        }

        $area->delete();

        return redirect()->route('areas.index')
            ->with('success', 'Area deleted successfully.');
    }
}
