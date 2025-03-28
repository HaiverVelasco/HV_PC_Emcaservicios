<?php

namespace App\Http\Controllers;

use App\Models\Software;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    public function index()
    {
        $softwares = Software::withCount('equipments')->paginate(10);
        return view('softwares.index', compact('softwares'));
    }

    public function create()
    {
        return view('softwares.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'license_type' => 'nullable|string|max:100'
        ]);

        $software = Software::create($validatedData);

        return redirect()->route('softwares.index')
            ->with('success', 'Software created successfully.');
    }

    public function edit(Software $software)
    {
        return view('softwares.edit', compact('software'));
    }

    public function update(Request $request, Software $software)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:50',
            'license_type' => 'nullable|string|max:100'
        ]);

        $software->update($validatedData);

        return redirect()->route('softwares.index')
            ->with('success', 'Software updated successfully.');
    }

    public function destroy(Software $software)
    {
        // Check if software is installed on any equipment
        if ($software->equipments()->count() > 0) {
            return redirect()->route('softwares.index')
                ->with('error', 'Cannot delete software installed on equipment.');
        }

        $software->delete();

        return redirect()->route('softwares.index')
            ->with('success', 'Software deleted successfully.');
    }
}
