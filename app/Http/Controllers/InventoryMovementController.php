<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryMovementController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
            'customer_id' => 'nullable|required_if:type,out|exists:customers,id',
        ]);

        if ($data['type'] === 'out' && $data['quantity'] > $product->stock) {
            return back()->withErrors(['quantity' => 'La salida no puede ser mayor que el stock disponible.']);
        }

        DB::transaction(function () use ($data, $product) {
            $product->update(['stock' => $product->stock + ($data['type'] === 'in' ? $data['quantity'] : -$data['quantity'])]);
            InventoryMovement::create($data + ['product_id' => $product->id, 'user_id' => Auth::id()]);
        });

        return back()->with('success', 'Movimiento registrado y stock actualizado.');
    }
}