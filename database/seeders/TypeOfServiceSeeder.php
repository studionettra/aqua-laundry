<?php

namespace Database\Seeders;

use App\Models\TypeOfService;
use Illuminate\Database\Seeder;

class TypeOfServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_name' => 'Cuci dan Gosok',
                'price' => 5000,
                'description' => 'Harga per kg untuk Cuci dan Gosok',
            ],
            [
                'service_name' => 'Hanya Cuci',
                'price' => 4500,
                'description' => 'Harga per kg untuk Hanya Cuci',
            ],
            [
                'service_name' => 'Hanya Gosok',
                'price' => 5000,
                'description' => 'Harga per kg untuk Hanya Gosok',
            ],
            [
                'service_name' => 'Laundry Besar',
                'price' => 7000,
                'description' => 'Harga per kg untuk item besar seperti selimut, karpet, mantel, dan sprei my love',
            ],
        ];

        foreach ($services as $service) {
            TypeOfService::firstOrCreate(
                ['service_name' => $service['service_name']],
                $service
            );
        }
    }
}
