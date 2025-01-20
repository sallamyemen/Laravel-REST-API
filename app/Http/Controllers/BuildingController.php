<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

class BuildingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/buildings",
     *     summary="Получение списка всех зданий",
     *     tags={"Buildings"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Building"))
     *     )
     * )
     */

    // Список зданий
    public function index()
    {
        $buildings = Building::all();

        return response()->json($buildings, 200);
    }
}
