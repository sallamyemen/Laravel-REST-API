<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Добавьте вручную записи в таблицу buildings
        $buildings = [
            [
                'address' => 'г. Москва, ул. Ленина, д. 1',
                'latitude' => 55.7558,
                'longitude' => 37.6173,
            ],
            [
                'address' => 'г. Санкт-Петербург, ул. Невский проспект, д. 10',
                'latitude' => 59.9343,
                'longitude' => 30.3351,
            ],
            [
                'address' => 'г. Екатеринбург, ул. Блюхера, д. 32/1',
                'latitude' => 56.8389,
                'longitude' => 60.6057,
            ],
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }
    }
}
