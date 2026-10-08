<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Cosida',
                'price' => 18000.00,
                'description' => 'Servicio de cosida de zapatos.',
                'image' => 'cosida.jpg',
            ],
            [
                'name' => 'Pegada',
                'price' => 12000.00,
                'description' => 'Servicio de pegada de suelas.',
                'image' => 'pegada.jpg',
            ],
            [
                'name' => 'Capellada',
                'price' => 70000.00,
                'description' => 'Servicio de reparación o cambio de capellada.',
                'image' => 'capellada.jpg',
            ],
        ] as $service) {
            $sourcePath = database_path('seeders/assets/services/'.$service['image']);
            $imagePath = 'services/'.$service['image'];

            if (!is_file($sourcePath)) {
                throw new RuntimeException("No se encontró la imagen del servicio: {$sourcePath}");
            }

            if (!Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->put($imagePath, file_get_contents($sourcePath));
            }

            $query = Service::query()->whereRaw('LOWER(name) = ?', [strtolower($service['name'])]);
            if ($service['name'] === 'Pegada') {
                $query->orWhereRaw('LOWER(name) = ?', ['pegadas']);
            }

            $existingService = $query->first();
            if (!$existingService) {
                $service['image'] = $imagePath;
                Service::create($service);
            } elseif (!$existingService->image || !Storage::disk('public')->exists($existingService->image)) {
                $existingService->update(['image' => $imagePath]);
            }
        }
    }
}