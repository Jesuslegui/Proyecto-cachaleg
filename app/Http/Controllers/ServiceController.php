<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|string',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name','price','description']);
        if($request->hasFile('image')) {
            $path = $request->file('image')->store('services','public');
            $data['image'] = $path;
        }

        Service::create($data);
        return redirect()->route('services.index')->with('success', 'Servicio creado.');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|string',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name','price','description']);
        if($request->hasFile('image')) {
            $path = $request->file('image')->store('services','public');
            $data['image'] = $path;
        }

        $service->update($data);
        return redirect()->route('services.index')->with('success', 'Servicio actualizado.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Servicio eliminado.');
    }
}