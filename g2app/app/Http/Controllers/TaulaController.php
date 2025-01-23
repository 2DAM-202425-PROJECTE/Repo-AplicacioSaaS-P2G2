<?php

namespace App\Http\Controllers;

use App\Models\Taula;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaulaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $restaurantId = $request->query('restaurant_id');
        $taules = Taula::where('id_restaurant', $restaurantId)->get();
        return response()->json($taules);
    }
}
