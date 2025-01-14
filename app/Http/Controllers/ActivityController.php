<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Organization;

class ActivityController extends Controller
{

    // Ограничение уровня вложенности деятельностей (до 3)
    public function getLimited()
    {
        // Получаем все активности с глубиной <= 3
        $activities = Activity::where('depth', '<=', 3)->get();

        return response()->json($activities, 200);
    }
}
