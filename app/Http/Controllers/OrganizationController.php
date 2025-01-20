<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Activity;

class OrganizationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/organizations/building/{buildingId}",
     *     summary="Получение организаций в здании",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         description="ID здания",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */

    // получения организаций в здании
    public function getOrganizationsByBuilding($buildingId)
    {
        return Organization::where('building_id', $buildingId)->get();
    }

    /**
     * @OA\Post(
     *     path="/organizations/radius",
     *     summary="Поиск организаций по радиусу",
     *     tags={"Organizations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="latitude", type="number", format="float", example=40.7128),
     *             @OA\Property(property="longitude", type="number", format="float", example=-74.0060),
     *             @OA\Property(property="radius", type="number", format="float", example=1000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */

    // поиск организаций по радиусу
    public function getOrganizationsByRadius(Request $request)
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius;

        return Organization::whereHas('building', function ($query) use ($latitude, $longitude, $radius) {
            $query->whereRaw("ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) <= ?", [
                $longitude, $latitude, $radius
            ]);
        })->get();
    }

    /**
     * @OA\Get(
     *     path="/organizations/activity/{activity}",
     *     summary="Список организаций по виду деятельности",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activity",
     *         in="path",
     *         description="ID вида деятельности",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */

    // Список всех организаций, которые относятся к указанному виду деятельности
    public function getByActivity($activity)
    {
        // Если ID активности, то получаем активность по ID
        $activity = Activity::findOrFail($activity);

        // Получаем все организации, связанные с этой активностью
        $organizations = $activity->organizations;

        return response()->json($organizations, 200);
    }

    /**
     * @OA\Get(
     *     path="/organizations/{organizationId}",
     *     summary="Получение информации об организации",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="organizationId",
     *         in="path",
     *         description="ID организации",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     )
     * )
     */

    // Вывод информации об организации по её идентификатору
    public function show($organizationId)
    {
        $organization = Organization::with(['building', 'activities'])->findOrFail($organizationId);

        return response()->json($organization, 200);
    }

    /**
     * @OA\Get(
     *     path="/organizations/activity-recursive/{activityId}",
     *     summary="Поиск организаций по виду деятельности с вложениями",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         description="ID вида деятельности",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */

    // Поиск организаций по виду деятельности (включая вложенные виды)
    public function getByActivityRecursive($activityId)
    {
        // Получаем все активности, которые являются данной активностью или её дочерними
        $activityIds = Activity::where('id', $activityId)
            ->orWhere('parent_id', $activityId)
            ->orWhereIn('parent_id', function ($query) use ($activityId) {
                $query->select('id')
                    ->from('activities')
                    ->where('parent_id', $activityId);
            })
            ->pluck('id');

        // Ищем организации, связанные с этими активностями
        $organizations = Organization::whereHas('activities', function ($query) use ($activityIds) {
            // Убедитесь, что ищем активности с этим ID
            $query->whereIn('activities.id', $activityIds);
        })->get();

        return response()->json($organizations, 200);
    }

    /**
     * @OA\Post(
     *     path="/organizations/search",
     *     summary="Поиск организаций по названию",
     *     tags={"Organizations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Organization Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *     )
     * )
     */

    // Поиск организации по названию
    public function searchByName(Request $request)
    {
        // Получаем параметр name из запроса
        $name = $request->name;

        if (!$name) {
            return response()->json(['error' => 'Name parameter is required'], 400);
        }

        // Ищем организации, чье название содержит значение параметра 'name'
        $organizations = Organization::where('name', 'LIKE', '%' . $name . '%')->get();

        // Возвращаем результат в формате JSON
        return response()->json($organizations, 200);
    }

}
