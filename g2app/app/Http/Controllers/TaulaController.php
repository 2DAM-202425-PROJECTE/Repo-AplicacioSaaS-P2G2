<?php


namespace App\Http\Controllers;

use App\Models\Taula;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaulaController extends Controller
{
    public function index(Request $request): Response
    {
        $restaurantId = $request->query('restaurant_id');
        $taules = Taula::where('id_restaurant', $restaurantId)
            ->select('id_taula', 'capacitat')
            ->get();
        return Inertia::render('Taules/Index', ['taules' => $taules]);
    }
}
