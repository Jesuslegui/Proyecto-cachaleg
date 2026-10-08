<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $availability = $request->input('availability');
        $category = $request->input('category');
        $products = Product::query()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('id', is_numeric($search) ? $search : 0);
            }))
            ->when($availability === 'available', fn ($query) => $query->where('stock', '>', 0))
            ->when($availability === 'low', fn ($query) => $query->whereBetween('stock', [1, 5]))
            ->when($availability === 'out', fn ($query) => $query->where('stock', '<=', 0))
            ->when(in_array($category, ['cueros', 'zuelas', 'hormas'], true), fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(12)
            ->withQueryString();

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
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
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

        DB::transaction(function () use ($data) {
            $product = Product::create($data);
            if ($product->stock > 0) {
                InventoryMovement::create(['product_id' => $product->id, 'quantity' => $product->stock, 'type' => 'in', 'reason' => 'Inventario inicial', 'user_id' => Auth::id()]);
            }
        });
        return redirect()->route('inventory.index')->with('success', 'Producto creado.');
    }

    public function show(Product $product)
    {
        $customers = Customer::query()->orderBy('name')->get();

        return view('products.show', compact('product', 'customers'));
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
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
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

        DB::transaction(function () use ($data, $product) {
            $oldStock = $product->stock;
            $product->update($data);
            $difference = $product->stock - $oldStock;
            if ($difference !== 0) {
                InventoryMovement::create(['product_id' => $product->id, 'quantity' => abs($difference), 'type' => $difference > 0 ? 'in' : 'out', 'reason' => 'Ajuste desde edición de producto', 'user_id' => Auth::id()]);
            }
        });
        return redirect()->route('inventory.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('inventory.index')->with('success', 'Producto eliminado.');
    }
}