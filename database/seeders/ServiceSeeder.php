<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'name' => 'Cosida',
            'price' => 15.00,
            'description' => 'Servicio de cosida de zapatos.',
        ]);

        Service::create([
            'name' => 'Pegada',
            'price' => 10.00,
            'description' => 'Servicio de pegada de suelas.',
        ]);
    }
}