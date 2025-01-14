<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Activity;

class OrganizationController extends Controller
{
    // получения организаций в здании
    public function getOrganizationsByBuilding($buildingId)
    {
        return Organization::where('building_id', $buildingId)->get();
    }

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

    // Список всех организаций, которые относятся к указанному виду деятельности
    public function getByActivity($activity)
    {
        // Если ID активности, то получаем активность по ID
        $activity = Activity::findOrFail($activity);

        // Получаем все организации, связанные с этой активностью
        $organizations = $activity->organizations;

        return response()->json($organizations, 200);
    }

    // Вывод информации об организации по её идентификатору
    public function show($organizationId)
    {
        $organization = Organization::with(['building', 'activities'])->findOrFail($organizationId);

        return response()->json($organization, 200);
    }

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


    // Поиск организации по названию
    public function searchByName(Request $request)
    {
        // Получаем параметр name из запроса
        $name = $request->query('name');

        // Ищем организации, чье название содержит значение параметра 'name'
        $organizations = Organization::where('name', 'like', "%$name%")->get();

        // Возвращаем результат в формате JSON
        return response()->json($organizations, 200);
    }

}
