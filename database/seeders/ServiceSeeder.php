<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Cosida',
                'price' => 18000.00,
                'description' => 'Servicio de cosida de zapatos.',
            ],
            [
                'name' => 'Pegadas',
                'price' => 12000.00,
                'description' => 'Servicio de pegada de suelas.',
            ],
            [
                'name' => 'Capellada',
                'price' => 70000.00,
                'description' => 'Servicio de reparación o cambio de capellada.',
            ],
        ] as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}