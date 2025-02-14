<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WeatherTest extends TestCase
{
    /**
     * Test successful retrieval of weather data.
     */
    public function test_it_returns_weather_data_successfully()
    {
        // Mock API response
        $mockApiResponse = [
            'weather' => [['description' => 'clear sky']],
            'main' => ['temp' => 25],
            'name' => 'Perth',
        ];

        // Fake HTTP response
        Http::fake([
            '*' => Http::response($mockApiResponse, 200),
        ]);

        // Clear cache to test fresh API call
        Cache::shouldReceive('get')->once()->with('weather_Perth_AU')->andReturn(null);
        Cache::shouldReceive('put')->once();

        // Send GET request to weather endpoint
        $response = $this->getJson('/api/weather');

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Weather data retrieved successfully.',
                'error' => false,
                'data' => $mockApiResponse,
            ]);
    }

    /**
     * Test retrieval of weather data from cache.
     */
    public function test_it_returns_weather_data_from_cache()
    {
        // Mock cached data
        $cachedData = [
            'weather' => [['description' => 'partly cloudy']],
            'main' => ['temp' => 22],
            'name' => 'Perth',
        ];

        // Simulate cached data
        Cache::shouldReceive('get')->once()->with('weather_Perth_AU')->andReturn($cachedData);

        // Send GET request to weather endpoint
        $response = $this->getJson('/api/weather');

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Weather data retrieved successfully.',
                'error' => false,
                'data' => $cachedData,
            ]);
    }

    /**
     * Test failed API response.
     */
    public function test_it_handles_failed_api_response()
    {
        // Mock failed API response
        Http::fake([
            '*' => Http::response(['message' => 'City not found'], 404),
        ]);

        // Clear cache to test fresh API call
        Cache::shouldReceive('get')->once()->with('weather_Perth_AU')->andReturn(null);

        // Send GET request to weather endpoint
        $response = $this->getJson('/api/weather');

        // Assert response
        $response->assertStatus(404)
            ->assertJson([
                'status' => 404,
                'message' => 'Failed to fetch weather data.',
                'error' => ['message' => 'City not found'],
                'data' => null,
            ]);
    }

    /**
     * Test exception handling during weather data retrieval.
     */
    public function test_it_handles_exceptions()
    {
        // Simulate an exception during API call
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        // Log exception
        Log::shouldReceive('info');

        // Clear cache to force API call
        Cache::shouldReceive('get')->once()->with('weather_Perth_AU')->andReturn(null);

        // Send GET request to weather endpoint
        $response = $this->getJson('/api/weather');

        // Assert response
        $response->assertStatus(500)
            ->assertJson([
                'status' => 500,
                'message' => 'Failed to fetch weather data.',
                'data' => null,
            ]);
    }
}
