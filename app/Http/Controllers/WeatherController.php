<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function show(Request $request, WeatherService $weatherService): JsonResponse
    {
        $lat = (float) $request->input('lat', -23.5505);
        $lng = (float) $request->input('lng', -46.6333);

        $weather = $weatherService->getWeatherForCoordinates($lat, $lng);

        return response()->json($weather);
    }
}
