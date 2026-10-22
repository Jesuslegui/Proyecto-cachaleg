<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        $repairs = Repair::with(['customer','service','creator'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->get();

        return view('repairs.index', compact('repairs'));
    }

    public function create()
    {
        $services = Service::all();
        return view('repairs.create', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string',
            'customer_phone' => 'nullable|string',
            'product_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'service_id' => 'nullable|exists:services,id',
            'description' => 'nullable|string',
            'received_at' => 'nullable|date',
            'estimated_delivery_at' => 'nullable|date',
            'status' => ['nullable', Rule::in($this->statuses())],
            'price' => 'nullable|numeric',
            'observations' => 'nullable|string',
        ]);

        unset($data['image']);
        $data['created_by'] = Auth::id();
        $data['status'] = $data['status'] ?? 'Recibido';
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('repairs', 'public');
        }

        $repair = Repair::create($data);

        return redirect()->route('repairs.index')->with('success', 'Reparación registrada.');
    }

    public function show(Repair $repair)
    {
        $repair->load(['customer','service','creator']);
        return view('repairs.show', compact('repair'));
    }

    public function edit(Repair $repair)
    {
        $services = Service::all();
        return view('repairs.edit', compact('repair','services'));
    }

    public function update(Request $request, Repair $repair)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string',
            'customer_phone' => 'nullable|string',
            'product_description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'service_id' => 'nullable|exists:services,id',
            'description' => 'nullable|string',
            'received_at' => 'nullable|date',
            'estimated_delivery_at' => 'nullable|date',
            'status' => ['nullable', Rule::in($this->statuses())],
            'price' => 'nullable|numeric',
            'observations' => 'nullable|string',
        ]);

        unset($data['image']);
        if ($request->hasFile('image')) {
            $previousImage = $repair->image;
            $data['image'] = $request->file('image')->store('repairs', 'public');
            $repair->update($data);
            if ($previousImage) {
                Storage::disk('public')->delete($previousImage);
            }
        } else {
            $repair->update($data);
        }
        return redirect()->route('repairs.index')->with('success', 'Reparación actualizada.');
    }

    public function destroy(Repair $repair)
    {
        $repair->delete();
        return redirect()->route('repairs.index')->with('success', 'Reparación eliminada.');
    }

    private function statuses(): array
    {
        return ['Recibido', 'En reparación', 'Listo para entregar', 'Entregado', 'Cancelado'];
    }
}
