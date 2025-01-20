<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ActivityController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/organizations/radius', [OrganizationController::class, 'getOrganizationsByRadius']);
//Список всех организаций, находящихся в конкретном здании
Route::get('buildings/{building}/organizations', [OrganizationController::class, 'getOrganizationsByBuilding']);
//Список всех организаций, которые относятся к указанному виду деятельности
Route::get('activities/{activity}/organizations', [OrganizationController::class, 'getByActivity']);
//Список организаций в радиусе/области относительно точки на карте
Route::get('activities/{activity}/organizations', [OrganizationController::class, 'getByActivity']);
// Список зданий
Route::get('buildings', [BuildingController::class, 'index']);
// Вывод информации об организации по её идентификатору
Route::get('organizations/{organization}', [OrganizationController::class, 'show']);
// Поиск организаций по виду деятельности (включая вложенные виды)
Route::get('activities/{activity}/organizations/recursive', [OrganizationController::class, 'getByActivityRecursive']);
//Поиск организации по названию
Route::post('organizations/search', [OrganizationController::class, 'searchByName']);
//Ограничение уровня вложенности деятельностей (до 3)
Route::get('activities/limited', [ActivityController::class, 'getLimited']);



