<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rootActivities = [
            ['name' => 'Еда'],
            ['name' => 'Автомобили'],
        ];

        foreach ($rootActivities as $root) {
            $parent = Activity::create($root);

            if ($root['name'] === 'Еда') {
                Activity::create(['name' => 'Мясная продукция', 'parent_id' => $parent->id]);
                Activity::create(['name' => 'Молочная продукция', 'parent_id' => $parent->id]);
            } elseif ($root['name'] === 'Автомобили') {
                $automobiles = Activity::create(['name' => 'Легковые', 'parent_id' => $parent->id]);
                Activity::create(['name' => 'Запчасти', 'parent_id' => $automobiles->id]);
                Activity::create(['name' => 'Аксессуары', 'parent_id' => $automobiles->id]);
            }
        }
    }
}
