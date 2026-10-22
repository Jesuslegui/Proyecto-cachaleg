<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::query()->orderBy('name')->paginate(15);
        return view('providers.index', compact('providers'));
    }

    public function create()
    {
        return view('providers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'country_code' => 'nullable|string',
        ]);

        Provider::create($request->all());

        return redirect()->route('providers.index')->with('success', 'Proveedor creado.');
    }

    public function show(Provider $provider)
    {
        return view('providers.show', compact('provider'));
    }

    public function edit(Provider $provider)
    {
        return view('providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'country_code' => 'nullable|string',
        ]);

        $provider->update($request->all());
        return redirect()->route('providers.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        return redirect()->route('providers.index')->with('success', 'Proveedor eliminado.');
    }
}