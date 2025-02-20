<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::with('creator')->get();
        return view('people.index', compact('people'));
    }

    public function show($id)
    {
        $person = Person::with(['children', 'parents'])->findOrFail($id);
        return view('people.show', compact('person'));
    }

    public function create()
    {
        return view('people.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        Person::create([
            'created_by' => auth()->id(),
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => strtoupper($request->last_name),
            'birth_name' => $request->birth_name ? strtoupper($request->birth_name) : strtoupper($request->last_name),
            'middle_names' => $request->middle_names ? ucwords(strtolower($request->middle_names)) : null,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('people.index')->with('success', 'Person created successfully!');
    }
}

