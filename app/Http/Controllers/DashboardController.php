<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Repair;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'lowStock' => Product::whereBetween('stock', [1, 5])->count(),
            'outOfStock' => Product::where('stock', '<=', 0)->count(),
            'customers' => Customer::count(),
            'pendingRepairs' => Repair::whereIn('status', ['Recibido', 'En reparación', 'Listo para entregar'])->count(),
        ];

        $latestMovements = InventoryMovement::with('product')
            ->latest()
            ->take(5)
            ->get();

        return view('home', compact('stats', 'latestMovements'));
    }
}