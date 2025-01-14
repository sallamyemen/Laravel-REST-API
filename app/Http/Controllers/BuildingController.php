<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

class BuildingController extends Controller
{
    // Список зданий
    public function index()
    {
        $buildings = Building::all();

        return response()->json($buildings, 200);
    }
}
