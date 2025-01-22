<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Organization;

class ActivityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/activities/limited",
     *     summary="Получение ограниченных деятельностей с глубиной <= 3",
     *     tags={"Activities"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Activity"))
     *     )
     * )
     */

    // Ограничение уровня вложенности деятельностей (до 3)
    public function getLimited()
    {
        // Получаем все активности с глубиной <= 3
        $activities = Activity::where('depth', '<=', 3)->get();

        return response()->json($activities, 200);
    }
}
