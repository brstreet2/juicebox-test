<?php

namespace App\Http\Controllers\Weather;

use App\Http\Controllers\Controller;
use App\Services\Weather\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function show()
    {
        return $this->weatherService->getCurrentWeather('Perth', 'AU');
    }
}
