<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем здания из базы данных
        $buildings = Building::all();

        // Получаем все виды деятельности
        $activities = Activity::all();

        // Проверяем, что здания и виды деятельности существуют
        if ($buildings->isEmpty() || $activities->isEmpty()) {
            $this->command->error('Заполните здания и виды деятельности перед запуском сидера для организаций.');
            return;
        }

        // Тестовые данные организаций
        $organizations = [
            [
                'name' => 'ООО Рога и Копыта',
                'phone_numbers' => json_encode(['2-222-222', '3-333-333']),
            ],
            [
                'name' => 'ИП Мясной Дом',
                'phone_numbers' => json_encode(['4-444-444']),
            ],
            [
                'name' => 'ООО Молочные Радости',
                'phone_numbers' => json_encode(['5-555-555', '6-666-666']),
            ],
        ];

        // Создаем организации, связываем их со зданиями и видами деятельности
        foreach ($organizations as $index => $orgData) {
            // Выбираем здание для организации (распределяем по кругу)
            $building = $buildings[$index % $buildings->count()];

            // Создаем организацию
            $organization = Organization::create(array_merge($orgData, [
                'building_id' => $building->id,
            ]));

            // Привязываем организацию к случайным видам деятельности
            $randomActivities = $activities->random(rand(1, 3)); // От 1 до 3 случайных видов деятельности
            $organization->activities()->attach($randomActivities->pluck('id')->toArray());
        }
    }
}
