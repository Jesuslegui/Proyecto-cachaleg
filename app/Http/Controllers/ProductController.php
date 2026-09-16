<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'size' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'colors' => 'nullable|string',
            'shape' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        $data = $request->only(['name','size','price','stock','colors','shape','category']);
        if($request->hasFile('image')) {
            $path = $request->file('image')->store('products','public');
            $data['image'] = $path;
        }

        Product::create($data);
        return redirect()->route('inventory.index')->with('success', 'Producto creado.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'size' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
            'colors' => 'nullable|string',
            'shape' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        $data = $request->only(['name','size','price','stock','colors','shape','category']);
        if($request->hasFile('image')) {
            $path = $request->file('image')->store('products','public');
            $data['image'] = $path;
        }

        $product->update($data);
        return redirect()->route('inventory.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('inventory.index')->with('success', 'Producto eliminado.');
    }
}