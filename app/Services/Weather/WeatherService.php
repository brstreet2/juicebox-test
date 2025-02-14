<?php

namespace App\Services\Weather;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected $apiUrl;

    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.weather.url');
        $this->apiKey = config('services.weather.key');
    }

    public function getCurrentWeather(string $city, string $countryCode)
    {
        try {
            // Cache key for storing the weather data
            $cacheKey = "weather_{$city}_{$countryCode}";
            $weatherData = Cache::get($cacheKey);

            if (!$weatherData) {
                // API call to OpenWeatherMap
                $apiKey = config('services.openweathermap.api_key');
                $url = config('services.openweathermap.base_url');

                $response = Http::get($url, [
                    'q'     => "{$city},{$countryCode}",
                    'appid' => $apiKey,
                    'units' => 'metric'
                ]);

                Log::info('Weather API Response: ', ['response' => $response->json()]);

                if ($response->failed()) {
                    return response()->json([
                        'status'  => $response->status(),
                        'message' => 'Failed to fetch weather data.',
                        'error'   => $response->json(),
                        'data'    => null,
                    ], $response->status());
                }

                $weatherData = $response->json();

                // Cache the response for 15 minutes
                Cache::put($cacheKey, $weatherData, 15 * 60);
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Weather data retrieved successfully.',
                'error'   => false,
                'data'    => $weatherData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Failed to retrieve weather data.',
                'error'   => $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }
}
